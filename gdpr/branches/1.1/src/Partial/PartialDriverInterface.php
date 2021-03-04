<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

use Pollen\Gdpr\GdprProxyInterface;
use tiFy\Partial\PartialDriverInterface as BasePartialDriverInterface;

interface PartialDriverInterface extends BasePartialDriverInterface, GdprProxyInterface
{
}