<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use RuntimeException;
use Pollen\Gdpr\Adapters\AdapterInterface;
use Pollen\Gdpr\Contracts\CookieLawContract;
use Pollen\Gdpr\Partial\PrivacyLinkPartial;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Partial\Drivers\ModalDriverInterface;
use tiFy\Support\Concerns\BootableTrait;
use tiFy\Support\Concerns\ContainerAwareTrait;
use tiFy\Support\Concerns\PartialManagerAwareTrait;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Request;
use tiFy\Support\Proxy\Router;
use tiFy\Support\Proxy\Storage;
use tiFy\Support\Proxy\View;

class Gdpr implements GdprInterface
{
    use BootableTrait;
    use ContainerAwareTrait;
    use PartialManagerAwareTrait;

    /**
     * Instance de la classe.
     * @var static|null
     */
    private static $instance;

    /**
     * Instance de l'adapteur associé.
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Liste des services par défaut fournis par conteneur d'injection de dépendances.
     * @var array
     */
    private $defaultProviders = [];

    /**
     * Instance du gestionnaire des ressources
     * @var LocalFilesystem|null
     */
    private $resources;

    /**
     * Instance du gestionnaire de configuration.
     * @var ParamsBag
     */
    protected $configBag;

    /**
     * Instance de la fenêtre modal d'affichage de la politique de confidentialité.
     * @var ModalDriverInterface|false|null
     */
    protected $modal;

    /**
     * Moteur des gabarits d'affichage.
     * @var ViewEngine|null
     */
    protected $viewEngine;

    /**
     * Url de requête HTTP XHR.
     * @var string
     */
    protected $xhrModalUrl;

    /**
     * @param array $config
     * @param Container|null $container
     */
    public function __construct(array $config = [], Container $container = null)
    {
        $this->setConfig($config);

        if (!is_null($container)) {
            $this->setContainer($container);
        }

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(): GdprInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new RuntimeException(sprintf('Unavailable %s instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function boot(): GdprInterface
    {
        if (!$this->isBooted()) {
            events()->trigger('cookie-law.booting', [$this]);

            $this->xhrModalUrl = Router::xhr(md5('CookieLaw'), [$this, 'xhrModal'])->getUrl();

            $this->partialManager()->register(
                'privacy-link',
                $this->containerHas(PrivacyLinkPartial::class)
                    ? PrivacyLinkPartial::class : new PrivacyLinkPartial($this, $this->partialManager())
            );

            $this->parseConfig();

            $this->setBooted();

            events()->trigger('cookie-law.booted', [$this]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function config($key = null, $default = null)
    {
        if (!isset($this->configBag) || is_null($this->configBag)) {
            $this->configBag = new ParamsBag();
        }

        if (is_string($key)) {
            return $this->configBag->get($key, $default);
        } elseif (is_array($key)) {
            return $this->configBag->set($key);
        } else {
            return $this->configBag;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAdapter(): ?AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @inheritDoc
     */
    public function getProvider(string $name)
    {
        return $this->config("providers.{$name}", $this->defaultProviders[$name] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function modal(): ?ModalDriverInterface
    {
        if (is_null($this->modal) && ($this->config('modal') !== false)) {
            $this->modal = $this->partialManager()->get('modal', $this->config('modal', []));
        }

        return $this->modal;
    }

    /**
     * @inheritDoc
     */
    public function parseConfig(): GdprInterface
    {
        $this->config(
            [
                'id'             => 'CookieLaw',
                'privacy_policy' => [
                    'content' => $this->view('partial/cookie-notice/default-txt'),
                    'title'   => $this->view('partial/cookie-notice/default-title'),
                ],
            ]
        );

        $modal = $this->config('modal', true);
        if ($this->config('modal') !== false) {
            if (!is_array($modal)) {
                $this->config(
                    [
                        'modal' => [
                            'ajax'      => [
                                'url' => $this->xhrModalUrl,
                            ],
                            'attrs'     => [
                                'id' => 'Modal-cookieLaw-privacyPolicy',
                            ],
                            'options'   => ['show' => false, 'backdrop' => true],
                            'size'      => 'xl',
                            'in_footer' => false,
                        ],
                    ]
                );
            }

            foreach (['header', 'body', 'footer'] as $part) {
                if (!$this->config()->has("modal.content.{$part}")) {
                    $this->config(
                        [
                            "modal.content.{$part}" => $this->view(
                                "partial/modal/content-{$part}",
                                $this->config()->all()
                            ),
                        ]
                    );
                }
            }

            if (!$this->config()->has('modal.viewer.override_dir')) {
                $this->config(['modal.viewer.override_dir' => $this->resources('views/partial/modal')]);
            }
        }

        return $this->getAdapter() ? $this->getAdapter()->parseConfig() : $this;
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null)
    {
        if (!isset($this->resources) || is_null($this->resources)) {
            $this->resources = Storage::local(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources');
        }

        return is_null($path) ? $this->resources : $this->resources->path($path);
    }

    /**
     * @inheritDoc
     */
    public function setAdapter(AdapterInterface $adapter): GdprInterface
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $attrs): GdprInterface
    {
        $this->config($attrs);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->view('partial/cookie-notice/index', $this->config()->all());
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->viewEngine)) {
            $this->viewEngine = $this->containerHas('cookie-law.view-engine')
                ? $this->containerGet('cookie-law.view-engine') : View::getPlatesEngine();
        }

        if (func_num_args() === 0) {
            return $this->viewEngine;
        }

        return $this->viewEngine->render($name, $data);
    }

    /**
     * @inheritDoc
     */
    public function xhrModal(): array
    {
        $modal = $this->parseConfig()->modal();

        $viewer = Request::input('viewer', []);
        foreach ($viewer as $key => $value) {
            $modal->view()->params([$key => $value]);
        }

        return [
            'success' => true,
            'data'    => $modal->view(
                'ajax-content',
                [
                    'title'   => $this->config('privacy_policy.title'),
                    'content' => $this->config('privacy_policy.content'),
                ]
            ),
        ];
    }
}
