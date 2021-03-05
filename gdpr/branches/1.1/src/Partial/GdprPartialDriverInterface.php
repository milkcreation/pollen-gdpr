<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

use Pollen\Gdpr\GdprProxyInterface;
use Pollen\Partial\PartialDriverInterface;

interface GdprPartialDriverInterface extends PartialDriverInterface, GdprProxyInterface
{
}