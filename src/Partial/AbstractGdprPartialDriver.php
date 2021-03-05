<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

use Pollen\Gdpr\GdprInterface;
use Pollen\Gdpr\GdprProxy;
use Pollen\Partial\PartialDriver;
use Pollen\Partial\PartialManagerInterface;

abstract class AbstractGdprPartialDriver extends PartialDriver implements GdprPartialDriverInterface
{
    use GdprProxy;

    public function __construct(GdprInterface $gdpr, PartialManagerInterface $partialManager)
    {
        $this->setGdpr($gdpr);

        parent::__construct($partialManager);
    }
}