<?php
/**
 * @var $this SurveyController
 * @var $answer WSurveyAnswer
 * @var $question WSurveyQuestion
 * @var $form TbActiveForm
 * @var $modelForm WSurveyReport
 */
?>
<tr class="item_survey_answer">
    <td class="answer_button">
        <?php
        $check = false;
        if(isset($modelForm->question[$question->id]['check']) && $modelForm->question[$question->id]['check']){
            foreach ($modelForm->question[$question->id]['check'] as $key => $value){
                if($value == $answer->id){
                    $check = true;
                    break;
                }
            }
        }
        if($question->type == WSurveyQuestion::TYPE_CHOOSE_ONE){
            echo CHtml::radioButton("WSurveyReport[question][$question->id][check][]", $check, array(
                'class' => 'flat',
                'id'    => "WSurveyReport_question_{$question->id}_check_{$answer->id}",
                'value' => $answer->id,
            ));
        }else if($question->type == WSurveyQuestion::TYPE_CHOOSE_MANY){
            echo CHtml::checkBox("WSurveyReport[question][$question->id][check][]", $check, array(
                'class' => 'flat',
                'id'    => "WSurveyReport_question_{$question->id}_check_{$answer->id}",
                'value' => $answer->id,
            ));
        }
        ?>

        <?php echo CHtml::hiddenField("WSurveyReport[question][$question->id][is_right][$answer->id]", $answer->is_right);?>
    </td>
    <td class="answer_content">
        <label for='<?php echo "WSurveyReport_question_{$question->id}_check_{$answer->id}"?>'>
            <?php echo $answer->content?>
        </label>
        <?php if($answer->type == WSurveyAnswer::TYPE_CUSTOMIZE){
            $content = '';
            if(isset($modelForm->question[$question->id]['content'][$answer->id]) && $modelForm->question[$question->id]['content'][$answer->id]){
                $content = $modelForm->question[$question->id]['content'][$answer->id];
            }
            echo CHtml::textField("WSurveyReport[question][$question->id][content][$answer->id]", $content, array(
                'class' => 'survey_input'
            ));
        }?>
    </td>
</tr>
