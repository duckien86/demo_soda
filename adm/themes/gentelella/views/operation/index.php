<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'orders') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Chỉnh sửa đơn hàng</h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_search', array('model' => $model, 'model_validate' => $model)); ?>
    <div class="x_content">
        <?php if ($post == 1):
            ?>
            <div class="table-responsive tbl_style center">
                <h5 style="margin-bottom: -15px;">Đơn hàng <span style="color:red;"><?= $model->id ?></span></h5>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-grid',
                    'dataProvider'  => $model->search_tool(isset($post) ? $post : FALSE),
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'create_date',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrders'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'name'        => 'Loại TB',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                return CHtml::encode(AOrders::model()->getTypeSimByOrder($data->id));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'delivery_type',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeDataOrders'),
                                'type'    => 'select',
                                'source'  => AOrders::model()->getAllDeliveredType(),
                                'options' => array(    //custom display

                                ),
                                'success' => 'js:function(data){              
                                    window.location.reload();
                                }',
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),

                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'payment_method',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeDataOrders'),
                                'type'    => 'select',
                                'source'  => AOrders::model()->getPaymentMethodOperation(),
                                'options' => array(    //custom display
                                ),
                                'success' => 'js:function(data){              
                                    window.location.reload();
                                }',
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),

                        ),

                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'sale_office_code',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeDataOrders'),
                                'type'    => 'select',
                                'source'  => SaleOffices::model()->getSaleOfficesByOrderId($model->id),
                                'options' => array(    //custom display

                                ),
                                'success' => 'js:function(data){              
                                    window.location.reload();
                                }',
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),
                            'visible'     => AOrders::model()->checkExistProvince($model->id) == TRUE,
                        ),

                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'sale_office_code',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrders'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            ),
                            'visible'  => AOrders::model()->checkExistProvince($model->id) == FALSE,

                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'address_detail',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeDataOrders'),
                                'type'    => 'select',
                                'source'  => BrandOffices::model()->getBrandOfficesByOrderId($model->id),
                                'options' => array(    //custom display

                                ),
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),
                            'visible'     => AOrders::model()->getDeliveredTypeByOrder($model->id) == 2,
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'address_detail',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrders'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            ),
                            'visible'  => AOrders::model()->getDeliveredTypeByOrder($model->id) == 1,

                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'note',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrders'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'otp',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrders'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),


                    ),
                )); ?>
            </div>

            <div class="table-responsive tbl_style center">
                <h5 style="margin-bottom: -15px; margin-top: 15px;">Chi tiết đơn hàng</h5>

                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-details-grid',
                    'dataProvider'  => $model_details->search(isset($post) ? $post : FALSE),
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'order_id',
                            'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'type',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeDataOrderDetails'),
                                'type'    => 'select',
                                'source'  => AOrderDetails::model()->getAllType(),
                                'options' => array(    //custom display

                                ),
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'item_name',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrderDetails'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'price',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrderDetails'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'header'      => Yii::t('adm/actions', 'action'),
                            'template'    => '{delete}',
                            'buttons'     => array(
                                'delete' => array(
                                    'label'   => '',
                                    'url'     => 'Yii::app()->createUrl("operation/deleteDetails", array("id"=>$data->order_id,"type"=>$data->type))',
                                    'visible' => '(ADMIN || SUPER_ADMIN)',
                                    'click'   => "function(){
                                        if(!confirm('Bạn có chắc muốn xóa!')) return false;
                                            $.fn.yiiGridView.update('aorders-details-grid', {
                                                type:'GET',
                                                url:$(this).attr('href'),
                                                success:function(text,status) {
         
                                                    window.location.reload();
                                                }
                                            });
                                        return false;
                                    }",
                                ),
                            ),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
                        ),

                    ),
                )); ?>
            </div>
            <div class="table-responsive tbl_style center">
                <h5 style="margin-bottom: -15px; margin-top: 15px; color:red;">Trạng thái đơn hàng</h5><br/>
                <h5 style="color:red;">- Sửa trạng thái dòng cuối cùng:</h5>
                <h5 style="color:blue"> &nbsp; - Thanh toán COD </h5>
                <h5>   &nbsp;&nbsp; + Đă đặt hàng : 10 0 0 </h5>
                <h5>   &nbsp;&nbsp; + Đã thanh toán : 0 10 0</h5>
                <h5>   &nbsp;&nbsp; + Hoàn tất : 0 0 10 (Sửa cả trong trạng thái giao vận nếu có)</h5>
                <h5>   &nbsp;&nbsp; + Hủy : 2 0 0 (Sửa cả trong trạng thái giao vận nếu có)</h5>
                <h5>   &nbsp;&nbsp; + Gửi trả : 3 0 0 (Sửa cả trong trạng thái giao vận nếu có)</h5>

                <h5 style="color:blue"> &nbsp; - Thanh toán COD - Thanh toán Online</h5>
                <h5>   &nbsp;&nbsp; + Đă đặt hàng : 10 10 0 </h5>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-state-grid',
                    'dataProvider'  => $model_state->searchOperation(isset($post) ? $post : FALSE),
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'order_id',
                            'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'create_date',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrderState'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'confirm',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrderState'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'paid',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrderState'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'delivered',
                            'header'   => 'Hoàn tất',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataOrderState'),
