<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Psr\Container\ContainerInterface as Container;
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
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(GdprInterface::class)) {
                $this->gdpr = $container->get(GdprInterface::class);
            } else {
                try {
                    $this->gdpr = Gdpr::getInstance();
                } catch(RuntimeException $e) {
                    $this->gdpr = new Gdpr();
                }
            }
        }

        return $this->gdpr;
    }

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param GdprInterface $gdpr
     *
     * @return GdprProxyInterface|static
     */
    public function setGdpr(GdprInterface $gdpr): GdprProxyInterface
    {
        $this->gdpr = $gdpr;

        return $this;
    }
}