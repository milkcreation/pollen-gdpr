<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
?>
<?php $this->before(); ?>

<div <?php echo $this->htmlAttrs(); ?>>
    <?php $this->insert('notice-text', $this->all()); ?>

    <div class="GdprBanner-buttons">
        <?php $this->insert('button-accept', $this->all()); ?>

        <?php $this->insert('button-policy', $this->all()); ?>
    </div>

    <?php $this->insert('notice-close', $this->all()); ?>
</div>

<?php if ($this->get('backdrop')) : ?>
    <div class="GdprBanner-backdrop"></div>
<?php endif; ?>

<?php $this->after();