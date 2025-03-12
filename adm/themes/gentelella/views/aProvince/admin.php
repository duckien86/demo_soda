<?php
/* @var $this AProvinceController */
/* @var $model AProvince */

$this->breadcrumbs = array(
    Yii::t('adm/menu','manage_business'),
    Yii::t('adm/menu','location'),
    Yii::t('adm/label', 'province') => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'province'); ?></h2>

<!--        <div class="pull-right">-->
<!--            --><?php //echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
<!--        </div>-->
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'aprovince-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'filter'       => $model,
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:80px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'name',
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'code',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'vnp_province_id',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList(
                            $model,
                            'status',
                            array(
                                AProvince::PROVINCE_ACTIVE   => Yii::t('adm/label', 'active'),
                                AProvince::PROVINCE_INACTIVE => Yii::t('adm/label', 'inactive')
                            ),
                            array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                        ),
                        'value'       => function ($data) {
                            if(empty($data->status)) $data->status = AProvince::PROVINCE_INACTIVE;
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    AProvince::PROVINCE_ACTIVE   => Yii::t('adm/label', 'active'),
                                    AProvince::PROVINCE_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                    'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '120px', 'style' => 'vertical-align:middle;'),
                    ),
//                    array(
//                        'header'      => '',
//                        'class'       => 'booster.widgets.TbButtonColumn',
//                        'template'    => '{view}',
//                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
//                    ),
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
                url: '<?=Yii::app()->controller->createUrl('aProvince/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#aprovince-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>
