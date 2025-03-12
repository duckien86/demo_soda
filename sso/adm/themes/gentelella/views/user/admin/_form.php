<div class="form">

    <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data')); ?>

    <span class="section"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></span>

    <?php echo CHtml::errorSummary(array($model, $profile)); ?>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'username', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'username', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 20, 'maxlength' => 20)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'username', array('class' => 'parsley-required')); ?></li>
            </ul>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'password', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activePasswordField($model, 'password', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 128)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'password', array('class' => 'parsley-required')); ?></li>
            </ul>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'email', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeTextField($model, 'email', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 128)); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'email', array('class' => 'parsley-required')); ?></li>
            </ul>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'superuser', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeDropDownList($model, 'superuser', User::itemAlias('AdminStatus'), array('class' => 'form-control')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'superuser', array('class' => 'parsley-required')); ?></li>
            </ul>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'status', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php echo CHtml::activeDropDownList($model, 'status', User::itemAlias('UserStatus'), array('class' => 'form-control')); ?>
            <ul class="parsley-errors-list">
                <li><?php echo CHtml::error($model, 'status', array('class' => 'parsley-required')); ?></li>
            </ul>
        </div>
    </div>
    <?php
    $profileFields = $profile->getFields();
    if ($profileFields) {
        foreach ($profileFields as $field) {
            ?>
            <div class="form-group">
                <?php
                echo CHtml::activeLabelEx($profile, $field->varname, array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'));

                echo '<div class="col-md-6 col-sm-6 col-xs-12">';
                if ($field->widgetEdit($profile)) {
                    echo $field->widgetEdit($profile);
                } elseif ($field->range) {
                    echo CHtml::activeDropDownList($profile, $field->varname, Profile::range($field->range));
                } elseif ($field->field_type == "TEXT") {
                    echo CHtml::activeTextArea($profile, $field->varname, array('class' => 'resizable_textarea form-control', 'rows' => 6, 'cols' => 50));
                } else {
                    echo CHtml::activeTextField($profile, $field->varname, array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                }
                echo '<ul class="parsley-errors-list"><li>' . CHtml::error($profile, $field->varname, array('class' => 'parsley-required')) . '</li></ul>';
                echo '</div>';
                ?>
            </div>	
            <?php
        }
    }
    ?>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php echo CHtml::endForm(); ?>

</div><!-- form -->