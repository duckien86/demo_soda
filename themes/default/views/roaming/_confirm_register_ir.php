<?php
    /* @var $this RoamingController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
?>
<div class="text-center font_bold font_16">Quý khách chưa đăng ký CVQT, vui lòng xác nhận đăng ký CVQT trước khi thực
    hiện đăng ký <?= CHtml::encode($modelPackage->name); ?></div>
<div class="space_20"></div>

<div class="package_info text-center">
    <?php echo CHtml::link('Hủy', '', array('class' => 'btn btn-default width_100 close_modal', 'data-dismiss' => 'modal')); ?>
    <?php echo CHtml::link('Đồng ý', '', array('id' => 'confirm_reg_ir', 'class' => 'btn bg_btn width_100')); ?>
</div>
<script>
    //    $('#modal_roaming').unbind('click').on('click', '#confirm_reg_ir', function (e) {
    $(document).on('click', '#confirm_reg_ir', function (e) {
        $(this).unbind('click');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/confirmRegisterIr');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                $('#modal_roaming .modal-body').html(result.content);
                $('#modal_roaming').modal('show');
            }
        });
    });
</script>
