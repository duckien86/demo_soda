<?php
    $this->pageTitle   = Yii::app()->name . ' - Error';
    $this->breadcrumbs = array(
        'Error',
    );
?>
<div class="container-fluid" style="min-height: 400px;">
    <h2>Error <?php echo $code; ?></h2>

    <div class="error">
        <?php echo CHtml::encode($message); ?>
    </div>
</div>