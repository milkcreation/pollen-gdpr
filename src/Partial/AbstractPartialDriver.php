<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

use Pollen\Gdpr\GdprInterface;
use Pollen\Gdpr\GdprProxy;
use tiFy\Partial\Contracts\PartialContract;
use tiFy\Partial\PartialDriver;

abstract class AbstractPartialDriver extends PartialDriver
{
    use GdprProxy;

    public function __construct(GdprInterface $gdpr, PartialContract $partialManager)
    {
        $this->setGdpr($gdpr);

        parent::__construct($partialManager);
    }

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, $data = [])
    {
        if (is_null($this->viewEngine)) {
            $viewEngine = parent::view();
            $viewEngine
                ->setParams(['gdpr' => $this->gdpr()])
                ->setFactory(GdprPartialView::class);
        }

        return parent::view($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->gdpr()->resources("views/partial/{$this->getAlias()}");
    }
}