<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Container\BaseServiceProvider;
use Pollen\Gdpr\Adapters\WpGdprAdapter;
use Pollen\Gdpr\Partial\GdprBannerPartial;
use Pollen\Gdpr\Partial\GdprPolicyPartial;
use Pollen\Partial\PartialManagerInterface;

class GdprServiceProvider extends BaseServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        GdprInterface::class,
        GdprPolicyInterface::class,
        GdprBannerPartial::class,
        GdprPolicyPartial::class,
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

        $this->getContainer()->share(
            GdprPolicyInterface::class,
            function () {
                return new GdprPolicy(
                    $this->getContainer()->get(GdprInterface::class)
                );
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
            GdprBannerPartial::class,
            function () {
                return new GdprBannerPartial(
                    $this->getContainer()->get(GdprInterface::class),
                    $this->getContainer()->get(PartialManagerInterface::class)
                );
            }
        );

        $this->getContainer()->add(
            GdprPolicyPartial::class,
            function () {
                return new GdprPolicyPartial(
                    $this->getContainer()->get(GdprInterface::class),
                    $this->getContainer()->get(PartialManagerInterface::class)
                );
            }
        );
    }
}