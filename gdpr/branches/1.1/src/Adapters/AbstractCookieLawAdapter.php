<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Adapters;

use Pollen\Gdpr\GdprInterface;
use Pollen\Gdpr\GdprProxy;

abstract class AbstractCookieLawAdapter implements AdapterInterface
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
