<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Gdpr\Adapters\WordpressAdapter;
use Pollen\Gdpr\Partial\PrivacyLinkPartial;
use tiFy\Container\ServiceProvider;
use tiFy\Partial\Contracts\PartialContract;
use tiFy\Support\Proxy\View;

class GdprServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        GdprInterface::class,
        PrivacyLinkPartial::class,
        WordpressAdapter::class,
        'gdpr.view-engine',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        events()->listen(
            'wp.booted',
            function () {
                /** @var GdprInterface $gdpr */
                $gdpr = $this->getContainer()->get(GdprInterface::class);

                $gdpr->setAdapter($gdpr->containerGet(WordpressAdapter::class))->boot();
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(
            GdprInterface::class,
            function () {
                return new Gdpr(config('gdpr', []), $this->getContainer());
            }
        );

        $this->registerAdapters();
        $this->registerPartialDrivers();
        $this->registerViewEngine();
    }

    /**
     * Déclaration des adapteurs.
     *
     * @return void
     */
    public function registerAdapters(): void
    {
        $this->getContainer()->share(
            WordpressAdapter::class,
            function () {
                return new WordpressAdapter($this->getContainer()->get(GdprInterface::class));
            }
        );
    }

    /**
     * Déclaration des pilotes de portions d'affichage.
     *
     * @return void
     */
    public function registerPartialDrivers(): void
    {
        $this->getContainer()->add(
            PrivacyLinkPartial::class,
            function () {
                return new PrivacyLinkPartial(
                    $this->getContainer()->get(GdprInterface::class),
                    $this->getContainer()->get(PartialContract::class)
                );
            }
        );
    }

    /**
     * Déclaration du service de moteur de gabarits d'affichage.
     *
     * @return void
     */
    public function registerViewEngine(): void
    {
        $this->getContainer()->share(
            'gdpr.view-engine',
            function () {
                /** @var GdprInterface $gdpr */
                $gdpr = $this->getContainer()->get(GdprInterface::class);

                return View::getPlatesEngine(
                    array_merge(
                        [
                            'directory'  => $gdpr->resources('views'),
                            'factory'    => GdprView::class,
                            'gdpr' => $gdpr,
                        ],
                        $gdpr->config('viewer', [])
                    )
                );
            }
        );
    }
}