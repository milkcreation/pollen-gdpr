<?php

declare(strict_types=1);

use Pollen\CookieLaw\Contracts\CookieLawContract;
use Pollen\CookieLaw\CookieLaw;

if (!function_exists('cookie_law')) {
    /**
     * Récupération de l'instance de gestionnaire de plugin.
     *
     * @return CookieLaw|null
     */
    function cookie_law(): ?CookieLawContract
    {
        return CookieLaw::instance();
    }
}