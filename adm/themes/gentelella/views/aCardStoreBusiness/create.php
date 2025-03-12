<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $modelOrder         AFTOrders
 * @var $modelDetail        AFTOrderDetails
 * @var $modelUser          UserLogin
 * @var $modelCard          ACardStoreBusiness
 * @var $contracts          array AFTContracts
 * @var $contract_details  array AFTContractsDetails
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'card_store_business_export') => array('export'),
    Yii::t('adm/label', 'export_card') => array('create'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'export_card') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array(
            'modelOrder'    => $modelOrder,
            'modelDetail'   => $modelDetail,
            'modelCard'     => $modelCard,
            'modelUser'     => $modelUser,
            'contracts'     => $contracts,
            'contract_details' => $contract_details,
        )); ?>
    </div>
</div>