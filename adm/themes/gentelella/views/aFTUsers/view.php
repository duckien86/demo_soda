<?php
/**
 * @var $this AFTUsersController
 * @var $model AFTUsers
 */

    $name = ($model->user_type == AFTUsers::USER_TYPE_AGENCY) ? $model->company : $model->username;

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_ft_users') => array('admin'),
        $name
    );

    $this->menu = array(
        array('label' => Yii::t('adm/label', 'manage_ft_users'), 'url' => array('admin')),
    );
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($name); ?></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $model,
                    'type'       => '',
                    'attributes' => array(
                        array(
                            'name'  => 'username',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->username);
                            },
                            'visible'   => $model->user_type == AFTUsers::USER_TYPE_AGENCY ? false : true
                        ),
                        array(
                            'name'  => 'fullname',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->fullname);
                            },
                        ),
                        array(
                            'name'  => 'company',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->company);
                            },
                        ),
                        array(
                            'name'  => 'email',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->email);
                            },
                        ),
                        array(
                            'name'  => 'phone',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->phone);
                            },
                        ),
                        array(
                            'name'  => 'tax_id',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->tax_id);
                            },
                        ),
                        array(
                            'name'  => 'address',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode($data->address);
                            },
                        ),
                        array(
                            'name'  => 'user_type',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode(AFTUsers::getTypeLabel($data->user_type));
                            },
                        ),
                        array(
                            'name'  => 'status',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return CHtml::encode(AFTUsers::model()->getNameStatusUsers($data->status));
                            },
                        ),
                        array(
                            'name'  => 'receive_method',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                $value = AFTUsers::getLabelReceiveMethod($data->receive_method);
                                return $value;
                            },
                            'visible'   => $model->user_type == AFTUsers::USER_TYPE_AGENCY ? true : false,
                        ),
                        array(
                            'name'  => 'receive_endpoint',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                $value = $data->receive_endpoint;
                                return CHtml::encode($value);
                            },
                            'visible'   => $model->user_type == AFTUsers::USER_TYPE_AGENCY ? true : false,
                        ),

//                        'password',
//                        'user_code',
//                        'email',
//                        'fullname',
//                        'mobile',
//                        'personal_id',
//                        'phone',
//                        'company',
//                        'user_type',
//                        'address',
//                        'tax_id',
//                        'bank_id',
//                        'bank_name',
//                        'agency_contract_number',
//                        'extra_info',
//                        'created_by',
//                        'created_date',
//                        'last_login',
//                        'status',
//                        'token_key',
//                        'agency_id',
//                        'verify_email',
//                        'system_username',
//                        'prefix',
//                        'suffix',
//                        'suffix_en',
//                        'advertiser_group_code',
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>
