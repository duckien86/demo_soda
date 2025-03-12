<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model AFTOrders
 * @var $model_logs AFTLogs
 * @var $model_details AFTOrderDetails
 */
    /*  */
    /*  */
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/actions', 'view'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="col-md-6">
            <div class="title-order">
                * Thông tin đơn hàng
            </div>
            <?php $this->widget('booster.widgets.TbDetailView', array(
                'data'       => $model,
                'type'       => '',
                'attributes' => array(
                    array(
                        'name'        => 'Mã đơn hàng',
                        'value'       => function ($data) {
                            return Chtml::encode($data->code);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Mã hợp đồng',
                        'value'       => function ($data) {
                            $return = '';
                            if ($data->contract_id != '') {
                                $contract_code = AFTContracts::model()->findByAttributes(array('id' => $data->contract_id));
                                if ($contract_code) {
                                    $return = $contract_code->code;
                                }
                            }

                            return CHtml::encode($return);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Ngày đặt hàng',
                        'value'       => function ($data) {
                            return Chtml::encode($data->create_time);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Thời hạn nhận hàng',
                        'value'       => function ($data) {
                            return Chtml::encode($data->delivery_date);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),

                    array(
                        'name'        => 'Trạng thái',
                        'value'       => function ($data) {
                            return Chtml::encode(AFTOrders::getStatusOrderCard($data->status));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Giá trị đơn hàng',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $total_renuve = AFTOrders::getTotalOrders($data->id);

                            return CHtml::link(number_format($total_renuve, 0, '', '.') . " đ", 'javascript:void(0)', array('style' => 'font-size:16px; color:blue;'));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'      => 'Ủy nhiệm chi',
                        'type'      => 'raw',
                        'value'     => function($data){
                            $url = AFTFiles::getFileUrl(AFTFiles::OBJECT_FILE_ACCEPT_PAYMENT,$data->id);
                            $name = AFTFiles::getFileName(AFTFiles::OBJECT_FILE_ACCEPT_PAYMENT,$data->id);
                            $link = '';
                            if($url){
                                $link = CHtml::link($name,$url, array(
                                    'target' => '_blank',
                                ));
                            }
                            return $link;
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
        <div class="col-md-6">
            <div class="title-order">
                * Thông tin khách hàng
            </div>
            <?php
            $user = AFTUsers::getUserByContract($model->contract_id);

            $this->widget('booster.widgets.TbDetailView', array(
                'data'       => $model,
                'type'       => '',
                'attributes' => array(
                    array(
                        'name'        => 'Người đặt hàng',
                        'value'       => CHtml::encode($model->orderer_name),
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Loại khách hàng',
                        'value'       => AFTUsers::getTypeLabel($user->user_type),
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Điện thoại đặt hàng',
                        'value'       => function ($data) {
                            return Chtml::encode($data->orderer_phone);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Người nhận hàng',
                        'value'       => function ($data) {
                            return Chtml::encode($data->receiver_name);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Ghi chú',
                        'value'       => function ($data) {
                            return Chtml::encode($data->note);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Hình thức nhận',
                        'value'       => AFTUsers::getLabelReceiveMethod($user->receive_method),
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'End Point',
                        'value'       => $user->getReceiveEndpoint(),
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>

        <div class="col-md-12" style="margin-top: 30px;">
            <div class="col-md-4 col-sm-4" style="float: right;">
                <a href="javascript:void(0);" onclick="send_sms('<?php echo $model->id ?>');" target="_blank" class="btn
                    btn-warning"
                   style="float: right; margin-right: 15px;z-index: 20;"><i class="glyphicon glyphicon-envelope"></i>
                    &nbsp;Gửi
                    SMS</a>
            </div>
            <?php $this->widget(
                'booster.widgets.TbTabs',
                array(
                    'type'        => 'tabs',
                    'tabs'        => array(
                        array(
                            'label'   => 'Chi tiết đơn hàng',
                            'content' => $this->renderPartial('_details', array('model_details' => $model_details, 'model' => $model), TRUE),
                            'active'  => TRUE,
                        ),
                        array(
                            'label'   => 'Lịch sử đơn hàng',
                            'content' => $this->renderPartial('_logs', array('model_logs' => $model_logs, 'model' => $model), TRUE),
                        ),

                    ),
                    'htmlOptions' => array('class' => 'site_manager')
                )
            ); ?>
        </div>
        <div class="popup_sendSms">

        </div>
    </div>
</div>

<script type="text/javascript">
    function send_sms(order_id) {
        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aFTOrders/showPopupSendSms') ?>',
            crossDomain: true,
            dataType: 'html',
            data: {
                order_id: order_id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function (result) {
                $('.modal-backdrop').remove();
                $('.popup_sendSms').html(result);
                var modal_id = 'modal_send_sms_' + order_id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }
</script>
