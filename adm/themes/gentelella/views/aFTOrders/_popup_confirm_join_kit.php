<?php if ($files && $order_id && $length_serial):

    ?>
    <div class="modal" id="modal_confirm_join_kit_<?php echo $order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 30%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 style="text-align: center;">Thông báo</h5>
                </div>
                <div class="modal-body" style="text-align: justify;">
                    <h5 style="text-align: center;">Hãy chắc chắn rằng danh sách Sim bạn upload đã được kích hoạt trên
                        SMCS</h5>
                    <input type="hidden" name="AFTFiles[object]" id="AFTFiles_object"
                           value="<?= $files->object ?>">
                    <input type="hidden" name="AFTFiles[object_id]" id="AFTFiles_object_id"
                           value="<?= $files->object_id ?>">
                    <input type="hidden" name="AFTFiles[file_name]" id="AFTFiles_file_name"
                           value="<?= $files->file_name ?>">
                    <input type="hidden" name="AFTFiles[file_ext]" id="AFTFiles_file_ext"
                           value="<?= $files->file_ext ?>">
                    <input type="hidden" name="AFTFiles[file_size]" id="AFTFiles_file_size"
                           value="<?= $files->file_size ?>">
                    <input type="hidden" name="AFTFiles[folder_path]" id="AFTFiles_folder_path"
                           value="<?= $files->folder_path ?>">
                    <input type="hidden" name="AFTFiles[length_serial]" id="AFTFiles_length_serial"
                           value="<?= $length_serial ?>">

                </div>
                <div class="modal-footer">
                    <?php echo CHtml::button('Xác nhận',
                        array("onclick" => "upload_file('$order_id')",
                              "class"   => "btn btn-success")); ?>
                    <button type="button" class="btn btn-default close-button" id="close-button-remove" data-dismiss="modal">
                        Hủy
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
        window.location.reload();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });
    $('#close-button-remove').click(function(){
        $('.modal-backdrop').remove();
        window.location.reload();
    });
    function upload_file(order_id) {

        var object = $('#AFTFiles_object').val();
        var object_id = $('#AFTFiles_object_id').val();
        var file_name = $('#AFTFiles_file_name').val();
        var file_ext = $('#AFTFiles_file_ext').val();
        var file_size = $('#AFTFiles_file_size').val();
        var folder_path = $('#AFTFiles_folder_path').val();
        var length_serial = $('#AFTFiles_length_serial').val();
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
//            dataType: 'json',
            url: '<?=Yii::app()->createUrl('aFTOrders/uploadSimAfterConfirm')?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                object: object,
                object_id: object_id,
                file_name: file_name,
                file_ext: file_ext,
                file_size: file_size,
                folder_path: folder_path,
                length_serial: length_serial,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                $('.modal-backdrop').remove();
                $('.popup_pending_join').html(data);
                $('.popup_confirm_join_kit').hide();
                var modal = 'modal_pending_' + order_id;

                $('#' + modal).modal("show");
            }
        });
    }
</script>

