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
                <?php echo $form->labelEx($model, 'categories_id', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'categories_id', ANewsCategories::getAllCategories(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'categories_id'); ?>
            </div>
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
                <?php echo $form->labelEx($model, 'hot', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'hot', ANews::getListPosition(), array('class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'hot'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'last_update', array('class' => 'col-md-12 no_pad'))?>
                <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'          => $model,
                    'attribute'      => 'last_update',
                    'language'       => 'vi',
                    'htmlOptions'    => array(
                        'style' => 'width:150px; border-radius: 0',
                        'size'  => '10',
                    ),
                    'defaultOptions' => array(
                        'showOn'            => 'focus',
                        'dateFormat'        => 'dd/mm/yy',
                        'showOtherMonths'   => TRUE,
                        'selectOtherMonths' => TRUE,
                        'changeMonth'       => TRUE,
                        'changeYear'        => TRUE,
                        'showButtonPanel'   => TRUE,
                    )
                ), TRUE);?>
                <?php echo $form->error($model, 'last_update'); ?>
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
            <div class="thumbnail_area">
                <?php $box = $this->beginWidget(
                    'booster.widgets.TbPanel',
                    array(
                        'title'       => 'Thumbnail (Giới hạn 50kb)',
                        'headerIcon'  => 'th-list',
                        'padContent'  => FALSE,
                        'htmlOptions' => array('class' => 'bootstrap-widget-table', 'data-toggle' => 'modal', 'data-target' => '.img_thumbnail')
                    )
                ); ?>
                <div style="padding: 10px;">
                    <div class="avatar-view" title="">
                        <?php
                            if (!$model->isNewRecord) {
                                $thumb_url = '../uploads/' . $model->thumbnail;
                            } else {
                                if ($model->thumbnail != '') {
                                    $thumb_url = '../uploads/' . $model->thumbnail;
                                } else {
                                    $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                }
                            };

                            echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre', 'width' => '40%')) : ''; ?>
                        <?php echo $form->hiddenField($model, 'thumbnail', array('id' => 'thumbnail_hidden')) ?>
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
                    'full_des',
                    array(
                        'wrapperHtmlOptions' => array(
                            'class' => '',
                        ),
                        'widgetOptions'      => array(
                            'editorOptions' => array(
                                'fullpage'        => 'js:true',
                                'resize_maxWidth' => '100%',
                                'resize_minWidth' => '220',
                                'filebrowserImageBrowseUrl' => '../vendors/kcfinder/browse.php?type=images',

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
                                ),
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
<!-- End thumbnail modal -->

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>