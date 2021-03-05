<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

Interface GdprProxyInterface
{
    /**
     * Instance du gestionnaire de politique de confidentialité.
     *
     * @return GdprInterface
     */
    public function gdpr(): GdprInterface;

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param GdprInterface $gdpr
     *
     * @return GdprProxyInterface|static
     */
    public function setGdpr(GdprInterface $gdpr): GdprProxyInterface;
}