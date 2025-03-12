<div class="x_panel">
    <div class="x_title">
        <h3><?= Yii::t('report/menu', 'cardFreedoo') ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_card', array('model' => $form)); ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($data) && !empty($data)):
            $Totals = $model->getTotal($data->getData(), array('TOTAL_CARD', 'RENUEVE_CARD'));
            ?>
            <div class="col-md-6">
                <div class="title">
                    <h5> * Doanh thu tổng quan</h5>
                </div>
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data,

                        'type'        => 'striped bordered  consended ',
                        'htmlOptions' => array(
                            'class' => 'tbl_style',
                            'id'    => 'thongsoChung-card',
                        ),
                        'columns'     => array(
                            array(
                                'header'      => 'Mệnh giá',
                                'value'       => function ($data) {

                                    $return = $data['NAPTIEN'];

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:left;',
                                ),
                                'footer'      => 'Tổng',
                            ),
                            array(
                                'header'      => 'Sản lượng',
                                'value'       => function ($data) {

                                    $return = $data['TOTAL_CARD'];

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:right;',
                                ),
                                'footer'      => number_format($Totals['TOTAL_CARD'], 0, "", "."),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'value'       => function ($data) {

                                    $return = $data['RENUEVE_CARD'];

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:right; ',
                                ),
                                'footer'      => number_format($Totals['RENUEVE_CARD'], 0, "", "."),
                            ),
                        ),
                    ));
                ?>
            </div>
        <?php endif; ?>
        <?php if (isset($data_detail) && !empty($data_detail)): ?>
            <div class="row">
                <div class="col-md-12">
                    <form method="post"
                          action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportCard'); ?>"
                          name="fday">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[province_code]"
                               value="<?php echo $form->province_code ?>">
                        <input type="hidden" name="excelExport[price_card]"
                               value="<?php echo $form->price_card ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                    <div class="title">
                        <h5> * Danh sách chi tiết</h5>
                    </div>
                    <?php
                        $this->widget('booster.widgets.TbGridView', array(
                            'dataProvider' => $data_detail,

                            'type'        => 'striped bordered  consended ',
                            'htmlOptions' => array(
                                'class' => 'tbl_style',
                                'id'    => 'chitiet-card',
                            ),
                            'columns'     => array(
                                array('header' => 'STT',
                                      'value'  => '++$row',
                                ),
                                array(
                                    'header'      => 'Số TB nạp thẻ',
                                    'value'       => function ($data) {

                                        $return = $data['MSISDN'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Ngày mua',
                                    'value'       => function ($data) {

                                        $return = $data['CREATED_DATE'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Mênh giá',
                                    'value'       => function ($data) {

                                        $return = $data['NAPTIEN'];

                                        return CHtml::encode(number_format($return, 0, "", "."));
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Doanh thu',
                                    'value'       => function ($data) {

                                        $return = $data['NAPTIEN'];

                                        return CHtml::encode(number_format($return, 0, "", "."));
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Tỉnh',
                                    'value'       => function ($data) {

                                        $return = $data['MATINH'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
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
<style>
    tfoot tr td:first-child {
        color: red;
        text-align: left !important;
        font-style: initial !important;
    }

    tfoot td {
        color: red;
        text-align: right !important;
        font-style: initial !important;
    }
</style>

