<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'orders') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );

?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('cskh/menu', 'report_shipper'); ?></h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_search_report', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'aorders-grid',
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'type'          => 'post',

                'columns' => array(
//                    array(
//                        'name'        => 'id',
//                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '80px', 'style' => 'text-align:center;vertical-align:middle;padding:10px;'),
//                    ),
                    array(
                        'name'        => 'full_name',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone_1',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone_2',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'address_detail',
                        'filter'      => FALSE,
                        'value'       => '$data->address_detail',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'total_order',
                        'filter'      => FALSE,
                        'value'       => '$data->total_order',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'renueve',
                        'filter'      => FALSE,
                        'value'       => 'number_format($data->total_order*20000, 0, "", "."). "đ"',
                        'htmlOptions' => array('style' => 'text-align: right;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'template'    => '{view}',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '80px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
        <div class="popup_data">
        </div>
    </div>
</div>
<style>
    .filters {
        background: #eee;
    }
</style>
