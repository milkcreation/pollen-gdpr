<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'  => 'GdprBanner-button GdprBanner-policy',
        'href'   => $this->get('policy-url'),
        'target' => '_blank'
    ],
    'content' => __('En savoir plus', 'pollen-gdpr')
]);