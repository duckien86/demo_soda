<?php if (isset($id)):
    $order = AOrders::model()->findByAttributes(array('id' => $id));
    $order_sim = ASim::model()->findByAttributes(array('order_id' => $id));
    $address = '';
    $type_sim = '';
    if ($order) {
        if ($order->delivery_type == AOrders::COD) {
            $address = $order->address_detail . " -- " . Ward::model()->getWard($order->ward_code) . " -- "
                . District::model()->getDistrict($order->district_code) . " -- " . Province::model()->getProvince($order->province_code);
        } else {
            $address = Ward::model()->getWard($order->ward_code) . " -- " . District::model()->getDistrict($order->district_code) . " -- "
                . Province::model()->getProvince($order->province_code);
        }
    }

    if ($order_sim) {
        if ($order_sim->type == ASim::TYPE_PREPAID) {
            $type_sim = 'Trả trước';
        } else {
            $type_sim = 'Trả sau';
        }
    }

    ?>
    <div class="modal" id="modal_<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" style="width: 65%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Phân công người vận chuyển</h4>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title">
                        <span>* Thông tin đơn hàng:</span>
                    </h5>
                    <h5 class="modal-title">
                        <span>- Địa chỉ nhận hàng:</span> <?= $address ?>
                    </h5>
                    <h5 class="modal-title">
                        <span>- Hình thức thuê bao:</span> <?= $type_sim ?>
                    </h5>
                    <?php
                        $this->widget('booster.widgets.TbGridView', array(
                            'dataProvider' => $data,

                            'type'        => 'striped bordered  consended ',
                            'htmlOptions' => array(
                                'class' => 'tbl_style',
                                'id'    => 'thongsoChung',
                            ),
                            'columns'     => array(
                                array(
                                    'header'      => 'Tên đăng nhập',
                                    'value'       => function ($data) {

                                        $return = $data->username;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:200px; text-align:left;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Họ tên',
                                    'value'       => function ($data) {

                                        $return = $data->full_name;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:200px; text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Email',
                                    'type'        => 'raw',
                                    'value'       => function ($data) {
                                        return CHtml::link($data->email, 'javascript:void(0)', array('id' => 'email_' . $data->id));
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:200px; text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Số điện thoại',
                                    'value'       => function ($data) {

                                        $return = $data->phone_1;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:200px; text-align:right; ',
                                    ),
                                ),
                                array(
                                    'header'      => 'Thao tác',
                                    'class'       => 'CDataColumn',
                                    'type'        => 'raw',
                                    'filter'      => TRUE,
                                    'htmlOptions' => array('style' => 'text-align:center'),
                                    'value'       => 'CHtml::radioButtonList("choice","",array("$data->id"=>""), array( "separator" => "$data->id"))',
                                ),
                            ),
                        ));
                    ?>
                </div>
                <div class="modal-footer">
                    <a id="<?php echo $id; ?>" style="margin-top: 5px;"
                       onclick="assignment_shipper('<?= $id ?>');"
                       class="btn btn-success">Phân công</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Hủy
                    </button>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<style>
    .modal-title span{
        color: red;
    }
</style>
<script type="text/javascript">
    function assignment_shipper(order_id) {
        $('.loading-div').css({
            "float": "left",
            "width": "100%",
            "height": "100%",
            "z-index": "999999",
            "position": "absolute",
            "text-align": "center"
        });
        $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
        var shipper_id = $('input:radio[name=choice]:checked').val();
        var email_id = 'email_' + shipper_id;
        var email = $('#' + email_id).text();
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aTraffic/assignmentShipperReturn')?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                shipper_id: shipper_id,
                email: email,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                if (data == 0) {
                    alert("Không thành công!");
                    window.location.reload();
                } else {
                    alert("Thành công!");
                    window.location.reload();
                }

                return true;
            }
        });
    }
</script>