<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $form CActiveForm */

?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="col-md-5 col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'msisdn'); ?>
                    <?php
                        echo $form->textField($model, 'msisdn', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                    ?>
                    <?php echo $form->error($model, 'msisdn'); ?>
                </div>

            </div>
            <div class="col-md-5 col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'package_code'); ?>
                    <?php
                        echo $form->textField($model, 'package_code', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                    ?>
                    <?php echo $form->error($model, 'package_code'); ?>
                </div>

            </div>
            <div class="col-md-2 col-sm-5" style="margin-top: 20px;">
                <?php echo CHtml::submitButton('Mở gói', array('class' => 'btn btn-success')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
