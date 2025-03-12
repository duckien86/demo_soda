<?php
    /* @var $this ASaleOfficesController */
    /* @var $model ASaleOffices */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'asales-offices-form',
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
                    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'code'); ?>
                    <?php echo $form->textField($model, 'code', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'code'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'location_type'); ?>
                    <?php echo $form->textField($model, 'location_type', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'location_type'); ?>
                </div>
            </div>

            <div class="col-md-6">

                <?php echo $form->labelEx($model, 'province_code'); ?>
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
                                    'update' => '#ASaleOffices_district_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                    'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' 
                                    $("#ASaleOffices_district_code").select2("val", "");
                                    $("#ASaleOffices_ward_code").select2("val", "");
                                '
                            ),
                        )
                    );
                    ?>
                    <?php echo $form->error($model, 'province_code'); ?>
                </div>

                <?php echo $form->labelEx($model, 'district_code'); ?>
                <div class="form-group">
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
                                    'update' => '#ASaleOffices_ward_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                    'data'   => array('district_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' 
                                    $("#ASaleOffices_ward_code").select2("val", "");
                                '
                            ),
                        )
                    );
                    ?>
                    <?php echo $form->error($model, 'district_code'); ?>
                </div>

                <?php echo $form->labelEx($model, 'ward_code'); ?>
                <div class="form-group">
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

            </div>


            <div class="buttons">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'update'), array('class' => 'btn btn-success')); ?>
            </div>

        </div>

        <?php $this->endWidget(); ?>
    </div>

</div><!-- form -->