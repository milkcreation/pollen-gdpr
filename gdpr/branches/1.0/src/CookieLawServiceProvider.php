<?php

declare(strict_types=1);

namespace Pollen\CookieLaw;

use Pollen\CookieLaw\Adapters\WordpressAdapter;
use Pollen\CookieLaw\Contracts\CookieLawContract;
use Pollen\CookieLaw\Partial\PrivacyLinkPartial;
use tiFy\Container\ServiceProvider;
use tiFy\Partial\Contracts\PartialContract;
use tiFy\Support\Proxy\View;

class CookieLawServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        CookieLawContract::class,
        PrivacyLinkPartial::class,
        WordpressAdapter::class,
        'cookie-law.view-engine',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        events()->listen(
            'wp.booted',
            function () {
                /** @var CookieLawContract $cookieLaw */
                $cookieLaw = $this->getContainer()->get(CookieLawContract::class);

                $cookieLaw->setAdapter($cookieLaw->containerGet(WordpressAdapter::class))->boot();
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(
            CookieLawContract::class,
            function () {
                return new CookieLaw(config('cookie-law', []), $this->getContainer());
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
                return new WordpressAdapter($this->getContainer()->get(CookieLawContract::class));
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
                    $this->getContainer()->get(CookieLawContract::class),
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
            'cookie-law.view-engine',
            function () {
                /** @var CookieLawContract $cookieLaw */
                $cookieLaw = $this->getContainer()->get(CookieLawContract::class);

                return View::getPlatesEngine(
                    array_merge(
                        [
                            'directory'  => $cookieLaw->resources('views'),
                            'factory'    => CookieLawView::class,
                            'cookie-law' => $cookieLaw,
                        ],
                        $cookieLaw->config('viewer', [])
                    )
                );
            }
        );
    }
}