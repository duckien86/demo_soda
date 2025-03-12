<?php
/**
 * @var $this AFTOrdersController
 * @var $model AFTOrders
 * @var $status int
 */
?>

<?php if ($model && $status):

    ?>
    <div class="modal" id="modal_<?php echo $model->id; ?>_<?php echo $status; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 style="text-align: center;"><?= AFTOrders::getNameStatusOrders($status); ?></h5>
                </div>
                <div class="modal-body">
                    <?php if ($show) { ?>
                        <h5 class="modal-title">Bạn có đồng ý với thay đổi của bạn!</h5>
                    <?php } else { ?>
                        <h5 class="modal-title">Bạn không được phép chuyển về trạng thái này!</h5>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <?php if ($show) { ?>
                        <?php echo CHtml::submitButton('Đồng ý',
                            array("onclick" => "changeStatus('$model->id','$status');",
                                "id" => "btnSubmitAcceptPayment",
                              "class" => "btn btn-success")); ?>
                    <?php } ?>
                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                        Hủy bỏ
                    </button>

                </div>
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
        $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
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
</script>

