<?php
    if ($order_id): ?>
        <div class="x_panel">
            <br class="x_title">
            <h4 style="color:red;"><?php echo isset($msg) ? $msg : '' ?></h4>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id'                   => 'upload-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => TRUE,
                'htmlOptions'          => array('enctype' => 'multipart/form-data'),
            )); ?>

            <div class="row">
                <div class="col-md-6 col-xs-12">

                    <div id="crop-thumbnail1" class="crop_preview">
                        <div class="thumbnail_area">
                            <?php $box = $this->beginWidget(
                                'booster.widgets.TbPanel',
                                array(
                                    'title'       => 'Ảnh chân dung',
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

                                        if ($upload_form->photo_face_url != '') {
                                            $thumb_url = '../uploads/' . $upload_form->photo_face_url;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';

                                        }
                                        echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre1', 'width' => '40%')) : ''; ?>
                                    <?php echo $form->hiddenField($upload_form, 'photo_face_url', array('id' => 'thumbnail_hidden1')) ?>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                    <div id="crop-thumbnail2" class="crop_preview">
                        <div class="thumbnail_area">
                            <?php $box = $this->beginWidget(
                                'booster.widgets.TbPanel',
                                array(
                                    'title'       => 'Ảnh mặt trước',
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
                                        if ($upload_form->photo_personal1_url != '') {
                                            $thumb_url = '../uploads/' . $upload_form->photo_personal1_url;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                        echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre2', 'width' => '40%')) : ''; ?>
                                    <?php echo $form->hiddenField($upload_form, 'photo_personal1_url', array('id' => 'thumbnail_hidden2')) ?>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div id="crop-thumbnail3" class="crop_preview">
                        <div class="thumbnail_area">
                            <?php $box = $this->beginWidget(
                                'booster.widgets.TbPanel',
                                array(
                                    'title'       => 'Ảnh mặt sau',
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
                                        if ($upload_form->photo_personal2_url != '') {
                                            $thumb_url = '../uploads/' . $upload_form->photo_personal2_url;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                        echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre3', 'width' => '40%')) : ''; ?>
                                    <?php echo $form->hiddenField($upload_form, 'photo_personal2_url', array('id' => 'thumbnail_hidden3')) ?>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                    <div id="crop-thumbnail4" class="crop_preview">
                        <div class="thumbnail_area">
                            <?php $box = $this->beginWidget(
                                'booster.widgets.TbPanel',
                                array(
                                    'title'       => 'Ảnh chụp phiếu đăng ký/hợp đồng',
                                    'headerIcon'  => 'th-list',
                                    'padContent'  => FALSE,
                                    'htmlOptions' => array(
                                        'class'       => 'bootstrap-widget-table',
                                        'data-toggle' => 'modal', 'data-target' => '.img_thumbnail4'
                                    )
                                )
                            ); ?>
                            <div style="padding: 10px;">
                                <div class="avatar-view" title="">
                                    <?php
                                        if ($upload_form->photo_order_board_url != '') {
                                            $thumb_url = '../uploads/' . $upload_form->photo_order_board_url;
                                        } else {
                                            $thumb_url = Yii::app()->theme->baseUrl . '/images/upload-icon.jpg';
                                        }
                                        echo $thumb_url != '' ? CHtml::image($thumb_url, '', array('id' => 'thumbnail_pre4', 'width' => '40%')) : ''; ?>
                                    <?php echo $form->hiddenField($upload_form, 'photo_order_board_url', array('id' => 'thumbnail_hidden4')) ?>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row buttons" style="margin-left: 0px;">
                <?php echo CHtml::submitButton('Đăng ký', array('class' => 'btn btn-success')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        </div>.

    <?php endif; ?>
<div class="clearfix">&nbsp;</div>
<?php $this->renderPartial('_modal_thumbnail1', array('model' => $upload_form)) ?>
<?php $this->renderPartial('_modal_thumbnail2', array('model' => $upload_form)) ?>
<?php $this->renderPartial('_modal_thumbnail3', array('model' => $upload_form)) ?>
<?php $this->renderPartial('_modal_thumbnail4', array('model' => $upload_form)) ?>
<!-- thumbnail modal -->

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>
<style>
    .alert {
        margin-top: 60px;
    }
</style>

