<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $modelOrder         AFTOrders
 * @var $modelUser          UserLogin
 * @var $customer           AFTUsers
 * @var $details            AFTOrderDetails[]
 * @var $contract           AFTContracts
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'card_store_business_export') => array('export'),
    $modelOrder->code
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'update_export_card') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form_update', array(
            'modelOrder'    => $modelOrder,
            'modelUser'     => $modelUser,
            'customer'      => $customer,
            'details'       => $details,
        )); ?>
    </div>
</div>