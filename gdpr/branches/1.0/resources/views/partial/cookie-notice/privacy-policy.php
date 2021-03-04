<?php
/**
 * @var Pollen\Gdpr\GdprView $this
 */
?>
<?php if ($modal = $this->modal()) : ?>
    <div class="CookieLaw-privacyPolicy">
        <?php echo $modal; ?>
    </div>
<?php endif;