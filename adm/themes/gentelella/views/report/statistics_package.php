<?php
/**
 * @var $this ReportController
 * @var $model Report
 * @var $form ReportForm
 * @var $form_validate ReportForm
 * @var $order ROrders
 */
?>

<div class="x_panel">
    <div class="x_title">
        <h3>Thống kê bán gói cước đơn lẻ</h3>
    </div>

    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_statistics_package', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>

        <?php if (isset($data) && !empty($data)): ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="title">
                        <h5> * Thống kê tổng quan</h5>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 no_pad">
            <?php
            $sum_total = 0;
            $sum_revenue = 0;
            foreach ($data->rawData as $order){
                $sum_total += $order->total_package;
                $sum_revenue += $order->renueve_package;
            }
            ?>

            <?php
            $this->widget('booster.widgets.TbGridView', array(
                'dataProvider' => $data,

                'type'        => 'striped bordered  consended ',
                'htmlOptions' => array(
                    'class' => 'tbl_style',
                    'id'    => 'report_statistics_package',
                ),
                'columns'     => array(
                    array(
                        'header'      => 'Tên gói',
                        'value'       => function ($data) {

                            $return = $data->item_name;

                            return $return;
                        },
                        'htmlOptions' => array(
                            'style' => 'width:100px; text-align:left;',
                        ),
                        'footer' => 'Tổng',
                        'footerHtmlOptions' => array(
                            'style' => 'font-weight: bold;',
                        ),
                    ),
                    array(
                        'header'      => 'Sản lượng',
                        'value'       => function ($data) {

                            $return = $data->total_package;

                            return $return;
                        },
                        'htmlOptions' => array(
                            'style' => 'width:100px; text-align:right;',
                        ),
                        'footer' => number_format($sum_total, 0, ",", "."),
                        'footerHtmlOptions' => array(
                            'style' => 'text-align:right; font-weight: bold;',
                        ),
                    ),
                    array(
                        'header'      => 'Doanh thu',
                        'value'       => function ($data) {

                            $return = number_format($data->renueve_package, 0, "", ".");

                            return $return;
                        },
                        'htmlOptions' => array(
                            'style' => 'width:100px; text-align:right;',
                        ),
                        'footer' => number_format($sum_revenue, 0, ",", "."),
                        'footerHtmlOptions' => array(
                            'style' => 'text-align:right; font-weight: bold;',
                        ),
                    ),
                ),
            ));
            ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="space_30"></div>

        <?php if ($form->on_detail == 'on'):?>
            <?php if (isset($data_detail) && !empty($data_detail)):?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="title">
                            <h5> * Thống kê chi tiết</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <form method="post"
                              action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportStatisticsPackage'); ?>"
                              name="fday">
                            <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                            <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                            <input type="hidden" name="excelExport[package_group]" value="<?php echo $form->package_group ?>">
                            <input type="hidden" name="excelExport[package_id]" value="<?php echo $form->package_id ?>">
                            <input type="hidden" name="excelExport[input_type]" value="<?php echo $form->input_type ?>">
                            <input type="hidden" name="excelExport[sim_freedoo]" value="<?php echo $form->sim_freedoo ?>">
                            <input type="hidden" name="excelExport[online_status]" value="<?php echo $form->online_status ?>">
                            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                        </form>
                    </div>
                </div>

                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data_detail,

                        'type'        => 'striped bordered  consended ',
                        'htmlOptions' => array(
                            'class' => 'tbl_style',
                            'id'    => 'report_detail_statistics_package',
                        ),
                        'columns'     => array(
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

                                    $return = $data->phone_contact;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'        => 'Kênh bán',
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
                            array(
                                'header'      => 'Loại thuê bao',
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $value = ($data->sim_freedoo == 1) ? 'Freedoo' : 'Vinaphone';
                                    return $value;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Tên gói',
                                'value'       => function ($data) {

                                    $return = $data->item_name;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Ngày mua',
                                'value'       => function ($data) {

                                    $return = $data->create_date;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px;;',
                                ),
                            ),
                            array(
                                'header'      => 'Nhóm gói',
                                'value'       => function ($data) {

                                    $return = Report::getTypeName($data->type_package);

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'value'       => function ($data) {

                                    $return = number_format($data->renueve_package, 0, "", ".");

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Trạng thái',
                                'value'       => function($data){
                                    return AOrders::getStatus($data->id);
                                },
                                'htmlOptions' => array(
                                ),
                            )
                        ),
                    ));
                ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
