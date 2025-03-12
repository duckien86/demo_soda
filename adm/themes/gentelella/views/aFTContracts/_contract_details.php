<?php
    /* @var $this AFTContractsController */
    /* @var $model AFTContracts */
    /* @var $contract_details AFTContractsDetails */
?>
<div class="table-responsive">
    <?php
        $this->widget('booster.widgets.TbGridView', array(
            'id'           => 'contract_details_grid',
            'dataProvider' => $contract_details,
            'type'         => 'bordered condensed striped',
            'columns'      => array(
                array(
                    'name'        => 'item_id',
                    'value'       => '$data->getPackageNameById($data->item_id)',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => Yii::t("adm/label", "price"),
                    'value'       => '$data->getPackagePriceById($data->item_id)',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => 'quantity',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => 'price_discount_percent',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => 'price_discount_amount',
                    'value'       => 'number_format($data->price_discount_amount,0,"",".")."đ"',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
                array(
                    'name'        => Yii::t("adm/label", "amount"),
                    'value'       => '$data->getAmountDetail($data->item_id,$data->quantity,$data->price_discount_percent,$data->price_discount_amount)',
                    'htmlOptions' => array('style' => 'vertical-align:middle;'),
                ),
            ),
        ));

        //reinstall datePicker after update ajax
        Yii::app()->clientScript->registerScript('re-install-date-picker', "
                    function reinstallDatePicker(id, data) {
                        $('#AFTContracts_start_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                        $('#AFTContracts_finish_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                    }
                ");
    ?>

</div>