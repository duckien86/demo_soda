<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'report_output_card_store') => array('reportCard'),
);

$show = ( ( isset($_REQUEST['ACardStoreBusiness'])
        || ( isset($_REQUEST['ajax'])
            && $_REQUEST['ajax'] == 'csb_report_card-grid' )
    ) && !$model->hasErrors()
) ? true : false;
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/menu', 'report_output_card_store') ?></h2>

        <div class="clearfix"></div>
    </div>

    <?php echo $this->renderPartial('/aCardStoreBusiness/_filter_area_report', array('model' => $model, 'type' => 'card')) ?>


    <?php if($show) : ?>

    <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardStoreBusinessReportCard'); ?>">
        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
        <input type="hidden" name="excelExport[order_code]" value="<?php echo $model->order_code ?>">
        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
    </form>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'csb_report_card-grid',
                'dataProvider'      => $model->searchReportCard(),
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
                            $value = '';
                            $list = AFTOrders::getListCardByOrder($data->order_id);
                            foreach ($list as $item) {
                                $value.= "<div>".number_format($item->raw_price,0,',','.') . " VND</div>";
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label', 'quantity'),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            $list = AFTOrders::getListCardByOrder($data->order_id);
                            foreach ($list as $item) {
                                $value.= "<div>".number_format($item->quantity,0,',','.')."</div>";
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => 'Tổng tiền',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            $list = AFTOrders::getListCardByOrder($data->order_id);
                            foreach ($list as $item) {
                                $total = AFTOrders::getOrderTotalCard($data->order_id, $item->raw_price);
                                $value.= "<div>".number_format($total,0,',','.') . " VND</div>";
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => 'Giá trị đơn hàng',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = AFTOrders::getTotalOrders($data->order_id);
                            return CHtml::encode(number_format($value,0,',','.') . ' VND');
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','detail'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view}',
                        'buttons'     => array(
                            'view' => array(
                                'options' => array(
                                    'target' => '_blank',
                                ),
                                'url'     => 'Yii::app()->createUrl(\'aCardStoreBusiness/reportCardDetail\', array(\'id\' => $data->order_id))',
                            ),
                        ),
                        'htmlOptions' => array(
                            'nowrap'    => 'nowrap',
                            'style'     => 'width:100px;text-align:center;vertical-align:middle;padding:10px'
                        ),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                ),
            ));
            ?>
        </div>
    </div>

    <?php endif ?>

</div>