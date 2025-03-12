<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('app', 'Contact Us');
$this->breadcrumbs = array(
    Yii::t('app', 'Contact'),
);
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo Yii::t('app', 'Contact Us') ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php if (Yii::app()->user->hasFlash('contact')): ?>
                    <div class="flash-success">
                        <?php echo Yii::app()->user->getFlash('contact'); ?>
                    </div>

                <?php else: ?>
                    <p>If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.</p>
                    <div class="form">

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'contact-form',
                            'enableClientValidation' => true,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-horizontal form-label-left',
                            ),
                        ));
                        ?>

                        <span class="section">Fields with <span class="required">*</span> are required.</span>

                        <?php echo $form->errorSummary($model); ?>

                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'name', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $form->textField($model, 'name', array('class' => 'form-control col-md-7 col-xs-12')); ?>
                                <ul class="parsley-errors-list">
                                    <li><?php echo $form->error($model, 'name', array('class' => 'parsley-required')); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'email', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $form->textField($model, 'email', array('class' => 'form-control col-md-7 col-xs-12')); ?>
                                <ul class="parsley-errors-list">
                                    <li><?php echo $form->error($model, 'email', array('class' => 'parsley-required')); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'subject', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $form->textField($model, 'subject', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 128)); ?>
                                <ul class="parsley-errors-list">
                                    <li><?php echo $form->error($model, 'subject', array('class' => 'parsley-required')); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'body', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $form->textArea($model, 'body', array('class' => 'resizable_textarea form-control', 'rows' => 6, 'cols' => 50)); ?>
                                <ul class="parsley-errors-list">
                                    <li><?php echo $form->error($model, 'body', array('class' => 'parsley-required')); ?></li>
                                </ul>
                            </div>
                        </div>

                        <?php if (CCaptcha::checkRequirements()): ?>
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'verifyCode', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php $this->widget('CCaptcha'); ?>
                                    <div>Please enter the letters as they are shown in the image above.<br/>Letters are not case-sensitive.</div>
                                    <?php echo $form->textField($model, 'verifyCode', array('class' => 'form-control')); ?>                                    
                                    <ul class="parsley-errors-list">
                                        <li><?php echo $form->error($model, 'verifyCode', array('class' => 'parsley-required')); ?></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-success')); ?>
                            </div>
                        </div>

                        <?php $this->endWidget(); ?>
                    </div><!-- form -->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>