<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;
use Pollen\Support\Proxy\PartialProxyInterface;

interface GdprInterface extends
    BootableTraitInterface,
    ConfigBagAwareTraitInterface,
    ContainerProxyInterface,
    PartialProxyInterface
{
    /**
     * Chargement.
     *
     * @return static
     */
    public function boot(): GdprInterface;

    /**
     * Récupération de l'instance de l'adapteur
     *
     * @return GdprAdapterInterface|null
     */
    public function getAdapter(): ?GdprAdapterInterface;

    /**
     * Chemin absolu vers une ressource (fichier|répertoire).
     *
     * @param string|null $path Chemin relatif vers la ressource.
     *
     * @return string
     */
    public function resources(?string $path = null): string;

    /**
     * Définition de l'adapteur associé.
     *
     * @param GdprAdapterInterface $adapter
     *
     * @return static
     */
    public function setAdapter(GdprAdapterInterface $adapter): GdprInterface;

    /**
     * Définition du chemin absolu vers le répertoire des ressources.
     *
     * @param string $resourceBaseDir
     *
     * @return static
     */
    public function setResourcesBaseDir(string $resourceBaseDir): GdprInterface;

}
