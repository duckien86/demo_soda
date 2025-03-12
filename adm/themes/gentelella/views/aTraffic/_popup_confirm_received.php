<?php if (isset($id) && isset($status)):
    ?>
    <div class="modal" id="modal_confirm" role="dialog">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-body">
                    <span><h4>Bạn có chắc chắn thu tiền ?</h4></span>
                </div>
                <div class="modal-footer">
                    <a id="<?php echo $id; ?>"
                       onclick="receive_money('<?= $id ?>','<?= $status ?>');"
                       class="btn btn-success">OK</a>
                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                        Hủy
                    </button>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<style>
    .modal-body span {
        color: red;
    }
    .modal-body{
        text-align: center;
    }
    .modal-footer{
        text-align: center !important;
    }
</style>
<script type="text/javascript">
    $('.close').click(function () {
        $('.modal-backdrop').remove();
        window.location.reload();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
        window.location.reload();
    });
    function receive_money(order_id,status) {
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
            url: '<?=Yii::app()->createUrl('aTraffic/changeStatusTraffic')?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                status: status,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                if (data == 0) {
                    alert("Không thành công!");
                    window.location.reload();
                } else {
                    alert("Thành công!");
                    window.location.reload();
                }

                return true;
            }
        });
    }
</script>