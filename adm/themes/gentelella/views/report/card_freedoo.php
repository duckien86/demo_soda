<div class="x_panel">
    <div class="x_title">
        <h3><?= Yii::t('report/menu', 'card') ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_card_freedoo', array('model' => $form)); ?>
        </div>
    </div>
    <div class="row">
        <div class="title" style="margin-left: 15px; margin-bottom: 15px;">
            <h5> * Doanh thu tổng quan</h5>
        </div>  


        <?php if (isset($card_overview) && !empty($card_overview)) {
            $Total_card = $model->getTotalObject($card_overview, array('total_card', 'renueve_card'));

            $card_overview = new CArrayDataProvider($card_overview, array(
                'keyField' => FALSE,
            ));
            ?>
            <div class="col-md-6">
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $card_overview,

                        'type'        => 'striped bordered  consended ',
                        'htmlOptions' => array(
                            'class' => 'tbl_style',
                            'id'    => 'thongsoChung-card',
                        ),
                        'columns'     => array(
                            array(
                                'header'      => 'Loại thẻ',
                                'value'       => function ($data) {
                                    $return = number_format($data->item_id, 0, "", ".");

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:left;',
                                ),
                                'footer'      => 'Tổng',
                            ),
                            array(
                                'header'      => 'Số lượng giao dịch',
                                'value'       => function ($data) {

                                    $return = number_format($data->total_card, 0, "", ".");

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                                'footer'      => number_format($Total_card['total_card'], 0, "", "."),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'value'       => function ($data) {

                                    $return = number_format($data->renueve_card, 0, "", ".");

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                                'footer'      => number_format($Total_card['renueve_card'], 0, "", "."),
                            ),

                        ),
                    ));
                ?>
            </div>
        <?php } ?>

        <?php if (isset($data_overview) && !empty($data_overview)) {
            $Total_overview = $model->getTotalObject($data_overview, array('total_card', 'renueve_card'));
            $data_overview  = new CArrayDataProvider($data_overview, array(
                'keyField' => FALSE,
            ));
            ?>

            <div class="col-md-6">
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data_overview,

                        'type'        => 'striped bordered  consended ',
                        'htmlOptions' => array(
                            'class' => 'tbl_style',
                            'id'    => 'thongsoChung-card-topup',
                        ),
                        'columns'     => array(
                            array(
                                'header'      => 'Loại dịch vụ',
                                'value'       => function ($data) {
                                    if ($data->type == 'card') {
                                        $return = 'Nạp thẻ';
                                    } else {
                                        $return = $data->type;
                                    }

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:left;',
                                ),
                                'footer'      => 'Tổng',
                            ),
                            array(
                                'header'      => 'Số lượng giao dịch',
                                'value'       => function ($data) {

                                    $return = number_format($data->total_card, 0, "", ".");

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                                'footer'      => number_format($Total_overview['total_card'], 0, "", "."),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'value'       => function ($data) {

                                    $return = number_format($data->renueve_card, 0, "", ".");

                                    return CHtml::encode($return);
                                },
                                'htmlOptions' => array(
                                    'style' => 'text-align:right;',
                                ),
                                'footer'      => number_format($Total_overview['renueve_card'], 0, "", "."),
                            ),

                        ),
                    ));
                ?>
            </div>
        <?php } ?>
        <?php if (isset($data_detail) && !empty($data_detail)):

            ?>
            <div class="row">
                <div class="col-md-12">
                    <form method="post"
                          action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportCardFreeDoo'); ?>"
                          name="fday">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[price_card]"
                               value="<?php echo $form->price_card ?>">
                        <input type="hidden" name="excelExport[card_type]"
                               value="<?php echo $form->card_type ?>">
                        <input type="hidden" name="excelExport[payment_method]"
                               value="<?php echo $form->payment_method ?>">
                        <input type="hidden" name="excelExport[sim_freedoo]"
                               value="<?php echo $form->sim_freedoo ?>">
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
                                    'header'      => 'Mã đơn hàng',
                                    'value'       => function ($data) {

                                        $return = $data->id;

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Loại dịch vụ',
                                    'value'       => function ($data) {
                                        if ($data->type == 'card') {
                                            $return = 'Nạp thẻ';
                                        } else {
                                            $return = $data->type;
                                        }

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Mệnh giá',
                                    'value'       => function ($data) {

                                        $return = $data->item_id;

                                        return CHtml::encode(number_format($return, 0, "", "."));
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Chiết khấu',
                                    'value'       => function ($data) {

                                        return CHtml::encode("4%");
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Doanh thu',
                                    'value'       => function ($data) {

                                        $return = $data->price;

                                        return CHtml::encode(number_format($return, 0, "", "."));
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Loại thuê bao',
                                    'value'       => function ($data) {

                                        if (Report::getTypeSim($data->phone_contact)) {
                                            return Chtml::encode("Freedoo");
                                        }

                                        return CHtml::encode("Vinaphone");
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'SĐT mua mã',
                                    'value'       => function ($data) {

                                        $return = $data->phone_contact;

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Phương thức thanh toán',
                                    'value'       => function ($data) {

                                        $return = AOrders::getPaymentMethod($data->payment_method);

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

    #thongsoChung-card-topup .summary {
        display: none;
    }

    #thongsoChung-card .summary {
        display: none;
    }
</style>

