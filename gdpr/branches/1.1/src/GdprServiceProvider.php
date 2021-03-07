<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Container\BaseServiceProvider;
use Pollen\Gdpr\Adapters\WpGdprAdapter;
use Pollen\Gdpr\Partial\CookieBannerPartial;
use Pollen\Gdpr\Partial\PolicyModalPartial;
use Pollen\Gdpr\Partial\PrivacyLinkPartial;
use Pollen\Partial\PartialManagerInterface;

class GdprServiceProvider extends BaseServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        GdprInterface::class,
        PrivacyLinkPartial::class,
        WpGdprAdapter::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(
            GdprInterface::class,
            function () {
                return new Gdpr([], $this->getContainer());
            }
        );

        $this->registerAdapters();
        $this->registerPartialDrivers();
    }

    /**
     * Déclaration des adapteurs.
     *
     * @return void
     */
    public function registerAdapters(): void
    {
        $this->getContainer()->share(
            WpGdprAdapter::class,
            function () {
                return new WpGdprAdapter($this->getContainer()->get(GdprInterface::class));
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
            CookieBannerPartial::class,
            function () {
                return new CookieBannerPartial(
                    $this->getContainer()->get(GdprInterface::class),
                    $this->getContainer()->get(PartialManagerInterface::class)
                );
            }
        );

        $this->getContainer()->add(
            PolicyModalPartial::class,
            function () {
                return new PolicyModalPartial(
                    $this->getContainer()->get(GdprInterface::class),
                    $this->getContainer()->get(PartialManagerInterface::class)
                );
            }
        );

        $this->getContainer()->add(
            PrivacyLinkPartial::class,
            function () {
                return new PrivacyLinkPartial(
                    $this->getContainer()->get(GdprInterface::class),
                    $this->getContainer()->get(PartialManagerInterface::class)
                );
            }
        );
    }
}