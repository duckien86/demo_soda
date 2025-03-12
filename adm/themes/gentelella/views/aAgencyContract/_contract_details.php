<?php
    /* @var $this AAgencyContractController */
    /* @var $model AAgencyContract */
    /* @var $contract_details AAgencyContractDetail */
?>
<div class="table-responsive">
    <?php
        $this->widget('booster.widgets.TbGridView', array(
            'id'           => 'contract_details_grid',
            'dataProvider' => $contract_details,
            'type'         => 'bordered condensed striped',
            'columns'      => array(
                array(
                    'header'      => 'Mã gói',
                    'name'        => 'item_id',
                    'sortable'    => false,
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'header'      => 'Tên gói',
                    'value'       => function($data){
                        $value = $data->package_name;
                        return $value;
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'header'      => Yii::t("adm/label", "price"),
                    'value'       => function($data){
                        $value = number_format($data->price,0,',', '.');
                        return $value;
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'header'      => Yii::t("adm/label", "type"),
                    'value'       => function($data){
                        $value = APackage::model()->getPackageType($data->type);
                        return $value;
                    },
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),

//                array(
//                    'name'        => 'quantity',
//                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
//                ),
                array(
                    'header'      => 'Chiết khấu %',
                    'value'       => 'number_format($data->price_discount_percent,0,"",".") . "%"',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'header'      => 'Chiết khấu giá',
                    'value'       => 'number_format($data->price_discount_amount,0,"",".")',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'header'      => Yii::t("adm/label", "amount"),
                    'value'       => '$data->getAmountDetail($data->item_id,$data->quantity,$data->price_discount_percent,$data->price_discount_amount)',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
            ),
        ));
    ?>

</div>