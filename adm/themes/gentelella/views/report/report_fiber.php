<div class="x_panel">
    <div class="x_title">
        <h3>Tra cứu hóa đơn FIBER</h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_fiber', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php if (isset($data) && !empty($data)) { ?>
                <div class="col-lg-12">
<!--                    <form method="post" action="--><?php //echo Yii::app()->createAbsoluteUrl('excelExport/incentiveAgency'); ?><!--"-->
<!--                          name="fday" target="_blank">-->
<!--                        <input type="hidden" name="YII_CSRF_TOKEN"-->
<!--                               value="--><?php //echo Yii::app()->request->csrfToken ?><!--">-->
<!--                        --><?php //echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
<!--                    </form>-->
                </div>
                <style>
                    #incentives-grid table tr th{
                        text-align: center;
                    }

                </style>
                <div class="col-md-12">
                    <div class="" style="margin: 10px 0px; font-weight: bold">Chi tiết đơn hàng fiber</div>
                    <?php $this->widget('booster.widgets.TbGridView', array(
                        'id' => 'incentives-grid',
                        'dataProvider' => $data,
                        'filter'       => $data,
                        'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                        'columns' => array(
                            array('header' => 'STT',
                                'value'  => '++$row',
                            ),
                            array(
                                'header' => 'Hóa đơn ID (FREEDOO)',
                                'type' => 'raw',
                                'filter' => true,
                                'value' => function ($data) {
                                    return Chtml::encode($data->freedoo_order_id);
                                },
                                'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                            ),
                            array(
                                'header' => 'Hóa đơn ID (FIBER)',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->fiber_order_id);
                                },
                                'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                            ),
                            array(
                                'header' => 'Mã KH',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->hdkh_id);
                                },
                                'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                            ),
                            array(
                                'header' => 'Tên KH',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->ten_kh);
                                },
                                'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                            ),

                            array(
                                'header' => 'Số ĐT KH',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->so_dt);
                                },
                                'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                            ),
                            array(
                                'header' => 'Tên yêu cầu',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->ten_yc);
                                },
                                'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;width:120px;'),
                            ),
                            array(
                                'header' => 'Số ĐT yêu cầu',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->so_dt_yc);
                                },
                                'htmlOptions' => array('style' => 'text-align:right;vertical-align:middle;width:150px;'),
                            ),

                            array(
                                'header' => 'Ngày tạo',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return Chtml::encode($data->created_on);
                                },
                                'htmlOptions' => array('style' => 'text-align:right;vertical-align:middle;width:150px;'),
                            ),
                        ),
                    )); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>