<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'button',
    'attrs'   => [
        'class'       => 'CookieBanner-close CookieBanner-noticeClose',
        'aria-label'  => __('Fermeture de la fenêtre', 'tify'),
    ],
    'content' => '&#x2715;',
]);