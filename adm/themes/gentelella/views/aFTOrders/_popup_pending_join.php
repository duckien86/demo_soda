<?php if ($order_id && $show):

    ?>
    <div class="modal" id="modal_pending_<?php echo $order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 30%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5>Thông báo</h5>
                </div>
                <div class="modal-body" style="text-align: justify;">
                    <?php if ($show): ?>
                        <p style="width: 80%; margin: auto;">Hệ thống đang bắt dầu tiến trình xử lý Ghép kít.
                            Chúng tôi sẽ thông báo lại khi có kết quả và vui lòng quay lại kiểm tra sau 5 phút</p>
                        <h5 style="color:red; text-align: center">Thời gian dự kiến <?= ($length_serial - 1) * 10 ?>
                            s</h5>
                    <?php else: ?>
                        <p style="width: 80%; margin: auto;">Quá trình upload xảy ra lỗi! Vui lòng upload lại file!</p>
                    <?php endif; ?>
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

</script>

