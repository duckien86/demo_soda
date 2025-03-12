<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $order_state AOrderState */
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/actions', 'view'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-md-6">
                <div class="title-order">
                    * Thông tin đơn hàng
                </div>
                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $model,
                    'type'       => '',
                    'attributes' => array(
                        array(
                            'name'        => Yii::t('web/portal', 'order_id'),
                            'value'       => function ($data) {
                                return Chtml::encode($data->id);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('web/portal', 'create_date'),
                            'value'       => function ($data) {
                                return Chtml::encode($data->create_date);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('web/portal', 'status'),
                            'value'       => function ($data) {
                                return Chtml::encode(AOrders::getStatus($data->id));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'time_left',
                            'type'        => 'raw',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $status = AOrders::getStatus($data->id);
                                if ($status != "Hoàn thành") {
                                    return CHtml::encode($data->getTimeLeft($data->create_date)['time']);
                                } else {
                                    return "Hoàn thành";
                                }
                            },
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('web/portal', 'payment_method'),
                            'value'       => function ($data) {
                                return CHtml::encode(AOrders::getPaymentMethod($data['payment_method']));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Phòng bán hàng',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $sale = SaleOffices::model()->getSaleOfficesByOrder($data['id']);
                                if ($sale != "" && isset($data['sale_office_code'])) {
                                    return CHtml::encode($sale) . CHtml::link("<span class='tooltiptext'>Danh sách đầu mối liên hệ</span><i class='fa fa-phone' aria-hidden='true'></i>", 'javascript:void(0)',
                                            array('onclick' => 'loadDetail("' . $data['sale_office_code'] . '","' . $data['id'] . '",'.AOrders::SALE_OFFICE_PERSON.');',
                                                  'class'   => 'btn btn-success contact_proxy', 'style' => 'background-color:#00ffd9; border:red;float: right;'));
                                }

                                return "Không có dữ liệu!";
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'  => Yii::t('web/portal', 'delivery_type'),
                            'type'  => 'raw',
                            'value' => function ($data) {
                                $brand = '';
                                if ($data['delivery_type'] == 2) {
                                    $brand = BrandOffices::model()->getBrandOffices($data['address_detail']);
                                }
                                if ($brand != '' && isset($data['address_detail'])) {
                                    return CHtml::encode(AOrderState::getDeliveryType($data['delivery_type'])) . CHtml::link("<span class='tooltiptext'>Danh sách đầu mối liên hệ</span><i class='fa fa-phone' aria-hidden='true'></i>", 'javascript:void(0)',
                                            array('onclick' => 'loadDetail("' . $data['address_detail'] . '","' . $data['id'] . '",'.AOrders::BRAND_OFFICE_PERSON.');',
                                                  'class'   => 'btn btn-success contact_proxy', 'style' => 'background-color:red; border:red;float: right;')) . "<br>" . Chtml::link("<span style='color:#964993; text-decoration: none;'>" . $brand . "</span>");
                                }

                                return CHtml::encode(AOrderState::getDeliveryType($data['delivery_type']));
                            },
                        ),

                        array(
                            'name'        => 'total_renueve',
                            'type'        => 'raw',
                            'value'       => function ($data) {

                                $total_renuve = AOrders::model()->getTotalRenueveOrder($data->id);

                                return CHtml::link(number_format($total_renuve, 0, '', '.') . " đ", 'javascript:void(0)', array('style' => 'font-size:16px; color:blue;'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                        ),
                    ),
                )); ?>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="title-order">
                        * Thông tin khách hàng
                    </div>
                    <?php $this->widget('booster.widgets.TbDetailView', array(
                        'data'       => $model,
                        'type'       => '',
                        'attributes' => array(
                            array(
                                'name'        => Yii::t('web/portal', 'full_name'),
                                'value'       => function ($data) {
                                    return Chtml::encode($data->full_name);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;'),
                            ),
                            array(
                                'name'        => 'SĐT liên hệ',
                                'value'       => function ($data) {
                                    return Chtml::encode($data->phone_contact);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;'),
                            ),
                            array(
                                'name'  => 'Địa chỉ liên hệ',
                                'type'  => 'raw',
                                'value' => function ($data) {
                                    if ($data->delivery_type == AOrders::COD) {
                                        $address = $data->address_detail . " -- " . Ward::model()->getWard($data->ward_code) . " -- "
                                            . District::model()->getDistrict($data->district_code) . " -- " . Province::model()->getProvince($data->province_code);
                                    } else {
                                        $address = District::model()->getDistrict($data->district_code) . " -- "
                                            . Province::model()->getProvince($data->province_code);
                                    }

                                    return CHtml::encode($address);
                                }
                            ),
                            array(
                                'name'        => Yii::t('web/portal', 'customer_note'),
                                'value'       => function ($data) {
                                    return Chtml::encode($data->customer_note);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if ((isset($order_detail) && !empty($order_detail)) && (isset($order_state)
                && !empty($order_state)) && (isset($order_shipper) && !empty($order_shipper))
            && (isset($logs_sim) && !empty($logs_sim))
        ) :
            ?>
            <div class="space_30"></div>
            <div class="row">
                <div class="col-md-4 col-sm-4" style="float: right;">
                    <a href="<?= Yii::app()->createUrl('aLogMt/admin') ?>" target="_blank" class="btn btn-warning"
                       style="float: right; margin-right: 15px;z-index: 20;">Tra cứu MT >></a>
                </div>
                <div class="col-md-12">
                    <div class="x_content">
                        <?php $this->widget(
                            'booster.widgets.TbTabs',
                            array(
                                'type'        => 'tabs',
                                'tabs'        => array(
                                    array(
                                        'label'   => 'Chi tiết đơn hàng',
                                        'content' => $this->renderPartial('_detail', array('order_detail' => $order_detail), TRUE),
                                        'active'  => TRUE,
                                    ),
                                    array(
                                        'label'   => 'Lịch sử đơn hàng',
                                        'content' => $this->renderPartial('_view_history', array('order_state' => $order_state), TRUE),
                                    ),
                                    array(
                                        'label'   => 'Người giao hàng',
                                        'content' => $this->renderPartial('_view_shipper', array('order_shipper' => $order_shipper), TRUE),
                                    ),
                                    array(
                                        'label'   => 'Lịch sử khai báo sim',
                                        'content' => $this->renderPartial('_view_log_sim', array('logs_sim' => $logs_sim), TRUE),
                                    ),

                                ),
                                'htmlOptions' => array('class' => 'site_manager')
                            )
                        );
                        ?>
                    </div>
                </div>

                <div class="space_30"></div>
                <div class="popup_proxy"></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<style>
    .summary {
        display: none;
    }

    .detail-view th {
        font-size: 12px;
        width: 200px;
    }

    .even th {
        line-height: 30px !important;
        vertical-align: middle;
    }

    .even td {
        line-height: 30px !important;
        vertical-align: middle;
    }

    .odd th {
        /*line-height: 30px !important;*/
        vertical-align: middle !important;
    }

    .contact_proxy {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .contact_proxy .tooltiptext {
        visibility: hidden;
        width: 200px;
        background-color: black;
        color: white;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        margin-top: -30px;
        border: 1px solid rgba(193, 193, 193, 0.25);
        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .contact_proxy:hover .tooltiptext {
        visibility: visible;
    }
</style>

<script type="text/javascript">
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });
    function loadDetail(value, order_id, type) {
        if (value != 0 && order_id != '') {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aOrders/proxy')?>',
                crossDomain: true,
//                dataType: 'json',
                data: {
                    value: value,
                    type: type,
                    order_id: order_id,
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                success: function (data) {
                    $('.modal-backdrop').remove();
                    $('.popup_proxy').html(data);

                    var modal_proxy = 'modal_proxy_' + order_id;

                    $('#' + modal_proxy).modal("show");
                    return false;
                }
            });
        } else {
            alert("Thiếu dữ liệu!");
        }
    }

    function test() {
        alert("vao");
    }
</script>