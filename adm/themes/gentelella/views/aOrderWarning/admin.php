<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

$this->breadcrumbs = array(
    'Đơn hàng cảnh báo' => array('admin'),
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_warning'); ?></h2>

        <div class="clearfix"></div>

    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model_search, 'model_validate' => $model)); ?>

    <div class="x_content">

        <div class="table-responsive tbl_style center">
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'aorder-warning-grid',
            'dataProvider'  => $model->search($post),
            'filter'        => $model,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'        => 'order_id',
//                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode($data->order_id);
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '200px'),
                ),
                array(
                    'header'      => 'TTKD',
//                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode(AProvince::model()->getProvinceByOrder($data->order_id));
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '200px'),
                ),
                array(
                    'header'      => 'PBH',
//                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode(ASaleOffices::model()->getSaleOfficesByOrder($data->order_id));
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '200px'),
                ),
                array(
                    'name'        => 'create_date',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode($data->create_date);
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'action_code',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode($data->getActionCode($data->action_code));
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'last_update',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode($data->last_update);
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'header'      => Yii::t('adm/actions', 'action'),
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'template'    => '{view}',
                    'buttons'     => array(
                        'view' => array(
                            'label' => '',
                            'url'   => 'Yii::app()->createUrl("aOrderWarning/view",
                                array("id"=>$data->order_id))',
                        ),
                    ),
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
        </div>
    </div>
</div>

