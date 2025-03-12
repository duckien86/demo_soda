<?php
/**
 * @var $this SurveyController
 * @var $model WSurveyQuestion
 * @var $form TbActiveForm
 * @var $modelForm WSurveyReport
 */
?>
<div class="item_survey_question item_survey_question_level">
    <div class="question_content">
        <?php echo $model->content ?>
    </div>
    <div class="survey_answer">

        <?php
        if($model->type != WSurveyQuestion::TYPE_CUSTOMIZE){
            $list_answer = WSurveyAnswer::getListAnswersByQuestionId($model->id);
            if(!empty($list_answer)){?>
                <table>
                    <tr>
                        <?php if(!empty($model->first_label)){?>
                            <td class="question_label"><?php echo CHtml::encode($model->first_label)?></td>
                        <?php }?>

                        <?php foreach ($list_answer as $answer){
                            $first = ($answer === reset($list_answer)) ? true : false;
                            $last = ($answer === end($list_answer)) ? true : false;
                            echo $this->renderPartial('/survey/_item_answer_level',array(
                                'answer' => $answer,
                                'question' => $model,
                                'form' => $form,
                                'modelForm' => $modelForm,
                                'first' => $first,
                                'last'  => $last,
                            ));
                        }
                        ?>

                        <?php if(!empty($model->last_label)){?>
                            <td class="question_label"><?php echo CHtml::encode($model->last_label)?></td>
                        <?php }?>
                    </tr>
                </table>
            <?php
            }
        }
        ?>

    </div>

</div>
