<?php
    /* @var $this ATokenLinksController */
    /* @var $model ATokenLinks */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage') => array('admin'),
        Yii::t('adm/actions', 'view')
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($model->pre_order_msisdn); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'attributes' => array(
                'id',
                'order_id',
                'customer_msisdn',
                'customer_email',
                'pre_order_msisdn',
                'send_link_method',
                'link',
                array(
                    'name'  => 'create_by',
                    'type'  => 'raw',
                    'value' => User::getUserName($model->create_by),
                ),
                array(
                    'name'  => 'create_date',
                    'type'  => 'raw',
                    'value' => date("d/m/Y H:i:s", strtotime($model->create_date)),
                ),
                array(
                    'name'  => 'last_update',
                    'type'  => 'raw',
                    'value' => date("d/m/Y H:i:s", strtotime($model->last_update)),
                ),

                array(
                    'name'  => 'status',
                    'type'  => 'raw',
                    'value' => $model->getStatusLabel($model->status),
                ),
            ),
        )); ?>
    </div>
</div>
