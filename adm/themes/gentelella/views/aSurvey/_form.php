<?php
    /* @var $this ASurveyController */
    /* @var $model ASurvey */
    /* @var $form TbActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'asurvey-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
        )); ?>

        <div class="col-md-12">
            <?= Yii::t('adm/actions', 'required_field') ?>
        </div>
        <div class="col-md-12">
            <?php echo $form->errorSummary($model); ?>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'short_des', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'short_des', array('class' => 'textarea', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'short_des'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'point', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->numberField($model, 'point', array('class' => 'textbox', 'min' => 0)); ?>
                <?php echo $form->error($model, 'point'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'limit', array('class' => 'col-md-12 no_pad')); ?>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon" id="un_limit_addon">
                        <a class="free-a" id="un_limit" onclick="unlimit();">
                            <?php echo Chtml::encode(Yii::t('adm/label','un_limit'));?>
                        </a>
                    </span>
                    <?php
                    echo $form->numberField($model, 'limit', array(
                        'class' => 'textbox',
                        'min' => 0,
                    ));
                    ?>
                    <?php echo $form->hiddenField($model, 'un_limit')?>
                </div>
                <?php echo $form->error($model, 'limit'); ?>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <!--Start Date-->
                        <?php echo $form->labelEx($model, 'start_date', array('class' => 'col-md-12 no_pad'))?>
                        <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'start_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'style' => 'width:150px; border-radius: 0',
                                'size'  => '10',
                            ),
                            'defaultOptions' => array(
                                'showOn'            => 'focus',
                                'dateFormat'        => 'yy/mm/dd',
                                'showOtherMonths'   => TRUE,
                                'selectOtherMonths' => TRUE,
                                'changeMonth'       => TRUE,
                                'changeYear'        => TRUE,
                                'showButtonPanel'   => TRUE,
                            )
                        ), TRUE);?>
                        <?php echo $form->error($model, 'start_date'); ?>
                    </div>
                    <div class="col-md-6">
                        <!--End Date-->
                        <?php echo $form->labelEx($model, 'end_date', array('class' => 'col-md-12 no_pad'))?>
                        <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'end_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'style' => 'width:150px; border-radius: 0',
                                'size'  => '10',
                            ),
                            'defaultOptions' => array(
                                'showOn'            => 'focus',
                                'dateFormat'        => 'yy/mm/dd',
                                'showOtherMonths'   => TRUE,
                                'selectOtherMonths' => TRUE,
                                'changeMonth'       => TRUE,
                                'changeYear'        => TRUE,
                                'showButtonPanel'   => TRUE,
                            )
                        ), TRUE);?>
                        <?php echo $form->error($model, 'end_date'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            } else {
                                echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            }
                        ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group buttons">
                <span class="btnintbl">
                    <span class="icondk">
                        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
                    </span>
                </span>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div>
    <!-- form -->
</div>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>

<script>
    function unlimit() {
        var limitInput = $('#ASurvey_limit');
        var unlimitInput = $('#ASurvey_un_limit');
        var hidden_id = 'ASurvey_unlimit';
        if (!limitInput.is('[readonly]')) {
            limitInput.prop("readonly", true);
            limitInput.val("");
            limitInput.css("background-color", "#E8E8E8");
            unlimitInput.val(<?php echo ASurvey::UN_LIMIT;?>);
            $('#un_limit_addon').css("background-color", "#39afe4");
            $('#un_limit').css("color", "white");
        } else {
            limitInput.prop("readonly", false);
            limitInput.css("background-color", "white");
            $('#un_limit_addon').css("background-color", "#eee");
            unlimitInput.val("");
            $('#un_limit').css("color", "black");
        }
    }

    $(document).ready(function () {
        if($('#ASurvey_un_limit').val() == <?php echo ASurvey::UN_LIMIT;?>){
            unlimit();
        }
    });
</script>