<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

$this->breadcrumbs = array(
    Yii::t('adm/menu','manage_business'),
    Yii::t('adm/menu','delivery'),
    'Giao hàng tại ĐGD' => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Giao hàng tại ĐGD</h2>
        <div class="clearfix"></div>
    </div>

    <?php $this->renderPartial('_filter_area', array('model' => $model_search, 'model_validate' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'aorders-grid',
                'dataProvider'  => $model->search_complete($post),
                'filter'        => $model,
                'enableSorting' => FALSE,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
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
                            $sale = SaleOffices::model()->getSaleOffices($data->sale_office_code);

                            return CHtml::encode($sale);
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                    ),
                    array(
                        'name'        => 'id',

                        'htmlOptions' => array('style' => 'width:100px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'sim',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::encode(AOrders::model()->getSim($data->id));
                        },
                        'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Loại thuê bao',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::encode(AOrders::model()->getTypeSimByOrder($data->id));
                        },
                        'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'full_name',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone_contact',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'time_left',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::encode($data->getTimeLeft($data->create_date)['time']);
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'width:100px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'payment_method',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::encode(AOrders::getPaymentMethod($data->payment_method));
                        },
                        'htmlOptions' => array('style' => 'width:100px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status_end',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return Chtml::encode(AOrders::getStatus($data->id));
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $serial = '';
                            if (Yii::app()->cache->get("serial_10_" . $data->id)) {
                                $serial = Yii::app()->cache->get("serial_10_" . $data->id);
                            }

                            return CHtml::link('Giao hàng', 'javascript:void(0)',
                                array('data-toggle' => "modal", 'data-target' => "#modal_" . $data->id, 'style' => 'color:blue;',
                                      'onclick'     => 'getOtp("' . $data->id . '","' . $serial . '")'));
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'Xem chi tiết',
                        'template'    => '{view}',
                        'buttons'     => array(
                            'view' => array(
                                'options' => array('target' => '_blank'),
                            ),
                        ),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
        <div class="popup_data">
        </div>
        <div class="popup_data_serial">
        </div>
        <div class="popup_data_exist_info"></div>
        <div class="popup_data_register"></div>
        <div class="popup_data_roaming"></div>
    </div>
</div>

<script type="text/javascript">

    function getOtp(id, serial_number='') {

        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aCompleteOrders/checkOtp') ?>',
            crossDomain: true,
            data: {
                order_id: id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function (result) {
                if (result == 1) {
                    var url = "<?=Yii::app()->createUrl('aCompleteOrders/registerInfo', array('t' => 1))?>";
                    url += '&order_id=' + id + '&serial_number=' + serial_number;
                    window.location.href = url;
                } else {
                    $('.modal-backdrop').remove();
                    $('.popup_data').children().remove("div");
                    $('.popup_data').append(result);
                    $('.popup_data_roaming').html(result);
                    $('.popup_register_result').html(result);
                    $('.popup_data_exist_info').html(result);
                    var modal_id = 'modal_' + id;
                    var modal_roaming = 'modal_roaming_' + id;
                    var modal_result_package = 'modal_result_package_' + id;
                    var modal_check_exist_info_ = 'modal_check_exist_info_' + id;
                    $('#' + modal_result_package).modal("show");
                    $('#' + modal_id).modal('show');
                    $('#' + modal_roaming).modal('show');
                    $('#' + modal_check_exist_info_).modal('show');
                    return false;
                }
            }
        });
    }
</script>


