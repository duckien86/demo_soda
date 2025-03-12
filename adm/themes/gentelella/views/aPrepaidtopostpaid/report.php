<?php
/* @var $this APrepaidtopostpaidController */
/* @var $model APrepaidToPostpaid */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'prepaid_to_postpaid') => array('report'),
    Yii::t('adm/menu', 'ptp_report_detail'),
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'ptp_report_detail'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/ptpReport'); ?>">
            <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
            <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
        </form>
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'a-ptp-report-detail-grid',
                'dataProvider' => $model->searchReportDetail(),
                'filter'       => $model,
                'itemsCssClass'=> 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'      => Yii::t('adm/label','order_id'),
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:120px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'msisdn',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $url = Yii::app()->createUrl('aPrepaidtopostpaid/view', array('id'=>$data->id));
                            return CHtml::link(ACustomers::getName($data->msisdn),$url);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'province_code',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => function($data){
                            return AProvince::getProvinceNameByCode($data->province_code);
                        },
                        'htmlOptions' => array('style' => 'width:160px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => '$data->create_date',
                        'htmlOptions' => array('style' => 'width:140px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'receive_date',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => '$data->receive_date',
                        'htmlOptions' => array('style' => 'width:140px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'finish_date',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => '$data->finish_date',
                        'htmlOptions' => array('style' => 'width:140px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => function($data){
                            $class = APrepaidToPostpaid::getLabelStatusClass($data->status);
                            return "<span class='$class'>".APrepaidToPostpaid::getStatusLabel($data->status)."</span>";
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'user',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => function($data){
                            return $data->user_id;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
