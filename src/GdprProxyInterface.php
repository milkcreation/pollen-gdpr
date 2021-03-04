<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

Interface GdprProxyInterface
{
    /**
     * Instance du gestionnaire de politique de confidentialité.
     *
     * @return GdprInterface|null
     */
    public function gdpr(): ?GdprInterface;

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param GdprInterface $gdpr
     *
     * @return GdprProxy|static
     */
    public function setGdpr(GdprInterface $gdpr): GdprProxy;
}