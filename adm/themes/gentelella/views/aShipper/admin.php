<?php
    /* @var $this CskhShipperController */
    /* @var $model CskhShipper */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        'Quản lý NV giao vận' => array('admin'),
    );

    $this->menu = array(
        array('label' => Yii::t('cskh/menu', 'create'), 'url' => array('create')),
    );

    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cskh-shipper-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/menu', 'manage_shipper') ?></h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_search_admin', array('model' => $model_search)); ?>
    <div class="x_content">
        <div class="col-md-12 col-xs-12">
            <form method="post"
                  action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/shipperAdmin'); ?>"
                  name="fday">
                <input type="hidden" name="excelExport[province_code]"
                       value="<?php echo $model->province_code ?>">
                <input type="hidden" name="excelExport[sale_offices_code]"
                       value="<?php echo $model->sale_offices_code ?>">
                <input type="hidden" name="excelExport[post]"
                       value="<?php echo $post ?>">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px;margin-top: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>
        </div>
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'adm-shipper-grid',
            'dataProvider'  => $model->search(isset($post) ? $post : FALSE),
//                'dataProvider'  => $model->search(),
            'filter'        => $model,
            'enableSorting' => FALSE,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'        => 'username',
                    'type'        => 'raw',
                    'value'       => 'CHtml::encode($data->username)',
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'full_name',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::encode($data->full_name)',
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'phone_1',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::encode($data->phone_1)',
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),
                array(
                    'name'        => 'phone_2',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::encode($data->phone_2)',
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),

                array(
                    'header'      => 'TTKD',
//                        'filter'      => CHtml::activeDropDownList($model, 'province_code', Province::model()->getAllProvince(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode(Province::model()->getProvince($data->province_code));
                    },
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),
                array(
                    'header'      => 'Tên PBH',
//                        'filter'      => CHtml::activeDropDownList($model, 'sale_offices_code', SaleOffices::model()->getAllSaleOffices(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'type'        => 'raw',
                    'value'       => function ($data) {

                        return CHtml::encode(SaleOffices::model()->getSaleOfficesId($data->sale_offices_code));
                    },
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),
                array(
                    'header'      => 'Trạng thái',
//                        'filter'      => CHtml::activeDropDownList($model, 'status', array(AShipper::ACTIVE => AShipper::ACTIVE_TEXT, AShipper::INACTIVE => AShipper::INACTIVE_TEXT), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        if ($data->status == AShipper::ACTIVE) {
                            return AShipper::ACTIVE_TEXT;
                        } else {
                            return AShipper::INACTIVE_TEXT;
                        }
                    },
                    'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;'),
                ),


                /*
                'phone_2',
                'address_detail',
                'district_code',
                'province_code',
                'ward_code',
                'brand_office_id',
                'email',
                'otp',
                'gender',
                'birthday',
                'personal_id',
                'personal_id_create_date',
                'personal_id_create_place',
                'status',
                */
                array(
                    'header'      => 'Thao tác',
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'buttons'     => array(
                        'update' => array(
                            'label' => '',
                            'url'   => 'Yii::app()->createUrl("aShipper/update", array("id"=>$data->id))',
                        ),
                        'view'   => array(
                            'label' => '',
                            'url'   => 'Yii::app()->createUrl("aShipper/view", 
                            array("id"=>$data->id, "start_date" => "' . $model->start_date . '","end_date"=>"' . $model->end_date . '"))',
                        ),
                        'delete' => array(
                            'label'   => '',
                            'url'     => 'Yii::app()->createUrl("aShipper/delete", array("id"=>$data->id))',
                            'visible' => '(ADMIN || SUPER_ADMIN)',
                        ),
                    ),
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>
<style>
    .table td {
        text-align: right;
    }
</style>