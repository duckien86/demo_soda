<?php if (isset($id)):

    ?>
    <div class="modal" id="modal_<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <?php if ($status == APosts::INACTIVE) { ?>
                        <h4 class="modal-title">Lý do muốn ẩn bình luận</h4>
                    <?php } else { ?>
                        <h4 class="modal-title">Lý do bạn phải khôi phục lại bình luận</h4>
                    <?php } ?>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <textarea id="info_<?php echo $id; ?>" rows="6" cols="12" style="width: 100%"></textarea>
                    </div>
                    <div class="row">
                        <input type="hidden" id="hidden_id" value="<?php echo $id; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="<?php echo $id; ?>" style="margin-top: 5px;"
                       onclick="ajaxUpdateResult(this.id,$('#info_<?= $id ?>').val(),'<?= $status ?>','<?= $sso_id ?>');"
                       class="btn btn-success">Kết thúc</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<script type="text/javascript">
    function ajaxUpdateResult(id, info, status, sso_id) {
        if (info != '') {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aComments/updateStatusInfo')?>',
                crossDomain: true,
                data: {id: id, info: info, status: status, sso_id: sso_id},
                success: function (result) {
                    window.location.reload();
                    return false;
                }
            });
        } else {
            alert("Bạn phải nhập lý do ẩn bình luận!");
        }
    }
</script>
