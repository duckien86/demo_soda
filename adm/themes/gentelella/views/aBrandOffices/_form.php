<?php
    /* @var $this ABrandOfficesController */
    /* @var $model ABrandOffices */
    /* @var $form CActiveForm */
    /* @var $province */
    /* @var $district */
    /* @var $ward */
?>
<div class="container-fluid">
    <div class="form">

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'abrand-offices-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
        )); ?>

        <?php echo $form->errorSummary($model); ?>

        <?= Yii::t('adm/actions', 'required_field') ?>

        <div class="space_10"></div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name'); ?>
                    <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'hotline'); ?>
                    <?php echo $form->textField($model, 'hotline', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'hotline'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'address'); ?>
                    <?php echo $form->textField($model, 'address', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'address'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'head_office'); ?>
                    <?php echo $form->textField($model, 'head_office', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'head_office'); ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'province_code',
                            'data'        => AProvince::model()->getAllProvince(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'province_code'),
                                'style'    => 'width:200px; margin-right: 15px;',
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->controller->createUrl('aBrandOffices/getDistrictByProvice'), //or $this->createUrl('loadcities') if '$this' extends CController
                                    'update' => '#ABrandOffices_district_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                    'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' 
                                    $("#ABrandOffices_district_code").select2("val", "");
                                    $("#ABrandOffices_ward_code").select2("val", "");
                                '
                            ),
                        )
                    );
                    ?>
                    <?php echo $form->error($model, 'province_code'); ?>
                    <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'district_code',
                            'data'        => (!empty($model->province_code)) ? ADistrict::getListDistrictByProvince($model->province_code) : array(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'district_code'),
                                'style'    => 'width:200px; margin-right: 15px;',
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->controller->createUrl('aBrandOffices/getWardByDistrict'), //or $this->createUrl('loadcities') if '$this' extends CController
                                    'update' => '#ABrandOffices_ward_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                    'data'   => array('district_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' 
                                    $("#ABrandOffices_ward_code").select2("val", "");
                                '
                            ),
                        )
                    );
                    ?>
                    <?php echo $form->error($model, 'district_code'); ?>
                    <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'ward_code',
                            'data'        => (!empty($model->district_code)) ? AWard::getListWardDistrict($model->district_code) : array(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'ward_code'),
                                'style'    => 'width:200px; margin-right: 15px;',
                            ),
                        )
                    );
                    ?>
                    <?php echo $form->error($model, 'ward_code'); ?>

                </div>

                <?php echo $form->labelEx($model, 'agency_id'); ?>
                <div class="form-group">
                    <?php echo $form->dropDownList($model,'agency_id', Agency::getListOption(), array(
                        'class' => 'form-control',
                        'style' => 'width:200px',
                        'empty' => 'Chọn ĐTBL'
                    ))?>
                    <?php echo $form->error($model, 'agency_id'); ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'descriptions', array('class' => 'col-md-12 no_pad')); ?>
                    <?php echo $form->textArea($model, 'descriptions', array('class' => 'textarea', 'maxlength' => 1000)); ?>
                    <?php echo $form->error($model, 'descriptions'); ?>
                </div>
            </div>

            <div class="col-md-12">
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
            </div>

            <div class="form-group buttons" style="margin-left: 10px;">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
            </div>

        </div>



        <?php $this->endWidget(); ?>

    </div>
    <!-- form -->
</div>