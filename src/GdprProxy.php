<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Exception;

/**
 * @see \Pollen\Gdpr\GdprProxyInterface
 */
trait GdprProxy
{
    /**
     * Instance du gestionnaire de politique de confidentialité.
     * @var GdprInterface|null
     */
    private $gdpr;

    /**
     * Instance du gestionnaire de politique de confidentialité.
     *
     * @return GdprInterface|null
     */
    public function gdpr(): ?GdprInterface
    {
        if (is_null($this->gdpr)) {
            try {
                $this->gdpr = Gdpr::instance();
            } catch (Exception $e) {
                $this->gdpr;
            }
        }

        return $this->gdpr;
    }

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param GdprInterface $gdpr
     *
     * @return GdprProxy|static
     */
    public function setGdpr(GdprInterface $gdpr): GdprProxy
    {
        $this->gdpr = $gdpr;

        return $this;
    }
}