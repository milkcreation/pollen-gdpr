<?php

declare(strict_types=1);

namespace Pollen\CookieLaw\Adapters;

use Pollen\CookieLaw\Contracts\CookieLawContract;

interface AdapterInterface
{
    /**
     * Traitement des attributs de configuration de rendu.
     *
     * @return CookieLawContract
     */
    public function parseConfig(): CookieLawContract;
}
