<?php
    /* @var $this SimController */
    /* @var $msg CskhOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'search_msisdn') => array('index'),
        Yii::t('adm/label', 'message'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'message') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <p>
            <?php echo CHtml::encode($msg); ?>
        </p>
    </div>
</div>