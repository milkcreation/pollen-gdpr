<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Support\Concerns\BootableTraitInterface;

interface GdprAdapterInterface extends BootableTraitInterface, GdprProxyInterface
{
    /**
     * Chargement.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Traitement des attributs de configuration de rendu.
     *
     * @return void
     */
    public function parseConfig(): void;
}
