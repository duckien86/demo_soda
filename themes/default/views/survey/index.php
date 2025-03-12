<?php
/**
 * @var $this SurveyController
 * @var $model WSurvey
 * @var $list_question array
 * @var $modelForm WSurveyReport
 * @var $msg string
 * @var $require_login boolean
 * @var $return_home boolean
 */

$this->pageTitle = 'VNPT SHOP - ' . $model->name;
?>

<div id="survey">

    <?php echo $this->renderPartial('/survey/_block_banner')?>

    <?php echo $this->renderPartial('/survey/_modal_complete', array(
        'msg' => $msg,
        'require_login' => $require_login,
        'return_home' => $return_home,
    ))?>

    <a href="#" class="btn btn-lg btn-detail hidden" data-toggle="modal" data-target="#modal_survey_confirm">
        <?php echo CHtml::encode(Yii::t('web/portal','view'))?>
    </a>
    <div class="container">
        <div id="survey_form_container">
            <div id="survey_form">
                <div class="title">
                    <h2><?php echo CHtml::encode($model->name)?></h2>
                </div>
                <div class="short_des">
                    <?php echo CHtml::encode($model->short_des)?>
                </div>
                <div class="content">
                    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'wsurveyreport-form',
                        'method' => 'post',
//                        'enableAjaxValidation' => true,
//                        'enableClientValidation' => true,
                    )); ?>

                    <?php echo CHtml::hiddenField('WSurveyReport[survey_id]', $modelForm->survey_id) ?>

                    <?php echo CHtml::errorSummary($modelForm)?>

                    <?php foreach ($list_question as $question) {
                        $view = '_item_question';
                        if(!empty($question->first_label) || !empty($question->last_label)){
                            $view = '_item_question_level';
                        }
                        $this->renderPartial("/survey/$view", array(
                            'model' => $question,
                            'form' => $form,
                            'modelForm' => $modelForm,
                        ));
                    } ?>

                    <div class="action">
                        <?php echo CHtml::submitButton(Yii::t('web/portal', 'send'),array(
                            'class'     => 'btn btn-lg btn-info',
                            'id'        => 'btnSubmitSurvey',
                        ))?>
                    </div>

                    <?php $this->endWidget()?>
                </div>


            </div>
        </div>
    </div>
</div>





<script>
    $(document).ready(function () {
        $('#survey_form input.flat').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
    });
</script>

