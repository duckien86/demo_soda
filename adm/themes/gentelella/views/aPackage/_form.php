<?php
/* @var $this APackageController */
/* @var $model APackage */
/* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'apackage-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
            'htmlOptions' => array('enctype' => 'multipart/form-data')
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
                <?php echo $form->labelEx($model, 'slug', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'slug', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'slug'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'code_vnpt', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'code_vnpt', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'code_vnpt'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'extra_params', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'extra_params', array('class' => 'textbox', 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'extra_params'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'type', $model->getAllPackageType(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist', 'onchange' => 'getType();')); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
            <div id="priceNormal">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'price', array('class' => 'col-md-12 no_pad')); ?>
                    <?php echo $form->textField($model, 'price', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                    <?php echo $form->error($model, 'price'); ?>
                </div>
            </div>
            <div class="row" id="priceForMyTV">
                <div class="col-md-6 rm-group">
                    <?php echo $form->labelEx($model, 'price_stb', array('class' => 'col-md-6 no_pad')); ?>
                    <?php echo $form->textField($model, 'price_stb', array('class' => 'textbox', 'maxlength' => 10)); ?>
                    <?php echo $form->error($model, 'price_stb'); ?>
                </div>
                <div class="col-md-6 form-group">
                    <?php echo $form->labelEx($model, 'price_no_stb', array('class' => 'col-md-6 no_pad')); ?>
                    <?php echo $form->textField($model, 'price_no_stb', array('class' => 'textbox', 'maxlength' => 10)); ?>
                    <?php echo $form->error($model, 'price_no_stb'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'price_discount', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="price_discount_addon">
                        <a href="javascript:void(0)" class="free-a"
                           id="price_discount" onclick="free(this.id);">Free</a>
                    </span>
                    <?php echo $form->textField($model, 'price_discount', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                    <input type="hidden" value="" name="APackage[free_price_discount]"
                           id="APackage_free_price_discount">
                </div>

                <?php echo $form->error($model, 'price_discount'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'freedoo', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'freedoo', APackage::model()->getTypeLocationPakage(), array(
                        'class' => 'form-control',
                    )
                ); ?>
                <?php echo $form->error($model, 'display_type'); ?>
            </div>
            <div class="col-md-4 no_pad_left">
                <div class="form-group" style="margin-top: 31px;">
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
            <div class="col-md-4 ">
                <div class="form-group" style="margin-top: 31px;">
                    <div class="checkbox-nopad">
                        <label>
                            <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'hot', array('checked' => 'checked', 'class' => 'flat')) . ' ' . 'Hot';
                            } else {
                                echo $form->checkBox($model, 'hot', array('class' => 'flat')) . ' ' . 'Hot';
                            }
                            ?>
                            &nbsp;&nbsp;&nbsp;</label>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'period', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'period', APackage::getArrayPackagePeriod(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'period'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'vip_user', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'vip_user', array(APackage::VIP_USER => Yii::t('adm/label', 'vip_user')), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'vip_user'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sort_index', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'sort_index', array('class' => 'textbox', 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'sort_index'); ?>
            </div>
            <div class="col-md-6 no_pad_left">
                <div class="form-group">
                    <label class="col-md-12 no_pad" for="APackage_min_age">Tuổi áp dụng từ</label>
                    <?php echo $form->textField($model, 'min_age', array('class' => 'textbox', 'maxlength' => 10, 'placeholder' => 'Tuổi bé nhất', 'onkeyup' => 'formatNumber(this);')); ?>
                    <?php echo $form->error($model, 'min_age'); ?>
                </div>
            </div>
            <div class="col-md-6 no_pad_right">
                <div class="form-group">
                    <?php echo $form->textField($model, 'max_age', array('class' => 'textbox', 'maxlength' => 10, 'style' => 'margin-top:22px;', 'placeholder' => 'Tuổi lớn nhất', 'onkeyup' => 'formatNumber(this);')); ?>
                    <?php echo $form->error($model, 'max_age'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'stock_id', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'stock_id', ASim::model()->getAllStore(), array(
                        'class' => 'form-control',
                        'empty' => 'Chọn kho',
                    )
                ); ?>
                <?php echo $form->error($model, 'display_type'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'display_type', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'display_type', APackage::model()->getDisplayType(), array(
                        'class' => 'form-control',
                    )
                ); ?>
                <?php echo $form->error($model, 'stock_id'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'short_description', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'short_description', array('class' => 'textarea', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'short_description'); ?>
            </div>
        </div>
        <div class="col-md-4">

            <div class="form-group">
                <?php echo $form->labelEx($model, 'sms_external', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="sms_external_addon">
                        <a href="javascript:void(0)" class="free-a"
                           id="sms_external" onclick="free(this.id);">Free</a>
                    </span>
                    <?php echo $form->textField($model, 'sms_external', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                    <input type="hidden" value="" name="APackage[free_sms_external]" id="APackage_free_sms_external">
                </div>

                <?php echo $form->error($model, 'sms_external'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sms_internal', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="sms_internal_addon">
                        <a href="javascript:void(0)"
                           id="sms_internal" class="free-a" onclick="free(this.id);">Free</a>
                    </span>
                    <?php echo $form->textField($model, 'sms_internal', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                    <input type="hidden" value="" name="APackage[free_sms_internal]" id="APackage_free_sms_internal">
                </div>

                <?php echo $form->error($model, 'sms_internal'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'call_external', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="call_external_addon">
                        <a href="javascript:void(0)"
                           id="call_external" class="free-a" onclick="free(this.id);">Free</a>
                    </span>
                    <?php echo $form->textField($model, 'call_external', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                    <input type="hidden" value="" name="APackage[free_call_external]" id="APackage_free_call_external">
                </div>

                <?php echo $form->error($model, 'call_external'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'call_internal', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="call_internal_addon">
                        <a href="javascript:void(0)" class="free-a"
                           id="call_internal" onclick="free(this.id);">Free</a>
                    </span>
                    <?php echo $form->textField($model, 'call_internal', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
                    <input type="hidden" value="" name="APackage[free_call_internal]" id="APackage_free_call_internal">
                </div>

                <?php echo $form->error($model, 'call_internal'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'data', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="data_addon">
                        <a href="javascript:void(0)" class="free-a"
                           id="data" onclick="free(this.id);">Free</a>
                    </span>
                    <?php echo $form->textField($model, 'data', array('class' => 'textbox', 'maxlength' => 10)); ?>
                    <input type="hidden" value="" name="APackage[free_data]" id="APackage_free_data">
                </div>

                <?php echo $form->error($model, 'data'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'cp_id', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'cp_id', APackage::model()->getCps(), array(
                        'class' => 'form-control',
                        'empty' => 'Chọn đối tác',
                    )
                ); ?>
                <?php echo $form->error($model, 'display_type'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'highlight', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'highlight', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'highlight'); ?>
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
                                'title' => 'Thumbnail home page',
                                'headerIcon' => 'th-list',
                                'padContent' => FALSE,
                                'htmlOptions' => array(
                                    'class' => 'bootstrap-widget-table',
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
                                'title' => 'Thumbnail 2',
                                'headerIcon' => 'th-list',
                                'padContent' => FALSE,
                                'htmlOptions' => array(
                                    'class' => 'bootstrap-widget-table',
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
                                'title' => 'Thumbnail 3',
                                'headerIcon' => 'th-list',
                                'padContent' => FALSE,
                                'htmlOptions' => array(
                                    'class' => 'bootstrap-widget-table',
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
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'province_code', array('class' => 'col-md-12 no_pad')); ?>
                <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model' => $model,
                        'attribute' => 'province_code',
                        'data' => AProvince::model()->getAllProvince(),
                        'htmlOptions' => array(
                            'multiple' => TRUE,
                            'prompt' => 'Chọn tất cả',
                            //reset value selected
                            'style' => 'width:100%'
                        ),
                    )
                );
                ?>
                <?php echo $form->error($model, 'province_code'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'display_in_checkout', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'display_in_checkout', APackage::model()->getDisplayInCheckout(), array(
                        'class' => 'form-control',
                    )
                ); ?>
                <?php echo $form->error($model, 'display_in_checkout'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'delivery_location_in_checkout', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'delivery_location_in_checkout', APackage::model()->getDeliveryLocationInCheckout(), array(
                        'class' => 'form-control',
                    )
                ); ?>
                <?php echo $form->error($model, 'delivery_location_in_checkout'); ?>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="form-group" style="margin-top: 31px;">
                <div class="checkbox-nopad">
                    <label>
                        <?php
                        echo $form->checkBox($model, 'package_local', array('class' => 'flat')) . ' ' . 'Cục bộ (Gói Fiber)';
                        ?>
                        &nbsp;&nbsp;&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox-nopad">
                    <?php echo $form->labelEx($model, 'parent_code', array('class' => 'col-md-12 no_pad')); ?>
                    <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model' => $model,
                            'attribute' => 'parent_code',
                            'data' => APackage::getPackageCodes(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt' => Yii::t('adm/label', 'parent_code'),
//                                    'style'  => 'margin-top: 20px'
                            ),
                        )
                    );

                    ?>

                    <?php echo $form->error($model, 'parent_code'); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="TypeTV">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type_tv', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'type_tv', APackage::model()->getTypeTV(), array(
                        'class' => 'form-control',
                    )
                ); ?>
                <?php echo $form->error($model, 'type_tv'); ?>
            </div>
        </div>
        <div class="col-md-4" id="">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'commercial', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'commercial', array('class' => 'textarea', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'commercial'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'service_type', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'service_type', APackage::model()->getServiceType(), array(
                        'class' => 'form-control',
                    )
                ); ?>
                <?php echo $form->error($model, 'service_type'); ?>
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
                    'widgetOptions' => array(
                        'editorOptions' => array(
                            'fullpage' => 'js:true',
                            'width' => '100%',
                            'resize_maxWidth' => '100%',
                            'resize_minWidth' => '220',
//                                    'filebrowserImageBrowseUrl' => '../vendors/kcfinder/browse.php?type=images',

                            'removePlugins' => 'elementspath,save,font',
                            'toolbarCanCollapse' => 'false',
                            'bodyClass' => 'formWidget',
                            'toolbar' => array(
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
                            'format_p' => array(
                                'element' => 'p',
                                'attributes' => NULL,
                            ),
                            'ignoreEmptyParagraph' => TRUE,
                            'font_style' => array(
                                'element' => NULL,
                            )
                        ),
                        'htmlOptions' => array('class' => 'formWidget')
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
    <?php
    if ($model->sms_external == -1){?>
    $('#APackage_free_sms_external').val(-1);
    $('#APackage_sms_external').val("");
    $('#APackage_sms_external').prop("readonly", true);
    $('#APackage_sms_external').css("background-color", "#E8E8E8");
    $('#sms_external_addon').css("background-color", "#39afe4");
    $('#sms_external').css("color", "white");
    <?php
    }
    ?>

    <?php
    if ($model->sms_internal == -1){?>
    $('#APackage_free_sms_internal').val(-1);
    $('#APackage_sms_internal').val("");
    $('#APackage_sms_internal').prop("readonly", true);
    $('#APackage_sms_internal').css("background-color", "#E8E8E8");
    $('#sms_internal_addon').css("background-color", "#39afe4");
    $('#sms_internal').css("color", "white");
    <?php
    }
    ?>
    <?php
    if ($model->price_discount == -1){?>
    $('#APackage_free_price_discount').val(-1);
    $('#APackage_price_discount').val("");
    $('#APackage_price_discount').prop("readonly", true);
    $('#APackage_price_discount').css("background-color", "#E8E8E8");
    $('#price_discount_addon').css("background-color", "#39afe4");
    $('#price_discount').css("color", "white");
    <?php
    }
    ?>
    <?php
    if ($model->call_external == -1){?>
    $('#APackage_free_call_external').val(-1);
    $('#APackage_call_external').val("");
    $('#APackage_call_external').prop("readonly", true);
    $('#APackage_call_external').css("background-color", "#E8E8E8");
    $('#call_external_addon').css("background-color", "#39afe4");
    $('#call_external').css("color", "white");
    <?php
    }
    ?>

    <?php
    if ($model->call_internal == -1){?>
    $('#APackage_free_call_internal').val(-1);
    $('#APackage_call_internal').val("");
    $('#APackage_call_internal').prop("readonly", true);
    $('#APackage_call_internal').css("background-color", "#E8E8E8");
    $('#call_internal_addon').css("background-color", "#39afe4");
    $('#call_internal').css("color", "white");
    <?php
    }
    ?>

    <?php
    if ($model->data == -1){?>
    $('#APackage_free_data').val(-1);
    $('#APackage_data').val("");
    $('#APackage_data').prop("readonly", true);
    $('#APackage_data').css("background-color", "#E8E8E8");
    $('#data_addon').css("background-color", "#39afe4");
    $('#data').css("color", "white");
    <?php
    }
    ?>
    function free(id) {
        var element_id = 'APackage_' + id;
        var attr = $(this).attr('readonly');
        var hidden_id = 'APackage_free_' + id;
        if (!$('#' + element_id).is('[readonly]')) {
            $('#' + element_id).prop("readonly", true);
            $('#' + element_id).val("");
            $('#' + element_id).css("background-color", "#E8E8E8");
            $('#' + hidden_id).val(-1);
            $('#' + id + '_addon').css("background-color", "#39afe4");
            $('#' + id).css("color", "white");
        } else {
            $('#' + element_id).prop("readonly", false);
            $('#' + element_id).css("background-color", "white");
            $('#' + id + '_addon').css("background-color", "white");
            $('#' + hidden_id).val("");
            $('#' + id).css("color", "black");
        }

    }

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

    $(document).ready(function () {
        $("#APackage_code").on('keyup', function () {
            var val = $(this).val();
            $(this).val(val.toUpperCase())
        });

        $("#APackage_name").on('keyup', function () {
            var slug = unsigned_string($(this).val());
            $('#APackage_slug').val(slug);
        });
        $("#APackage_slug").on('blur', function () {
            var slug = unsigned_string($(this).val());
            $('#APackage_slug').val(slug);
        });
    })

    function getType() {
        var values = document.getElementById("APackage_type").value
        if (values == 14) {
            document.getElementById("TypeTV").style.display = "block";
            document.getElementById("priceForMyTV").style.display = "block";
            document.getElementById("priceNormal").style.display = "none";
        }
    }

    window.onload = function () {
        var values = document.getElementById("APackage_type").value
        if (values == 14) {
            document.getElementById("TypeTV").style.display = "block";
            document.getElementById("priceNormal").style.display = "none";
            document.getElementById("priceForMyTV").style.display = "block";
        }
    }

</script>
<style>
    #TypeTV {
        display: none;
    }

    .free-a:hover {
        text-decoration: none;
    }

    .free-a:active {
        text-decoration: none;
    }

    .free-a:focus {
        text-decoration: none;
    }

    .free-a {
        color: black;
    }

    #apackage-form .input-group-addon {
        background-color: white;
    }

    .no_pad_left {
        padding-left: 0px;
    }

    .no_pad_right {
        padding-right: 0px;
    }

    .no_pad_top {
        padding-top: 0px;
    }

    .no_pad_bottom {
        padding-bottom: 0px;
    }
    #priceForMyTV{
        display: none;
    }
</style>