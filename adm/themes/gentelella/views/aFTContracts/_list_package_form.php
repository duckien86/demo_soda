<?php
    /* @var $this AFTContractsController */
    /* @var $form TbActiveForm */
    /* @var $modelDetail AFTContractsDetails */
    /* @var $packages array AFTPackage */
    /* @var $details array */
    /* @var $item AFTPackage */
?>
<table class="items table table-bordered table-condensed table-striped">
    <thead>
    <tr>
        <th>Tên sản phẩm</th>
        <th>Giá sản phẩm</th>
        <th>Số lượng</th>
        <th>Kiểu chiết khấu</th>
        <th>Giá trị CK</th>
        <th>Giá sau CK</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!$packages){
        echo "<td colspan='6'>Chưa có dữ liệu</td>";
    }else {
        foreach ($packages as $item) {
//            $readonly = ($item->type == AFTPackage::FT_PACKAGE_TYPE_KHDN) ? true : false;
            $readonly = false;
            $style = ($readonly) ? 'background: #cccccc; cursor: no-drop' : '';
            $type = (isset($details[$item->id]['type'])) ? $details[$item->id]['type'] : AFTContractsDetails::TYPE_PERCENT;
            $price_discount = (isset($details[$item->id]['price_discount'])) ? $details[$item->id]['price_discount'] : 0;
            $quantity = (isset($details[$item->id]['quantity'])) ? $details[$item->id]['quantity'] : 0;
            $price = (isset($item->price)) ? $item->price : 0;
            ?>
            <tr data-id="<?= $item->id ?>">
                <td class="col-md-2">
                    <?php echo CHtml::encode($item->name); ?>
                    <?php echo CHtml::hiddenField("AFTContractsDetails[$item->id][name]", $item->name);?>
                </td>
                <td class="col-md-2 item-price" data-value="<?= $item->price ?>">
                    <?= CHtml::encode(number_format($item->price, 0, "", ".")); ?>đ
                </td>
                <td class="col-md-2 item-quantity">
                    <?php echo CHtml::numberField("AFTContractsDetails[$item->id][quantity]", $quantity, array(
                        'id' => "AFTContractsDetails_{$item->id}_quantity",
                        'value' => $quantity,
                        'min' => 0,
                        'placeholder' => Yii::t('adm/label', 'quantity'),
                        'oninput' => 'calculatorPriceDiscount(this)',
                        'class' => 'textbox', 'size' => 60, 'maxlength' => 255,
                    )); ?>
                </td>
                <td class="col-md-2 item-type">
                    <?php echo CHtml::dropDownList("AFTContractsDetails[$item->id][type]", '',
                        array(AFTContractsDetails::TYPE_PERCENT => Yii::t('adm/label', 'percent'), AFTContractsDetails::TYPE_VALUE => Yii::t('adm/label', 'value')),
                        array('options' => array($type => array('selected' => TRUE)),
                            'onchange' => 'calculatorPriceDiscount(this)',
                            'class' => 'dropdownlist no_pad',
                            'id' => "AFTContractsDetails_{$item->id}_type",
                            'readonly' => $readonly,
                            'style'    => $style
                        )); ?>
                </td>
                <td class="col-md-2 item-price-discount">
                    <?php echo CHtml::numberField("AFTContractsDetails[$item->id][price_discount]", $price_discount, array(
                        'value' => $price_discount,
                        'min' => 0,
                        'max' => ($type == AFTContractsDetails::TYPE_PERCENT) ? 100 : $item->price,
                        'placeholder' => Yii::t('adm/label', 'price_discount'),
                        'oninput' => 'calculatorPriceDiscount(this)',
                        'class' => 'textbox', 'size' => 60, 'maxlength' => 10,
                        'id' => "AFTContractsDetails_{$item->id}_price_discount",
                        'readonly' => $readonly,
                        'style'    => $style
                    )); ?>
                </td>
                <td class="col-md-2 item-total">
                    <?php
                    if ($type == AFTContractsDetails::TYPE_PERCENT) {
                        $total = ($price - $price * ($price_discount / 100)) * $quantity;
                    } else {
                        $total = ($price - $price_discount) * $quantity;
                    }
                    echo CHtml::encode(number_format($total, 0, "", ".")) . 'đ';
                    ?>
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
        if (type == <?=AFTContractsDetails::TYPE_PERCENT?>) {
            $(itemContainer).find('.item-price-discount input').attr({"max": 100});
            total = parseInt(price - price * (price_discount / 100)) * quantity;
        } else {
            $(itemContainer).find('.item-price-discount input').attr({"max": price});
            total = parseInt(price - price_discount) * quantity;
        }

        $(itemContainer).find('.item-total').html(formatNumber(total) + 'đ');
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