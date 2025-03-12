<?php
    /* @var $this ASurveyQuestionController */
    /* @var $model ASurveyQuestion */
    /* @var $form TbActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'asurveyquestion-form',
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

        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'survey_id', array('class' => 'col-md-12 no_pad')); ?>
                <?php $this->widget('booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'survey_id',
                        'data'        => CHtml::listData(ASurvey::model()->findAll(),'id','name'),
                        'htmlOptions' => array(
                            'style'    => 'width:100%',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('adm/label', 'select'),
                        ),
                    )
                ); ?>
                <?php echo $form->error($model, 'survey_id'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->dropDownList($model, 'type', ASurveyQuestion::getAllQuestionType(), array('class' => 'dropdownlist')); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sort_order', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->numberField($model, 'sort_order', array('class' => 'textbox', 'min' => 0)); ?>
                <?php echo $form->error($model, 'sort_order'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'point', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->numberField($model, 'point', array('class' => 'textbox', 'min' => 0)); ?>
                <?php echo $form->error($model, 'point'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'first_label', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'first_label', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'first_label'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'last_label', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'last_label', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'last_label'); ?>
            </div>
        </div>
        <div class="col-md-6">
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
            <div class="form-group">
                <?php echo $form->labelEx($model, 'content', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textArea($model, 'content', array('class' => 'textarea'))?>
                <?php echo $form->error($model, 'content'); ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <a id="btnAddAnswer" onclick="addAnswer(this)" class="btn btn-info"><?php echo CHtml::encode(Yii::t('adm/label','add_answer'));?></a>
            </div>
            <div id="listAnswers" class="form-group">
                <?php
                if(!empty($model->answer) && is_array($model->answer)){
                    foreach ($model->answer as $index => $answer) {
                        echo $this->renderPartial('/aSurveyQuestion/_item_answer', array('model'=>$answer, 'index' => $index));
                    }
                }
                ?>
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

<style>
    .item_answer{
        padding: 15px;
        border: 1px solid #c5c5c5;
        margin-bottom: 15px;
        position: relative;
    }
    .item_answer .btnRemoveAnswer{
        position: absolute;
        right: 15px;
        top: 15px;
    }
</style>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/fileupload/jquery.fileupload.js"></script>

<script>
   function addAnswer(selector) {
       var button = $(selector);
       var container = $('#listAnswers');
       var index = container.find('.item_answer').last().attr('data-index');
       var YII_CSRF_TOKEN = <?php echo "\"".Yii::app()->request->csrfToken."\""?>;

       button.addClass('disabled');
       if(index){
           index = parseInt(index) + 1;
       }else{
           index = 1;
       }
       $.ajax({
           url: '<?php echo Yii::app()->controller->createUrl('aSurveyQuestion/addAnswer')?>',
           type: 'post',
           dataType: 'html',
           data: {
               'index' : index,
               'YII_CSRF_TOKEN' : YII_CSRF_TOKEN
           },
           success: function (result) {
               container.append(result);
               button.removeClass('disabled');
           }
       });
   }

    function removeAnswer(selector) {
        $(selector).closest('.item_answer').remove();
    }

    $(document).ready(function () {
        $('#ASurveyQuestion_type').on('change', function () {
            var type = parseInt($(this).val());
            if(type == <?php echo ASurveyQuestion::TYPE_CUSTOMIZE?>){
                $('#btnAddAnswer').addClass('hidden');
                $('#listAnswers').empty();
            }else{
                $('#btnAddAnswer').removeClass('hidden');
            }
        });
    });
</script>