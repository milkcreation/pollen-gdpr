<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'button',
    'attrs'   => [
        'class'       => 'GdprBanner-close GdprBanner-noticeClose',
        'aria-label'  => __('Fermeture de la fenêtre', 'pollen-gdpr'),
    ],
    'content' => '&#x2715;',
]);