<?php
    /* @var $this CardController */
    /* @var $modelOrder WOrders */
    /* @var $orderDetails WOrderDetails */
    /* @var $form CActiveForm */
    /* @var $operation */
?>
<div class="card">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'topup_form',
        'enableAjaxValidation' => TRUE,
    )); ?>
    <?php echo CHtml::hiddenField('OrdersData_operation', $operation); ?>
    <div class="form-group">
        <?php echo CHtml::label(Yii::t('web/portal', 'input_phone_contact') . '<span class="required">*</span>', 'WOrders_phone_contact', array('class' => 'label_text')) ?>
        <?php echo $form->textField($modelOrder, 'phone_contact',
            array('class'    => 'textbox_lg', 'maxlength' => 255,
                  'onchange' => 'getOrderPrice();'
            )); ?>
        <?php echo $form->error($modelOrder, 'phone_contact'); ?>
    </div>
    <div class="uppercase label_text"><?= Yii::t('web/portal', 'choose_card_value') ?> <span class="required">*</span>
    </div>
    <div class="form-group">
        <div class="form-group">
            <?php echo $form->error($orderDetails, 'price'); ?>
        </div>
        <ul class="form-group-radio grid4">
            <?php
                $card = Yii::app()->params['card_value'];
                foreach ($card as $key => $item):
                    ?>
                    <li>
                        <div class="radio radio-btn">
                            <input id="WOrderDetails_price_<?= $key ?>" type="radio" name="WOrderDetails[price]"
                                   class="radio_price" onclick="getOrderPrice();"
                                   value="<?= $key; ?>">
                            <label class="touch" for="WOrderDetails_price_<?= $key ?>"
                                   style="background: url('<?= Yii::app()->theme->baseUrl ?>/images/t_<?= $key ?>.png') no-repeat;background-size: 100% 100%">
                            </label>
                            <span class="card_label">
                                Giá bán: <?= number_format(($key * WOrders::PRICE_DISCOUNT_CARD), 0, "", "."); ?>đ
                            </span>
                        </div>
                    </li>
                <?php endforeach; ?>
        </ul>
    </div>
    <div class="space_10"></div>
    <div class="form-group">
        <?php
            if (isset($operation) && $operation == OrdersData::OPERATION_BUYCARD) {
                echo $form->labelEx($orderDetails, 'quantity', array('class' => 'label_text'));
                echo $form->textField($orderDetails, 'quantity', array(
                    'class' => 'textbox_lg', 'maxlength' => 255, 'onchange' => 'getOrderPrice();'
                ));
                echo $form->error($orderDetails, 'quantity');
            } else {
                echo $form->hiddenField($orderDetails, 'quantity');
            }
        ?>
    </div>
    <div class="space_10"></div>
    <div class="text-center">
        <?php echo CHtml::submitButton(Yii::t('web/portal', 'continue'), array('class' => 'btn btn_continue')); ?>
    </div>
    <?php $this->endWidget(); ?>
    <div class="space_30"></div>
</div>

<script>
    //    $('input.radio_price').on('click', function () {
    //        $('input.radio_price').addClass('checked');
    //    });

    /**
     * Tinh gia cho don hang
     */
    function getOrderPrice() {
        var form_data = new FormData(document.getElementById("topup_form"));//formID
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('card/getOrderPrice');?>",
            crossDomain: true,
            dataType: 'json',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (result) {
                $('#order_price_temp').html(result.content);
            }
        });
    }
</script>