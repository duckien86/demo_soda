<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'aftorders-details-grid',
    'dataProvider'  => $model_details->search($model->id),
    'filter'        => $model_details,
    'enableSorting' => FALSE,
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'columns'       => array(

        array(
            'name'        => 'item_id',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                $return = '';
                $card = AFTPackage::model()->findByPk($data->item_id);
                if($card){
                    $return = $card->name . " (".number_format($card->price,0,',','.') . " VND)";
                }
                
                return CHtml::encode($return);
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'name'        => 'quantity',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(number_format($data->quantity, 0, '', '.'));
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle; text-align:right;'),
        ),

        array(
            'name'        => 'price',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(number_format($data->price, 0, '', '.') . " đ");
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align:right;'),
        ),

        array(
            'header'      => 'Triết khấu',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                $value = AFTContractsDetails::getDiscountLabel($data->order_id, $data->item_id);
                return CHtml::encode($value);
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align:right;'),
        ),
        array(
            'header'      => 'Thành tiền',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                return CHtml::encode(number_format($data->price * $data->quantity, 0, '', '.') . " đ");
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align:right;'),
        ),
    ),
)); ?>