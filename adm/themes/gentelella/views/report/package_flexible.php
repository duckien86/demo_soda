<div class="x_panel">
    <div class="x_title">
        <h3>Doanh thu bán gói cước linh hoạt</h3>
    </div>

    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_package_flexible', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($data) && !empty($data)):
            ?>
            <div class="row">
                <div class="col-md-12">
                    <form method="post"
                          action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportPackageFlexible'); ?>"
                          name="fday">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[period]"
                               value="<?php echo $form->period ?>">
                        <input type="hidden" name="excelExport[package_group]"
                               value="<?php echo $form->package_group ?>">
                        <input type="hidden" name="excelExport[package_id]"
                               value="<?php echo $form->package_id ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                    <div class="title">
                        <h5> * Danh sách chi tiết</h5>
                    </div>
                    <?php
                        $this->widget('booster.widgets.TbGridView', array(
                            'dataProvider' => $data,

                            'type'        => 'striped bordered  consended ',
                            'htmlOptions' => array(
                                'class' => 'tbl_style',
                                'id'    => 'detail-package',
                            ),
                            'columns'     => array(
                                array('header' => 'STT',
                                      'value'  => '++$row',
                                ),
                                array(
                                    'header'      => 'Mã đơn hàng',
                                    'value'       => function ($data) {
                                        $return = $data['id'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Số thuê bao',
                                    'value'       => function ($data) {

                                        $return = $data['customer_msisdn'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Thoại nội mạng',
                                    'value'       => function ($data) {

                                        $return = $data['capacity_call_int'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Thoại ngoại mạng',
                                    'value'       => function ($data) {

                                        $return = $data['capacity_call_ext'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'SMS nội mạng',
                                    'value'       => function ($data) {

                                        $return = $data['capacity_sms_int'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'SMS ngoại mạng',
                                    'value'       => function ($data) {

                                        $return = $data['capacity_sms_ext'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Data',
                                    'value'       => function ($data) {

                                        $return = $data['capacity_data'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Ngày mua',
                                    'value'       => function ($data) {

                                        $return = $data['create_date'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => '',
                                    ),
                                ),
                                array(
                                    'header'      => 'Doanh thu',
                                    'value'       => function ($data) {

                                        $return = number_format($data['total'], 0, "", ".");

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px; text-align:right;',
                                    ),
                                ),


                            ),
                        ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
