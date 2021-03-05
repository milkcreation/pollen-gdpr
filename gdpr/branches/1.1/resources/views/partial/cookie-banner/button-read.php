<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'  => 'CookieBanner-button CookieBanner-read',
        'href'   => $this->get('privacy_policy.permalink'),
        'target' => '_blank'
    ],
    'content' => __('En savoir plus', 'tify')
]);