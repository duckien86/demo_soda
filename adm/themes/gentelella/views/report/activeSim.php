<div class="x_panel">
    <div class="x_title">
        <h3>Báo cáo thuê bao</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_activeSim', array('model' => $form)); ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($data) && !empty($data)):
            ?>

            <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportActiveSim'); ?>" target="_blank">
                <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                <input type="hidden" name="excelExport[vnp_province_id]" value="<?php echo $form->vnp_province_id ?>">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>

            <div class="col-md-12">
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'id'            => 'activeSim-grid',
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'dataProvider' => $data,
                        'columns'     => array(
                            array(
                                'header'      => 'Loại TB',
                                'value'       => function ($data) {
                                    return $data['MSISDN'];
                                },
                            ),
                            array(
                                'header'      => 'Loại SP',
                                'value'       => function ($data) {
                                    return $data['LOAI_TB'];
                                },
                            ),
                            array(
                                'header'      => 'Ngày kích hoạt',
                                'value'       => function ($data) {
                                    return $data['NGAY_KH'];
                                },
                            ),
                            array(
                                'header'      => 'Ngày khởi tạo',
                                'value'       => function ($data) {
                                    return $data['NGAY_HM'];
                                },
                            ),
                            array(
                                'header'      => 'Tỉnh',
                                'value'       => function ($data) {
                                    return  $data['MATINH'];
                                },
                            ),
                            array(
                                'header'      => 'Mã đơn hàng',
                                'value'       => function ($data) {
                                    return $data['ORDER_ID'];
                                },
                            ),
                        ),
                    ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

