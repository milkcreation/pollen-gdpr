<?php

declare(strict_types=1);

namespace Pollen\CookieLaw;

use Exception;
use Pollen\CookieLaw\Contracts\CookieLawContract;

trait CookieLawAwareTrait
{
    /**
     * Instance du gestionnaire.
     * @var CookieLawContract|null
     */
    private $cookieLaw;

    /**
     * Récupération de l'instance de l'application.
     *
     * @return CookieLawContract|null
     */
    public function cookieLaw(): ?CookieLawContract
    {
        if (is_null($this->cookieLaw)) {
            try {
                $this->cookieLaw = CookieLaw::instance();
            } catch (Exception $e) {
                $this->cookieLaw;
            }
        }

        return $this->cookieLaw;
    }

    /**
     * Définition du gestionnaire.
     *
     * @param CookieLawContract $cookieLaw
     *
     * @return static
     */
    public function setCookieLaw(CookieLawContract $cookieLaw): self
    {
        $this->cookieLaw = $cookieLaw;

        return $this;
    }
}