<?php
/* @var $this APrepaidtopostpaidController */
/* @var $model APrepaidToPostpaid */

$this->breadcrumbs = array(
    Yii::t('adm/menu','search'),
    Yii::t('adm/menu','order'),
    'ĐH chuyển đổi trả sau' => array('admin'),
);
?>
<style>
    .red_color{
        color: red;
    }
</style>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'ptp_change_list'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="right">
        <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/prepaidtopostpaidAdmin'); ?>" target="_blank">
            <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
            <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
            <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code ?>">
            <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $model->sale_office_code ?>">
            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
        </form>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'aprepaidtopostpaid-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'itemsCssClass'=> 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'      => Yii::t('adm/label', 'order_id_short'),
                        'name'        => 'id',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $url = Yii::app()->createUrl('aPrepaidtopostpaid/view', array('id'=>$data->id));
                            return CHtml::link($data->id,$url);
                        },
                        'htmlOptions' => array('style' => 'width:70px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'msisdn',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->msisdn);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'channel',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return $data->promo_code;
                        },
                        'htmlOptions' => array('style' => 'width:80px;vertical-align:middle; text-transform: capitalize'),
                    ),
                    array(
                        'name'        => 'package',
                        'type'        => 'raw',
                        'filter'       => false,
                        'value'       => function($data){
                            return APackage::getPackageNameByCode($data->package_code);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'full_name',
                        'type'        => 'raw',
                        'filter'       => false,
                        'value'       => '$data->full_name',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'address',
                        'type'        => 'raw',
                        'filter'       => false,
                        'value'       => function($data){
                            return  $data->address_detail . ' ' .
                                AWard::getWardNameByCode($data->ward_code) . ', ' .
                                ADistrict::getDistrictNameByCode($data->district_code) . ', ' .
                                AProvince::getProvinceNameByCode($data->province_code);
                        },
                        'htmlOptions' => array('style' => 'width:170px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'type'        => 'raw',
                        'filter'       => false,
                        'value'       => '$data->create_date',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'time_left',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $result = '';
                            $time_left = $data->getTimeLeftAssign($data->create_date, 72);
                            if($data->status != APrepaidToPostpaid::PTP_COMPLETE){
                                $result    = CHtml::encode($time_left['time']);
                            }
                            return '<span class="'.$time_left['class_name'].'">'.$result.'</span>';

                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                    ),
                    array(
                        'name'        => 'user_id',
                        'type'        => 'raw',
                        'filter'       => false,
                        'value'       => function($data){
                            return (!empty($data->user_id)) ? $data->user_id : Yii::t('adm/label', 'not_assigned');
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'       => false,
                        'value'       => function($data){
                            $class = APrepaidToPostpaid::getBtnActionClass($data->status);
                            return CHtml::link(APrepaidToPostpaid::getStatusLabel($data->status), '#', array(
                                'class' => "btn $class",
                                'style' => "width:100%",
                                'data-toggle' => 'modal',
                                'data-target' => '#ptpAdminModal',
                                'onclick' => "getPtpContent(this,'$data->id')"
                            ));
                        },
                        'htmlOptions' => array('style' => 'width:80px;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'header'      => Yii::t('adm/actions', 'action'),
//                        'class'       => 'booster.widgets.TbButtonColumn',
//                        'template'    => '{view} {delete}',
//                        'buttons'     => array(
//                            'delete' => array(
//                                'visible' => '$data->status != 10 && $data->status !=2',
//                            ),
//                        ),
//                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'width:80px;text-align:center;vertical-align:middle;padding:10px'),
//                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
<?php echo $this->renderPartial('/aPrepaidtopostpaid/_modal_confirm')?>

<script>
function getPtpContent(selector, ptp_id) {
    console.log(ptp_id);
    var button = $(selector);
    button.addClass('disabled');
    $.ajax({
        url: '<?php echo Yii::app()->controller->createUrl('aPrepaidtopostpaid/getPtpContent')?>',
        type: 'post',
        dataType: 'html',
        data: {
            'APrepaidToPostpaid' : {
                'id' : ptp_id
            },
            'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken?>'
        },
        success: function (result) {
            $('#ptpAdminModal .modal-body').html(result);
            button.removeClass('disabled');
        }
    });
}

function approvePtp(selector, ptp_id) {
    var button = $(selector);
    button.addClass('disabled');
    $.ajax({
        url: '<?php echo Yii::app()->controller->createUrl('aPrepaidtopostpaid/approvePtp')?>',
        type: 'post',
        dataType: 'html',
        data: {
            'APrepaidToPostpaid' : {
                'id' : ptp_id
            },
            'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken?>'
        },
        success: function (result) {
            $('#ptpAdminModal .modal-body').html(result);
            $.fn.yiiGridView.update('aprepaidtopostpaid-grid');
        }
    });
}

</script>