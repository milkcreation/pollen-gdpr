<?php
/**
 * @var Pollen\Gdpr\GdprView $this
 */
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieLaw-button CookieLaw-button--accept',
        'href'        => "#{$this->get('attrs.id')}",
        'data-toggle' => 'notice.trigger'
    ],
    'content' => __('Accepter', 'tify')
]);