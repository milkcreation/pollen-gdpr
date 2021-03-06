<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

Interface GdprProxyInterface
{
    /**
     * Instance du gestionnaire de politique de confidentialit√©.
     *
     * @return GdprInterface
     */
    public function gdpr(): GdprInterface;

    /**
     * D√©finition du gestionnaire de politique de confidentialit√©.
     *
     * @param GdprInterface $gdpr
     *
     * @return void
     */
    public function setGdpr(GdprInterface $gdpr): void;
}