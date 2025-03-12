<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','location'),
        'Phòng bán hàng' => array('admin'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Phòng bán hàng</h2>
        <div class="clearfix"></div>
    </div>
    <div class="pull-right">
        <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
    </div>
    <div class="x_content">
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'asale-offices-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
//            'enableSorting' => FALSE,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'  => 'name',
                    'value' => function ($data) {
                        return CHtml::encode($data->name);
                    },
//                    'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                ),
                array(
                    'name'  => 'ward_code',
                    'value' => function ($data) {
                        return CHtml::encode($data->ward_code);
                    },
//                    'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                ),
                array(
                    'name'  => 'district_code',
                    'value' => function ($data) {
                        return CHtml::encode($data->district_code);
                    },
//                    'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                ),

                array(
                    'name'  => 'province_code',
                    'value' => function ($data) {
                        return CHtml::encode($data->province_code);
                    },
//                    'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                ),

                array(
                    'name'  => 'code',
                    'value' => function ($data) {
                        return CHtml::encode($data->code);
                    },
//                    'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                ),

                array(
                    'header'      => Yii::t('adm/actions', 'action'),
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '100px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>
