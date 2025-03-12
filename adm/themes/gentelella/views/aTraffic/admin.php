<?php
    /* @var $this ATrafficController */
    /* @var $model ATraffic */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu', 'receive_shipper_manage') => array('admin'),
    );

    if (isset($show) && $show == TRUE) {
        $data_overview = array(
            'total_shipped' => 0,
            'total_received' => 0,
        );

        $data = $model->search(FALSE);

        foreach ($data as $order){
            if(empty($order->receive_cash_by) && empty($order->receive_cash_date)){
                $data_overview['total_shipped'] ++;
            }else if(!empty($order->receive_cash_by) && !empty($order->receive_cash_date)){
                $data_overview['total_received'] ++;
            }
        }
    }
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/menu', 'receive_shipper_manage'); ?></h2>
        <div class="clearfix"></div>

    </div>
    <?php $this->renderPartial('_search_admin', array('model' => $model_search, 'model_validate' => $model)); ?>

    <?php if (isset($show) && $show == TRUE): ?>
        <div class="x_content">
            <div class="table-responsive tbl_style center" style="width:40%;">
                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $data_overview,
                    'type'       => '',
                    'id'         => 'thutien_overview',
                    'attributes' => array(
                        array(
                            'name'    => 'Tổng đơn hàng chưa thu',
                            'value'   => function ($data) {
                                return Chtml::encode($data['total_shipped']);
                            },
                            'visible' => ($model->status_shipper == 1 || $model->status_shipper == ''),
                        ),
                        array(
                            'name'    => 'Tổng đơn hàng đã thu',
                            'value'   => function ($data) {
                                return Chtml::encode($data['total_received']);
                            },
                            'visible' => ($model->status_shipper == 2 || $model->status_shipper == ''),
                        ),
                        array(
                            'name'    => 'Tổng đơn hàng đã giao',
                            'value'   => function ($data) {
                                return Chtml::encode($data['total_received'] + $data['total_shipped']);
                            },
                            'visible' => ($model->status_shipper == ''),
                        ),
                    ),
                )); ?>
            </div>
            <div class="table-responsive tbl_style center">
                <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/orderReceive'); ?>" name="fday">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
                    <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code ?>">
                    <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $model->sale_office_code ?>">
                    <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $model->brand_offices_id ?>">
                    <input type="hidden" name="excelExport[status_shipper]" value="<?php echo $model->status_shipper ?>">
                    <input type="hidden" name="excelExport[payment_method]" value="<?php echo $model->payment_method ?>">
                    <input type="hidden" name="excelExport[delivery_type]" value="<?php echo $model->delivery_type ?>">
                    <input type="hidden" name="excelExport[item_sim_type]" value="<?php echo $model->item_sim_type ?>">
                    <input type="hidden" name="excelExport[post]" value="<?= isset($post) ? $post : FALSE ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'atraffic-receive-grid',
                    'itemsCssClass' => 'table table-responsive table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'dataProvider'  => $model->search(TRUE),
                    'filter'        => $model,
                    'type'          => 'post',
                    'enableSorting' => FALSE,
                    'columns'       => array(
                        array(
                            'header'      => 'TTKD',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = AProvince::getProvinceNameByCode($data->province_code, FALSE);

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                        ),
                        array(
                            'header'      => 'PBH',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = ASaleOffices::getSaleOfficesNameByCode($data->sale_office_code, FALSE);

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header' => 'Nhân viên hoàn tất',
                            'type'   => 'raw',
                            'filter' => FALSE,
                            'value'  => function ($data) {
                                if(!empty($data->shipper_name)){
                                    $value  = $data->shipper_name;
                                }else{
                                    $value = ALogsSim::getUserByOrder($data->id);
                                }
                                return CHtml::encode($value);
                            },

                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px'),
                        ),
                        array(
                            'header'      => 'Mã ĐH',
                            'value'       => function($data){
                                $value = $data->id;
                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                        ),
                        array(
                            'header'      => 'Ngày hoàn tất',
                            'value'       => function($data){
                                $value = $data->delivered_date;
                                return CHtml::encode($value);
                            },
                            'filter'      => FALSE,
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                        ),

                        array(
                            'header'      => 'Tiền SIM',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = number_format($data->price_sim, 0, '', '.');

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Tiền gói',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = number_format($data->price_package, 0, '', '.');

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Tiền đặt cọc',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = number_format($data->price_term, 0, '', '.');

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Tiền ship',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = number_format($data->price_ship, 0, '', '.');

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Phương thức thanh toán',
                            'filter'      => FALSE,
                            'value'       => function ($data) {

                                return CHtml::encode(ATraffic::getPaymentMethod($data->payment_method));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Tổng tiền',
                            'filter'      => FALSE,
                            'value'       => function ($data) {

                                $value = number_format($data->getTrafficTotalRevenue(), 0, '', '.');

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Trạng thái thu tiền',
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'status_shipper',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => $this->createUrl('aTraffic/showPopupReceive'),
                                'type'    => 'select',
                                'source'  => ATraffic::model()->getAllStatusTrafficAdmin(),
                                'options' => array(    //custom display
                                    'display' => 'js: function(value, sourceData) {
                                      var selected = $.grep(sourceData, function(o){ return value == o.value; }),
                                          colors = {1: "green", 2: "blue", 3: "red", 4: "gray"};
                                      $(this).text(selected[0].text).css("color", colors[value]);
                                      if (value==-1 || value==2){
                                        $(this).text(selected[0].text).css("color","black");
                                        $(this).unbind("click");
                                      }

                                    }'
                                ),
                                'success' => 'js:function(data){
                                    $(".popup_data").html(data);
                                    $("#modal_confirm").modal("show");
                               
                                }',
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),
                        ),
                        array(
                            'header'      => 'Người thu',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $value = $data->receiver;

                                return CHtml::encode($value);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:80px;'),
                        ),
                        array(
                            'template'    => '{view}',
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'buttons'     => array(
                                'view' => array(
                                    'options' => array('target' => '_blank'),
                                ),
                            ),
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),

                        ),
                    ),
                )); ?>
            </div>
            <div class="popup_data">
            </div>
        </div>
    <?php endif; ?>
</div>

<style type="text/css">
    .search_enhance {
        display: none;
    }

    #thutien_overview th {
        width: 300px !important;
    }
</style>
