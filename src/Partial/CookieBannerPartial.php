<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Partial;

use Pollen\Http\JsonResponse;
use Pollen\Http\JsonResponseInterface;
use Pollen\Http\Response;
use Pollen\Http\ResponseInterface;
use Pollen\Support\HtmlAttrs;
use Pollen\Support\Proxy\CookieProxy;

class CookieBannerPartial extends AbstractGdprPartialDriver
{
    use CookieProxy;

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(
            parent::defaultParams(),
            [
                'cookie' => [
                    'name'     => 'cookie-banner',
                    'lifetime' => 3600 * 24 * 3,
                    'value'    => 1,
                ],
            ]
        );
    }

    /**
     * Balise de chargement différée.
     *
     * @return string
     */
    public function defer(): string
    {
        $cookie = $this->cookie('cookie-banner', $this->get('cookie', []));

        if ($cookie->checkRequestValue()) {
            return '';
        }

        $attrs = [
            'class'        => 'CookieBanner-defer',
            'data-request' => [
                'endpoint' => $this->getXhrUrl([], 'xhrDeferResponse'),
                'method'   => 'POST',
                'headers'  => [
                    'Content-type'     => 'text/html; charset=UTF-8',
                    'X-Requested-with' => 'XMLHttpRequest',
                ],
                'body'     => $this->all(),
            ],
        ];

        $htmlAttrs = new HtmlAttrs($attrs);

        return "<div {$htmlAttrs}></div>";
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set(
            'attrs.data-request',
            [
                'endpoint' => $this->getXhrUrl([], 'xhrAcceptResponse'),
                'method'   => 'POST',
                'headers'  => [
                    'Content-type'     => 'text/html; charset=UTF-8',
                    'X-Requested-with' => 'XMLHttpRequest',
                ],
                'body'     => $this->all(),
            ]
        );

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->gdpr()->resources('/views/partial/cookie-banner');
    }

    /**
     * Contrôleur de traitement des requêtes XHR d'acceptation du cookie.
     *
     * @param array ...$args
     *
     * @return JsonResponseInterface
     */
    public function xhrAcceptResponse(...$args): JsonResponseInterface
    {
        $data = $this->httpRequest()->toArray();
        $cookie = $this->cookie(
            'cookie-banner',
            array_merge(
                $data['cookie'] ?? [],
                ['value' => 1]
            )
        )->unqueue();

        return new JsonResponse(
            [
                'success' => true
            ],
            200,
            [
                'Set-Cookie' => (string)$cookie,
            ]
        );
    }

    /**
     * Contrôleur de traitement des requêtes XHR de récupération d'affichage.
     *
     * @param array ...$args
     *
     * @return ResponseInterface
     */
    public function xhrDeferResponse(...$args): ResponseInterface
    {
        return new Response($this->render());
    }
}