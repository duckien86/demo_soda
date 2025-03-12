<?php
    /* @var $this ABannersController */
    /* @var $model ABanners */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'banners') => array('admin'),
        $model->title,
    );

    $this->menu = array(
        array('label' => Yii::t('adm/actions', 'create'), 'url' => array('create')),
        array('label' => Yii::t('adm/actions', 'update'), 'url' => array('update', 'id' => $model->id)),
        array('label' => Yii::t('adm/label', 'manage_banners'), 'url' => array('admin')),
    );
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
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
                            'name'  => 'img_desktop',
                            'type'  => 'raw',
                            'value' => $model->getImageUrl($model->img_desktop),
                        ),
                        array(
                            'name'  => 'img_mobile',
                            'type'  => 'raw',
                            'value' => $model->getImageUrl($model->img_mobile),
                        ),
                        'title',
                        'target_link',
                        'sort_order',
                        array(
                            'name'  => 'status',
                            'type'  => 'raw',
                            'value' => $model->getStatusLabel(),
                        ),
                        array(
                            'name'  => 'type',
                            'type'  => 'raw',
                            'value' => $model->getCategoriesTypeLabel(),
                        ),
                        'content_html',
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>