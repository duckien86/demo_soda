<?php
    /* @var $this ANewsController */
    /* @var $model ANews */
    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','forum'),
        Yii::t('adm/label', 'manage_customer') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_customer'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">

            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'acustomers-grid',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'username',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->username), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->phone), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;text-align:left;'),
                    ),
                    array(
                        'name'        => 'email',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->email), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:200px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'bonus_point',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->bonus_point), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;text-align:right;'),
                    ),
                    array(
                        'name'        => 'province_code',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => 'CHtml::link(CHtml::encode($data->getProvince($data->province_code)), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'level',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode(ACustomers::getLevel($data->bonus_point));
                        },
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ACustomers::ACTIVE   => Yii::t('adm/label', 'active'),
                                    ACustomers::INACTIVE => Yii::t('adm/label', 'inactive'),
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '150px', 'style' => 'vertical-align:middle;'),
                    ),
                    /*
                    'bonus_point',
                    'create_time',
                    'last_update',
                    'otp',
                    'full_name',
                    'genre',
                    'customer_type',
                    'district_code',
                    'province_code',
                    'address_detail',
                    'personal_id',
                    'personal_id_create_date',
                    'personal_id_create_place',
                    'extra_info',
                    'bank_account_id',
                    'bank_brandname',
                    'bank_name',
                    'bank_account_name',
                    'job',
                    'status',
                    'nation',
                    'avatar',
                    'profile_picture',
                    'level',
                    */
                    array(
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view}',
                        'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
<script language="javascript">
    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aCustomers/changeStatus')?>',
                crossDomain: true,
                data: {id: id, status: status},
                success: function (result) {
                    window.location.reload();
                    return false;
                }
            });
        } else {
            window.location.reload();
        }
    }
</script>