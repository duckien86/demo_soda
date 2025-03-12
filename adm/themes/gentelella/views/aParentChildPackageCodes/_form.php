<?php
    /* @var $this AParentChildPackageCodesController */
    /* @var $model AParentChildPackageCodes */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'aparent-child-package-codes-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'parent_code'); ?>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'parent_code',
                            'data'        => APackage::getPackageCodes(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'package_id'),
                                //reset value selected
                                'class' => 'form-control',
                                'style'    => 'width:100%'
                            ),
                        )
                    );

                ?>
                <?php echo $form->error($model, 'parent_code'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'child_code'); ?>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'child_code',
                            'data'        => APackage::getPackageCodes(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'package_id'),
                                //reset value selected
                                'class' => 'form-control',
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
                <?php echo $form->error($model, 'child_code'); ?>
            </div>

            <div class="form-group buttons">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'update'), array('class' => 'btn btn-success')); ?>
            </div>

        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->