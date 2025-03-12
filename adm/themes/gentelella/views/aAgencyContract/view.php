<?php
    /* @var $this AFTContractsController */
    /* @var $model AFTContracts */
    /* @var $contract_details AFTContractsDetails */

    $this->breadcrumbs = array(
        'Đại lý tổ chức',
        'Hợp dồng' => array('admin'),
        $model->code,
    );

    $this->menu = array(
        array('label' => Yii::t('adm/actions', 'create'), 'url' => array('create')),
        array('label' => Yii::t('adm/actions', 'update'), 'url' => array('update', 'id' => $model->id)),
        array('label' => Yii::t('adm/label', 'contracts_manage'), 'url' => array('admin')),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($model->code); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'type'       => '',
            'attributes' => array(
                'id',
                'code',
                'agency_id',
                array(
                    'name'  => 'create_time',
                    'type'  => 'raw',
                    'value' => date("d/m/Y H:i:s", strtotime($model->create_time)),
                ),
                array(
                    'name'  => 'last_update',
                    'type'  => 'raw',
                    'value' => date("d/m/Y H:i:s", strtotime($model->last_update)),
                ),
                array(
                    'name'  => 'start_date',
                    'type'  => 'raw',
                    'value' => date("d/m/Y", strtotime($model->start_date)),
                ),
                array(
                    'name'  => 'finish_date',
                    'type'  => 'raw',
                    'value' => date("d/m/Y", strtotime($model->finish_date)),
                ),
                'note',
                array(
                    'name'  => 'status',
                    'type'  => 'raw',
                    'value' => $model->getStatusLabel($model->status),
                ),
                array(
                    'name'  => Yii::t('adm/label', 'folder_path_contract'),
                    'type'  => 'raw',
                    'value' => $model->getFileUrl($model->id),
                ),
                array(
                    'name'  => 'create_by',
                    'type'  => 'raw',
                    'value' => User::getUserName($model->create_by),
                ),
            ),
        )); ?>

        <div class="space_30"></div>

        <?php if ($contract_details): ?>
            <fieldset class="list_package">
                <legend><?= Yii::t('adm/label', 'contract_details') ?></legend>
                <?php $this->renderPartial('_contract_details', array(
                    'contract_details' => $contract_details,
                )); ?>
            </fieldset>
        <?php endif; ?>
    </div>
</div>
