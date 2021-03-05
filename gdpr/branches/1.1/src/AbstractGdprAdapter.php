<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

abstract class AbstractGdprAdapter implements GdprAdapterInterface
{
    use GdprProxy;

    /**
     * @param GdprInterface $gdpr
     */
    public function __construct(GdprInterface $gdpr)
    {
        $this->setGdpr($gdpr);
    }
}
