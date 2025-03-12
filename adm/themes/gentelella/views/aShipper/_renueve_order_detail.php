
<div class="title">
    * Tổng số đơn hàng : <?= isset($total_order) ? $total_order : 0 ?>.<br>
    * Tổng doanh thu đơn hàng
    : <?= (isset($total_renueve_date)) ? number_format($total_renueve_date, 0, '', '.') . " đ" : 0 ?>.<br>
    * Tổng doanh thu shipper
    : <?= (isset($total_order)) ? number_format($total_order * 15000, 0, '', '.') . " đ" : 0 ?>.<br>
    * Tổng hoa hồng shipper
    : <?= (isset($total_order)) ? number_format($total_order * 20000, 0, '', '.') . " đ" : 0 ?>.
</div>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'orders-grid',
    'dataProvider'  => $data,
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'ajaxType'      => TRUE,
    'columns'       => array(
        array(
            'name'        => 'id',
            'value'       => function ($data) {
                return CHtml::encode($data->id);
            },
            'htmlOptions' => array('style' => 'width:100px;vertical-align:middle;'),
        ),
        array(
            'name'        => 'shipper_id',
            'value'       => function ($data) {
                return CHtml::encode($data->shipper_id);
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'Họ tên khách hàng',
            'value'       => function ($data) {
                return CHtml::encode($data->full_name);
            },
            'htmlOptions' => array('style' => 'width:160px;text-align: center;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'phone_contact',
            'value'       => function ($data) {
                return CHtml::encode($data->phone_contact);
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'Thời hạn giao hàng',
            'value'       => function ($data) {
                return CHtml::encode($data->delivery_date);
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'finish_date',
            'value'       => function ($data) {
                return CHtml::encode($data->finish_date);
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'delivery_type',
            'value'       => function ($data) {
                $return = "";
                if ($data->delivery_type == AOrders::COD) {
                    $return = "Nhận hàng tại nhà";
                } else {
                    $return = "Nhận tài phòng bán hàng";
                }

                return $return;
            },
            'htmlOptions' => array('style' => 'width:140px;text-align: center;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'Doanh thu đơn hàng',
            'value'       => function ($data) {
                return number_format(CHtml::encode($data->renueve_order), 0, '', '.') . " đ";
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: right;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'Hoa hồng',
            'value'       => function ($data) {
                if ($data->delivery_type == AOrders::COD) {
                    $return = "15.000 đ";
                } else {
                    $return = "0 đ";
                }
                return $return;
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: right;word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'Phí ship',
            'value'       => function ($data) {
                $return = '0';
                if ($data->delivery_type == AOrders::COD) {
                    $return = "20.000 đ";
                } else {
                    $return = "0 đ";
                }

                return $return;
            },
            'htmlOptions' => array('style' => 'width:90px;text-align: right;word-break: break-word;vertical-align:middle;'),
        ),

    ),
)); ?>
<style>
    .title {
        margin-left: 15px;
        margin-top: 20px;
    }
</style>
