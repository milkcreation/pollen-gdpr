<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

use BadMethodCallException;
use Exception;
use tiFy\Partial\Drivers\ModalDriverInterface;
use tiFy\Partial\PartialView;
use tiFy\Wordpress\Contracts\Query\QueryPost;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getId()
 * @method string getIndex()
 * @method ModalDriverInterface modal()
 * @method false|QueryPost privacyPolicy()
 */
class GdprPartialView extends PartialView
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'after',
        'attrs',
        'before',
        'content',
        'getId',
        'getIndex',
        'modal',
        'privacyPolicy',
    ];

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->mixins)) {
            try {
                $cookieLaw = $this->engine->params('cookie-law');

                return $cookieLaw->{$name}(...$arguments);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __CLASS__ . ' throws an exception during the method call [%s] with message : %s',
                        $name,
                        $e->getMessage()
                    )
                );
            }
        } else {
            return parent::__call($name, $arguments);
        }
    }
}