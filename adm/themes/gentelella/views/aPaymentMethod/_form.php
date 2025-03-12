<?php
    /* @var $this APaymentMethodController */
    /* @var $model APaymentMethod */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'apayment-method-form',
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
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 50)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'config_param', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'config_param', array('disabled' => 'disabled', 'class' => 'textarea', 'maxlength' => 2000)); ?>
                <?php echo $form->error($model, 'config_param'); ?>
            </div>
        </div>
        <div class="col-md-6">
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
                        &nbsp;&nbsp;&nbsp;</label>
                </div>
            </div>
            <div class="thumbnail_area">
                <?php $box = $this->beginWidget(
                    'booster.widgets.TbPanel',
                    array(
                        'title'       => 'Logo',
                        'headerIcon'  => 'th-list',
                        'padContent'  => FALSE,
                        'htmlOptions' => array('class' => 'bootstrap-widget-table', 'data-toggle' => 'modal', 'data-target' => '.img_thumbnail')
                    )
                ); ?>
                <div style="padding: 10px;">
                    <div class="avatar-view" title="">
                        <?php
                            if (!$model->isNewRecord) {
                                $thumb_url = '../uploads/' . $model->logo;
                            } else {
                                if ($model->logo != '') {
                                    $thumb_url = '../uploads/' . $model->logo;
                                } else {
                                    $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                }
                            };

                            echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre', 'width' => '40%')) : ''; ?>
                        <?php echo $form->hiddenField($model, 'logo', array('id' => 'thumbnail_hidden')) ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="col-md-12">
            <!--CKEditor-->
            <?php
                echo $form->ckEditorGroup(
                    $model,
                    'description',
                    array(
                        'wrapperHtmlOptions' => array(
                            'class' => '',
                        ),
                        'widgetOptions'      => array(
                            'editorOptions' => array(
                                'fullpage'             => 'js:true',
                                'width'                => '100%',
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
</div>

<!-- thumbnail modal -->
<?php $this->renderPartial('_modal_thumbnail', array('model' => $model)) ?>
<!-- thumbnail modal -->

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>