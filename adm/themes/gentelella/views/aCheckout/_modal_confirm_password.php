<?php
    /**
     * Created by PhpStorm.
     * User: staff
     * Date: 9/4/2018
     * Time: 4:42 PM
     */
?>
<style>
    #confirm_password .modal-dialog{
        max-width: 400px;
    }
</style>
<!-- Modal -->
<div id="confirm_password" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center help-block"><b>Xác nhận mật khẩu</b></h4>
            </div>
            <div class="modal-body text-center">
                <div  class="form-group">
                    <p class="error_msg"></p>
                    <div id='box_cf_pw'>
                        <p><input class="form-control" id="conform_password" placeholder="Nhập mật khẩu để xác nhận..." type="password" name="conform_password"></p>
                        <button id="btn_conform_password" onClick="confirmPassword()" type="button" class="btn btn_continue">Kiểm tra</button>
                    </div>
                    <div id="box_from_pw" style="display: none">
                        <?php echo CHtml::submitButton(Yii::t('web/portal', 'continue'), array('class' => 'btn btn-success')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //radio button sim_type onclick
    $(".error_msg").text('');
    function confirmPassword() {
        $('#btn_conform_password').prop('disabled', true)
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('aCheckout/confirmPassword');?>",
            crossDomain: true,
            dataType: 'json',
            data: {conform_password: $('#conform_password').val(), YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"},
            success: function (result) {
                $('#btn_conform_password').prop('disabled', false)
                $(".error_msg").text(result.msg);
                if(result.code == 1){
                    $('#box_cf_pw').css('display' , 'none');
                    $('#box_from_pw').css('display' , 'block');
                }
            }
        });
    }
</script>
