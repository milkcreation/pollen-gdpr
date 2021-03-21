<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Http\ResponseInterface;

interface GdprPolicyInterface extends GdprProxyInterface
{
    /**
     * Récupération du texte de politique de confidentialité.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Récupération du titre de politique de confidentialité.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Récupération de l'url vers la page de politique de confidentialité.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Traitement des variables d'environnement.
     *
     * @param string $content
     *
     * @return string
     */
    public function parseVars(string $content): string;

    /**
     * Définition des paramètres.
     *
     * @var array $params
     *
     * @return static
     */
    public function setParams(array $params): GdprPolicyInterface;

    /**
     * Définition du texte de politique de confidentialité.
     *
     * @param string $text
     *
     * @return static
     */
    public function setText(string $text): GdprPolicyInterface;

    /**
     * Définition du titre de politique de confidentialité.
     *
     * @param string $title
     *
     * @return static
     */
    public function setTitle(string $title): GdprPolicyInterface;

    /**
     * Définition des variables d'environnements.
     *
     * @param array $vars
     *
     * @return static
     */
    public function setVars(array $vars): GdprPolicyInterface;

    /**
     * Définition de l'url vers la page de politique de confidentialité.
     *
     * @param string $url
     *
     * @return static
     */
    public function setUrl(string $url): GdprPolicyInterface;

    /**
     * Réponse de la requête HTTP XHR.
     *
     * @return ResponseInterface
     */
    public function xhrResponse(): ResponseInterface;
}
