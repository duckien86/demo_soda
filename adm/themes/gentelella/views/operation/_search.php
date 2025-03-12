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
                    <?php echo $form->labelEx($model, 'id'); ?>
                    <?php
                        echo $form->textField($model, 'id', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                    ?>
                    <?php echo $form->error($model, 'id'); ?>
                </div>
            </div>
            <div class="col-md-2 col-sm-5" style="margin-top: 20px;">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>


