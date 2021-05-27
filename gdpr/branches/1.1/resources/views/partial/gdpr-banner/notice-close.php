<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'button',
    'attrs'   => [
        'class'       => 'GdprBanner-close GdprBanner-noticeClose',
        'aria-label'  => 'Fermeture de la fenêtre',
    ],
    'content' => '&#x2715;',
]);