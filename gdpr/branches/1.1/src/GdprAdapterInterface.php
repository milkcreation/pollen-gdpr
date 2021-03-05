<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

interface GdprAdapterInterface extends GdprProxyInterface
{
    /**
     * Traitement des attributs de configuration de rendu.
     *
     * @return GdprInterface
     */
    public function parseConfig(): GdprInterface;
}
