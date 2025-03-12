<?php
/**
 * @var $this ReportController
 * @var $form ReportForm
 * @var $form_validate ReportForm
 * @var $model Report
 */
?>

<div class="x_panel">
    <div class="x_title">
        <h3>Doanh thu sim số</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_sim', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>

        <?php if (isset($data) && !empty($data)): ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="title">
                        <h5> * Doanh thu tổng quan</h5>
                    </div>
                </div>
                <div class="col-sm-6">
                    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportSim'); ?>" target="_blank">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                        <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $form->sale_office_code ?>">
                        <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                        <input type="hidden" name="excelExport[payment_method]" value="<?php echo $form->payment_method ?>">
                        <input type="hidden" name="excelExport[input_type]" value="<?php echo $form->input_type ?>">
                        <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $form->brand_offices_id ?>">
                        <input type="hidden" name="excelExport[item_sim_type]" value="<?php echo $form->item_sim_type ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                </div>
            </div>

            <?php
            $sum_total_sim_prepaid = 0;
            $sum_total_sim_postpaid = 0;
            $sum_revenue_sim_prepaid = 0;
            $sum_revenue_sim_postpaid = 0;
            $sum_revenue_sim = 0;
            foreach ($data->rawData as $order){
                $sum_total_sim_prepaid += $order->total_sim_prepaid;
                $sum_total_sim_postpaid += $order->total_sim_postpaid;
                $sum_revenue_sim_prepaid += $order->revenue_sim_prepaid;
                $sum_revenue_sim_postpaid += $order->revenue_sim_postpaid;
                $sum_revenue_sim += $order->renueve_sim;
            }
            ?>

            <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'dataProvider' => $data,
                    'type'        => 'striped bordered  consended ',
                    'htmlOptions' => array(
                        'class' => 'tbl_style',
                        'id'    => 'report_sim',
                    ),
                    'columns'     => array(
                        array(
                            'header'    => 'Tỉnh thành',
                            'value'     => function($data){
                                return AProvince::getProvinceNameByCode($data->province_code);
                            },
                            'footer'    => 'Tổng',
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'    => 'SL SIM trả trước',
                            'value'     => function($data){
                                return number_format($data->total_sim_prepaid, 0, ',', '.');
                            },
                            'footer'    => number_format($sum_total_sim_prepaid, 0, ',', '.'),
                            'htmlOptions'       => array('style' => 'text-align: right;'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'    => 'SL SIM trả sau',
                            'value'     => function($data){
                                return number_format($data->total_sim_postpaid, 0, ',', '.');
                            },
                            'footer'    => number_format($sum_total_sim_postpaid, 0, ',', '.'),
                            'htmlOptions'       => array('style' => 'text-align: right;'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'    => 'Doanh thu SIM trả trước',
                            'value'     => function($data){
                                return number_format($data->revenue_sim_prepaid, 0, ',', '.');
                            },
                            'footer'    => number_format($sum_revenue_sim_prepaid, 0, ',', '.'),
                            'htmlOptions'       => array('style' => 'text-align: right;'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'    => 'Doanh thu SIM trả sau',
                            'value'     => function($data){
                                return number_format($data->revenue_sim_postpaid, 0, ',', '.');
                            },
                            'footer'    => number_format($sum_revenue_sim_postpaid, 0, ',', '.'),
                            'htmlOptions'       => array('style' => 'text-align: right;'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'    => 'Tổng doanh thu',
                            'value'     => function($data){
                                return number_format($data->renueve_sim, 0, ',', '.');
                            },
                            'footer'    => number_format($sum_revenue_sim, 0, ',', '.'),
                            'htmlOptions'       => array('style' => 'text-align: right;'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                    ),
                ));
            ?>
        </div>
        <?php endif; ?>

        <div class="space_30"></div>

        <?php if ($form->on_detail == 'on'): ?>
            <?php if (isset($data_detail) && !empty($data_detail)): ?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="title">
                            <h5> * Danh sách chi tiết</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportSimDetail'); ?>" target="_blank">
                            <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                            <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                            <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                            <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $form->sale_office_code ?>">
                            <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                            <input type="hidden" name="excelExport[payment_method]" value="<?php echo $form->payment_method ?>">
                            <input type="hidden" name="excelExport[input_type]" value="<?php echo $form->input_type ?>">
                            <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $form->brand_offices_id ?>">
                            <input type="hidden" name="excelExport[item_sim_type]" value="<?php echo $form->item_sim_type ?>">
                            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                        </form>
                    </div>
                </div>

                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data_detail,
                        'type'         => 'striped bordered  consended ',
                        'htmlOptions'  => array(
                            'class' => 'tbl_style',
                            'id'    => 'report_sim_detail',
                        ),
                        'columns'      => array(
                            array('header' => 'STT',
                                  'value'  => '++$row',
                            ),
                            array(
                                'header'      => 'Mã đơn hàng',
                                'value'       => function ($data) {
                                    $return = $data->id;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Số thuê bao',
                                'value'       => function ($data) {
                                    $return = $data->sim;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Hình thức',
                                'value'       => function ($data) {

                                    $return = ASim::getTypeLabel($data->type_sim);

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Ngày kích hoạt',
                                'value'       => function ($data) {

                                    $return = $data->create_date;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),

                            array(
                                'header'      => 'TTKD',
                                'value'       => function ($data) {
                                    $value = AProvince::getProvinceNameByCode($data->province_code);
                                    return $value;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Phòng BH',
                                'value'       => function ($data) {
                                    $value = ASaleOffices::getSaleOfficesNameByCode($data->sale_office_code);
                                    return CHtml::encode($value);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Tiền đặt cọc',
                                'value'       => function ($data) {
                                    $value = number_format($data->price_term, 0, ",", ".");
                                    return $value;
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'value'       => function ($data) {
                                    $value = number_format($data->renueve_sim, 0, ",", ".");
                                    return $value;
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

