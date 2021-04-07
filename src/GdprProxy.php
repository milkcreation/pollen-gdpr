<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Support\StaticProxy;
use RuntimeException;

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
     * @return GdprInterface
     */
    public function gdpr(): GdprInterface
    {
        if ($this->gdpr === null) {
            try {
                $this->gdpr = Gdpr::getInstance();
            } catch (RuntimeException $e) {
                $this->gdpr = StaticProxy::getProxyInstance(
                    GdprInterface::class,
                    Gdpr::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
            }
        }

        return $this->gdpr;
    }

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param GdprInterface $gdpr
     *
     * @return void
     */
    public function setGdpr(GdprInterface $gdpr): void
    {
        $this->gdpr = $gdpr;
    }
}