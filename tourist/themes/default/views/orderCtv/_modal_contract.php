<div id="modal_contract" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('tourist/label','contract_info') ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" id="btn-close" class="btn" data-dismiss="modal"><?php echo Yii::t('tourist/label','close') ?></button>
            </div>
        </div>

    </div>
</div>
<script>
    $('#modal_contract').appendTo("body");
</script>
