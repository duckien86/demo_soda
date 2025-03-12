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
            <div class="col-md-4 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'msisdn'); ?>
                    <?php
                        echo $form->textField($model, 'msisdn', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                    ?>
                    <?php echo $form->error($model, 'msisdn'); ?>
                </div>

            </div>
            <div class="col-md-4 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'action'); ?>
                    <?php echo $form->dropDownList($model, 'action', ASim::model()->getAction(), array(
                            'class' => 'form-control',
                            'empty' => 'Chọn thao tác',
                        )
                    ); ?>
                    <?php echo $form->error($model, 'action'); ?>
                </div>

            </div>
            <div class="col-md-4 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'store_id'); ?>
                    <?php echo $form->dropDownList($model, 'store_id', ASim::model()->getAllStore(), array(
                            'class' => 'form-control',
                            'empty' => 'Chọn thao tác',
                        )
                    ); ?>
                    <?php echo $form->error($model, 'store_id'); ?>
                </div>

            </div>
        </div>
        <div class="col-md-2 col-sm-5" style="margin-top: 20px;">
            <?php echo CHtml::submitButton('Thực hiện', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
