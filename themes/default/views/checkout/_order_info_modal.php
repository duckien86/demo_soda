<!-- Modal order info-->
<div id="order_info_modal" class="sim_info_modal modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Chi tiết đơn hàng</h4>
            </div>
            <div class="modal-body">
                <?php $this->renderPartial('_panel_order', array(
                    'modelSim'     => $modelSim,
                    'modelOrder'   => $modelOrder,
                    'modelPackage' => $modelPackage,
                    'amount'       => $amount,
                )); ?>
                <?php if($modelSim->type== WSim::TYPE_POSTPAID){ ?>
                    <div class="policy_box">
                        <div class="checkbox">
                            <label><input id="accept_policy" type="checkbox" value="" style="margin-top: 0px !important;">Đồng ý với điều kiện mua sim trả sau có cam kết</label>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="trigger collapsed">
                                        Chi tiết điều khoản
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <ul>
                                        <li>1. Thuê bao phải đặt cọc và thanh toán online 01 tháng cước cam kết và sẽ được khấu trừ vào tháng cuối cùng của thời gian cam kết</li>
                                        <li>2. Thuê bao chỉ được chuyển quyền sử dụng và chuyển tỉnh sau 6 tháng hòa mạng</li>
                                        <li>3. Thuê bao không được hủy số hoặc thanh lý hợp đồng trong thời gian cam kết</li>
                                        <li>4. Thuê bao phải thanh toán đầy đủ cước cam kết trong thời gian cam kết.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="modal-footer">
                <a class="btn btn_green btn_continue" data-dismiss="modal" href="#">Đóng</a>
                <?php if($modelSim->type== WSim::TYPE_POSTPAID){ ?>
                    <?php echo CHtml::submitButton(Yii::t('web/portal', 'verify'), array('class' => 'btn btn_continue', 'disabled' => true)); ?>
                <?php }else{?>
                    <?php echo CHtml::submitButton(Yii::t('web/portal', 'verify'), array('class' => 'btn btn_continue', 'disabled' => false)); ?>
                <?php }?>
            </div>
        </div>

    </div>
</div>
<script>
    $('#accept_policy').on('change',function(){
        if($(this).is(':checked')){
            $('#order_info_modal .btn_continue').prop('disabled', false)
        }else{
            $('#order_info_modal .btn_continue').prop('disabled', true)
        }
    })
</script>
<!--./END modal order info-->