<?php
    /* @var $this CheckoutController */
    /* @var $package WPackage */
    /* @var $modelPackage WPackage */
?>
<div class="list_package package_panels">
    <div class="panel-group" id="accordion">
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
                        <div class="panel panel-default">
                            <input class="package_checkbox" data-delivery_location_in_checkout="<?= $item->delivery_location_in_checkout ?>" id="WOrders_package_<?= $i ?>" value="<?= $item->id ?>" <?= $checked ?>
                                   type="checkbox"
                                   name="WOrders[package][]">
                            <a class="panel_title" data-id="<?= $item->id ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $item->id ?>">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><?= CHtml::encode($item->name) ?></h4>
                                    <span class="checked_sight"></span>
                                </div>
                            </a>
                            <div id="collapse<?= $item->id ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php
                                        $this->renderPartial('_block_package', array('data' => $item));
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php }$i++; endforeach; ?>
            <?php endif; ?>
    </div>
</div>
<?php $this->renderPartial('_confirm_package_modal');