<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_roaming', 'htmlOptions' => array('data-backdrop' => 'static', 'data-keyboard' => 'false'))
); ?>
<div class="modal-header">
    <a class="close close_modal" data-dismiss="modal">&times;</a>
    <img class="logo" src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_freedoo.png">
</div>
<div class="modal-body">
</div>
<?php $this->endWidget(); ?>
<script>
    $(document).on('click', '.btn_reg_rx', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getFormRegister');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                package_id: $(this).attr('data-packageid'),
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                modal_body.html(result.content);
                modal_roaming.modal('show');
            }
        });

        /*reset modal*/
        modal_roaming.on('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind(); // or $(this)
            modal_body.empty();
            modal_roaming.removeData('bs.modal');
        }).modal('hide');
    });

    $(document).on('click', '.btn_search_rx', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getFormSendOtpSearch');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                modal_body.html(result.content);
                modal_roaming.modal('show');
            }
        });
        /*reset modal*/
        modal_roaming.on('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind(); // or $(this)
            modal_body.empty();
            modal_roaming.removeData('bs.modal');
        }).modal('hide');
    });

    $(document).on('click', '#btn_confirm_cancel_ir_rx', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getFormConfirmCancel');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                modal_body.html(result.content);
                modal_roaming.modal('show');
            }
        });
        /*reset modal*/
        modal_roaming.on('hidden.bs.modal', function (e) {
            //refresh page
            location.reload();//reset click on modal
            $(e.currentTarget).unbind(); // or $(this)
            modal_body.empty();
            modal_roaming.removeData('bs.modal');
        }).modal('hide');
    });

    $(document).on('click', '.close_modal', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        /*reset modal*/
        modal_roaming.on('hidden.bs.modal', function (e) {
            //refresh page
            location.reload();//reset click on modal
            $(e.currentTarget).unbind(); // or $(this)
            modal_body.empty();
            modal_roaming.removeData('bs.modal');
        }).modal('hide');
    });

    $(document).on('click', '#btn_register_ir', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getFormRegisterIrOnly');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                modal_body.html(result.content);
                modal_roaming.modal('show');
            }
        });
        /*reset modal*/
        modal_roaming.on('hidden.bs.modal', function (e) {
            //refresh page
            location.reload();//reset click on modal
            $(e.currentTarget).unbind(); // or $(this)
            modal_body.empty();
            modal_roaming.removeData('bs.modal');
        }).modal('hide');
    });
</script>
