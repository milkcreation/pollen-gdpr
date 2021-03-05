<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieBanner-button CookieBanner-accept',
        'href'        => "#{$this->get('attrs.id')}",
        'data-toggle' => 'notice.trigger'
    ],
    'content' => __('Accepter', 'tify')
]);