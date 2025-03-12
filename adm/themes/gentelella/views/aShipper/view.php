<?php
    /* @var $this CskhShipperController */
    /* @var $model CskhShipper */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        'Quản lý NV giao vận' => array('admin'),
        $model->id,
    );

    $this->menu = array(
        array('label' => Yii::t('cskh/menu', 'manage_shipper'), 'url' => array('admin')),
    );
?>
<div class="x_panel">
    <div class="x_content">
        <div class="container">
            <div class="x_title">
                <h4>Thông tin shipper</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive tbl_style center">
                        <?php $this->widget('booster.widgets.TbDetailView', array(
                            'data'       => $model,
                            'type'       => '',
                            'attributes' => array(
                                array(
                                    'name'        => "Mã shipper",
                                    'value'       => function ($data) {
                                        return Chtml::encode($data['id']);
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => 'Tên đăng nhập',
                                    'value'       => function ($data) {

                                        return CHtml::encode($data->username);
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => "Họ và tên",
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->full_name);
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => 'Số ĐT 1',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->phone_1);
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => 'Doanh thu tổng',
                                    'value'       => function ($data) {

                                        return CHtml::encode($data->getTotalOrder($data->id, $data->start_date, $data->end_date));
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle; text-align:right;'),
                                ),
                            ),
                        )); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive tbl_style center">
                        <?php $this->widget('booster.widgets.TbDetailView', array(
                            'data'       => $model,
                            'type'       => '',
                            'attributes' => array(
                                array(
                                    'name'        => 'Tỉnh thành',
                                    'value'       => function ($data) {
                                        return Chtml::encode(AOrders::model()->getProvince($data->province_code));
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => 'Quận huyện',
                                    'value'       => function ($data) {

                                        return Chtml::encode(AOrders::model()->getDistrict($data->district_code));
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => "Phường Xã",
                                    'value'       => function ($data) {
                                        return CHtml::encode(AOrders::model()->getWard($data->ward_code));
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => 'Điểm giao dịch',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->brand_office_id);
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),
                                array(
                                    'name'        => 'Địa chỉ',
                                    'value'       => function ($data) {
                                        return CHtml::encode($data->address_detail);
                                    },
                                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                ),

                            ),
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="space_30"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_content">
                        <?php $this->widget(
                            'booster.widgets.TbTabs',
                            array(
                                'type'        => 'tabs',
                                'tabs'        => array(
                                    array(
                                        'label'   => ($start_date != '' && $end_date != '') ? 'Doanh thu chi tiết từ ngày ' . date("d/m/Y",strtotime(str_replace('-', '/', $start_date))) . ' đến ngày ' . date("d/m/Y",strtotime(str_replace('-', '/', $end_date))) : 'Doanh thu chi tiết',
                                        'content' => $this->renderPartial('_renueve_detail', array('model' => $model, 'total_renueve_date' => $total_renueve_date, 'total_order' => $total_order, 'data' => $data), TRUE),
                                        'active'  => TRUE,
                                    ),
                                ),
                                'htmlOptions' => array('class' => 'site_manager')
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="space_30"></div>
            </div>
        </div>
    </div>
</div>

