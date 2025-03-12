<div class="x_panel">
    <div class="x_title">
        <h3><?= Yii::t('adm/label', 'report_at_affiliate') ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_affiliate', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <?php if ($post == 1 && isset($data) && !empty($data)):
            ?>
            <div class="col-md-5">
                <div style="color: red;">
                    <h5>Thống kê tổng quan</h5>
                </div>
                <?php
                    $Totals = $form->getTotal($data->getData(), array('total_order', 'total_renueve'));
                ?>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'reportAt-sim-grid',
                    'dataProvider'  => $data,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'TTKD',
                            'value'       => function ($data) {

                                return CHtml::encode(AProvince::model()->getProvinceVnp($data->vnp_province_id));
                            },
                            'footer'      => 'Tổng',
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Sản lượng',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->total_order, 0, "", "."));
                            },
                            'footer'      => number_format(isset($Totals['total_order']) ? $Totals['total_order'] : 0, 0, '', '.'),
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Doanh thu',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->total_renueve, 0, "", "."));
                            },
                            'footer'      => number_format(isset($Totals['total_renueve']) ? $Totals['total_renueve'] : 0, 0, '', '.'),
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                    ),
                )); ?>

            </div>
            <div class="col-md-12">
                <div style="color: red; margin-bottom: -20px;margin-top: 20px;">
                    <h5>Thống kê chi tiết</h5>
                </div>
                <form method="post"
                      action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportAffiliateAt'); ?>"
                      name="fday">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                    <input type="hidden" name="excelExport[province_code]"
                           value="<?php echo $form->province_code ?>">
                    <input type="hidden" name="excelExport[status]"
                           value="<?php echo $form->status ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'reportAt-detail-sim-grid',
                    'dataProvider'  => $data_detail,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                    'columns'       => array(
                        array('header' => 'STT',
                              'value'  => '++$row',
                        ),
                        array(
                            'header'      => 'TTKD',
                            'value'       => function ($data) {

                                return CHtml::encode(AProvince::model()->getProvinceVnp($data['vnp_province_id']));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'PBH',
                            'value'       => function ($data) {
                                return CHtml::encode(SaleOffices::model()->getSaleOfficesByOrder($data['order_code']));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Mã ĐH',
                            'value'       => function ($data) {

                                return CHtml::encode($data['order_code']);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'CTV',
                            'value'       => function ($data) {
                                $username = ACtvUsers::getUserName($data['publisher_id']);

                                return CHtml::encode($username);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Mã CTV',
                            'value'       => function ($data) {
                                $owner_code = ACtvUsers::getOwnerCode($data['publisher_id']);

                                return CHtml::encode($owner_code);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Mã GT',
                            'value'       => function ($data) {
                                $inviter_code = ACtvUsers::getInviterCode($data['publisher_id']);

                                return CHtml::encode($inviter_code);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Hình thức',
                            'value'       => function ($data) {
                                return CHtml::encode(AReportATForm::getTypeSimByType($data['sub_type']));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Trạng thái',
                            'value'       => function ($data) {
                                return CHtml::encode(AReportATForm::getStatusOrder($data['action_status']));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Kênh bán hàng',
                            'value'       => function ($data) {
                                return "AFFILIATE";
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
//                        array(
//                            'name'        => 'Số lần gia hạn',
//                            'value'       => function ($data) {
//                                return CHtml::encode($data['renewal_count']);
//                            },
//                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
//                        ),
                        array(
                            'header'      => 'Tiền sim',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data['price_sim'], 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tiền gói',
                            'value'       => function ($data) {

                                return CHtml::encode(number_format($data['price_package'], 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tổng',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data['price_sim'] + $data['price_package'], 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Hoa hông phát triển CTV',
                            'value'       => function ($data) {
                                $amount_ctv = 0;
                                $data_ctv   = AReportATForm::getPublisherAward($data['order_code'], $data['action_status']);
                                if (!empty($data_ctv)) {
                                    $amount_ctv = $data_ctv[0]['amout'];
                                }

                                return CHtml::encode($amount_ctv);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Hoa hồng sim',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data['amount_sim'], 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Hoa hồng gói',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data['amount_package'], 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tổng hoa hồng',
                            'value'       => function ($data) {
                                $amount_ctv = '';
                                $data_ctv   = AReportATForm::getPublisherAward($data['order_id'], $data['order_status']);
                                if (!empty($data_ctv)) {
                                    $amount_ctv = $data_ctv['amout'];
                                }

                                return CHtml::encode(number_format($data['amount_package'] + $data['amount_sim'] + $amount_ctv, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                    ),
                )); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
<style>
    #reportAt-sim-grid .summary {
        display: none;
    }

    tfoot td {
        text-align: right;
    }

    tfoot tr td:first-child {
        text-align: left !important;
    }


</style>

