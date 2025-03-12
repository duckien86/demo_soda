<?php
    /* @var $this APackageController */
    /* @var $model APackage */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'apackage-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
            'htmlOptions'          => array('enctype' => 'multipart/form-data')
        )); ?>

        <div class="col-md-12">
            <?= Yii::t('adm/actions', 'required_field') ?>
        </div>
        <div class="col-md-12">
            <?php echo $form->errorSummary($model); ?>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'code', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'code', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'code'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'vip_user', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'vip_user', array(APackage::VIP_USER => Yii::t('adm/label', 'vip_user')), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'vip_user'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'short_description', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'short_description', array('class' => 'textarea', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'short_description'); ?>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'price', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'price', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                <?php echo $form->error($model, 'price'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'price_discount', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'price_discount', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                <?php echo $form->error($model, 'price_discount'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'type', $model->getAllPackageType(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'extra_params', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'extra_params', array('class' => 'textarea', 'maxlength' => 500)); ?>
                <?php echo $form->error($model, 'extra_params'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'period', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'period', APackage::getArrayPackagePeriod(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'period'); ?>
            </div>
            <div class="form-group" style="margin-top: 32px;">
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

        </div>
        <div class="col-md-12 no_pad">
            <div class="col-md-4">
                <!-- Cropping Preview -->
                <div id="crop-thumbnail1" class="crop_preview">
                    <div class="thumbnail_area">
                        <?php $box = $this->beginWidget(
                            'booster.widgets.TbPanel',
                            array(
                                'title'       => 'Thumbnail home page',
                                'headerIcon'  => 'th-list',
                                'padContent'  => FALSE,
                                'htmlOptions' => array(
                                    'class'       => 'bootstrap-widget-table',
                                    'data-toggle' => 'modal', 'data-target' => '.img_thumbnail1'
                                )
                            )
                        ); ?>
                        <div style="padding: 10px;">
                            <div class="avatar-view" title="">
                                <?php
                                    if (!$model->isNewRecord) {
                                        $thumb_url = '../uploads/' . $model->thumbnail_1;
                                    } else {
                                        if ($model->thumbnail_1 != '') {
                                            $thumb_url = '../uploads/' . $model->thumbnail_1;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                    };

                                    echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre1', 'width' => '40%')) : ''; ?>
                                <?php echo $form->hiddenField($model, 'thumbnail_1', array('id' => 'thumbnail_hidden1')) ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <!-- End Cropping Preview -->
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="col-md-4">
                <!-- Cropping Preview -->
                <div id="crop-thumbnail2" class="crop_preview">
                    <div class="thumbnail_area">
                        <?php $box = $this->beginWidget(
                            'booster.widgets.TbPanel',
                            array(
                                'title'       => 'Thumbnail 2',
                                'headerIcon'  => 'th-list',
                                'padContent'  => FALSE,
                                'htmlOptions' => array(
                                    'class'       => 'bootstrap-widget-table',
                                    'data-toggle' => 'modal', 'data-target' => '.img_thumbnail2'
                                )
                            )
                        ); ?>
                        <div style="padding: 10px;">
                            <div class="avatar-view" title="">
                                <?php
                                    if (!$model->isNewRecord) {
                                        $thumb_url = '../uploads/' . $model->thumbnail_2;
                                    } else {
                                        if ($model->thumbnail_2 != '') {
                                            $thumb_url = '../uploads/' . $model->thumbnail_2;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                    };

                                    echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre2', 'width' => '40%')) : ''; ?>
                                <?php echo $form->hiddenField($model, 'thumbnail_2', array('id' => 'thumbnail_hidden2')) ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <!-- End Cropping Preview -->
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="col-md-4">
                <!-- Cropping Preview -->
                <div id="crop-thumbnail3" class="crop_preview">
                    <div class="thumbnail_area">
                        <?php $box = $this->beginWidget(
                            'booster.widgets.TbPanel',
                            array(
                                'title'       => 'Thumbnail 3',
                                'headerIcon'  => 'th-list',
                                'padContent'  => FALSE,
                                'htmlOptions' => array(
                                    'class'       => 'bootstrap-widget-table',
                                    'data-toggle' => 'modal', 'data-target' => '.img_thumbnail3'
                                )
                            )
                        ); ?>
                        <div style="padding: 10px;">
                            <div class="avatar-view" title="">
                                <?php
                                    if (!$model->isNewRecord) {
                                        $thumb_url = '../uploads/' . $model->thumbnail_3;
                                    } else {
                                        if ($model->thumbnail_3 != '') {
                                            $thumb_url = '../uploads/' . $model->thumbnail_3;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                    };

                                    echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre3', 'width' => '40%')) : ''; ?>
                                <?php echo $form->hiddenField($model, 'thumbnail_3', array('id' => 'thumbnail_hidden3')) ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <!-- End Cropping Preview -->
                <div class="clearfix">&nbsp;</div>
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
<div class="clearfix">&nbsp;</div>
<?php $this->renderPartial('_modal_thumbnail1', array('model' => $model)) ?>
<?php $this->renderPartial('_modal_thumbnail2', array('model' => $model)) ?>
<?php $this->renderPartial('_modal_thumbnail3', array('model' => $model)) ?>
<!-- thumbnail modal -->

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>
<script>
    function formatNumber(obj) {
        var num = $(obj).val();
        num = num.toString().replace(/\$|\./g, '');

        if (isNaN(num))
            num = "";
        sign = (num == (num = Math.abs(num)));
        num = Math.floor(num * 100 + 0.50000000001);
        num = Math.floor(num / 100).toString();

        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
            num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
        $(obj).val(num);
    }
</script>