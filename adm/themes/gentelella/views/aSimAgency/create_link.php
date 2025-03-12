<?php
    /* @var $this SimController */
    /* @var $modelOrder AOrders */
    /* @var $model ATokenLinks */
    /* @var $form CActiveForm */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'search_msisdn') => array('index'),
        Yii::t('adm/label', 'create'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'create_token_link') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="form">

            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                   => 'ktv_create_link',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => TRUE,
            )); ?>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'customer_msisdn', array('class' => 'label_text')); ?>
                    <?php echo $form->textField($model, 'customer_msisdn', array('class' => 'textbox', 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'customer_msisdn'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'customer_email', array('class' => 'label_text')); ?>
                    <?php echo $form->textField($model, 'customer_email', array('class' => 'textbox', 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'customer_email'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($modelOrder, 'invitation', array('class' => 'label_text')); ?>
                    <?php echo $form->textField($modelOrder, 'invitation', array('class' => 'textbox', 'maxlength' => 255)); ?>
                    <?php echo $form->error($modelOrder, 'invitation'); ?>
                </div>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-7">
                <div class="form-group" style="margin-top: 32px;">
                    <div class="checkbox-nopad">
                        <label>
                            <?php
                                if ($modelOrder->isNewRecord) {
                                    echo $form->checkBox($modelOrder, 'active_cod', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/label', 'active_cod');
                                } else {
                                    echo $form->checkBox($modelOrder, 'active_cod', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active_cod');
                                }
                            ?>
                            &nbsp;&nbsp;&nbsp;</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group buttons">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'create_link'), array('class' => 'btn btn-success')); ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</div>