<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Support\Concerns\BootableTrait;

abstract class AbstractGdprAdapter implements GdprAdapterInterface
{
    use BootableTrait;
    use GdprProxy;

    /**
     * @param GdprInterface $gdpr
     */
    public function __construct(GdprInterface $gdpr)
    {
        $this->setGdpr($gdpr);

        $this->boot();
    }

    /**
     * @inheritDoc
     */
    abstract public function boot(): void;
}
