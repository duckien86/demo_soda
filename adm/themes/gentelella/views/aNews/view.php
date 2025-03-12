<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        Yii::t('adm/label', 'news') => array('admin'),
        $model->title
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($model->title); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'attributes' => array(
                'id',
                array(
                    'name'  => 'folder_path',
                    'type'  => 'raw',
                    'value' => $model->getImageUrl($model->thumbnail),
                ),
                array(
                    'name'  => 'categories_id',
                    'type'  => 'raw',
                    'value' => $model->getNewsCategoriesTitle(),
                ),
                'title',
                'create_date',
                'last_update',
                array(
                    'name'  => 'hot',
                    'type'  => 'raw',
                    'value' => $model->getLabelPosition($model->hot),
                ),
                'sort_order',
                array(
                    'name'  => 'status',
                    'type'  => 'raw',
                    'value' => $model->getStatusLabel(),
                ),
                'short_des',
                array(
                    'name' => 'full_des',
                    'type' => 'raw',
                ),
            ),
        )); ?>
    </div>
</div>


