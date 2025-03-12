<?php
    $this->pageTitle   = Yii::app()->name . ' - Error';
    $this->breadcrumbs = array(
        'Error',
    );
?>
<div id="error-404" class="container">
    <div class="row">
        <div class="col-sm-9">
            <h2>Error <?php echo $code; ?></h2>
            <p><?php echo CHtml::encode($message); ?></p>
        </div>
        <div class="col-sm-3 text-center">
            <img src="<?php echo Yii::app()->theme->baseUrl?>/images/img_404.png">
        </div>
        <div class="col-xs-12 redirect">

            <?php echo CHtml::link(Yii::t('adm/label', 'back_home'), Yii::app()->controller->createUrl('site/index'), array(
                'class' => 'btn'
            )); ?>

            <?php echo CHtml::link(Yii::t('adm/label', 'continue_find_sim'), Yii::app()->controller->createUrl('sim/index'), array(
                'class' => 'btn'
            )); ?>
        </div>
    </div>
</div>