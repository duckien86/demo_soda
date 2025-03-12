<?php
    /* @var $this ASurveyReportController */
    /* @var $model ASurveyReport */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'survey_report') => array('admin'),
        $model->id
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        array(
            'name'  => 'user_id',
            'type'  => 'raw',
            'value' => ACustomers::getName($model->user_id),
        ),
        array(
            'name'  => 'order_id',
            'type'  => 'raw',
            'value' => $model->order_id,
        ),
        array(
            'name'  => 'phone',
            'type'  => 'raw',
            'value' => AOrders::getOrderPhoneContact($model->order_id),
        ),
        array(
            'name'  => 'question_id',
            'type'  => 'raw',
            'value' => ASurveyQuestion::getQuestionContent($model->question_id),
        ),
        array(
            'name'  => 'answer_id',
            'type'  => 'raw',
            'value' => ASurveyAnswer::getAnswerContent($model->answer_id),
        ),
        array(
            'name'  => 'content',
            'type'  => 'raw',
            'value' => $model->content,
        ),
        array(
            'name'  => 'is_right',
            'type'  => 'raw',
            'value' => function($data){
                $value = null;
                if($data->is_right){
                    if($data->is_right == ASurveyAnswer::RIGHT_ANSWER){
                        $value = Yii::t('adm/label','correct');
                    }else{
                        $value = Yii::t('adm/label','wrong');
                    }
                }
                return $value;
            },
        ),
        array(
            'name'  => 'create_date',
            'type'  => 'raw',
            'value' => $model->create_date,
        ),
    ),
)); ?>
