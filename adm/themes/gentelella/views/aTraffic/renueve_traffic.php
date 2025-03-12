<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'report_renueve_traffic') => array('renueve_traffic'),
        'Danh sách',
    );

?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/menu', 'report_renueve_traffic'); ?></h2>
        <div class="clearfix"></div>

    </div>
    <?php $this->renderPartial('_search', array('model' => $model_search, 'model_validate' => $model)); ?>

    <?php if (isset($show) && $show == TRUE): ?>
        <div class="x_content">
            <div class="table-responsive tbl_style center" style="width:60%;">
                <?php
                    $total = $model->getTotal($data_overview->getData(), array('total', 'total_renueve', 'package'));
                ?>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'atraffic-renueve-grid',
                    'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                    'dataProvider'  => $data_overview,
                    'type'          => 'post',
                    'enableSorting' => FALSE,
                    'columns'       => array(
                        array(
                            'name'        => 'Loại trạng thái',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                return CHtml::encode($data['title']);
                            },
                            'footer'      => 'Tổng',
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ), array(
                            'name'        => 'Tổng đơn',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                return CHtml::encode(number_format($data['total'], 0, '', '.'));
                            },
                            'footer'      => number_format($total['total'], 0, '', '.'),
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Tổng doanh thu',
                            'filter'      => FALSE,
                            'value'       => function ($data) {

                                return CHtml::encode(number_format($data['total_renueve'] - $data['package'], 0, '', '.'));
                            },
                            'footer'      => number_format($total['total_renueve'] - $total['package'], 0, '', '.'),
                            'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                        ),
                    ),
                )); ?>
            </div>
      
            <?php if ($post == TRUE):

                ?>
                <form method="post"
                      action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/trafficRenueve'); ?>"
                      name="fday">
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
                    <input type="hidden" name="excelExport[province_code]"
                           value="<?php echo $model->province_code ?>">
                    <input type="hidden" name="excelExport[sale_office_code]"
                           value="<?php echo $model->sale_office_code ?>">
                    <input type="hidden" name="excelExport[shipper_id]"
                           value="<?php echo $model->shipper_id ?>">
                    <input type="hidden" name="excelExport[status_shipper]"
                           value="<?php echo $model->status_shipper ?>">
                    <input type="hidden" name="excelExport[payment_method]"
                           value="<?php echo $model->payment_method ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>

                <div class="table-responsive tbl_style center">
                    <?php $this->widget('booster.widgets.TbGridView', array(
                        'id'            => 'atraffic-grid',
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'dataProvider'  => $model->search_renueve_report(),
                        'filter'        => $model,
                        'type'          => 'post',

                        'enableSorting' => FALSE,
                        'columns'       => array(
                            array(
                                'name'        => 'province_code',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(ATraffic::model()->getProvince($data->province_code));
                                },
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'sale_office_code',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    $sale = SaleOffices::model()->getSaleOfficesByOrder($data->id);

                                    return CHtml::encode($sale);
                                },
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                            ),
                            array(
                                'name'        => 'id',
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'create_date',
                                'filter'      => FALSE,
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'shipper_id',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(ATraffic::model()->getShipperName($data->shipper_id));
                                },
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'payment_method',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(AOrders::getPaymentMethod($data->payment_method));
                                },
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'status_shipper',
                                'type'        => 'raw',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(ATraffic::model()->getStatusTraffic($data->getStatus($data->id)));
                                },
                                'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:150px'),
                            ),
                            array(
                                'name'        => 'amount_sim',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(number_format(ATraffic::model()->getRenueveByType('sim', $data->id), 0, '', '.'));
                                },
                                'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'amount_package',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(number_format(ATraffic::model()->getRenueveByType('package', $data->id), 0, '', '.'));
                                },
                                'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'amount_term',
                                'filter'      => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::encode(number_format(ATraffic::model()->getRenueveByType('price_term', $data->id), 0, '', '.'));
                                },
                                'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'amount_shipper',
                                'filter'      => FALSE,
                                'value'       => function ($data) {

                                    return CHtml::encode(number_format(ATraffic::model()->getPriceShip($data->id), 0, '', '.'));
                                },
                                'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'name'        => 'total_amount',
                                'filter'      => FALSE,
                                'value'       => function ($data) {

                                    return CHtml::encode(number_format(ATraffic::model()->getRenueveByType('', $data->id, TRUE), 0, '', '.'));
                                },
                                'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;width:130px;'),
                            ),
                            array(
                                'template'    => '{view}',
                                'buttons'     => array(
                                    'view' => array(
                                        'options' => array('target' => '_new'),
                                    ),
                                ),
                                'class'       => 'booster.widgets.TbButtonColumn',
                                'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                            ),
                        ),
                    )); ?>
                </div>
            <?php endif; ?>
            <div class="popup_data">
            </div>
        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
    function getShipper(id, province_code, district_code, ward_code='') {
        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aTraffic/getShipperByAddress')?>',
            crossDomain: true,
            data: {
                id: id,
                ward_code: ward_code,
                district_code: district_code,
                province_code: province_code,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (result) {
                $('.popup_data').children().remove("div");
                $('.popup_data').append(result);
                var modal_id = 'modal_' + id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }


</script>
<style type="text/css">
    .search_enhance {
        display: none;
    }

    #thutien_overview th {
        width: 300px !important;
    }

    tfoot td:first-child {
        color: red !important;
        text-align: left;
    }

    tfoot td {
        text-align: right;
        color: red !important;
    }

    #atraffic-renueve-grid .summary {
        display: none;
    }
</style>