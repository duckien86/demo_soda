<?php
/**
 * @var $this AFTOrdersController
 * @var $model AFTOrders
 * @var $status int
 * @var $form CActiveForm
 */
?>

<?php if ($model && $status):

    ?>
    <div class="modal" id="modal_<?php echo $model->id; ?>_<?php echo $status; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'aftorders_accepted_payment-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation'=>false,
                'htmlOptions' => array(
                    'onsubmit' => 'return false'
                )
            )); ?>
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 style="text-align: center;"><?= AFTOrders::getNameStatusOrders($status); ?></h5>
                </div>
                <div class="modal-body">
                    <?php if ($show) { ?>
<!--                        <h5 class="modal-title">Bạn có đồng ý với thay đổi của bạn!</h5>-->

                        <div id="accepted_payment_files_error" class="text-danger"></div>

                        <?php echo $form->hiddenField($model,'id')?>

                        <div class="form-group">
                            <label>Upload File chứng thực thanh toán</label>

                            <?php echo $form->fileField($model,'accepted_payment_files', array(
                                'accept' => 'image/*, .csv, .pdf',
                                'required' => true
                            )); ?>
                        </div>

                        <div class="form-group">
                            <label>Hình thức thanh toán</label>

                            <?php echo $form->dropDownList($model, 'payment_method', AFTOrders::getListPayment(), array(
                                'class' => 'form-control',
                                'style' => 'width: 200px',
                            ))?>
                        </div>

                    <?php } else { ?>
                        <h5 class="modal-title">Bạn không được phép chuyển về trạng thái này!</h5>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <?php if ($show) { ?>
                        <?php echo CHtml::submitButton('Đồng ý',
                            array(
                                "class"     => "btn btn-success",
                                "onclick"   => "submitAcceptPayment();",
                                'id'        => 'btnSubmitAcceptPayment'
                            )); ?>
                    <?php } ?>
                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                        Hủy bỏ
                    </button>

                </div>
            </div>
            <?php $this->endWidget();?>
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


    function changeStatus(id, status) {
        $('#btnSubmitAcceptPayment').addClass('disabled');
        $('.loading-div').css({
            "float": "left",
            "width": "100%",
            "height": "100%",
            "z-index": "999999",
            "position": "absolute",
            "text-align": "center"
        });
        $('.loading-div').html("<img style='text-align:center; width:24px; height:24px;margin-top:10%; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aFTOrders/setStatus')?>',
            crossDomain: true,
            dataType: 'json',
            data: {id: id, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
            success: function (result) {
                if (result === true) {
//                    $('#aftorders-grid').yiiGridView('update', {
//                        data: $(this).serialize()
//                    });
                    alert("Thay đổi trạng thái thành công!");
                    $('#btnSubmitAcceptPayment').removeClass('disabled');
                    window.location.reload();
                    return true;
                }

            }
        });
    }
    
    function submitAcceptPayment() {
        if( document.getElementById("<?php echo CHtml::activeId($model,'accepted_payment_files')?>").files.length == 0 ){
            return;
        }
        var form_data = new FormData(document.getElementById("aftorders_accepted_payment-form"));//id_form
        var error = $('#accepted_payment_files_error');
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('aFTOrders/uploadFileAcceptPayment')?>',
            type: 'post',
            dataType: 'json',
            data: form_data,
            enctype: 'multipart/form-data',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            crossDomain: true,
            success: function (result) {
                console.log(result);
                if(result['error']){
                    error.html(result['error']);
                }else{
                    error.html('');
                    changeStatus('<?php echo $model->id?>', '<?php echo $status?>');
                }
            }
        });
    }
</script>

