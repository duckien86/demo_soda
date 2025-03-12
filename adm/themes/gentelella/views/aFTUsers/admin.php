<?php
    /* @var $this AFTUsersController */
    /* @var $model AFTUsers */
    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_ft_users') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_ft_users'); ?></h2>
        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'            => 'aftusers-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'        => 'username',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeTextField($model, 'username', array('class' => 'form-control')),
                    'value'       => function($data){
                        $value = '';
                        if($data->user_type == AFTUsers::USER_TYPE_CTV){
                            $arr = explode('@',$data->username);
                            $value = $arr[0];
                        }else{
                            $value = $data->username;
                        }
                        return Chtml::encode($value);
                    },
                    'htmlOptions' => array('style' => 'width: 120px; word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'email',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeTextField($model, 'email', array('class' => 'form-control')),
                    'value'       => 'CHtml::encode($data->email)',
                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'fullname',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeTextField($model, 'fullname', array('class' => 'form-control')),
                    'value'       => 'CHtml::encode($data->fullname)',
                    'htmlOptions' => array('style' => 'width: 180px;word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'company',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeTextField($model, 'company', array('class' => 'form-control')),
                    'value'       => 'CHtml::encode($data->company)',
                    'htmlOptions' => array('style' => 'width: 160px; word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'user_type',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeDropDownList($model, 'user_type', AFTUsers::getListType(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'value'       => function($data){
                        return CHtml::encode(AFTUsers::getTypeLabel($data->user_type));
                    },
                    'htmlOptions' => array('style' => 'width: 140px; word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'status',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeDropDownList($model, 'status', AFTUsers::model()->getStatusUsers(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'value'       => function ($data) {
                        $value = '';
                        if($data->user_type == AFTUsers::USER_TYPE_CTV){
                            $value = AFTUsers::model()->getNameStatusUsers($data->status);
                        }else{
                            $value = CHtml::activeDropDownList($data, 'status',
                                AFTUsers::model()->getStatusUsers(),
                                array('class'    => 'dropdownlist',
                                    'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        }

                        return $value;
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),

                /*
                'mobile',
                'personal_id',
                'phone',
                'company',
                'user_type',
                'address',
                'tax_id',
                'bank_id',
                'bank_name',
                'agency_contract_number',
                'extra_info',
                'created_by',
                'created_date',
                'last_login',
                'status',
                'token_key',
                'agency_id',
                'verify_email',
                'system_username',
                'prefix',
                'suffix',
                'suffix_en',
                'advertiser_group_code',
                */
                array(
                    'header'      => 'Chi tiết',
                    'template'    => '{view} {update}',
                    'buttons'     => array(
                        'view' => array(
                            'options' => array('target' => '_blank'),
                        ),
                        'update' => array(
                            'visible' => '$data->getBtnUpdate()'
                        )
                    ),
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>
<style>
    .filters {
        color: black;
    }
</style>
<script type="text/javascript">
    function changeStatus(id, status) {
        var r = confirm('Bạn có chắc chắn muốn thay đổi!');
        if (r == true) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->createUrl('aFTUsers/setStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
                success: function (result) {
                    if (result === true) {
                        $('#aftusers-grid').yiiGridView('update', {
                            data: $(this).serialize()
                        });
                        window.location.reload();
                        return true;
                    }
                }
            });
        }
    }
</script>
