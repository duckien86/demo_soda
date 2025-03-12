<?php if ($model->scenario === 'update'): ?>
    <span class="section"><?php echo Rights::getAuthItemTypeName($model->type); ?></span>
<?php endif; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'form-horizontal form-label-left',
    ),
        ));
?>

<div class="form-group"<?php echo $model->type == 2 || (isset($_GET['type']) && $_GET['type'] == 2) ? '' : ' style="display:none"'?>>
    <?php echo $form->labelEx($model, 'name', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->textField($model, 'name', array('class' => 'form-control col-md-7 col-xs-12', 'maxlength' => 255)); ?>
        <ul class="parsley-errors-list">
            <li><?php echo $form->error($model, 'name', array('class' => 'parsley-required')); ?></li>
        </ul>
        <p class="hint"><?php echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'description', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->textField($model, 'description', array('class' => 'form-control col-md-7 col-xs-12', 'maxlength' => 255)); ?>
        <ul class="parsley-errors-list">
            <li><?php echo $form->error($model, 'description', array('class' => 'parsley-required')); ?></li>
        </ul>
        <p class="hint"><?php echo Rights::t('core', 'A descriptive name for this item.'); ?></p>
    </div>
</div>

<?php if (Rights::module()->enableBizRule === true): ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'bizRule', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $form->textField($model, 'bizRule', array('class' => 'form-control col-md-7 col-xs-12', 'maxlength' => 255)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo $form->error($model, 'bizRule', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></p>
        </div>
    </div>

<?php endif; ?>

<?php if (Rights::module()->enableBizRule === true && Rights::module()->enableBizRuleData): ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'data', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo $form->textField($model, 'data', array('class' => 'form-control col-md-7 col-xs-12', 'maxlength' => 255)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo $form->error($model, 'data', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></p>
        </div>
    </div>

<?php endif; ?>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <?php echo CHtml::submitButton(Rights::t('core', 'Save'), array('class' => 'btn btn-success')); ?> | <?php echo CHtml::link(Rights::t('core', 'Cancel'), Yii::app()->user->rightsReturnUrl); ?>
    </div>
</div>

<?php $this->endWidget(); ?>