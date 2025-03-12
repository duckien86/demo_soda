<?php
    /* @var $this TopupController */
    /* @var $model TTopupQueue */

    $this->breadcrumbs = array(
        Yii::t('tourist/label', 'topup') => array('admin'),
    );
?>

<div class="x_panel">

    <div class="text-right" style="margin-bottom: -10px">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('excelExport/topupUploadFileTemplate'); ?>" target="_blank" class="btn btn-success" style="">Lấy File mẫu</a>
        <a data-target="#modal_upload_topup" data-toggle="modal" id="btnUploadFileTopup" class="btn btn-primary">Upload File</a>
    </div>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'topup-grid',
                'dataProvider'      => $model->search(),
                'filter'            => $model,
                'afterAjaxUpdate'   => 'reinstallDatePicker',
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'    => 'STT',
                        'value'     => '++$row',
                        'htmlOptions' => array('style' => 'width:50px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'msisdn',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return substr($data->msisdn, 0, 5)."xxxxxxx";
                        },
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'name'        => 'serial',
//                        'type'        => 'raw',
//                        'value'       => function($data){
//                            return CHtml::encode($data->serial);
//                        },
//                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'pin',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = ($data->status == TTopupQueue::TOPUP_SUCCESS) ? $data->pin : substr($data->pin, 0, 5)."xxxxxxx";
                            return CHtml::encode($value);
                        },
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'name'        => 'value',
//                        'type'        => 'raw',
//                        'value'       => function($data){
//                            return CHtml::encode(number_format($data->value,0,',','.'));
//                        },
//                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList($model, 'status', TTopupQueue::getListStatus(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                        'value'       => function ($data) {
                            $class = TTopupQueue::getStatusLabelClass($data->status);
                            $html = "<span class='$class'>".TTopupQueue::getStatusLabel($data->status)."</span>";
                            return $html;
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'vertical-align:middle;'),
                    ),

                    array(
                        'header'      => Yii::t('tourist/label', 'actions'),
                        'template'    => '{view} {delete}',
                        'buttons'     => array(
                            'delete' => array(
                                'visible' => '$data->getBtnDelete()',
                            )
                        ),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px; min-width: 80px'),
                    ),
                ),
            ));

            //reinstall datePicker after update ajax
            Yii::app()->clientScript->registerScript('re-install-date-picker', "
                function reinstallDatePicker(id, data) {
                    $('#TTopupQueue_create_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                }
            ");
            ?>
        </div>
    </div>
</div>

<?php echo $this->renderPartial('/topup/_modal_upload')?>
