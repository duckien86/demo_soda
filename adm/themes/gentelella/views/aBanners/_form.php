<?php
    /* @var $this ABannersController */
    /* @var $model ABanners */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'abanners-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
            'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-label-left')
        )); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="clearfix"></div>
        <div class="col-md-12 no_pad">
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'title', array('class' => 'col-md-3 no_pad')); ?>
                    <?php echo $form->textField($model, 'title', array('class' => 'textbox', 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12 no_pad')); ?>
                    <?php echo $form->dropDownList($model, 'type', $model->getAllBannerType(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                    <?php echo $form->error($model, 'type'); ?>
                </div>
                <!-- Cropping Preview -->
                <div id="crop-banner-desktop" class="crop_preview">
                    <div class="thumbnail_area">
                        <?php $box = $this->beginWidget(
                            'booster.widgets.TbPanel',
                            array(
                                'title'       => 'Image Desktop (Limited 200kb)',
                                'headerIcon'  => 'th-list',
                                'padContent'  => FALSE,
                                'htmlOptions' => array(
                                    'class'       => 'bootstrap-widget-table',
                                    'data-toggle' => 'modal', 'data-target' => '.img_thumbnail'
                                )
                            )
                        ); ?>
                        <div style="padding: 10px;">
                            <div class="avatar-view" title="">
                                <?php
                                    if (!$model->isNewRecord) {
                                        $thumb_url = '../uploads/' . $model->img_desktop;
                                    } else {
                                        if ($model->img_desktop != '') {
                                            $thumb_url = '../uploads/' . $model->img_desktop;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                    };

                                    echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre', 'width' => '40%')) : ''; ?>
                                <?php echo $form->hiddenField($model, 'img_desktop', array('id' => 'thumbnail_hidden')) ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <!-- End Cropping Preview -->
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'sort_order', array('class' => 'col-md-3 no_pad')); ?>
                    <?php echo $form->textField($model, 'sort_order', array('class' => 'textbox')); ?>
                    <?php echo $form->error($model, 'sort_order'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'target_link', array('class' => 'col-md-3 no_pad')); ?>
                    <?php echo $form->textField($model, 'target_link', array('class' => 'textbox', 'maxlength' => 1000)); ?>
                    <?php echo $form->error($model, 'target_link'); ?>
                </div>
                <!-- Cropping Preview -->
                <div id="crop-banner-mobile">
                    <div class="thumbnail_area">
                        <?php $box = $this->beginWidget(
                            'booster.widgets.TbPanel',
                            array(
                                'title'       => 'Image Mobile (Limited 200kb)',
                                'headerIcon'  => 'th-list',
                                'padContent'  => FALSE,
                                'htmlOptions' => array(
                                    'class'       => 'bootstrap-widget-table',
                                    'data-toggle' => 'modal', 'data-target' => '.img_thumb_mobile'
                                )
                            )
                        ); ?>
                        <div style="padding: 10px;">
                            <div class="avatar-view-mobile" title="">
                                <?php
                                    if (!$model->isNewRecord) {
                                        $thumb_url = '../uploads/' . $model->img_mobile;
                                    } else {
                                        if ($model->img_mobile != '') {
                                            $thumb_url = '../uploads/' . $model->img_mobile;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                    };

                                    echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_mobile_pre', 'width' => '40%')) : ''; ?>
                                <?php echo $form->hiddenField($model, 'img_mobile', array('id' => 'thumbnail_mobile_hidden')) ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <!-- End Cropping Preview -->
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="checkbox-nopad" style="margin-top: 23px;">
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
            </div>
        </div>
        <div class="col-md-12">
            <!--CKEditor-->
            <?php
                echo $form->ckEditorGroup(
                    $model,
                    'content_html',
                    array(
                        'wrapperHtmlOptions' => array(
                            'class' => '',
                        ),
                        'widgetOptions'      => array(
                            'editorOptions' => array(
                                'fullpage'        => 'js:true',
                                'width'           => '100%',
                                'resize_maxWidth' => '100%',
                                'resize_minWidth' => '220',
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
        <div class="space_10"></div>
        <div class="col-md-12">
            <div class="form-group">
            <span class="btnintbl mar_left_30">
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
<div class="clearfix">&nbsp;</div>
<!-- thumbnail modal -->
<?php $this->renderPartial('_modal_thumbnail', array('model' => $model)) ?>
<?php $this->renderPartial('_modal_thumbnail_mobile', array('model' => $model)) ?>
<!-- thumbnail modal -->

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>