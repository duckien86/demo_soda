<?php
/**
 * @var $this WorldcupController
 * @var $modelMatch WWCMatch
 * @var $modelForm WWCReport
 * @var $show boolean
 * @var $save boolean
 */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id'       => 'modal_worldcup_predict',
        'autoOpen' => $show,
    )
); ?>

<div class="modal-body">
    <?php if(!$modelMatch->isNewRecord){?>
    <?php echo $this->renderPartial('/worldcup/_modal_predict_content', array(
        'modelMatch' => $modelMatch,
        'modelForm'  => $modelForm,
        'save'       => $save,
    ))?>
    <?php }?>
</div>

<?php $this->endWidget()?>