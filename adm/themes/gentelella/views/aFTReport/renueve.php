<?php
/**
 * @var $this AFTReportController
 * @var $model AFTReport
 * @var $data CArrayDataProvider
 * @var $data_details CArrayDataProvider
 */
?>
<div class="x_panel">

    <div class="x_title">
        <h3><?php echo Yii::t('adm/menu', 'report_tourist_revenue') . ' - Sim KHDN' ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">

        <div class="col-md-12">
            <?php $this->renderPartial('_search_renueve', array('model' => $model)); ?>
        </div>
        <div class="col-md-6">
            <?php if (isset($data) && !empty($data)): ?>

                <h5 class="title">
                    * Doanh thu theo gói:
                </h5>

                <?php
                $total_output = 0;
                $total_revenue = 0;
                foreach ($data->rawData as $order){
                    $total_output += $order->total_success;
                    $total_revenue += $order->revenue;
                }
                ?>

                <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'dataProvider' => $data,
                    'type'         => 'striped bordered  consended ',
                    'htmlOptions'  => array(
                        'class' => 'tbl_style',
                        'id'    => 'thongsoChung',
                    ),
                    'columns'      => array(
                        array(
                            'header'      => 'Tên sản phẩm',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                return $data->package_name;
                            },
                            'footer'      => 'Tổng',
                            'htmlOptions' => array(
                                'style' => 'width:100px',
                            ),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'      => 'Sản lượng',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $value = number_format($data->total_success,0,',','.');
                                return $value;
                            },
                            'footer'      => number_format($total_output,0,',','.'),
                            'htmlOptions' => array(
                                'style' => 'width:100px;text-align:right;',
                            ),
                            'footerHtmlOptions' => array('style' => 'text-align: right; font-weight: bold;'),
                        ),
                        array(
                            'header'      => 'Doanh thu',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $value = number_format($data->revenue,0,',','.');
                                return $value;
                            },
                            'footer'      => number_format($total_revenue,0,',','.'),
                            'htmlOptions' => array(
                                'style' => 'width:100px;text-align:right;',
                            ),
                            'footerHtmlOptions' => array('style' => 'text-align: right; font-weight: bold;'),
                        ),

                    ),
                ));
                ?>

            <?php endif; ?>

        </div>

        <?php if(isset($data_details) && !empty($data_details) && $model->on_detail == 'on'): ?>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="title">
                            * Chi tiết
                        </h5>
                    </div>
                    <div class="col-sm-6">
                        <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportTouristRevenue'); ?>" target="_blank">
                            <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>"/>
                            <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>"/>
                            <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code ?>"/>
                            <input type="hidden" name="excelExport[order_type]" value="<?php echo $model->order_type ?>"/>
                            <input type="hidden" name="excelExport[status_order]" value="<?php echo $model->status_order ?>"/>
                            <input type="hidden" name="excelExport[package_id]" value="<?php echo $model->package_id ?>"/>

                            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                        </form>
                    </div>
                </div>

                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data_details,
                        'filter'       => $model,
                        'type'         => 'striped bordered  consended ',
                        'htmlOptions'  => array(
                            'class' => 'tbl_style',
                            'id'    => 'sim-detail',
                        ),
                        'columns'      => array(
                            array(
                                'header'      => 'STT',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => '++$row',
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Mã hợp đồng',
                                'filter'      => CHtml::activeTextField($model, 'contract_code', array('class' => 'form-control'))
                                    . CHtml::activeHiddenField($model,'start_date', array('id' => ''))
                                    . CHtml::activeHiddenField($model,'end_date', array('id' => ''))
                                    . CHtml::activeHiddenField($model,'province_code', array('id' => ''))
                                    . CHtml::activeHiddenField($model,'order_type', array('id' => ''))
                                    . CHtml::activeHiddenField($model,'status_order', array('id' => ''))
                                    . CHtml::activeHiddenField($model,'package_id', array('id' => ''))
                                    . CHtml::activeHiddenField($model,'on_detail', array('id' => ''))
                                ,
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $value = '';
                                    if($data->user_type != AFTUsers::USER_TYPE_CTV){
                                        $value = $data->contract_code;
                                    }
                                    return $value;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left; width: 100px;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Mã đơn hàng',
                                'filter'      => CHtml::activeTextField($model, 'order_code', array('class' => 'form-control')),
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    return $data->code;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left; width: 120px;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Khách hàng',
                                'filter'      => CHtml::activeTextField($model, 'customer', array('class' => 'form-control')),
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $value = '';
                                    if($data->user_type == AFTUsers::USER_TYPE_CTV){
                                        $arr = explode('@', $data->customer);
                                        $value = $arr[0].'(CTV)';
                                    }else{
                                        $value = $data->customer;
                                    }
                                    return $value;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left; width: 100px;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Sản phẩm',
                                'filter'      => CHtml::activeTextField($model, 'package_name', array('class' => 'form-control')),
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    return $data->package_name;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left; width: 100px;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Ngày đặt hàng',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => function($data){
                                    return $data->create_time;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Ngày hoàn tất',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => function($data){
                                    return $data->finish_date;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                            ),
                            array(
                                'header'      => 'Sản lượng',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $value = number_format($data->total_success,0,',','.');
                                    return $value;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: right;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $value = number_format($data->revenue,0,',','.');
                                    return $value;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: right;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                            ),
                            array(
                                'header'      => 'Trạng thái',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $status = AFTOrders::getStatusLabelOrderSim($data->status);
                                    $class = AFTOrders::getStatusClassOrderSim($data->status);
                                    $value = "<span class='$class'>$status</span>";
                                    return $value;
                                },
                                'htmlOptions' => array('style' => 'vertical-align: middle; text-align: center; width: 90px;'),
                                'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                            ),
                            array(
                                'header'      => 'Note',
                                'filter'      => false,
                                'type'        => 'raw',
                                'value'       => function($data){
                                    return $data->note;
                                }
                            )
                        ),
                    ));
                ?>
            </div>

        <?php endif; ?>

    </div>
</div>
<script type="text/javascript">

</script>
