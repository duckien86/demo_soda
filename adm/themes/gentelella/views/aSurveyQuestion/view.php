<?php
    /* @var $this ASurveyController */
    /* @var $model ASurvey */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'survey') => array('admin'),
        $model->name
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        'name',
        array(
            'name' => 'point',
            'type' => 'raw',
            'value' => number_format($model->point,0,',','.'),
        ),
        'start_date',
        'end_date',
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => $model->getStatusLabel(),
        ),
    ),
)); ?>
