<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Adapters;

use Pollen\Gdpr\GdprInterface;
use Pollen\Gdpr\GdprProxyInterface;

interface AdapterInterface extends GdprProxyInterface
{
    /**
     * Traitement des attributs de configuration de rendu.
     *
     * @return GdprInterface
     */
    public function parseConfig(): GdprInterface;
}
