<?php
    /* @var $this CheckoutController */
    /* @var $package WPackage */
    /* @var $modelPackage WPackage */
?>
<div id="checkout_slider" class="list_package owl-carousel owl-theme">
    <?php

        if ($package):
            $i = 0;
            foreach ($package as $item):
                $checked = '';
                if (($modelPackage && $item->id == $modelPackage->id) || $item->id == Yii::app()->session['ss_package_id']) {
                    $checked = 'checked';
                }
                if($item->id != 'pDa489iAWzn01zrSQOPuPlshjfT2hYBx'){ // bỏ gói vd89
                ?>
                <div class="pack_chk">
                    <input class="chk_package" data-delivery_location_in_checkout="<?= $item->delivery_location_in_checkout ?>" id="WOrders_package_<?= $i ?>" value="<?= $item->id ?>" <?= $checked ?>
                           type="checkbox"
                           name="WOrders[package][]">
                    <label for="WOrders_package_<?= $i ?>">
                        <?php
                            $this->renderPartial('_block_package', array('data' => $item));
                        ?>
                    </label>
                </div>
                <?php }$i++; endforeach; ?>
        <?php endif; ?>
</div>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_package')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="text-center" id="package_name"></h4>
</div>
<div class="modal-body">
    <div id="package_info">

    </div>
    <div class="space_30"></div>
    <div class="pull-right">
        <?= CHtml::link(Yii::t('web/portal', 'close'), '#', array('class' => 'btn btn_green', 'data-dismiss' => 'modal')) ?>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>
<?php $this->renderPartial('_confirm_package_modal');
