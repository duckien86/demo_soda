<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 * @var $order AFTOrders
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'report_output_card_store') => array('reportCard'),
);

?>

<div class="x_panel">
    <div class="x_title">
        <h2 style="width: 100%"><?php echo Yii::t('adm/menu', 'report_output_card_store_detail') . ' - ' .$order->code ?></h2>

        <div class="clearfix"></div>
    </div>

    <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardStoreBusinessReportCardDetail'); ?>">
        <input type="hidden" name="excelExport[order_id]" value="<?php echo $model->order_id ?>">
        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
    </form>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'csb_report_card-grid',
                'dataProvider'      => $model->searchReportCardDetail(),
                'filter'            => $model,
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
                        'name'        => 'create_date',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y H:i:s', strtotime($data->create_date));
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','order_id'),
                        'name'        => 'order_code',
                        'filter'      => false,
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
                        'value'       => function($data){
                            return CHtml::encode($data->serial);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'pin',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $pin = substr($data->pin, 0, 5) . "xxxxxxx";
                            return CHtml::encode($pin);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
//                    array(
//                        'header'      => Yii::t('adm/label','item_total_price'),
//                        'type'        => 'raw',
//                        'value'       => function($data){
//                            $value = '';
//                            return $value;
//                        },
//                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
//                        'headerHtmlOptions' => array('class' => 'text-center'),
//                    ),
                    array(
                        'name'        => 'status',
                        'filter'      => CHtml::activeDropDownList($model,'status',ACardStoreBusiness::getListStatusExport(), array(
                            'class' => 'form-control',
                        )),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $class = ACardStoreBusiness::getStatusLabelClass($data->status);
                            $value = "<span class='$class'>".ACardStoreBusiness::getStatusLabel($data->status)."</span>";
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                ),
            ));
            ?>
        </div>
    </div>

</div>