//                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),


                        array(
                            'header'      => Yii::t('adm/actions', 'action'),
                            'template'    => '{delete}',
                            'buttons'     => array(
                                'delete' => array(
                                    'label'   => '',
                                    'url'     => 'Yii::app()->createUrl("operation/deleteState", array("id"=>$data->id))',
                                    'visible' => '(ADMIN || SUPER_ADMIN)',
                                    'click'   => "function(){
                                        if(!confirm('Bạn có chắc muốn xóa!')) return false;
                                            $.fn.yiiGridView.update('aorders-state-grid', {
                                                type:'GET',
                                                url:$(this).attr('href'),
                                                success:function(text,status) {
         
                                                    window.location.reload();
                                                }
                                            });
                                        return false;
                                    }",
                                ),
                            ),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
                        ),

                    ),
                )); ?>
            </div>
            <div class="table-responsive tbl_style center">
                <h5 style="margin-bottom: -15px; margin-top: 15px; color: red;">Trạng thái giao vận (nếu có). </h5><br/>
                <h5>- Gửi trả: sửa trạng thái gửi trả thành 2</h5>
                <h5>- Hủy: sửa trạng thái gửi trả  thành 2</h5>
                <h5>- Ko gửi trả:</h5>
                <h5>   &nbsp;&nbsp; + sửa trạng thái giao vận thành 1 : Đã giao</h5>
                <h5>   &nbsp;&nbsp; + sửa trạng thái giao vận thành 0 : Chưa giao</h5>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-shipper-order-grid',
                    'dataProvider'  => $model_shipper_order->search(isset($post) ? $post : FALSE),
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'header'      => 'Mã ĐH',
                            'name'        => 'id',
                            'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'status',
                            'header'   => 'Trạng thái giao vận',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataShipperOrder'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'order_status',
                            'header'   => 'Trạng thái gửi trả',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeDataShipperOrder'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                    ),
                )); ?>
            </div>
            <div class="table-responsive tbl_style center">
                <h5 style="margin-bottom: -15px; margin-top: 15px; color: red;">Thông tin sim. </h5><br/>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-shipper-order-grid',
                    'dataProvider'  => $model_sim->search(isset($post) ? $post : FALSE),
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'header'      => 'Mã ĐH',
                            'name'        => 'order_id',
                            'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'msisdn',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeSim'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'serial_number',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeSim'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'price',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeSim'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'type',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeSim'),
                                'type'    => 'select',
                                'source'  => ASim::model()->getAllType(),
                                'options' => array(    //custom display

                                ),
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),
                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'status',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeSim'),
                                'type'    => 'select',
                                'source'  => ASim::model()->getAllStatus(),
                                'options' => array(    //custom display

                                ),
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),
                        ),
                    ),
                )); ?>
            </div>
            <div class="table-responsive tbl_style center">
                <h5 style="margin-bottom: -15px; margin-top: 15px; color: red;">Logs sim. </h5><br/>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-sim-grid',
                    'dataProvider'  => $model_logs_sim->search(isset($post) ? $post : FALSE),
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'class'    => 'booster.widgets.TbEditableColumn',
                            'name'     => 'create_date',
                            'sortable' => FALSE,
                            'editable' => array(
                                'url'        => Yii::app()->createUrl('operation/changeLogsSim'),
                                'placement'  => 'right',
                                'inputclass' => 'span3'
                            )
                        ),
                        array(
                            'header'      => 'Mã ĐH',
                            'name'        => 'order_id',
                            'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Số điện thoại',
                            'name'        => 'msisdn',
                            'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                        ),

                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'registered',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeLogsSim'),
                                'type'    => 'select',
                                'source'  => ALogsSim::model()->getAllRegisterFor(),
                                'options' => array(    //custom display

                                ),
                                'success' => 'js:function(data){              
                                    window.location.reload();
                                }',
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),

                        ),
                        array(
                            'class'       => 'booster.widgets.TbEditableColumn',
                            'name'        => 'status',
                            'filter'      => FALSE,
                            'editable'    => array(
                                'url'     => Yii::app()->createUrl('operation/changeLogsSim'),
                                'type'    => 'select',
                                'source'  => ALogsSim::model()->getAllStatus(),
                                'options' => array(    //custom display

                                ),
                                'success' => 'js:function(data){              
                                    window.location.reload();
                                }',
                            ),
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:left;',

                            ),

                        ),
                        array(
                            'header'      => Yii::t('adm/actions', 'action'),
                            'template'    => '{delete}',
                            'buttons'     => array(
                                'delete' => array(
                                    'label'   => '',
                                    'url'     => 'Yii::app()->createUrl("operation/deleteLogSim", array("id"=>$data->id))',
                                    'visible' => '(ADMIN || SUPER_ADMIN)',
                                    'click'   => "function(){
                                        if(!confirm('Bạn có chắc muốn xóa!')) return false;
                                            $.fn.yiiGridView.update('aorders-sim-grid', {
                                                type:'GET',
                                                url:$(this).attr('href'),
                                                success:function(text,status) {
         
                                                    window.location.reload();
                                                }
                                            });
                                        return false;
                                    }",
                                ),
                            ),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
                        ),
                    ),
                )); ?>
            </div>

        <?php endif; ?>
    </div>
</div>
<style>
    #content {
        overflow-x: scroll;
    }
</style>
<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
</script>