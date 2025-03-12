<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_redeem') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_redeem'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form method="post"
              action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportARedeemHistory'); ?>"
              name="fday">
            <input type="hidden" name="excelExport[test]" value="">
            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
        </form>
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'aredeem-history-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'        => 'msisdn',
                    'type'        => 'raw',
                    'value'       => 'CHtml::link($data->msisdn, array(\'view\', \'id\' => $data->id))',
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'username',
//                    'filter'      => '$data->username',
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return CHtml::encode($data->username);
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),

                array(
                    'name'        => 'create_date',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::link($data->create_date, array(\'view\', \'id\' => $data->id))',
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'package_code',
                    'type'        => 'raw',
                    'value'       => 'CHtml::link($data->package_code, array(\'view\', \'id\' => $data->id))',
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
                ),
                array(
                    'name'        => 'point_amount',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::link($data->point_amount, array(\'view\', \'id\' => $data->id))',
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right;vertical-align:middle;padding:10px'),
                ),
//                array(
//                    'name'        => 'transaction_id',
//                    'filter'      => FALSE,
//                    'type'        => 'raw',
//                    'value'       => 'CHtml::link($data->transaction_id, array(\'view\', \'id\' => $data->id))',
//                    'htmlOptions' => array('nowrap' => 'nowrap'),
//                ),
                array(
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'template'    => '{view}',
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>

