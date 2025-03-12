<?php
/**
 * @var $this           ReportController
 * @var $form           ReportForm
 * @var $form_validate  ReportForm
 * @var $model          Report
 * @var $data           CArrayDataProvider
 * @var $data_detail    CArrayDataProvider
 */
?>

<div class="x_panel">
    <div class="x_title">
        <h3><?= Yii::t('report/menu', 'report_index') ?></h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
        <?php if (isset($data) && !empty($data)): ?>
            <div class="col-md-6 top_col">
                <span class="title"> * Doanh thu tổng quan:</span>

                <?php
                $sum_revenue_sim = 0;
                $sum_revenue_package = 0;
                $sum_revenue_term = 0;
                $sum_revenue = 0;
                foreach ($data->rawData as $order){
                    $sum_revenue_sim += $order->renueve_sim;
                    $sum_revenue_package += $order->renueve_package;
                    $sum_revenue_term += $order->renueve_term;

                    if($order->type_sim == ASim::TYPE_POSTPAID){
                        $order->renueve = $order->renueve_sim + $order->renueve_term;
                    }else{
                        $order->renueve = $order->renueve_sim + $order->renueve_term + $order->renueve_package;
                    }
                    $sum_revenue += $order->renueve;
                }
                ?>

                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data,
                        'type'         => 'striped bordered consended ',
                        'htmlOptions'  => array(
                            'class' => 'tbl_style no-summary',
                            'id'    => 'report-synthetic',
                        ),
                        'columns'      => array(
                            array(
                                'header'      => 'Hình thức',
                                'value'       => function ($data) {
                                    $value = '';
                                    if ($data->type_sim == ASim::TYPE_PREPAID) {
                                        $value = "Trả trước";
                                    } else if($data->type_sim == ASim::TYPE_POSTPAID){
                                        $value = "Trả sau";
                                    }

                                    return CHtml::encode($value);
                                },
                                'footer'      => 'Tổng',
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Doanh thu Sim',
                                'value'       => function ($data) {
                                    $value = number_format($data->renueve_sim, 0, ',', '.');
                                    return CHtml::encode($value);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                                'footer'      => number_format($sum_revenue_sim, 0, ',', '.'),
                            ),
                            array(
                                'header'      => 'Doanh thu gói kèm sim',
                                'value'       => function ($data) {
                                    $return = number_format($data->renueve_package, 0, ',', '.');

                                    return CHtml::encode($return);
                                },
                                'footer'      => number_format($sum_revenue_package, 0, ',', '.'),
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Tiền đặt cọc',
                                'value'       => function ($data) {
                                    $return = number_format($data->renueve_term, 0, ',', '.');

                                    return CHtml::encode($return);
                                },
                                'footer'      => number_format($sum_revenue_term, 0, ',', '.'),
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Tổng doanh thu',
                                'value'       => function ($data) {
                                    $value = number_format($data->renueve, 0, ',', '.');
                                    return CHtml::encode($value);
                                },
                                'footer'      => number_format($sum_revenue, 0, ',', '.'),
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                            ),


                        ),
                    ));
                ?>
            </div>
        <?php endif; ?>

        <?php if ($form->on_detail == 'on'): ?>

            <?php if (isset($data_detail) && !empty($data_detail)):
                ?>
                <div class="col-md-12 top_col">
                    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportIndex'); ?>"
                          name="fday" target="_blank">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                        <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $form->sale_office_code ?>">
                        <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                        <input type="hidden" name="excelExport[payment_method]" value="<?php echo $form->payment_method ?>">
                        <input type="hidden" name="excelExport[delivery_type]" value="<?php echo $form->delivery_type ?>">
                        <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $form->brand_offices_id ?>">
                        <input type="hidden" name="excelExport[receive_status]" value="<?php echo $form->receive_status ?>">
                        <input type="hidden" name="excelExport[input_type]" value="<?php echo $form->input_type ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>

                    <span class="title"> * Doanh thu chi tiết:</span>
                    <?php
                        $this->widget('booster.widgets.TbGridView', array(
                            'dataProvider' => $data_detail,
                            'type'         => 'striped bordered consended ',
                            'htmlOptions'  => array(
                                'class' => 'tbl_style',
                                'id'    => 'report-synthetic-detail',
                            ),
                            'columns'      => array(
                                array('header' => 'STT',
                                      'value'  => '++$row',
                                ),
                                array(
                                    'header'      => 'Mã đơn hàng',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->id);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Trạng thái thu tiền',
                                    'value'       => function ($data) {
                                        $value = ReportForm::getNameReceiveStatus($data->receive_status);
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Phương thức thanh toán',
                                    'value'       => function ($data) {
                                        $value = AOrders::getPaymentMethod($data->payment_method);
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Ngày hoàn tất',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->delivery_date);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),

                                array(
                                    'header'      => 'Số thuê bao',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->sim);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Người hoàn tất',
                                    'value'       => function ($data) {
                                        if(!empty($data->shipper_name)){
                                            $value = $data->shipper_name;
                                        }else{
                                            $value = ALogsSim::getUserByOrder($data->id);
                                        }
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Tên gói',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->item_name);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),

                                array(
                                    'header'      => 'TTKD',
                                    'value'       => function ($data) {
                                        $value = AProvince::getProvinceNameByCode($data->province_code, FALSE);
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Phòng BH',
                                    'value'       => function ($data) {
                                        $value = ASaleOffices::getSaleOfficesNameByCode($data->sale_office_code, FALSE);
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Doanh thu sim',
                                    'value'       => function ($data) {
                                        $value = number_format($data->renueve_sim, 0, ',', '.');
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Doanh thu gói',
                                    'value'       => function ($data) {
                                        $value = number_format($data->renueve_package, 0, ',', '.');
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Tiền đặt cọc',
                                    'value'       => function ($data) {
                                        $value = number_format($data->renueve_term, 0, ',', '.');
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Tổng doanh thu',
                                    'value'       => function ($data) {
                                        if($data->type_sim == ASim::TYPE_POSTPAID){
                                            $revenue = $data->renueve_sim + $data->renueve_term;
                                        }else{
                                            $revenue = $data->renueve_sim + $data->renueve_term + $data->renueve_package;
                                        }
                                        $value = number_format($revenue, 0, ',', '.');
                                        return CHtml::encode($value);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),

                            ),
                        ));
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>

<style>
    .no-summary .summary {
        display: none;
        margin-top: 10px;
    }

    tfoot tr td:first-child {
        color: red;
        text-align: left !important;
        font-style: initial !important;
    }

    tfoot td {
        color: red;
        text-align: right !important;
        font-style: initial !important;
    }
</style>