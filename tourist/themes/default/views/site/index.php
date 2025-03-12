<?php
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist');
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'dashboard'),
);
?>

<div id="home" class="row">
    <div class="col-md-4 col-sm-4">
        <div class="item item-order">
            <div class="item-content">
                <h2><?php echo CHtml::encode(Yii::t('tourist/label', 'order_create'))?></h2>
                <p><?php echo CHtml::encode(Yii::t('tourist/label', 'order_note'))?></p>
            </div>

            <?php echo CHtml::link(Yii::t('tourist/label', 'order_create'), '', array(
                'class' => 'btn btn-lg',
                'onclick' => 'showModalChooseOrder()',
            ));?>
        </div>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="item item-info">
            <div class="item-content">
                <h2><?php echo CHtml::encode(Yii::t('tourist/label', 'info'))?></h2>
                <p><?php echo CHtml::encode(Yii::t('tourist/label', 'info_note'))?></p>
            </div>
            <?php echo CHtml::link(Yii::t('tourist/label', 'view_info'),
                Yii::app()->createUrl('user/info'), array(
                'class' => 'btn btn-lg'
            ))?>
        </div>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="item item-manage">
            <div class="item-content">
                <h2><?php echo CHtml::encode(Yii::t('tourist/label', 'orders'))?></h2>
                <p><?php echo CHtml::encode(Yii::t('tourist/label', 'manage_order_note'))?></p>
            </div>
            <?php echo CHtml::link(Yii::t('tourist/label', 'view_info'),
                Yii::app()->createUrl('order/admin'), array(
                    'class' => 'btn btn-lg'
                ))?>
        </div>
    </div>
</div>
