<?php
/**
 * @var $this ASurveyQuestionController
 * @var $model ASurveyAnswer
 * @var $index int
 */
?>
<div class="item_answer" data-index="<?php echo $index?>">
    <h3><?php echo CHtml::encode(Yii::t('adm/label','answer') . ' #' . $index)?></h3>
    <div class="row">
        <?php echo CHtml::hiddenField("ASurveyQuestion[answer][$index][id]", $model->id);?>
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo CHtml::encode(Yii::t('adm/label','answer_type'))?> <span class="required">*</span></label>
                <?php echo CHtml::dropDownList("ASurveyQuestion[answer][$index][type]", $model->type, ASurveyAnswer::getAllAnswerType(), array('class' => 'dropdownlist')); ?>
                <?php echo CHtml::error($model, 'type')?>
            </div>
            <div class="form-group">
                <label><?php echo CHtml::encode(Yii::t('adm/label','sort_order'))?></label>
                <?php echo CHtml::numberField("ASurveyQuestion[answer][$index][sort_order]",$model->sort_order, array('class' => 'textbox', 'min' => 0));?>
                <?php echo CHtml::error($model, 'sort_order')?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php echo CHtml::checkBox("ASurveyQuestion[answer][$index][is_right]", ASurveyAnswer::isRight($model->is_right), array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'right_answer'); ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php echo CHtml::checkBox("ASurveyQuestion[answer][$index][status]", ASurveyAnswer::isActive($model->status), array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active'); ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <label><?php echo CHtml::encode(Yii::t('adm/label','answer_content'))?></label>
            <?php echo CHtml::textArea("ASurveyQuestion[answer][$index][content]", $model->content, array('class'=>"textarea"));?>
            <?php echo CHtml::error($model, 'content')?>
        </div>

        <a onclick="removeAnswer(this);" class="btn btn-danger btnRemoveAnswer" title="<?php echo CHtml::encode(Yii::t('adm/label','delete'))?>">
            <i class="fa fa-times"></i>
        </a>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('input.flat').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });
</script>
