<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model UserLogin
 * @var $form TbActiveForm
 * @var $open boolean
 */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'modal_confirm_export',
        'autoOpen' => $open,
    )
); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Xác thực người dùng</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div id="confirm_export_msg" class="text-success"></div>
                <div id="confirm_export_error" class="text-danger"></div>
                <?php echo $form->labelEx($model,'password', array('class' => 'form-label'));?>
                <p>(Nhập mật khẩu của bạn để tiến hành xác thực)</p>
                <?php echo $form->passwordField($model,'password', array(
                    'class' => 'form-control',
                    'required'  => true,
                ));?>
                <?php echo $form->error($model,'password');?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group text-center">
                <?php echo CHtml::submitButton(Yii::t('adm/label','authenticate'), array(
                    'class' => 'btn btn-primary',
                ));?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>

<style>
    #modal_confirm_export .modal-dialog{
        width: 400px;
    }
</style>
