<?php
    /* @var $this ACategoriesController */
    /* @var $model ACategories */

    $this->breadcrumbs = array(
        Yii::t('adm/book', 'Lịch sử đổi điểm') => array('admin'),
        $model->msisdn,
    );

    $this->menu = array(
        array('label' => Yii::t('adm/label', 'manage_redeem'), 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2> Thông tin chi tiết <?= $model->msisdn ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12">

                    <?php $this->widget('booster.widgets.TbDetailView', array(
                        'data'       => $model,
                        'attributes' => array(
                            array(
                                'name'  => 'sso_id',
                                'value' => function ($data) {
                                    return CHtml::encode($data->sso_id);
                                }
                            ),
                            array(
                                'name'  => 'package_code',
                                'value' => function ($data) {
                                    return CHtml::encode($data->package_code);
                                }
                            ),
                            array(
                                'name'  => 'create_date',
                                'value' => function ($data) {
                                    return CHtml::encode($data->create_date);
                                }
                            ),
                            array(
                                'name'  => 'point_amount',
                                'value' => function ($data) {
                                    return CHtml::encode($data->point_amount);
                                }
                            ),
                            array(
                                'name'  => 'transaction_id',
                                'value' => function ($data) {
                                    return CHtml::encode($data->transaction_id);
                                }
                            ),
                            array(
                                'name'  => 'msisdn',
                                'value' => function ($data) {
                                    return CHtml::encode($data->msisdn);
                                }
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
