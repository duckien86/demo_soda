<div class="x_panel">

    <div class="x_title">
        <h3><?= Yii::t('report/menu', 'report_online_paid') ?></h3>
    </div>
    <div class="clearfix"></div>


    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_online_paid', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
        <?php if (isset($data) && !empty($data)):
            ?>
            <div class="col-md-12 top_col">
                <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportOnlinePaid'); ?>" name="fday">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                    <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                    <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $form->sale_office_code ?>">
                    <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                    <input type="hidden" name="excelExport[payment_method]" value="<?php echo $form->payment_method ?>">
                    <input type="hidden" name="excelExport[delivery_type]" value="<?php echo $form->delivery_type ?>">
                    <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $form->brand_offices_id ?>">
                    <input type="hidden" name="excelExport[online_status]" value="<?php echo $form->online_status ?>">
                    <input type="hidden" name="excelExport[status_type]" value="<?php echo $form->status_type ?>">
                    <input type="hidden" name="excelExport[paid_status]" value="<?php echo $form->paid_status ?>">
                    <input type="hidden" name="excelExport[item_sim_type]" value="<?php echo $form->item_sim_type ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
                <span class="title"> * Doanh thu chi tiết:</span>
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data,
                        'type'         => 'striped bordered  consended ',
                        'htmlOptions'  => array(
                            'class' => 'tbl_style',
                            'id'    => 'sim-detail',
                        ),
                        'columns'      => array(
                            array('header' => 'STT',
                                  'value'  => '++$row',
                            ),
                            array(
                                'header'      => 'Mã đơn hàng',
                                'value'       => function ($data) {

                                    $return = $data['order_id'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Số thuê bao',
                                'value'       => function ($data) {
                                    $return = '';
                                    if ($data['sim'] != '') {
                                        $return = $data['sim'];
                                    } else {
                                        $return = $data['phone_contact'];
                                    }

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Phương thức thanh toán',
                                'value'       => function ($data) {

                                    $return = ReportForm::getPaymentMethod($data['payment_method']);

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Ngày thanh toán',
                                'value'       => function ($data) {

                                    $return = Report::getPaidDate($data['order_id']);

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),

                            array(
                                'header'      => 'TTKD',
                                'value'       => function ($data) {
                                    $return = AProvince::model()->getProvince($data['province_code']);

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Phòng bán hàng',
                                'value'       => function ($data) {
                                    $return = ASaleOffices::model()->getSaleOffices($data['sale_office_code']);

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),

                            array(
                                'header'      => 'Doanh thu sim',
                                'value'       => function ($data) {
                                    $return = $data['price_sim'];

                                    return CHtml::encode(number_format($return, 0, "", "."));
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px;text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Doanh thu gói',
                                'value'       => function ($data) {
                                    $price_package = $data['price_package'];

                                    return CHtml::encode(number_format($price_package, 0, "", "."));
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px;text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Tiền đặt cọc',
                                'value'       => function ($data) {
                                    $price_item = $data['price_term'];

                                    return CHtml::encode(number_format($price_item, 0, "", "."));
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px;text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Tổng doanh thu',
                                'value'       => function ($data) {
                                    $total_price = 0;
                                    $sim         = ASim::model()->findByAttributes(array('order_id' => $data['order_id']));
                                    if ($sim) {
                                        if ($data['price_term'] > 0) {
                                            $total_price = $data['price_term'] + $data['price_sim'];
                                        } else {
                                            if ($sim->type == 2) {
                                                $total_price = $data['price_sim'];
                                            } else {
                                                $total_price = $data['price_sim'] + $data['price_package'];
                                            }
                                        }
                                    }

                                    return CHtml::encode(number_format($total_price, 0, "", "."));
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px;text-align:right;',
                                ),
                            ),
                            array(
                                'header'      => 'Trạng thái',
                                'value'       => function ($data) {
                                    $return = AOrders::getStatus($data['order_id']);

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px;text-align:right;',
                                ),
                            ),

                            array(
                                'header'      => 'Ghi chú',
                                'value'       => function ($data) {
                                    $return = $data['note'];

                                    return CHtml::encode($return);
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
    </div>

</div>

