<?php
    /* @var $this AMenuController */
    /* @var $model AMenu */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_menu') => array('admin'),
        $model->name                       => array('view', 'id' => $model->id),
        Yii::t('adm/actions', 'update'),
    );

    $this->menu = array(
        array('label' => Yii::t('adm/actions', 'create'), 'url' => array('create')),
        array('label' => Yii::t('adm/actions', 'view'), 'url' => array('view', 'id' => $model->id)),
        array('label' => Yii::t('adm/label', 'manage_menu'), 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= Yii::t('adm/label', 'update') ?> #<?php echo CHtml::encode($model->name); ?></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php $this->renderPartial('_form', array('model' => $model)); ?>
            </div>
        </div>
    </div>
</div>