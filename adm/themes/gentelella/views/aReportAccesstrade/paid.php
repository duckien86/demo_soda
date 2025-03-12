<div class="x_panel">
    <div class="x_title">
        <h3><?= Yii::t('adm/label', 'paid_affiliate') ?></h3>
    </div>

    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_paid', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <?php if ($post == 1 && isset($data) && !empty($data)):
//            $Totals = $form->getTotal($data->getData(), array('total_amount'));
            ?>
            <div class="col-md-12">
                <div style="color: red; margin-bottom: -20px;margin-top: 20px;">
                    <h5>Thống kê chi tiết</h5>
                </div>
                <form method="post"
                      action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportPaidAffiliate'); ?>"
                      name="fday">
                    <input type="hidden" name="excelExport[month]" value="<?php echo $form->month ?>">
                    <input type="hidden" name="excelExport[year]" value="<?php echo $form->year ?>">
                    <input type="hidden" name="excelExport[province_code]"
                           value="<?php echo $form->province_code ?>">
                    <input type="hidden" name="excelExport[ctv_type]"
                           value="<?php echo $form->ctv_type ?>">
                    <input type="hidden" name="excelExport[ctv_id]"
                           value="<?php echo $form->ctv_id ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'reportAt-detail-sim-grid',
                    'dataProvider'  => $data,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                    'columns'       => array(
                        array('header' => 'STT',
                              'value'  => '++$row',
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
                            'header'      => 'Ngân hàng',
                            'value'       => function ($data) {
                                $inviter_code = ACtvCommissionStatisticMonth::getBanks($data['publisher_id']);

                                return CHtml::encode($inviter_code);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Tên tài khoản',
                            'value'       => function ($data) {
                                $inviter_code = ACtvCommissionStatisticMonth::getAccountName($data['publisher_id']);

                                return CHtml::encode($inviter_code);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Số tài khoản',
                            'value'       => function ($data) {
                                $inviter_code = ACtvCommissionStatisticMonth::getBankAccount($data['publisher_id']);

                                return CHtml::encode($inviter_code);
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'TTKD',
                            'value'       => function ($data) {

                                return CHtml::encode(AProvince::model()->getProvinceVnp($data['vnp_province_id']));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
//                        array(
//                            'name'        => 'Người cập nhật',
//                            'value'       => function ($data) {
//                                if (!empty($data['update_by'])) {
//                                    return CHtml::encode(ACtvSystemUser::getUserName($data['update_by']));
//                                }
//
//                                return "Chưa thanh toán";
//                            },
//                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
//                        ),
                        array(
                            'name'        => 'Thời gian thanh toán',
                            'value'       => function ($data) {
                                if ($data['update_time'] != NULL) {
                                    return CHtml::encode($data['update_time']);
                                }

                                return "Chưa thanh toán";
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Thù lao tháng đối soát',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data['total_amount'], 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Thù lao tồn đọng',
                            'value'       => function ($data) {
                                $month  = Yii::app()->cache->get('query_month') ? Yii::app()->cache->get('query_month') : date('m');
                                $return = ACtvCommissionStatisticMonth::getCommisionReceive($data['publisher_id'], $month, $data['transaction_id']);

                                return CHtml::encode(number_format($return, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Tổng thù lao',
                            'value'       => function ($data) {
                                $month  = Yii::app()->cache->get('query_month') ? Yii::app()->cache->get('query_month') : date('m');
                                $amount_receive = ACtvCommissionStatisticMonth::getCommisionReceive($data['publisher_id'], $month, $data['transaction_id']);
                                $return         = $amount_receive + $data['total_amount'];

                                return CHtml::encode(number_format($return, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'name'        => 'Trạng thái',
                            'value'       => function ($data) {

                                return CHtml::encode(AReportATForm::getStatusPaid($data['status']));
                            },
                            'htmlOptions' => array('style' => 'text-align: left; word-break: break-word; vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Lý do',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $bank   = ACtvCommissionStatisticMonth::getBanks($data->publisher_id);
                                $month  = Yii::app()->cache->get('query_month') ? Yii::app()->cache->get('query_month') : date('m');
                                $amount_receive = ACtvCommissionStatisticMonth::getCommisionReceive($data['publisher_id'], $month, $data['transaction_id']);
                                $return = '';
                                if ($bank == '' && $data->status != 10) {
                                    $return = "Chưa đủ thông tin thanh toán!";
                                } else if ($data->status != 10 && ($data->total_amount + $amount_receive) < 200000) {
                                    $return = "Tổng thù lao tháng nhỏ hơn 200.000 đ!";
                                }

                                return $return;
                            },
                            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
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

