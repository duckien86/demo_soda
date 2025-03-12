<?php
    /* @var $this ACategoriesController */
    /* @var $model ACategories */

    $this->breadcrumbs = array(
        Yii::t('adm/book', 'Quản lý người dùng') => array('admin'),
        $model->username,
    );

    $this->menu = array(
        array('label' => Yii::t('adm/label', 'manage_customer'), 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2> Thông tin chi tiết <?= $model->username ?> </h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php $this->widget('booster.widgets.TbDetailView', array(
                        'data'       => $model,
                        'attributes' => array(
                            array(
                                'name'  => 'Tên đăng nhập',
                                'value' => function ($data) {
                                    return CHtml::encode(ACustomers::getName($data->sso_id));
                                }
                            ),
                            array(
                                'name'  => 'phone',
                                'value' => function ($data) {
                                    return CHtml::encode($data->phone);
                                }
                            ),
                            array(
                                'name'  => 'username',
                                'value' => function ($data) {
                                    return CHtml::encode($data->username);
                                }
                            ),
                            array(
                                'name'  => 'email',
                                'value' => function ($data) {
                                    return CHtml::encode($data->email);
                                }
                            ),
                            array(
                                'name'  => 'birthday',
                                'value' => function ($data) {
                                    return CHtml::encode($data->birthday);
                                }
                            ),
                            array(
                                'name'  => 'full_name',
                                'value' => function ($data) {
                                    return CHtml::encode($data->full_name);
                                }
                            ),
                            array(
                                'name'  => 'genre',
                                'value' => function ($data) {
                                    return CHtml::encode(ACustomers::getGenre($data->genre));
                                }
                            ),
                            array(
                                'name'  => 'full_name',
                                'value' => function ($data) {
                                    return CHtml::encode($data->full_name);
                                }
                            ),
                            array(
                                'name'  => 'level',
                                'value' => function ($data) {
                                    return CHtml::encode(ACustomers::getLevel($data->bonus_point));
                                }
                            ),
                            array(
                                'name'  => 'province_code',
                                'value' => function ($data) {
                                    return CHtml::encode(AProvince::getProvince($data->province_code));
                                }
                            ),
                            array(
                                'name'  => 'district_code',
                                'value' => function ($data) {
                                    return CHtml::encode(ADistrict::getDistrict($data->district_code));
                                }
                            ),

                        ),
                    )); ?>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php $this->widget('booster.widgets.TbDetailView', array(
                        'data'       => $model,
                        'attributes' => array(
                            array(
                                'name'  => 'address_detail',
                                'value' => function ($data) {
                                    return CHtml::encode($data->address_detail);
                                }
                            ),
                            array(
                                'name'  => 'personal_id',
                                'value' => function ($data) {
                                    return CHtml::encode($data->personal_id);
                                }
                            ),
                            array(
                                'name'  => 'personal_id_create_date',
                                'value' => function ($data) {
                                    return CHtml::encode($data->personal_id_create_date);
                                }
                            ),
                            array(
                                'name'  => 'personal_id_create_place',
                                'value' => function ($data) {
                                    return CHtml::encode($data->personal_id_create_place);
                                }
                            ),
                            array(
                                'name'  => 'bank_account_id',
                                'value' => function ($data) {
                                    return CHtml::encode($data->bank_account_id);
                                }
                            ),
                            array(
                                'name'  => 'bank_brandname',
                                'value' => function ($data) {
                                    return CHtml::encode($data->bank_brandname);
                                }
                            ),
                            array(
                                'name'  => 'bank_account_name',
                                'value' => function ($data) {
                                    return CHtml::encode($data->bank_account_name);
                                }
                            ),
                            array(
                                'name'  => 'bank_name',
                                'value' => function ($data) {
                                    return CHtml::encode($data->bank_name);
                                }
                            ),
                            array(
                                'name'  => 'nation',
                                'value' => function ($data) {
                                    return CHtml::encode($data->nation);
                                }
                            ),
                            array(
                                'name'  => 'job',
                                'value' => function ($data) {
                                    return CHtml::encode($data->job);
                                }
                            ),

                        ),
                    )); ?>
                </div>
                <?php if (isset($data_history_point) && !empty($data_history_point)):
                    ?>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                        <div class="table-responsive tbl_style center">
                            <span style="color:red; font-size: 18px;">* Lịch sử điểm </span>
                            <?php $this->widget('booster.widgets.TbGridView', array(
                                'id'            => 'point-history-customers',
                                'dataProvider'  => $data_history_point,
                                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                                'columns'       => array(
                                    array(
                                        'name'   => 'create_date',
                                        'type'   => 'raw',
                                        'filter' => FALSE,
                                        'value'  => 'CHtml::link($data->create_date, array(\'update\', \'id\' => $data->id))',
                                    ),
                                    array(
                                        'name'  => 'event',
                                        'type'  => 'raw',
                                        'value' => function ($data) {
                                            if ($data->amount < 0) {
                                                $type = APointHistory::TYPE_SUB;
                                            } else {
                                                $type = APointHistory::TYPE_ADD;
                                            }

                                            return APointHistory::model()->convertEvent($data->event, $type);
                                        },
                                    ),
                                    array(
                                        'name'   => 'amount',
                                        'type'   => 'raw',
                                        'filter' => FALSE,
                                        'value'  => 'CHtml::link($data->amount, array(\'update\', \'id\' => $data->id))',
                                    ),
                                    array(
                                        'name'   => 'amount_before',
                                        'type'   => 'raw',
                                        'filter' => FALSE,
                                        'value'  => 'CHtml::link($data->amount_before, array(\'update\', \'id\' => $data->id))',
                                    ),
                                    array(
                                        'name'   => 'note',
                                        'type'   => 'raw',
                                        'filter' => FALSE,
                                        'value'  => 'CHtml::link($data->note, array(\'update\', \'id\' => $data->id))',
                                    ),
                                ),
                            )); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<style>
    .summary {
        display: none;
    }
</style>
