<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 * @var $data CActiveDataProvider
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'report_export_card_store') => array('reportExport'),
);

$show = ( ( isset($_REQUEST['ACardStoreBusiness'])
            || ( isset($_REQUEST['ajax'])
            && $_REQUEST['ajax'] == 'csb_report_export-grid' )
          ) && !$model->hasErrors()
        ) ? true : false;
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/menu', 'report_export_card_store') ?></h2>

        <div class="clearfix"></div>
    </div>

    <?php echo $this->renderPartial('/aCardStoreBusiness/_filter_area_report', array('model' => $model, 'type' => 'export')) ?>


    <?php if($show) : ?>

    <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardStoreBusinessReportExport'); ?>">
        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
        <input type="hidden" name="excelExport[order_code]" value="<?php echo $model->order_code ?>">
        <input type="hidden" name="excelExport[status]" value="<?php echo $model->status ?>">
        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
    </form>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'csb_report_export-grid',
                'dataProvider'      => $model->searchReportExport(),
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'      => 'STT',
                        'type'        => 'raw',
                        'value'       => '++$row',
                        'htmlOptions' => array('style' => 'width:50px;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'order_date',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y H:i:s', strtotime($data->order_date));
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','order_id'),
                        'name'        => 'order_code',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->order_code);
                        },
                        'htmlOptions' => array('style' => 'width:140px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'value',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode(number_format($data->value,0,',','.') . ' VND');
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'serial',
                        'type'        => 'raw',
                        'value'       => '$data->serial',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $class = ACardStoreBusiness::getStatusLabelClass($data->status);
                            $value = "<span class='$class'>".ACardStoreBusiness::getStatusLabel($data->status)."</span>";
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'note',
                        'type'        => 'raw',
                        'value'       => '$data->note',
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                ),
            ));
            ?>
        </div>
    </div>

    <?php endif ?>

</div>