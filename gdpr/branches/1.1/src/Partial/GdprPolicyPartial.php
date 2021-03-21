<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

class GdprPolicyPartial extends AbstractGdprPartialDriver
{
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!$this->has('content')) {
            $this->set('content', __('conditions relatives à la politique des données personnelles', 'pollen-gdpr'));
        }

        if (!$this->has('attrs.data-gdpr')) {
            $this->set('attrs.data-gdpr', 'policy');
        }

        if (!$this->has('attrs.href')) {
            $this->set('attrs.href', $this->gdpr()->policy()->getUrl());
        }

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->gdpr()->resources('/views/partial/gdpr-policy');
    }
}