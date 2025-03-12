<?php
/* @var $this AOrdersController */
/* @var $model AOrders */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'search'),
    Yii::t('adm/menu', 'order'),
    'ĐH Internet & Truyền hình' => array('searchFiber'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Tra cứu đơn hàng</h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area_fiber', array('model' => $model)); ?>
    <div class="x_content">
        <?php if (isset($data) && !empty($data)) { ?>

            <div style="overflow-x:auto;">
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id' => 'aorders-grid',
                    'dataProvider' => $data,
//                'filter'        => $model,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns' => array(
                        array(
                            'header' => 'Ngày tạo ĐH',
                            'filter' => FALSE,
                            'type' => 'raw',
                            'value' => function ($data) {
                                $value = date("d/m/Y", strtotime($data->create_date));
                                return $value;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:110px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name' => 'id',
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Tên KH',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->full_name;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:110px;'),
                        ),
                        array(
                            'header' => 'Điện thoại',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->phone_contact;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Gói đăng ký',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->package_fiber_name;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Đăng ký TTT',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                if ($data->period > 0) {
                                    return $data->period . ' Ngày';
                                } else
                                    return '--';
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Kênh bán',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->promo_code;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'PBH',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->phong_bh;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'TTKD',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->province_code;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Quận/Huyện',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->district_code;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Địa chỉ',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->address_detail;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Ngày Hợp Đồng',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->ngay_ky_hd;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'NV tạo Hợp đồng',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->ten_nv;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Mã NV',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->ma_nv;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'A/c Fiber Number',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->ma_tb;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Gói thi công',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->loaihinh_tb . ' - ' . $data->package_fiber_name;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Số tháng TTT',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return $data->thangtratruoc;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Cước HM',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return number_format($data->cuochoamang);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'CK HM',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return number_format($data->ckhm);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Tiền TTT',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return number_format($data->tienttt);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'CK TTT',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return number_format($data->ckttt);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Tổng DT',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                return number_format($data->cuochoamang + $data->tienttt + $data->ckhm + $data->ckttt);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header' => 'Trạng thái đơn hàng',
                            'type' => 'raw',
                            'filter' => FALSE,
                            'value' => function ($data) {

                                return Chtml::encode(AOrders::getStatusFiber($data->id));
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header' => 'Ghi chú',
                            'type' => 'raw',
                            'filter' => FALSE,
                            'value' => function ($data) {

                                return $data->note;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header' => 'STBOX',
                            'type' => 'raw',
                            'filter' => FALSE,
                            'value' => function ($data) {
                                if ($data->stb_use = 'no') {
                                    return 'Không sử dụng';
                                } elseif ($data->stb_use = 'yes') {
                                    return 'Có sử dụng';
                                }
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        )
                    ),
                )); ?>
            </div>
        <?php } ?>
    </div>
</div>
<style>
    #aorders-grid tr td {
        font-size: 13px !important;
    }

    .table-responsive {
        overflow-x: scroll !important;
        min-height: 0.01%;
        width: 100% !important;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }
</style>
