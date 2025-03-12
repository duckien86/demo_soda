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
        <h3>Doanh thu bán gói cước đơn lẻ</h3>
    </div>

    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_package', array('model' => $form, 'form_validate' => $form_validate)); ?>
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
                    <form method="post"
                          action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportPackageSingle'); ?>"
                          name="fday">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[package_group]" value="<?php echo $form->package_group ?>">
                        <input type="hidden" name="excelExport[package_id]" value="<?php echo $form->package_id ?>">
                        <input type="hidden" name="excelExport[input_type]" value="<?php echo $form->input_type ?>">
                        <input type="hidden" name="excelExport[sim_freedoo]" value="<?php echo $form->sim_freedoo ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                </div>
            </div>

            <?php
            $sum_total_prepaid  = 0;
            $sum_total_postpaid = 0;
            $sum_total_data     = 0;
            $sum_total_vas      = 0;
            $sum_total_roaming  = 0;
            $sum_revenue        = 0;
            foreach ($data->rawData as $order){
                switch ($order->type_package){
                    case APackage::PACKAGE_PREPAID:
                        $sum_total_prepaid+= $order->total;
                        break;
                    case APackage::PACKAGE_POSTPAID:
                        $sum_total_postpaid+= $order->total;
                        break;
                    case APackage::PACKAGE_DATA:
                        $sum_total_data+= $order->total;
                        break;
                    case APackage::PACKAGE_VAS:
                        $sum_total_vas+= $order->total;
                        break;
                    case APackage::PACKAGE_ROAMING:
                        $sum_total_roaming+= $order->total;
                        break;
                }
                $sum_revenue += $order->renueve_package;
            }
            ?>

            <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'dataProvider' => $data,

                    'type'        => 'striped bordered  consended ',
                    'htmlOptions' => array(
                        'class' => 'tbl_style',
                        'id'    => 'report_package',
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
                            'header'      => 'Trả trước',
                            'value'       => function ($data) {
                                $return = ($data->type_package == APackage::PACKAGE_PREPAID)
                                    ? number_format($data->total, 0, ',', '.') : "";
                                return $return;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px; text-align:right;',
                            ),
                            'footer' => number_format($sum_total_prepaid, 0, ",", "."),
                            'footerHtmlOptions' => array(
                                'style' => 'text-align:right; font-weight: bold;',
                            ),
                        ),
                        array(
                            'header'      => 'Trả sau',
                            'value'       => function ($data) {
                                $return = ($data->type_package == APackage::PACKAGE_POSTPAID)
                                    ? number_format($data->total, 0, ',', '.') : "";
                                return $return;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px; text-align:right;',
                            ),
                            'footer' => number_format($sum_total_postpaid, 0, ",", "."),
                            'footerHtmlOptions' => array(
                                'style' => 'text-align:right; font-weight: bold;',
                            ),
                        ),
                        array(
                            'header'      => 'Data',
                            'value'       => function ($data) {
                                $return = ($data->type_package == APackage::PACKAGE_DATA)
                                    ? number_format($data->total, 0, ',', '.') : "";
                                return $return;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px; text-align:right;',
                            ),
                            'footer' => number_format($sum_total_data, 0, ",", "."),
                            'footerHtmlOptions' => array(
                                'style' => 'text-align:right; font-weight: bold;',
                            ),
                        ),
                        array(
                            'header'      => 'Vas',
                            'value'       => function ($data) {
                                $return = ($data->type_package == APackage::PACKAGE_VAS)
                                    ? number_format($data->total, 0, ',', '.') : "";
                                return $return;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px; text-align:right;',
                            ),
                            'footer' => number_format($sum_total_vas, 0, ",", "."),
                            'footerHtmlOptions' => array(
                                'style' => 'text-align:right; font-weight: bold;',
                            ),
                        ),
                        array(
                            'header'      => 'Roaming',
                            'value'       => function ($data) {
                                $return = ($data->type_package == APackage::PACKAGE_ROAMING)
                                    ? number_format($data->total, 0, ',', '.') : "";
                                return $return;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px; text-align:right;',
                            ),
                            'footer' => number_format($sum_total_roaming, 0, ",", "."),
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
        <?php endif; ?>

        <div class="space_30"></div>

        <?php if ($form->on_detail == 'on'):?>
            <?php if (isset($data_detail) && !empty($data_detail)):?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="title">
                            <h5> * Danh sách chi tiết</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <form method="post"
                              action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportPackageSingleDetail'); ?>"
                              name="fday">
                            <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                            <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                            <input type="hidden" name="excelExport[package_group]" value="<?php echo $form->package_group ?>">
                            <input type="hidden" name="excelExport[package_id]" value="<?php echo $form->package_id ?>">
                            <input type="hidden" name="excelExport[input_type]" value="<?php echo $form->input_type ?>">
                            <input type="hidden" name="excelExport[sim_freedoo]" value="<?php echo $form->sim_freedoo ?>">
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
                            'id'    => 'report_package_detail',
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
                                    'style' => '',
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


                        ),
                    ));
                ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
