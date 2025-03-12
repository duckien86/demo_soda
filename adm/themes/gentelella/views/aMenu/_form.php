<?php
    /* @var $this AMenuController */
    /* @var $model AMenu */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'amenu-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'action'               => ($model->isNewRecord) ? array('create') : array('update', 'id' => $model->id),
        'enableAjaxValidation' => FALSE,
        'htmlOptions'          => array(
            'enctype' => 'multipart/form-data',
            'class'   => 'form-horizontal form-label-left avatar-form',
        ),
    )); ?>

    <?= Yii::t('adm/actions', 'required_field'); ?>

    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model, 'parent_id') ?>

    <div class="col-md-12">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'icon'); ?>
                <?php echo $form->fileField($model, 'icon'); ?>
                <?php echo $form->error($model, 'icon'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'size' => 40, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'target_link'); ?>
                <?php echo $form->textField($model, 'target_link', array('class' => 'textbox', 'size' => 40, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'target_link'); ?>
            </div>
            <?php
                if ($model->isNewRecord):
                    ?>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'parent_id'); ?>
                        <?php
                            echo $form->dropDownList($model, 'parent_id', $model->getListParentId(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist'));
                        ?>
                    </div>
                    <?php
                endif;
            ?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="checkbox-nopad" style="margin-top: 18px;">
                    <label>
                        <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            } else {
                                echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            }
                        ?>
                        &nbsp;&nbsp;&nbsp;</label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'positions'); ?>
                <?php echo $form->dropDownList($model, 'positions', $model->getListPositions(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sort_order'); ?>
                <?php echo $form->textField($model, 'sort_order', array('class' => 'textbox', 'size' => 40, 'maxlength' => 3)); ?>
                <?php echo $form->error($model, 'sort_order'); ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <span class="btnintbl pull-right">
            <span class="icondk"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'),array('class'=>'btn btn-success')); ?></span>
        </span>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->