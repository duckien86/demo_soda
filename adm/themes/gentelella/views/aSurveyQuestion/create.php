<?php
    /* @var $this ASurveyQuestionController */
    /* @var $model ASurveyQuestion */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'survey_question') => array('admin'),
        Yii::t('adm/actions', 'create'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'create') ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>