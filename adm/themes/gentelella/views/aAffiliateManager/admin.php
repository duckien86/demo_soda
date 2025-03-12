<?php
    /* @var $this AAffiliateManagerController */
    /* @var $model AAffiliateManager */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu', 'manage_affiliate') => array('admin'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/menu', 'manage_affiliate'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'aaffiliatemanager-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:80px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'name',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'code',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList($model,'status', AAffiliateManager::getListStatus(), array('class'=> 'form-control')),
                        'value'       => function($data){
                            return AAffiliateManager::getStatusLabel($data->status);
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'type',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'url_redirect',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => 'md5',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = $data->code . $data->create_date . $data->status;
                            return md5($value);
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
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