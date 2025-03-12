<div id="modal_<?= $id ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <input type="hidden" value="point-<?= $id ?>" class="point-popup">

        <input type="hidden" value="<?= $point_user ?>" class="point-user">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Đổi diểm nhận quà</h4>
            </div>
            <div class="modal-body">
                <p style="text-align: justify">Bạn có chắc chắn muốn đổi. Điểm của bạn sẽ bị trừ theo giá trị hiện tại
                    của món quà bạn muốn đổi</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="confirmPackage();return false;">Xác nhận</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<div class="show-popup-success">

</div>
<script>
    function confirmPackage() {
        var id_product = $('.point-popup').val();
        var point_user = $('.point-user').val();
        point_user = point_user - $('#' + id_product).val();
        $.ajax({
            type: "POST",

            url: '<?=Yii::app()->createUrl('landing/updatePoint')?>',
            crossDomain: true,
            data: {
                point_user_popup: point_user,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                alert("Đổi quà thành công!");
                window.location.reload();
            },
            error: function (data) {

            }
        });
    }

</script>