<?php
/* @var $this AOrdersController */
/* @var $model AOrders */

$this->breadcrumbs = array(
    Yii::t('adm/menu','search'),
    Yii::t('adm/menu','order'),
    'ĐH Gói' => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Tra cứu đơn hàng gói đơn lẻ</h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_package_single', array('model' => $model)); ?>

    <div class="x_content">
<!--        <div class="table-responsive tbl_style center">-->
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/orderPackageSingle'); ?>" target="_blank">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date;?>" />
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date;?>" />
                        <input type="hidden" name="excelExport[item_id]" value="<?php echo $model->item_id;?>" />
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '', 'style'=>'float:right')); ?>
                    </form>
                </div>
            </div>
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'aorders-grid',
                'dataProvider'  => $model->searchPackageSingle(TRUE),
                'filter'        => $model,
                'enableSorting' => FALSE,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'header'      => 'Mã ĐH',
                        'name'        => 'id',
                        'value'       => function ($data) {
                            return CHtml::encode($data->id);
                        },
                        'htmlOptions' => array('style' => 'width:100px;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Số TB',
                        'name'        => 'phone_contact',
                        'value'       => function ($data) {
                            return CHtml::encode($data['phone_contact']);
                        },
                        'htmlOptions' => array('style' => 'width:100px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Kênh bán',
                        'name'        => 'channel',
                        'filter'      => false,
                        'value'       => function ($data) {
                            $value = '';
                            if(!empty($data->promo_code)){
                                $value = $data->promo_code;
                            }else if(!empty($data->affiliate_source)){
                                $value = $data->affiliate_source;
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Tên gói',
                        'name'        => 'item_name',
                        'filter'      => false,
                        'value'       => function ($data) {
                            return CHtml::encode($data->item_name);
                        },
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Thời gian mở gói',
                        'value'       => function ($data) {
                            return CHtml::encode($data->package_register_date);
                        },
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Doanh thu',
                        'value'       => function ($data) {
                            return CHtml::encode($data->total_renueve);
                        },
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Trạng thái',
                        'name'        => 'status',
                        'filter'      => false,
                        'value'       => function ($data) {
                            return Chtml::encode(AOrders::getStatus($data->id));
                        },
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Người thực hiện',
                        'name'        => 'user_id',
                        'filter'      => false,
                        'value'       => function ($data) {
                            return User::getUserName($data->user_id);
                        },
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'template'    => '{view}',
                        'buttons'     => array(
                            'view' => array(
                                'options' => array('target' => '_blank'),
                            ),
                        ),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
<!--    </div>-->
</div>
