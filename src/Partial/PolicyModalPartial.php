<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

class PolicyModalPartial extends AbstractGdprPartialDriver
{
    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->gdpr()->resources('/views/partial/policy-modal');
    }
}