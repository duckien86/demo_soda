<?php
/* @var $this AAgencyContractController */
/* @var $form TbActiveForm */
/* @var $modelDetail AAgencyContractDetail */
/* @var $packages APackage[] */
/* @var $details array */
/* @var $item APackage */

$item_sim_prepaid = AAgencyContractDetail::ITEM_SIM_PREPAID;
$item_sim_postpaid = AAgencyContractDetail::ITEM_SIM_POSTPAID;
?>
<table class="items table table-bordered table-condensed table-striped">
    <thead>
    <tr>
        <th>Tên sản phẩm</th>
        <th>Giá sản phẩm</th>
        <th>Loại</th>
<!--        <th>Số lượng</th>-->
        <th>Kiểu chiết khấu</th>
        <th>Giá trị CK</th>
        <th>Giá sau CK</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($packages)){

        foreach ($packages as $item) {
            $readonly = false;
            $style = ($readonly) ? 'background: #cccccc; cursor: no-drop' : '';
            $type = (isset($details[$item->code]['type'])) ? $details[$item->code]['type'] : AAgencyContractDetail::TYPE_PERCENT;
            $price_discount = (isset($details[$item->code]['price_discount'])) ? $details[$item->code]['price_discount'] : 0;
            $quantity = 1;
            $price = (isset($item->price)) ? $item->price : 0;

            $hidden = (in_array($item->code, array(
                AAgencyContractDetail::ITEM_SIM_PREPAID,
                AAgencyContractDetail::ITEM_SIM_POSTPAID
            ))) ? 'hidden' : '';
            ?>
            <tr data-id="<?= $item->code ?>">
                <td>
                    <?php echo CHtml::encode($item->name); ?>
                    <?php echo CHtml::hiddenField("AAgencyContractDetail[$item->code][name]", $item->name);?>
                </td>


                <td class="item-price" data-value="<?= $item->price ?>">
                    <div class="<?php echo $hidden?>">
                        <?= CHtml::encode(number_format($item->price, 0, "", ".")); ?>đ
                    </div>
                </td>


                <td class="item-quantity hidden">
                    <?php echo CHtml::numberField("AAgencyContractDetail[$item->code][quantity]", $quantity, array(
                        'id' => "AAgencyContractDetail_{$item->code}_quantity",
                        'value' => $quantity,
                        'min' => 0,
                        'placeholder' => Yii::t('adm/label', 'quantity'),
                        'oninput' => 'calculatorPriceDiscount(this)',
                        'class' => 'textbox', 'size' => 60, 'maxlength' => 255,
                    )); ?>
                </td>


                <td>
                    <?php
                        if(in_array($item->code, array(
                            AAgencyContractDetail::ITEM_SIM_PREPAID,
                            AAgencyContractDetail::ITEM_SIM_POSTPAID
                        ))){
                            echo ASim::getTypeLabel($item->type);
                        }else{
                            echo APackage::model()->getPackageType($item->type);
                        }
                    ?>
                </td>


                <td class="item-type">
                    <?php echo CHtml::dropDownList("AAgencyContractDetail[$item->code][type]", '',
                        array(AAgencyContractDetail::TYPE_PERCENT => Yii::t('adm/label', 'percent'), AAgencyContractDetail::TYPE_VALUE => Yii::t('adm/label', 'value')),
                        array('options' => array($type => array('selected' => TRUE)),
                            'onchange' => 'calculatorPriceDiscount(this)',
                            'class' => 'dropdownlist no_pad',
                            'id' => "AAgencyContractDetail_{$item->code}_type",
                            'readonly' => $readonly,
                            'style'    => $style
                        )); ?>
                </td>


                <td class="item-price-discount">
                    <?php echo CHtml::numberField("AAgencyContractDetail[$item->code][price_discount]", $price_discount, array(
                        'value' => $price_discount,
                        'min' => 0,
                        'max' => ($type == AAgencyContractDetail::TYPE_PERCENT) ? 100 : $item->price,
                        'placeholder' => Yii::t('adm/label', 'price_discount'),
                        'oninput' => 'calculatorPriceDiscount(this)',
                        'class' => 'textbox', 'size' => 60, 'maxlength' => 10,
                        'id' => "AAgencyContractDetail_{$item->code}_price_discount",
                        'readonly' => $readonly,
                        'style'    => $style
                    )); ?>
                </td>


                <td class="item-total">
                    <div class="<?php echo $hidden?>">
                        <?php
                        if ($type == AAgencyContractDetail::TYPE_PERCENT) {
                            $total = ($price - $price * ($price_discount / 100)) * $quantity;
                        } else {
                            $total = ($price - $price_discount) * $quantity;
                        }
                        echo CHtml::encode(number_format($total, 0, "", ".")) . 'đ';
                        ?>
                    </div>
                </td>
            </tr>
        <?php }
    }
    ?>
    </tbody>
</table>
<script>
    function calculatorPriceDiscount(itemTag) {
        var itemContainer = $(itemTag).closest('tr');
        var price = ($(itemContainer).find('.item-price').attr('data-value'));
        var quantity = ($(itemContainer).find('.item-quantity input').val());
        var type = $(itemContainer).find('.item-type select').val();
        var price_discount = ($(itemContainer).find('.item-price-discount input').val());

        var total = 0;
        if (type == <?=AAgencyContractDetail::TYPE_PERCENT?>) {
            $(itemContainer).find('.item-price-discount input').attr({"max": 100});
            total = parseInt(price - price * (price_discount / 100)) * quantity;
        } else {
            $(itemContainer).find('.item-price-discount input').attr({"max": price});
            total = parseInt(price - price_discount) * quantity;
        }

        $(itemContainer).find('.item-total div').html(formatNumber(total) + 'đ');
    }

    function formatNumber(num) {
        num = num.toString().replace(/\$|\./g, '');

        if (isNaN(num))
            num = "";
        num = Math.floor(num * 100 + 0.50000000001);
        num = Math.floor(num / 100).toString();

        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) {
            num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
        }
        return num;
    }
</script>