<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Gdpr\Adapters\WpGdprAdapter;
use Pollen\Http\ResponseInterface;
use Pollen\Support\Filesystem;
use Pollen\Support\Proxy\RouterProxy;
use RuntimeException;
use Pollen\Gdpr\Partial\GdprBannerPartial;
use Pollen\Gdpr\Partial\GdprPolicyPartial;
use Pollen\Routing\RouteInterface;
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
    use RouterProxy;

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
     * Instance du gestionnaire de politique de confidentialité.
     * @var GdprPolicyInterface
     */
    private $policy;

    /**
     * Instance de la route XHR d'affichage de la politique de confidentialité.
     * @var RouteInterface
     */
    protected $policyXhrRoute;

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
    public function __construct(array $config = [], ?Container $container = null)
    {
        $this->setConfig($config);

        if ($container !== null) {
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
            //events()->trigger('gdpr.booting', [$this]);

            $this->partial()->register(
                'gdpr-banner',
                $this->containerHas(GdprBannerPartial::class)
                    ? GdprBannerPartial::class : new GdprBannerPartial($this, $this->partial())
            );

            $this->partial()->register(
                'gdpr-policy',
                $this->containerHas(GdprPolicyPartial::class)
                    ? GdprPolicyPartial::class : new GdprPolicyPartial($this, $this->partial())
            );

            if ($router = $this->router()) {
                $this->policyXhrRoute = $router->xhr(
                    '/api/' . md5('gdpr') . '/policy',
                    [$this, 'policyXhrResponse'],
                    'GET'
                );
            }

            if ($this->adapter === null && defined('WPINC')) {
                $this->setAdapter(new WpGdprAdapter($this));
            }

            $this->setBooted();
            //events()->trigger('gdpr.booted', [$this]);
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
    public function policy(): GdprPolicyInterface
    {
        if ($this->policy === null) {
            $policy = $this->containerHas(GdprPolicyInterface::class) ?
                $this->containerGet(GdprPolicyInterface::class) : new GdprPolicy($this);

            $this->setPolicy($policy);
        }

        return $this->policy;
    }

    /**
     * @inheritDoc
     */
    public function policyXhrResponse(): ResponseInterface
    {
        return $this->policy()->xhrResponse();
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
    public function setPolicy(GdprPolicyInterface $policy): GdprInterface
    {
        $this->policy = $policy;

        if ($this->policyXhrRoute instanceof RouteInterface) {
            $this->policy->setUrl($this->router()->getRouteUrl($this->policyXhrRoute));
        }

        $this->policy->setParams($this->config('policy', []));

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
