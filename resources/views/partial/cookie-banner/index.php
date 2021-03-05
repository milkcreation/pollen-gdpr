<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
?>
<?php $this->before(); ?>
<?php //$this->insert('privacy-policy', $this->all()); ?>
<div <?php echo $this->htmlAttrs(); ?>>
    <?php $this->insert('notice-text', $this->all()); ?>

    <div class="CookieBanner-buttons">
        <?php $this->insert('button-accept', $this->all()); ?>

        <?php $this->insert('button-read', $this->all()); ?>
    </div>

    <?php $this->insert('notice-close', $this->all()); ?>
</div>
<?php /*<div class="CookieBanner-backdrop"></div>*/ ?>
<?php $this->after(); ?>