<?php
/* @var $this AOrdersController */
/* @var $model AOrders */

$this->breadcrumbs = array(
    Yii::t('adm/menu','search'),
    Yii::t('adm/menu','order'),
    'ĐH SIM' => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'orders'); ?></h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>

    <div class="x_content">

        <div class="row note">
            <div class="left">
                <span class="prepaid"></span> Trả trước <br/>
                <span class="postpaid"></span> Trả sau
            </div>
            <div class="right">
                <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/orderAdminTest'); ?>" target="_blank">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
                    <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code ?>">
                    <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $model->sale_office_code ?>">
                    <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $model->brand_offices_id ?>">
                    <input type="hidden" name="excelExport[delivery_type]" value="<?php echo $model->delivery_type ?>">
                    <input type="hidden" name="excelExport[period]" value="<?php echo $model->period ?>">
                    <input type="hidden" name="excelExport[status_shipper]" value="<?php echo $model->status_shipper ?>">
                    <input type="hidden" name="excelExport[channel]" value="<?php echo $model->channel ?>">
                    <input type="hidden" name="excelExport[affiliate_source]" value="<?php echo $model->affiliate_source ?>">
                    <input type="hidden" name="excelExport[promo_code]" value="<?php echo $model->promo_code ?>">
                    <input type="hidden" name="excelExport[is_pre_order]" value="<?php echo $model->is_pre_order ?>">
                    <input type="hidden" name="excelExport[item_sim_type]" value="<?php echo $model->item_sim_type ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
            </div>
        </div>

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'aorders-grid',
                'dataProvider'  => $model->search(TRUE),
                'filter'        => $model,
                'enableSorting' => FALSE,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'channel',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            if(!empty($data->promo_code)){
                                $value = $data->promo_code;
                            }else if(!empty($data->affiliate_source)){
                                $value = $data->affiliate_source;
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:80px;vertical-align:middle; text-transform: capitalize'),
                    ),
//                    array(
//                        'header'        => 'Hoa hồng tạm tính',
//                        'filter'        => false,
//                        'type'          => 'raw',
//                        'value'         => function($data){
//                            $value = '';
//                            $create_date = date('Y-m-d',strtotime(str_replace('/','-', $data->create_date)));
//                            $accept_rose_date = date('Y-m-d', strtotime('2018-07-01'));
//                            if( ($create_date >= $accept_rose_date) && (!empty($data->affiliate_source) || !empty($data->promo_code)) ){
//                                $rose_sim = $data->getRoseSimProvisional();
//                                $rose_package = $data->getRosePackageProvisional();
//                                $value = number_format($rose_sim+$rose_package, 0, ',' , '.') . ' đ';
//                            }
//                            return $value;
//                        },
//                        'htmlOptions' => array('style' => 'width:80px;vertical-align:middle'),
//                    ),
                    array(
                        'name'        => 'sim',
                        'value'       => function ($data) {
                            return $data->sim;
                        },
                        'cssClassExpression' => ' 
                            $data->type_sim == 1 ? "prepaid" : "postpaid" 
                        ',
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'header'      => 'Loại TB',
//                        'filter'      => FALSE,
//                        'value'       => function ($data) {
//                            $value = ASim::getTypeLabel($data->type_sim);
//                            return $value;
//                        },
//                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
//                    ),
                    array(
                        'name'        => 'full_name',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:110px;'),
                    ),
                    array(
                        'name'        => 'phone_contact',
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'name'        => 'ward_code',
//                        'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'address_detail',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return $data->getAddress();
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:150px;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = date("d/m/Y H:i:s", strtotime($data->create_date));
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:110px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Ngày đặt hàng',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = (!empty($data->pre_order_date)) ? date("d/m/Y H:i:s", strtotime($data->pre_order_date)) : '';
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:110px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status_end',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {

                            return Chtml::encode(AOrders::getStatus($data->id));
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                    ),
//                    array(
//                        'name'        => 'time_left',
//                        'type'        => 'raw',
//                        'filter'      => FALSE,
//                        'value'       => function ($data) {
//                            $status = AOrders::getStatus($data->id);
//                            if ($status != "Hoàn thành") {
//                                return CHtml::encode(AOrders::model()->getTimeLeft($data->create_date));
//                            } else {
//                                return "Hoàn thành";
//                            }
//                        },
//                        'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'Trạng thái GV',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            if ($data->delivery_type == 1) {
                                return CHtml::encode(AOrders::model()->getStatusTraffic($data->getTrafficStatus($data->id)));
                            } else {
                                return 'Nhận tại ĐGD';
                            }
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header' => 'Kho số',
                        'type' => 'raw',
                        'value' => function($data){
                            return Yii::app()->params['stock_config'][$data->store_id];
                        }
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
    </div>
</div>

<style>
    .prepaid{
        color: #ed0678;
    }
    .postpaid{
        color: #00a1e4;
    }
    .note{
        padding-left: 15px;
        padding-right: 15px;
    }
    .note span{
        display: inline-block;
        width: 16px;
        height: 10px;
    }
    .note span.prepaid{
        background: #ed0678;
    }
    .note span.postpaid{
        background: #00a1e4;
    }
</style>


<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
</script>