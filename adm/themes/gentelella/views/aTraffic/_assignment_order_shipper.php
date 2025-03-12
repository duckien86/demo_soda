Thông tin đơn hàng.
<?php
    $this->widget('booster.widgets.TbGridView', array(
        'dataProvider' => $order_data,
        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
        'type'        => 'striped bordered  consended ',
        'htmlOptions' => array(
            'class' => 'tbl_style',
            'id'    => 'thongsoChung',
        ),
        'columns'     => array(
            array(
                'header'      => 'Mã đơn hàng',
                'value'       => function ($data) {

                    $return = $data->id;

                    return $return;
                },
                'htmlOptions' => array(
                    'style' => 'width:200px; text-align:left;',
                ),
            ),
            array(
                'header'      => 'Tên khách hàng',
                'value'       => function ($data) {

                    $return = $data->full_name;

                    return $return;
                },
                'htmlOptions' => array(
                    'style' => 'width:200px; text-align:right;',
                ),
            ),
            array(
                'header'      => 'Số điện thoại liên hệ',
                'type'        => 'raw',
                'value'       => function ($data) {
                    return $data->phone_contact;
                },
                'htmlOptions' => array(
                    'style' => 'width:200px; text-align:right;',
                ),
            ),
            array(
                'header'      => 'Địa chỉ',
                'value'       => function ($data) {

                    $return = $data->address_detail;

                    return $return;
                },
                'htmlOptions' => array(
                    'style' => 'width:200px; text-align:right; ',
                ),
            ),

        ),
    ));
?>