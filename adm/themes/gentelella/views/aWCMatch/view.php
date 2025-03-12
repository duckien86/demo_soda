<?php
/**
 * @var $this AWCMatchController
 * @var $model AWCMatch
 */
$this->breadcrumbs = array(
    Yii::t('adm/label', 'wc_match') => array('admin'),
    $model->team_name_1. ' - '.$model->team_name_2 => array('update', 'id' => $model->id)
);
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        array(
            'name'  => 'team_name_1',
            'type'  => 'raw',
            'value' => $model->team_name_1 . " ($model->team_code_1)",
        ),
        array(
            'name'  => 'team_name_2',
            'type'  => 'raw',
            'value' => $model->team_name_2 . " ($model->team_name_2)",
        ),
        array(
            'name'  => 'type',
            'type'  => 'raw',
            'value' => AWCMatch::getTypeLabel($model->type),
        ),
        array(
            'name'  => 'start_time',
            'type'  => 'raw',
            'value' => date('H:i d-m-Y', strtotime($model->start_time)),
        ),
        array(
            'name'  => 'tỉ số',
            'type'  => 'raw',
            'value' => function($data){
                $value = null;
                if(!empty($data->score_1) && !empty($data->score_2)){
                    $value = $data->score_1 . ' - ' .$data->score_2;
                }
                return $value;
            },
        ),
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => AWCMatch::getStatusLabel($model->status),
        ),
        'create_time',
    ),
)); ?>
