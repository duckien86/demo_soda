<?php $this->beginWidget('booster.widgets.TbModal', array(
    'id' => 'modal_change_msisdn_prefix',
    'htmlOptions' => array(
        'data-backdrop' => 'static',
        'data-keyboard' => 'false'
    ))
); ?>
<div class="modal-header">
    <h4 class="text-center"><?php echo Yii::t('web/portal', 'notify'); ?></h4>
</div>
<div class="modal-body">
    <div class="text-center">
        Số điện thoại <b id="msisdn_prefix_old"></b> của bạn đã được chuyển sang đầu số mới: <b id="msisdn_prefix_new"></b>
    </div>
    <div id="msg_remove_keep">
    </div>
    <div class="space_30"></div>
    <div class="text-center">
        <a class="btn btn_green" data-dismiss="modal">Xác nhận</a>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>

<script>

    $(document).ready(function() {
        $('#modal_change_msisdn_prefix').appendTo('body');
    });

    function changeMsisdnPrefix(input, callback){
        var msisdn = input.value;
        if(msisdn.match(/^\d{9,12}$/)){
            $.ajax({
                type: "POST",
                url: '<?php echo Yii::app()->createUrl('site/changeMsisdnPrefix');?>',
                dataType: 'json',
                data: {
                    'msisdn' : msisdn,
                    'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken;?>'
                },
                success: function (result) {
                    if(result.change == true){
                        $(input).val(result.msisdn_prefix_new);
                        if(callback != null){
                            callback();
                        }
                        $('#modal_change_msisdn_prefix #msisdn_prefix_old').text(result.msisdn_prefix_old);
                        $('#modal_change_msisdn_prefix #msisdn_prefix_new').text(result.msisdn_prefix_new);
                        $('#modal_change_msisdn_prefix').modal('show');
                    }
                }
            });
        }

    }
</script>