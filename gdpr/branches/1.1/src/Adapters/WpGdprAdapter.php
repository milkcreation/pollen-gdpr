<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Adapters;

use Pollen\Gdpr\AbstractGdprAdapter;

class WpGdprAdapter extends AbstractGdprAdapter
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!$this->isBooted()) {
            $this->gdpr()->setPolicy(new WpGdprPolicy($this->gdpr()));

            $this->setBooted();
        }
    }

    /**
     * @inheritDoc
     */
    public function parseConfig(): void
    {

    }
}
