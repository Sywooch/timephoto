<?php /* @var $this Controller */

$i = 1;
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="container">
        <?php echo $content; ?>
    </div><!-- page -->
<?php $this->endContent(); ?>