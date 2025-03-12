<div class="form">

    <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal form-label-left')); ?>

    <p class="note"></p>
    <span class="section"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></span>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="form-group row varname">
        <?php echo CHtml::activeLabelEx($model, 'varname', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo (($model->id) ? CHtml::activeTextField($model, 'varname', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 50, 'readonly' => true)) : CHtml::activeTextField($model, 'varname', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 50))); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'varname', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t("Allowed lowercase letters and digits."); ?></p>
        </div>
    </div>

    <div class="form-group row title">
        <?php echo CHtml::activeLabelEx($model, 'title', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'title', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 255)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'title', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Field name on the language of "sourceLanguage".'); ?></p>
        </div>
    </div>

    <div class="form-group row field_type">
        <?php echo CHtml::activeLabelEx($model, 'field_type', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo (($model->id) ? CHtml::activeTextField($model, 'field_type', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 50, 'readonly' => true, 'id' => 'field_type')) : CHtml::activeDropDownList($model, 'field_type', ProfileField::itemAlias('field_type'), array('class' => 'form-control', 'id' => 'field_type'))); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'field_type', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Field type column in the database.'); ?></p>
        </div>
    </div>

    <div class="form-group row field_size">
        <?php echo CHtml::activeLabelEx($model, 'field_size', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo (($model->id) ? CHtml::activeTextField($model, 'field_size', array('class' => 'form-control col-md-7 col-xs-12', 'readonly' => true)) : CHtml::activeTextField($model, 'field_size', array('class' => 'form-control col-md-7 col-xs-12'))); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'field_size', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Field size column in the database.'); ?></p>
        </div>
    </div>

    <div class="form-group row field_size_min">
        <?php echo CHtml::activeLabelEx($model, 'field_size_min', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'field_size_min', array('class' => 'form-control col-md-7 col-xs-12')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'field_size_min', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('The minimum value of the field (form validator).'); ?></p>
        </div>
    </div>

    <div class="form-group row required">
        <?php echo CHtml::activeLabelEx($model, 'required', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeDropDownList($model, 'required', ProfileField::itemAlias('required'), array('class' => 'form-control')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'required', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Required field (form validator).'); ?></p>
        </div>
    </div>

    <div class="form-group row match">
        <?php echo CHtml::activeLabelEx($model, 'match', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'match', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 255)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'match', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t("Regular expression (example: '/^[A-Za-z0-9\s,]+$/u')."); ?></p>
        </div>
    </div>

    <div class="form-group row range">
        <?php echo CHtml::activeLabelEx($model, 'range', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'range', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 5000)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'range', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Predefined values (example: 1;2;3;4;5 or 1==One;2==Two;3==Three;4==Four;5==Five).'); ?></p>
        </div>
    </div>

    <div class="form-group row error_message">
        <?php echo CHtml::activeLabelEx($model, 'error_message', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'error_message', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 255)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'error_message', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Error message when you validate the form.'); ?></p>
        </div>
    </div>

    <div class="form-group row other_validator">
        <?php echo CHtml::activeLabelEx($model, 'other_validator', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'other_validator', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 255)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'other_validator', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('JSON string (example: {example}).', array('{example}' => CJavaScript::jsonEncode(array('file' => array('types' => 'jpg, gif, png'))))); ?></p>
        </div>
    </div>

    <div class="form-group row default">
        <?php echo CHtml::activeLabelEx($model, 'default', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo (($model->id) ? CHtml::activeTextField($model, 'default', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 255, 'readonly' => true)) : CHtml::activeTextField($model, 'default', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 255))); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'default', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('The value of the default field (database).'); ?></p>
        </div>
    </div>

    <div class="form-group row widget">
        <?php echo CHtml::activeLabelEx($model, 'widget', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php
            list($widgetsList) = ProfileFieldController::getWidgets($model->field_type);
            echo CHtml::activeDropDownList($model, 'widget', $widgetsList, array('class' => 'form-control', 'id' => 'widgetlist'));
            //echo CHtml::activeTextField($model,'widget',array('size'=>60,'maxlength'=>255)); 
            ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'widget', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Widget name.'); ?></p>
        </div>
    </div>

    <div class="form-group row widgetparams">
        <?php echo CHtml::activeLabelEx($model, 'widgetparams', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'widgetparams', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 5000, 'id' => 'widgetparams')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'widgetparams', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('JSON string (example: {example}).', array('{example}' => CJavaScript::jsonEncode(array('param1' => array('val1', 'val2'), 'param2' => array('k1' => 'v1', 'k2' => 'v2'))))); ?></p>
        </div>
    </div>

    <div class="form-group row position">
        <?php echo CHtml::activeLabelEx($model, 'position', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'position', array('class' => 'form-control col-md-7 col-xs-12')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'position', array('class' => 'parsley-required')); ?></li>
            </ul>
            <p class="hint"><?php echo UserModule::t('Display order of fields.'); ?></p>
        </div>
    </div>

    <div class="form-group row visible">
        <?php echo CHtml::activeLabelEx($model, 'visible', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeDropDownList($model, 'visible', ProfileField::itemAlias('visible'), array('class' => 'form-control')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'visible', array('class' => 'parsley-required')); ?></li>
            </ul>
        </div>
    </div>

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->
<div id="dialog-form" title="<?php echo UserModule::t('Widget parametrs'); ?>">
    <form>
        <fieldset>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
            <label for="value">Value</label>
            <input type="text" name="value" id="value" value="" class="text ui-widget-content ui-corner-all" />
        </fieldset>
    </form>
</div>
