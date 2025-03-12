<div class="x_panel">
    <div class="x_title">
        <h3><?= Yii::t('adm/label', 'report_at_sim') ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_sim', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <?php if ($post == 1 && isset($data) && !empty($data)):
            ?>
            <div class="col-md-4">
                <div style="color: red;">
                    <h5>Thống kê tổng quan</h5>
                </div>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'reportAt-sim-grid',
                    'dataProvider'  => $data,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'type',
                            'value'       => function ($data) {
                                return CHtml::encode(AReportATForm::getTypeSimByType($data->type));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'total_order',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->total_order, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'total_renueve',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->total_renueve, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                    ),
                )); ?>

            </div>
            <div class="col-md-12">
                <div style="color: red; margin-bottom: -20px;margin-top: 20px;">
                    <h5>Thống kê chi tiết</h5>
                </div>
                <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportSimAt'); ?>" name="fday">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                    <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                    <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                    <input type="hidden" name="excelExport[channel_code]" value="<?php echo $form->channel_code ?>">
                    <input type="hidden" name="excelExport[status]" value="<?php echo $form->status ?>">
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
                            'name'        => 'order_id',
                            'value'       => function ($data) {
                                return CHtml::encode($data->order_id);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'item_name',
                            'value'       => function ($data) {
                                return CHtml::encode($data->item_name);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'sub_type',
                            'value'       => function ($data) {
                                return CHtml::encode(AReportATForm::getTypeSimByType($data->sub_type));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'order_status',
                            'value'       => function ($data) {
                                return CHtml::encode(AReportATForm::getStatusOrderAT($data->order_status));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'order_province_code',
                            'value'       => function ($data) {
                                $province = '';
//                                if (Yii::app()->redis_backend->get('be_static_province_' . $data->order_province_code)) {
//                                    $province = Yii::app()->redis_backend->get('be_static_province_' . $data->order_province_code);
//                                } else {
                                $province = AProvince::model()->getProvince($data->order_province_code);

//                                }

                                return CHtml::encode($province);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'affiliate_click_id',
                            'value'       => function ($data) {
                                return CHtml::encode($data->affiliate_click_id);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'affiliate_channel',
                            'value'       => function ($data) {
                                return CHtml::encode(AReportATForm::getChannelByCode($data->affiliate_channel));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'order_note',
                            'value'       => function ($data) {
                                if ($data->order_status == 0) {
                                    return CHtml::encode($data->order_note);
                                }

                                return "";
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Ngày mua hàng',
                            'value'       => function ($data) {
                                return CHtml::encode($data->order_create_date);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'item_price',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->item_price, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'item_price_term',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->item_price_term, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tổng',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->item_price_term + $data->item_price, 0, "", "."));
                            },
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'amount',
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data->amount, 0, "", "."));
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
</style>

