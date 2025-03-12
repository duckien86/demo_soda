<?php if ($model): ?>
    <div class="modal" id="modal_<?php echo $model->id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Phân công đơn hàng <?= $model->code ?></h4>
                </div>
                <div class="modal-body">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'id'                   => 'assign-form',
                        'action'               => Yii::app()->createAbsoluteUrl("aFTOrders/assign"),
                        'enableAjaxValidation' => TRUE,
                        'htmlOptions'          => array('onsubmit' => "return true;"),
                    )); ?>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <?php echo $form->dropDownList($model, 'user_id', ($model->province_code != '') ? User::getUserPBHDN($model->province_code) : array(), array(
                                        'class' => 'form-control change-order',
                                        'empty' => 'Chọn nhân viên phụ trách',

                                    )
                                ); ?>
                                <?php
                                    echo $form->error($model, 'user_id');
                                ?>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo CHtml::submitButton('Phân công',
                        array("onclick" => "assign_order('$model->id');",
                              "id"      => "$model->id",
                              "class"   => "btn btn-success")); ?>
                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                        Hủy bỏ
                    </button>
                </div>
                <?php $this->endWidget(); ?>
            </div>

        </div>
    </div>
<?php endif; ?>
<style>
    .close {
        margin-top: -10px !important;
    }
</style>
<script type="text/javascript">
    // Xác thực mã otp
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });

    function assign_order(order_id) {
        var user_id = $('#AFTOrders_user_id').val();
        if (user_id != '') {
            $('#AFTOrders_user_id_em_').css("display", "none");
            $('.loading-div').css({
                "float": "left",
                "width": "100%",
                "height": "100%",
                "z-index": "999999",
                "position": "absolute",
                "text-align": "center"
            });
            $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%;; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: '<?=Yii::app()->createUrl('aFTOrders/assign')?>',
                crossDomain: true,
                data: {
                    order_id: order_id,
                    user_id: user_id,
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
                },
                success: function (data) {
                    if (data == 1) {
                        alert("Thành công!");
                    } else {
                        alert("Thất bại!");
                    }
                    window.location.reload();
                    return true;
                }
            });
        } else {
            $('#AFTOrders_user_id_em_').html("Bạn phải chọn nhân viên phụ trách!");
            $('#AFTOrders_user_id_em_').css("display", "block");
        }
    }
</script>

