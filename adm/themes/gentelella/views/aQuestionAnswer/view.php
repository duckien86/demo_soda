<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_cate_qa') => array('admin'),
        Yii::t('adm/actions', 'view'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'manage_cate_qa') ?></h2>
        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/label', 'manage_cate_qa'), array('admin'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'attributes' => array(
                array(
                    'name'        => 'question',
                    'value'       => function ($data) {
                        return Chtml::encode($data['question']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => 'answer',
                    'value'       => function ($data) {
                        return Chtml::encode($data['answer']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => 'cate_qa_id',
                    'value'       => function ($data) {
                        return Chtml::encode(ACategoryQa::getCateQa($data['cate_qa_id']));
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => 'status',
                    'value'       => function ($data) {
                        return Chtml::encode(AQuestionAnswer::getStatus($data['status']));
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
            ),
        )); ?>
    </div>
</div>
