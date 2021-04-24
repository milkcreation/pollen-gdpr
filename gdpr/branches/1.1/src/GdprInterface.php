<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Concerns\ResourcesAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;
use Pollen\Support\Proxy\PartialProxyInterface;
use Pollen\Support\Proxy\RouterProxyInterface;

interface GdprInterface extends
    BootableTraitInterface,
    ConfigBagAwareTraitInterface,
    ResourcesAwareTraitInterface,
    ContainerProxyInterface,
    PartialProxyInterface,
    RouterProxyInterface
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
     * Récupération de l'instance du gestionnaire de politique de confidentialité.
     *
     * @return GdprPolicyInterface
     */
    public function policy(): GdprPolicyInterface;

    /**
     * Réponse de la requête HTTP XHR.
     *
     * @return ResponseInterface
     */
    public function policyXhrResponse(): ResponseInterface;

    /**
     * Définition de l'adapteur associé.
     *
     * @param GdprAdapterInterface $adapter
     *
     * @return static
     */
    public function setAdapter(GdprAdapterInterface $adapter): GdprInterface;

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param GdprPolicyInterface $policy
     *
     * @return static
     */
    public function setPolicy(GdprPolicyInterface $policy): GdprInterface;
}
