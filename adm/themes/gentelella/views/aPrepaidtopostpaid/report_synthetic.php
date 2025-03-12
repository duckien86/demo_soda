<?php
/* @var $this APrepaidtopostpaidController */
/* @var $model APrepaidToPostpaid */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'prepaid_to_postpaid') => array('report'),
    Yii::t('adm/menu', 'ptp_report_synthetic'),
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'ptp_report_synthetic'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area_report', array('model' => $model)); ?>
    <div class="x_content">
        <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/ptpReportSynthetic'); ?>">
            <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
            <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
        </form>
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'aprepaidtopostpaidreport-grid',
                'dataProvider' => $model->searchReportSynthetic(),
                'itemsCssClass'=> 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header' => 'Stt',
                        'value'  => '$row+1',
                        'htmlOptions' => array('style' => 'width:50px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'province_code',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return AProvince::getProvinceNameByCode($data->province_code);
                        },
                        'htmlOptions' => array('style' => 'width:160px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','total_receive'),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $start_date = null;
                            $end_date = null;
                            if(isset(Yii::app()->session['ptpReportSynthetic_start_date']) && isset(Yii::app()->session['ptpReportSynthetic_end_date'])){
                                $start_date = Yii::app()->session['ptpReportSynthetic_start_date'];
                                $end_date = Yii::app()->session['ptpReportSynthetic_end_date'];
                                $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
                                $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';
                            }
                            return APrepaidToPostpaid::getTotalReceiveByProvince($data->province_code, $start_date, $end_date);
                        },
                        'htmlOptions' => array('style' => 'width:160px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','total_success'),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $start_date = null;
                            $end_date = null;
                            if(isset(Yii::app()->session['ptpReportSynthetic_start_date']) && isset(Yii::app()->session['ptpReportSynthetic_end_date'])){
                                $start_date = Yii::app()->session['ptpReportSynthetic_start_date'];
                                $end_date = Yii::app()->session['ptpReportSynthetic_end_date'];
                                $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
                                $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';
                            }
                            return APrepaidToPostpaid::getTotalSuccessByProvince($data->province_code, $start_date, $end_date);
                        },
                        'htmlOptions' => array('style' => 'width:160px;word-break: break-word;vertical-align:middle;'),
                    ),

                    array(
                        'header'      => Yii::t('adm/label','success_percent'),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $start_date = null;
                            $end_date = null;
                            if(isset(Yii::app()->session['ptpReportSynthetic_start_date']) && isset(Yii::app()->session['ptpReportSynthetic_end_date'])){
                                $start_date = Yii::app()->session['ptpReportSynthetic_start_date'];
                                $end_date = Yii::app()->session['ptpReportSynthetic_end_date'];
                                $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
                                $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';
                            }
                            $receive = APrepaidToPostpaid::getTotalReceiveByProvince($data->province_code, $start_date, $end_date);
                            $success = APrepaidToPostpaid::getTotalSuccessByProvince($data->province_code, $start_date, $end_date);
                            $percent = $success/$receive*100;

                            if($percent == intval($percent)){
                                $decimal = 0;
                            }else{
                                $decimal = 2;
                            }
                            return number_format($success/$receive*100,$decimal,',','.') . '%';
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
