<?php

declare(strict_types=1);

namespace Pollen\CookieLaw\Contracts;

use RuntimeException;
use Pollen\CookieLaw\Adapters\AdapterInterface;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Contracts\Support\ParamsBag;
use tiFy\Partial\Drivers\ModalDriverInterface;

/**
 * @mixin \tiFy\Support\Concerns\BootableTrait
 * @mixin \tiFy\Support\Concerns\ContainerAwareTrait
 * @mixin \tiFy\Support\Concerns\PartialManagerAwareTrait
 */
interface CookieLawContract
{
    /**
     * Récupération de l'instance de l'extension.
     *
     * @return static
     *
     * @throws RuntimeException
     */
    public static function instance(): CookieLawContract;

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Chargement.
     *
     * @return static
     */
    public function boot(): CookieLawContract;

    /**
     * Récupération de paramètre|Définition de paramètres|Instance du gestionnaire de paramètre.
     *
     * @param string|array|null $key Clé d'indice du paramètre à récupérer|Liste des paramètre à définir.
     * @param mixed $default Valeur de retour par défaut lorsque la clé d'indice est une chaine de caractère.
     *
     * @return ParamsBag|int|string|array|object
     */
    public function config($key = null, $default = null);

    /**
     * Récupération de l'instance de l'adapteur
     *
     * @return AdapterInterface|null
     */
    public function getAdapter(): ?AdapterInterface;

    /**
     * Récupération d'un service fourni par le conteneur d'injection de dépendance.
     *
     * @param string $name
     *
     * @return callable|object|string|null
     */
    public function getProvider(string $name);

    /**
     * Récupération de l'instance de la fenêtre modale.
     *
     * @return ModalDriverInterface|null
     */
    public function modal(): ?ModalDriverInterface;

    /**
     * Traitement des attributs de configuration.
     *
     * @return static
     */
    public function parseConfig(): CookieLawContract;

    /**
     * Chemin absolu vers une ressources (fichier|répertoire).
     *
     * @param string|null $path Chemin relatif vers la ressource.
     *
     * @return LocalFilesystem|string|null
     */
    public function resources(?string $path = null);

    /**
     * Définition de l'adapteur associé.
     *
     * @param AdapterInterface $adapter
     *
     * @return static
     */
    public function setAdapter(AdapterInterface $adapter): CookieLawContract;

    /**
     * Définition des paramètres de configuration.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function setConfig(array $attrs): CookieLawContract;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Récupération d'une instance du gestionnaire de gabarits d'affichage ou affichage d'un gabarit.
     *
     * @param string|null $name
     * @param array $data
     *
     * @return ViewEngine|string
     */
    public function view(?string $name = null, array $data = []);

    /**
     * Controleur de traitement de la requête HTTP XHR.
     *
     * @return array
     */
    public function xhrModal(): array;
}
