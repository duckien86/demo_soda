<?php
/**
 * @var $model TOrders
 * @var $model_details TOrderDetails
 */
?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'torders-details-grid',
    'dataProvider'  => TOrderDetails::getAllOrdersDetails($model, true),
    'enableSorting' => FALSE,
    'template'      => '{items}{pager}',
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'columns'       => array(
        array(
            'name'        => Yii::t('tourist/label', 'product'),
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(TPackage::getPackageName($data->item_id));
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),

        array(
            'name'        => Yii::t('tourist/label', 'quantity'),
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(number_format($data->quantity, 0, ',', '.'));
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),

        array(
            'name'        => Yii::t('tourist/label', 'price_short'),
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(number_format($data->price, 0, ',', '.') . " đ");
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align:right;'),
            'footer'      => Yii::t('tourist/label','order_total_price'),
         ),

//        array(
//            'header'      => 'Triết khấu',
//            'filter'      => FALSE,
//            'type'        => 'raw',
//            'value'       => function ($data) {
//                return CHtml::encode(TContractsDetails::getItemDiscountStringByOrderDetail($data));
//            },
//            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align:right;'),
//        ),

        array(
            'header'      => Yii::t('tourist/label', 'item_total_price'),
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(number_format($data->price * $data->quantity, 0, ',', '.') . " đ");
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align:right;'),
            'footer'      => CHtml::link(number_format(TOrders::getTotalOrders($model->id), 0, '', '.') . " đ", 'javascript:void(0)', array('style' => 'text-align: right; font-size:14px; color:#ed0677;')),
        ),
    ),
)); ?>
<style>
    tfoot td{
        text-align: right;
    }
</style>
