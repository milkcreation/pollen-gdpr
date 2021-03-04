<?php

declare(strict_types=1);

namespace Pollen\CookieLaw\Adapters;

use Pollen\CookieLaw\CookieLawAwareTrait;
use Pollen\CookieLaw\Contracts\CookieLawContract;

abstract class AbstractCookieLawAdapter implements AdapterInterface
{
    use CookieLawAwareTrait;

    /**
     * @param CookieLawContract $cookieLawManager
     */
    public function __construct(CookieLawContract $cookieLawManager)
    {
        $this->setCookieLaw($cookieLawManager);
    }
}
