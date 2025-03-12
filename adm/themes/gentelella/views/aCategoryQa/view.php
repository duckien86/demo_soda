<?php
    /* @var $this ACategoryQaController */
    /* @var $model ACategoryQa */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_cate_qa') => array('admin'),
        $model->name,
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo $model->name ?></h2>
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
                    'name'        => "name",
                    'value'       => function ($data) {
                        return Chtml::encode($data['name']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "status",
                    'value'       => function ($data) {
                        return Chtml::encode(ACategoryQa::getStatus($data['status']));
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
            ),
        )); ?>
    </div>
</div>
