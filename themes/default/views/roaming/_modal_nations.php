<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_nations')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="text-center"><?php echo Yii::t('web/portal', 'popup_title_nation'); ?></h4>
</div>
<div class="modal-body">
</div>
<?php $this->endWidget(); ?>
<script>
    $(document).on('click', '.view_nation', function (e) {
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('roaming/getNations');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                package_id: $(this).attr('data-packageid'),
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                $('#modal_nations .modal-body').html(result.content);
                $('#modal_nations').modal('show');
            }
        });
    });
</script>
