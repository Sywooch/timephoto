<?php /* @var $this Controller */

$i = 1;
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>