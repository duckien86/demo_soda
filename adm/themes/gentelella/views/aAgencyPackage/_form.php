<?php
    /* @var $this AgencyPackageController */
    /* @var $model AgencyPackage */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'agency-package-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>
<!--    --><?php //echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->labelEx($model,'agency_id'); ?>
                <?php echo $form->dropDownList($model, 'agency_id', User::getListAgency(), array(
                    'class' => 'form-control',
                    'empty' => 'Tất cả'
                ))?>
                <?php echo $form->error($model,'agency_id'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'display_type'); ?>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'display_type',
                            'data'        =>  AAgencyPackage::getDisplayTypeLabels(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'display_type'),
                                //reset value selected
                                'style'    => 'width:100%',
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->controller->createUrl('aAgencyPackage/getPackageByDisplayType'), 
                                    'update' => '#AAgencyPackage_package_code', 
                                    'data'   => array('display_type' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' 
                                    $("#AAgencyPackage_package_code").select2("val", "");
                                '
                            )
                        )
                    );
                ?>
                <?php echo $form->error($model,'display_type'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'package_code'); ?>
                <?php
                    if(!$model->isNewRecord){
                        echo $form->telField($model,'package_code', array('disabled' => true, 'class' => 'form-control'));
                    }else{
                        $package_by_display = CHtml::listData(APackage::getListPackageByDisplayCheckout(Sim::TYPE_PREPAID), 'code', 'name' ) ;
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'package_code',
                                'data'        =>  ($model->display_type == AAgencyPackage::DISPLAY_IN_BUY_SIM) ? $package_by_display  :APackage::getPackageCodes(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => Yii::t('adm/label', 'package_id'),
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    }

                ?>
                <?php echo $form->error($model,'package_code'); ?>
            </div>

            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-success')); ?>
            </div>
        </div>
    </div>



    <?php $this->endWidget(); ?>

</div><!-- form -->