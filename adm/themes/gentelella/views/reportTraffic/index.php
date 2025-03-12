<div class="x_panel">

    <div class="x_title">
        <h3>Báo cáo hiệu năng chiến dịch</h3>
    </div>
    <div class="clearfix"></div>


    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>

        <?php if (isset($data) && !empty($data)):
            ?>
            <div class="col-md-8 top_col">
                <span class="title"> * Sản lượng tổng quan:</span>
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data_total,
                        'type'         => 'striped bordered  consended ',
                        'htmlOptions'  => array(
                            'class' => 'tbl_style',
                            'id'    => 'traffic-link-overview',
                        ),
                        'columns'      => array(
                            array(
                                'header'      => 'Chiến dịch',
                                'value'       => function ($data) {

                                    $return = $data['CAMPAIGN'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Kênh',
                                'value'       => function ($data) {

                                    $return = $data['CHANNEL_CODE'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Tổng sản lượng truy cập',
                                'value'       => function ($data) {

                                    $return = $data['TOTAL'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Tỷ lệ chuyển đổi thành công',
                                'value'       => function ($data) {

                                    $return = $data['ORDER_SUCCESS'] / $data['TOTAL'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Tỷ lệ chuyển đổi',
                                'value'       => function ($data) {

                                    $return = $data['ORDER'] / $data['TOTAL'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),

                        ),
                    ));
                ?>
            </div>
            <div class="col-md-8 top_col">
                <form method="post"
                      action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportTraffic'); ?>"
                      name="fday">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                    <input type="hidden" name="excelExport[channel_code]" value="<?php echo $form->channel_code ?>">
                    <input type="hidden" name="excelExport[utm_campaign]" value="<?php echo $form->utm_campaign ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
                <span class="title"> * Sản lượng chi tiết:</span>
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data,
                        'type'         => 'striped bordered  consended ',
                        'htmlOptions'  => array(
                            'class' => 'tbl_style',
                            'id'    => 'traffic-link-detail',
                        ),
                        'columns'      => array(
                            array('header' => 'STT',
                                  'value'  => '++$row',
                            ),
                            array(
                                'header'      => 'Ngày',
                                'value'       => function ($data) {

                                    $return = $data['RXTIME_DATE'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Chiến dịch',
                                'value'       => function ($data) {
                                    $return = $data['CAMPAIGN'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Kênh',
                                'value'       => function ($data) {
                                    $return = $data['CHANNEL_CODE'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Tổng lượng truy cập',
                                'value'       => function ($data) {

                                    $return = $data['TOTAL'];

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),

                        ),
                    ));
                ?>
            </div>
        <?php endif; ?>
    </div>

</div>

