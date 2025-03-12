<?php
    /* @var $this ANewsController */
    /* @var $model ANews */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'anews-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
        )); ?>

        <div class="col-md-12">
            <?= Yii::t('adm/actions', 'required_field') ?>
        </div>
        <div class="col-md-12">
            <?php echo $form->errorSummary($model); ?>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'title', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'title', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'title'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sort_order', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'sort_order', array('class' => 'textbox')); ?>
                <?php echo $form->error($model, 'sort_order'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'short_des', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'short_des', array('class' => 'textarea', 'maxlength' => 1000)); ?>
                <?php echo $form->error($model, 'short_des'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php echo $form->checkBox($model, 'hot', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'checkbox_in_home_page'); ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            } else {
                                echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            }
                        ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <!--CKEditor-->
            <?php
                echo $form->ckEditorGroup(
                    $model,
                    'full_des',
                    array(
                        'wrapperHtmlOptions' => array(
                            'class' => '',
                        ),
                        'widgetOptions'      => array(
                            'editorOptions' => array(
                                'fullpage'             => 'js:true',
                                'resize_maxWidth'      => '100%',
                                'resize_minWidth'      => '220',
//                                    'filebrowserImageBrowseUrl' => '../vendors/kcfinder/browse.php?type=images',

                                'removePlugins'        => 'elementspath,save,font',
                                'toolbarCanCollapse'   => 'false',
                                'bodyClass'            => 'formWidget',
                                'toolbar'              => array(
                                    array('Source', '-',
                                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-',
                                        'NumberedList', 'BulletedList', '-',
                                        'Outdent', 'Indent', 'Blockquote', '-',
                                        'Link', 'Unlink', '-'),
                                    array('Format', 'Image', 'Flash', 'Table', 'Smiley', 'SpecialChar', '-',
                                        'TextColor', 'BGColor', '-',
                                        'Undo', 'Redo', '-',
                                        'Maximize'),
                                ),
                                'format_p'             => array(
                                    'element'    => 'p',
                                    'attributes' => NULL,
                                ),
                                'ignoreEmptyParagraph' => TRUE,
                                'font_style'           => array(
                                    'element' => NULL,
                                )
                            ),
                            'htmlOptions'   => array('class' => 'formWidget')
                        )
                    )
                );
            ?>
            <!--End CKEditor-->
        </div>
        <div class="col-md-12">
            <div class="form-group buttons">
		<span class="btnintbl">
            <span class="icondk">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
            </span>
        </span>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div>
    <!-- form -->
