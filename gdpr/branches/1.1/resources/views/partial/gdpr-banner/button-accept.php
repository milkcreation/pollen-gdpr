<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
echo $this->partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'GdprBanner-button GdprBanner-accept',
        'href'        => "#{$this->get('attrs.id')}",
        'data-toggle' => 'notice.trigger'
    ],
    'content' => 'Accepter'
]);