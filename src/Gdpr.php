<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Support\Filesystem;
use RuntimeException;
use Pollen\Gdpr\Partial\CookieBannerPartial;
use Pollen\Gdpr\Partial\PolicyModalPartial;
use Pollen\Gdpr\Partial\PrivacyLinkPartial;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ConfigBagAwareTrait;
use Pollen\Support\Proxy\ContainerProxy;
use Pollen\Support\Proxy\PartialProxy;
use Psr\Container\ContainerInterface as Container;

class Gdpr implements GdprInterface
{
    use BootableTrait;
    use ConfigBagAwareTrait;
    use ContainerProxy;
    use PartialProxy;

    /**
     * Instance principale.
     * @var static|null
     */
    private static $instance;

    /**
     * Instance de l'adapteur associé.
     * @var GdprAdapterInterface|null
     */
    private $adapter;

    /**
     * Liste des services par défaut fournis par conteneur d'injection de dépendances.
     * @var array
     */
    private $defaultProviders = [];

    /**
     * Chemin vers le répertoire des ressources.
     * @var string|null
     */
    protected $resourcesBaseDir;

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

        if ($this->config('boot_enabled', true)) {
            $this->boot();
        }

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * Récupération de l'instance principale.
     *
     * @return static
     */
    public static function getInstance(): GdprInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new RuntimeException(sprintf('Unavailable [%s] instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function boot(): GdprInterface
    {
        if (!$this->isBooted()) {
            //events()->trigger('cookie-law.booting', [$this]);

            $this->partial()->register(
                'cookie-banner',
                $this->containerHas(CookieBannerPartial::class)
                    ? CookieBannerPartial::class : new CookieBannerPartial($this, $this->partial())
            );

            $this->partial()->register(
                'policy-modal',
                $this->containerHas(PolicyModalPartial::class)
                    ? PolicyModalPartial::class : new PolicyModalPartial($this, $this->partial())
            );

            $this->partial()->register(
                'privacy-link',
                $this->containerHas(PrivacyLinkPartial::class)
                    ? PrivacyLinkPartial::class : new PrivacyLinkPartial($this, $this->partial())
            );

            $this->parseConfig();

            $this->setBooted();
            //events()->trigger('cookie-law.booted', [$this]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAdapter(): ?GdprAdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @inheritDoc
     */
    public function parseConfig(): void
    {
        /*$this->config(
            [
                'id'             => 'CookieLaw',
                'privacy_policy' => [
                    'content' => $this->view('partial/cookie-notice/default-txt'),
                    'title'   => $this->view('partial/cookie-notice/default-title'),
                ],
            ]
        );*/

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

            /*foreach (['header', 'body', 'footer'] as $part) {
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
            }*/

            if (!$this->config()->has('modal.viewer.override_dir')) {
                $this->config(['modal.viewer.override_dir' => $this->resources('views/partial/modal')]);
            }
        }

        if ($adapter = $this->getAdapter()) {
            $adapter->parseConfig();
        }
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null): string
    {
        if ($this->resourcesBaseDir === null) {
            $this->resourcesBaseDir = Filesystem::normalizePath(
                realpath(
                    dirname(__DIR__) . '/resources/'
                )
            );

            if (!file_exists($this->resourcesBaseDir)) {
                throw new RuntimeException('Gdpr ressources directory unreachable');
            }
        }

        return is_null($path) ? $this->resourcesBaseDir : $this->resourcesBaseDir . Filesystem::normalizePath($path);
    }

    /**
     * @inheritDoc
     */
    public function setAdapter(GdprAdapterInterface $adapter): GdprInterface
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setResourcesBaseDir(string $resourceBaseDir): GdprInterface
    {
        $this->resourcesBaseDir = Filesystem::normalizePath($resourceBaseDir);

        return $this;
    }
}
