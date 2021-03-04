<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use BadMethodCallException;
use Exception;
use tiFy\Partial\Drivers\ModalDriverInterface;
use tiFy\Wordpress\Contracts\Query\QueryPost;
use tiFy\View\Factory\PlatesFactory;

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
class GdprView extends PlatesFactory
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
                $gdpr = $this->engine->params('gdpr');

                return $gdpr->{$name}(...$arguments);
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