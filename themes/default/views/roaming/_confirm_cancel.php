<?php
    /* @var $this RoamingController */
?>
<div class="text-center font_25 lbl_color_blue">
    Hủy gói cước
</div>
<div class="space_20"></div>
<div class="col-md-2"></div>
<div class="col-md-8">
    <a href="#" onclick="return false" class="btn btn_cancel_ir" id="test_btn_cancel_ir">Hủy CVQT</a>
    <div class="space_20"></div>
    <a href="#" onclick="return false" class="btn btn_cancel_rx">Hủy gói Data Roaming</a>
</div>
<div class="col-md-2"></div>
<div class="space_20"></div>
<script>
    $(document).on('click', '.btn_cancel_ir', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getFormSendOtpCancelIr');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                modal_body.html(result.content);
                modal_roaming.modal('show');
            }
        });
    });

    $(document).on('click', '.btn_cancel_rx', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getFormSendOtpCancel');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                modal_body.html(result.content);
                modal_roaming.modal('show');
            }
        });
    });
</script>
