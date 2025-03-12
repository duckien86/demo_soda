<?php
    /* @var $this ABackendLogsController */
    /* @var $model ABackendLogs */

    $this->breadcrumbs = array(
        'Abackend Logs' => array('index'),
        $model->id,
    );

?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/actions', 'view'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->widget('zii.widgets.CDetailView', array(
            'data'       => $model,
            'attributes' => array(
                'id',
                'username',
                'ipaddress',
                'logtime',
                'controller',
                'action',
                'detail',
            ),
        )); ?>
    </div>
</div>
