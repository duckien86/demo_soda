<?php
    /* @var $this PackageController */
    /* @var $searchPackageForm SearchPackageForm */
    /* @var $form CActiveForm */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'confirm_search_package')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
</div>
<div class="modal-body text-center">
    <script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
    <div class="text-center font_15">
        Vui lòng nhấn xác nhận để tìm gói cước ưu đãi dành riêng cho bạn
    </div>
    <div class="space_20"></div>
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'confirm_search_package',
        'action'               => Yii::app()->controller->createUrl('package/searchPackage'),
        'enableAjaxValidation' => true,
//        'htmlOptions'          => array('onsubmit' => 'return false;'),
    )); ?>
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <?php echo $form->hiddenField($searchPackageForm, 'msisdn'); ?>
        <?php echo $form->hiddenField($searchPackageForm, 'searchType'); ?>
        <div class="form-group">
            <div id="captcha_place_holder"
                 class="g-recaptcha"
                 data-sitekey="6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t"></div>
            <?php echo $form->error($searchPackageForm, 'captcha'); ?>
        </div>
    </div>
    <div class="col-md-2"></div>
    <div class="space_10"></div>
    <div class="package_info text-center">
        <?php echo CHtml::submitButton('Xác nhận', array('class' => 'btn bg_btn')); ?>
    </div>
    <?php $this->endWidget(); ?>
    <div class="space_10"></div>
</div>
<?php $this->endWidget(); ?>

<script>
//    $('#confirm_search_package').unbind('submit').on('submit', '#confirm_search_package', function (e) {
//        var modal = $('#confirm_search_package');
//        var modal_body = $('#confirm_search_package .modal-body');
//        e.preventDefault();
//        $(':input[type="submit"]').prop('disabled', true);
//        // this.submit();
//        $.ajax({
//            url: $(this).attr('action'),
//            crossDomain: true,
//            type: $(this).attr('method'),
//            cache: false,
//            dataType: "json",
//            data: $(this).serialize(),
//            success: function (result) {
//                $(':input[type="submit"]').prop('disabled', false);
//                modal_body.html(result.content);
//            },
//            error: function (request, status, err) {
//                $(':input[type="submit"]').prop('disabled', false);
//            }
//        });
//    });
</script>