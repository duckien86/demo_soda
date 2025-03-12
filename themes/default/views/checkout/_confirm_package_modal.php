<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_package')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="package_title text-center" id="package_name"></h4>
</div>
<div class="modal-body">
    <div id="package_info">

    </div>
    <div class="space_30"></div>
    <div class="pull-right">
        <?= CHtml::link(Yii::t('web/portal', 'close'), '#', array('class' => 'btn btn_green', 'data-dismiss' => 'modal')) ?>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>
<!-- Modal modal_register_package -->
<div id="modal_register_package" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Điều khoản sử dụng SIM HEY</h4>
            </div>
            <div class="modal-body">
                <h5>Điều khoản sử dụng bộ SIM HEYZALO Bundle</h5>
                <p>1. Đối tượng: Chỉ dành riêng cho Học sinh, Sinh viên</p>
                <p>2. Độ tuổi: Áp dụng cho khách hàng từ 14-25 tuổi</p>
                <p>3. Sau khi kích hoạt trong vòng 30 ngày kể từ ngày đăng ký TTTB thành công, quý khách vui lòng soạn tin nhắn: KH< dấu cách >HZ gửi 900 để kích hoạt ưu đãi gói HEYZALO Bundle.</p>
            </div>

            <div class="modal-footer" style="text-align: center">
                <div class="col-xs-6">
                    <a href=""></a>
                    <button id="reject_package" type="button" class="btn btn-danger" data-dismiss="modal">Không đồng ý</button>
                </div>
                <div class="col-xs-6">
                    <button data-type="1" type="button" class="btn btn-success confirm_package " data-dismiss="modal">Đồng ý</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function getPackageDetail(package_id) {
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('checkout/getPackageDetail');?>",
            crossDomain: true,
            dataType: 'json',
            data: {package_id: package_id, YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"},
            success: function (result) {
                $('#package_info').html(result.content);
                $("#package_name").text(result.package_name);
                $('#modal_package').modal('show');
            }
        });
    }
</script>