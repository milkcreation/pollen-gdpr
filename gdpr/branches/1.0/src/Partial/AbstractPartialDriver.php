<?php

declare(strict_types=1);

namespace Pollen\CookieLaw\Partial;

use Pollen\CookieLaw\Contracts\CookieLawContract;
use tiFy\Partial\Contracts\PartialContract;
use tiFy\Partial\PartialDriver;
use Pollen\CookieLaw\CookieLawAwareTrait;

abstract class AbstractPartialDriver extends PartialDriver
{
    use CookieLawAwareTrait;

    public function __construct(CookieLawContract $cookieLawManager, PartialContract $partialManager)
    {
        $this->setCookieLaw($cookieLawManager);

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
                ->setParams(['cookie-law' => $this->cookieLaw()])
                ->setFactory(CookieLawPartialView::class);
        }

        return parent::view($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->cookieLaw()->resources("views/partial/{$this->getAlias()}");
    }
}