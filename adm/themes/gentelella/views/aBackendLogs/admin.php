<?php
    /* @var $this ABackendLogsController */
    /* @var $model ABackendLogs */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_log') => array('admin'),
    );

?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_log'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'abackend-logs-grid',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'username',
                        'type'        => 'raw',
                        'value'       => 'CHtml::encode($data->username)',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'ipaddress',
                        'type'        => 'raw',
                        'value'       => 'CHtml::encode($data->ipaddress)',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'logtime',
                        'type'        => 'raw',
                        'value'       => 'CHtml::encode($data->logtime)',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'controller',
                        'type'        => 'raw',
                        'value'       => 'CHtml::encode($data->controller)',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'action',
                        'type'        => 'raw',
                        'value'       => 'CHtml::encode($data->action)',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'detail',
                        'type'        => 'raw',
                        'value'       => 'CHtml::encode($data->detail)',
                        'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:200px !important;
                        '),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>

<style>
    .table {
        width: 100%;
        float: left;
        overflow: scroll;
    }
</style>