<?php
    /* @var $this CheckoutController */
    /* @var $payment_method WPaymentMethod */
    /* @var $form CActiveForm */
?>
<div class="form">
    <div class="panel-checkout step-payment">

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'form_step3',
            'enableAjaxValidation' => TRUE,
        )); ?>
        <div class="main_left_section-actions">
            <div class="panel">
                <?php if ($payment_method):
                    foreach ($payment_method as $item):
                        ?>
                        <div class="group">
                            <div class="radio select-method disabled">
                                <input type="radio" id="pm_<?= $item->id; ?>" name="PaymentMethod"
                                       value="<?= $item->id; ?>">
                                <label for="pm_<?= $item->id; ?>">
                                    <div class="payment-method">
                                        <div class="col-md-2">
                                            <div class="thumbnail">
                                                <img alt=""
                                                     src="<?= Yii::app()->params->upload_dir . $item->logo; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="description">
                                                <div class="title"><?= CHtml::encode($item->name); ?></div>
                                                <div class="subtitle"><?= $item->description; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="space_10"></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="pull-left">
                <a href="<?= Yii::app()->controller->createUrl('checkoutapi/checkout'); ?>">
                    « Chỉnh sửa thông tin
                </a>
            </div>
            <div class="pull-right text-right">
                <?php echo CHtml::submitButton(Yii::t('web/portal', 'payment'), array(
                    'class' => 'btn btn_green'
                )); ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
