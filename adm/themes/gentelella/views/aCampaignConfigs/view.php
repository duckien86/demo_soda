<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        'Quản lý link quảng cáo' => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2>Quản lý link quảng cáo</h2>
        <div class="pull-right">
            <?php echo CHtml::link('Quản lý link quảng cáo', array('admin'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'attributes' => array(
                array(
                    'name'        => "utm_source",
                    'value'       => function ($data) {
                        return Chtml::encode($data['utm_source']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "utm_medium",
                    'value'       => function ($data) {
                        return Chtml::encode($data['utm_medium']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "utm_campaign",
                    'value'       => function ($data) {
                        return Chtml::encode($data['utm_campaign']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "target_link",
                    'value'       => function ($data) {
                        return Chtml::encode($data['target_link']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "type",
                    'value'       => function ($data) {
                        return Chtml::encode($data['type']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "create_date",
                    'value'       => function ($data) {
                        return Chtml::encode($data['create_date']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => "status",
                    'value'       => function ($data) {
                        return Chtml::encode($data['status']);
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
            ),
        )); ?>
    </div>
</div>
