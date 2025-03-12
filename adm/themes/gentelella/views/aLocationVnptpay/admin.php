<?php
    /* @var $this ALocationVnptpayController */
    /* @var $model ALocationVnptpay */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        Yii::t('adm/menu', 'account'),
        Yii::t('adm/label', 'location_vnptpay') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'location_vnptpay'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'alocationvnptpay-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:80px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','province'),
                        'name'        => 'province',
                        'value'       => function($data){
                            return CHtml::encode(AProvince::getProvinceNameByCode($data->id));
                        },
                        'htmlOptions' => array('style' => 'width:80px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => '',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view} {update} {delete}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>

        </div>
    </div>
</div>