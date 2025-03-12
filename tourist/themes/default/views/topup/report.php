<?php
    /* @var $this TopupController */
    /* @var $model TTopupQueue */

    $this->breadcrumbs = array(
        Yii::t('tourist/label', 'topup') => array('admin'),
    );
?>

<div class="x_panel">
    <?php $this->renderPartial('/topup/_filter_area', array('model' => $model)); ?>

    <div class="text-right" style="margin-bottom: -10px; position: relative">

        <div class="report_summary">
            <table>
                <tr>
                    <td>Tổng số thành công:</td>
                    <td><?php echo number_format(TTopupQueue::getQuantity(null,TTopupQueue::TOPUP_SUCCESS,null));?></td>
                </tr>
                <tr>
                    <td>Tổng số thất bại:</td>
                    <td><?php echo number_format(TTopupQueue::getQuantity(null,TTopupQueue::TOPUP_FAILED,null));?></td>
                </tr>
            </table>
        </div>


        <form action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/topupReport');?>" method="post" target="_blank" style="display: inline; position: absolute; right: 0; bottom: 25px;">
            <input name="YII_CSRF_TOKEN" type="hidden" value="<?php echo Yii::app()->request->csrfToken?>"/>
            <input name="excelExport[start_date]" type="hidden" value="<?php echo $model->start_date?>"/>
            <input name="excelExport[end_date]" type="hidden" value="<?php echo $model->end_date?>"/>
            <input name="excelExport[status]" type="hidden" value="<?php echo $model->status?>"/>
            <button class="btn btn-warning" type="submit">Xuất Excel</button>
        </form>

    </div>

    <div class="x_content">

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'topup-grid',
                'dataProvider'      => $model->search(),
                'afterAjaxUpdate'   => 'reinstallDatePicker',
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'htmlOptions'       => array(
                    'style' => 'padding-top: 0; padding-bottom: 15px',
                ),
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
                    array(
                        'name'        => 'pin',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = ($data->status == TTopupQueue::TOPUP_SUCCESS) ? $data->pin : substr($data->pin, 0, 5)."xxxxxxx";
                            return CHtml::encode($value);
                        },
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'value',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode(number_format($data->value,0,',','.'));
                        },
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $class = TTopupQueue::getStatusLabelClass($data->status);
                            $html = "<span class='$class'>".TTopupQueue::getStatusLabel($data->status)."</span>";
                            return $html;
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'note',
                        'type'        => 'raw',
                        'value'       => '$data->note',
                        'htmlOptions' => array('width' => '100px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('tourist/label', 'actions'),
                        'template'    => '{view}',
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
