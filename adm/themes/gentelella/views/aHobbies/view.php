<?php
    /* @var $this ACategoriesController */
    /* @var $model ACategories */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'menu_hobbies') => array('admin'),
        $model->name,
    );

    $this->menu = array(
        array('label' => Yii::t('adm/label', 'manage_hobbies'), 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2> Thông tin chi tiết <?= $model->name ?></h2>
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
                                'name'  => 'index_order',
                                'value' => function ($data) {
                                    return CHtml::encode($data->index_order);
                                }
                            ),
                            array(
                                'name'  => 'status',
                                'value' => function ($data) {
                                    return CHtml::encode($data->status);
                                }
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
