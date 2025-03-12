<?php
    /* @var $this APostCategoryController */
    /* @var $model APostCategory */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','forum'),
        Yii::t('adm/label', 'manage_post_cate') => array('admin'),
        $model->name,
    );

?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Danh mục chủ đề: <?= $model->name ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php $this->widget('booster.widgets.TbDetailView', array(
                        'data'       => $model,
                        'attributes' => array(
                            array(
                                'name'  => 'name',
                                'value' => function ($data) {
                                    return CHtml::encode($data->name);
                                }
                            ),
                            array(
                                'name'  => 'description',
                                'type'  => 'raw',
                                'value' => function ($data) {
                                    return $data->description;
                                }
                            ),
                            array(
                                'name'  => 'sort_order',
                                'value' => function ($data) {
                                    return CHtml::encode($data->sort_order);
                                }
                            ),
                            array(
                                'name'  => 'home_display',
                                'value' => function ($data) {
                                    return CHtml::encode($data->home_display);
                                }
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>

