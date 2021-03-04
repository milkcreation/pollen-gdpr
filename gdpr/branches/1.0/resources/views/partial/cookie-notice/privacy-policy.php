<?php
/**
 * @var Pollen\CookieLaw\CookieLawView $this
 */
?>
<?php if ($modal = $this->modal()) : ?>
    <div class="CookieLaw-privacyPolicy">
        <?php echo $modal; ?>
    </div>
<?php endif;