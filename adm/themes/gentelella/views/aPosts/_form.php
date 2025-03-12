<?php
    /* @var $this APostsController */
    /* @var $model APosts */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'aposts-form',
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
        <div class="col-md-5">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'title', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'title', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'title'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'post_category_id', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'post_category_id', $model->getAllPostCate(), array('class' => 'textbox',)); ?>
                <?php echo $form->error($model, 'post_category_id'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sort_order', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'sort_order', array('class' => 'textbox')); ?>
                <?php echo $form->error($model, 'sort_order'); ?>
            </div>
            <div class="thumbnail_area">
                <?php $box = $this->beginWidget(
                    'booster.widgets.TbPanel',
                    array(
                        'title'       => 'Thumbnail',
                        'headerIcon'  => 'th-list',
                        'padContent'  => FALSE,
                        'htmlOptions' => array('class' => 'bootstrap-widget-table', 'data-toggle' => 'modal', 'data-target' => '.img_thumbnail')
                    )
                ); ?>
                <div style="padding: 10px;">
                    <div class="avatar-view" title="">
                        <?php
                            $DS = DIRECTORY_SEPARATOR;
                            if (!$model->isNewRecord) {
                                $thumb_url = '..' . $DS . $model->image;
                            } else {
                                if ($model->image != '') {
                                    $thumb_url = '..' . $DS . $model->image;
                                } else {
                                    $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                }
                            };

                            echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre', 'width' => '40%')) : ''; ?>
                        <?php echo $form->hiddenField($model, 'image', array('id' => 'thumbnail_hidden')) ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>

        <div class="col-md-4">

            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . 'Hiện trên trang chủ';
                            } else {
                                echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . 'Kích hoạt';
                            }
                        ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group buttons">
                <?php echo $form->labelEx($model, 'content', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'content', array('class' => 'textarea', 'rows' => 10, 'style' => 'height:inherit;')); ?>
                <?php echo $form->error($model, 'content'); ?>
            </div>
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

<!-- thumbnail modal -->
<?php $this->renderPartial('_modal_thumbnail', array('model' => $model)) ?>
<!-- End thumbnail modal -->

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>