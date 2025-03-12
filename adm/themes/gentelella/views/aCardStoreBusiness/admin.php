<?php
    /* @var $this ACardStoreBusinessController */
    /* @var $model ACardStoreBusiness */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_card_store_business'),
        Yii::t('adm/menu', 'import_card_store') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/menu', 'import_card_store'); ?></h2>

        <div class="clearfix"></div>
    </div>

    <?php echo $this->renderPartial("/aCardStoreBusiness/_filter_area", array(
        'model' => $model,
    ));?>

    <div class="pull-right">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardUploadFileTemplate'); ?>" target="_blank" class="btn btn-warning">Lấy File mẫu</a>
        <a data-target="#modal_upload_card_store" data-toggle="modal" id="btnUploadFileCard" class="btn btn-primary">Upload File</a>
    </div>

    <div class="x_content">

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'acardstorebusiness-grid',
                'dataProvider'      => $model->search(),
                'afterAjaxUpdate'   => 'reinstallDatePicker',
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'      => 'STT',
                        'type'        => 'raw',
                        'value'       => '++$row',
                        'htmlOptions' => array(
                            'style'     => 'width:50px;vertical-align:middle;',
                        ),
                    ),
                    array(
                        'name'        => 'serial',
                        'type'        => 'raw',
                        'value'       => '$data->serial',
                        'htmlOptions' => array('style' => 'width:140px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'pin',
                        'type'        => 'raw',
                        'value'       => function($data){
//                            $pin = ($data->status == ACardStoreBusiness::CARD_SUCCESS) ? $data->pin : substr($data->pin, 0, 5) . "xxxxxxx";
                            $pin = substr($data->pin, 0, 5) . "xxxxxxx";
                            return $pin;
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'value',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return number_format($data->value,0,',','.') . ' VND';
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y H:i:s', strtotime($data->create_date));
                        },
                        'htmlOptions' => array('style' => 'width:160px;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'name'        => 'import_code',
//                        'type'        => 'raw',
//                        'value'       => '$data->import_code',
//                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $status = '<span class="'.ACardStoreBusiness::getStatusLabelClass($data->status).'">'.ACardStoreBusiness::getStatusLabel($data->status).'</span>';
                            return $status;
                        },
                        'htmlOptions' => array('width' => '120px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view}',
                        'buttons'     => array(
                            'view' => array(
                                'options' => array(
                                    'target' => '_blank',
                                ),
                            ),
                        ),
                        'htmlOptions' => array(
                            'nowrap'    => 'nowrap',
                            'style'     => 'width:100px;text-align:center;vertical-align:middle;padding:10px'
                        ),
                    ),
                ),
            ));

            //reinstall datePicker after update ajax
            Yii::app()->clientScript->registerScript('re-install-date-picker', "
                function reinstallDatePicker(id, data) {
                    $('#ACardStore_exprie').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                }
            ");
            ?>
        </div>
    </div>
</div>

<?php echo $this->renderPartial('/aCardStoreBusiness/_modal_upload')?>


