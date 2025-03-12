<?php
/**
 * @var $this SurveyController
 * @var $model WSurveyQuestion
 * @var $form TbActiveForm
 * @var $modelForm WSurveyReport
 */
?>
<div class="item_survey_question">
    <div class="question_content">
        <?php echo $model->content ?>
    </div>
    <div class="survey_answer">

        <?php
        if($model->type != WSurveyQuestion::TYPE_CUSTOMIZE){
            $list_answer = WSurveyAnswer::getListAnswersByQuestionId($model->id);
            if(!empty($list_answer)){
                echo "<table>";
                foreach ($list_answer as $answer){
                    echo $this->renderPartial('/survey/_item_answer',array(
                        'answer' => $answer,
                        'question' => $model,
                        'form' => $form,
                        'modelForm' => $modelForm,
                    ));
                }
                echo "</table>";
            }
        }else{

        }
        ?>

    </div>

</div>
