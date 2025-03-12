<?php
/**
 * @var $this PackageController
 * @var $activeId int
 */
?>
<div class="list_category_package_container">
    <ul id="list_category_package" role="tablist">
        <li role="presentation" class="<?php echo ($activeId && $activeId == WPackage::PACKAGE_HOT) ? 'active' : ''?>">
            <a href="#block_package_hot" aria-controls="block_package_hot" role="tab" data-toggle="tab" data-type="<?php echo WPackage::PACKAGE_HOT?>">
                <img src="<?php echo WPackage::getPackageIconByType(WPackage::PACKAGE_HOT)?>">
                <?php echo Yii::t('web/portal', 'package_hot') ?>
            </a>
        </li>
        <li role="presentation" class="<?php echo ($activeId && $activeId == WPackage::PACKAGE_PREPAID) ? 'active' : ''?>">
            <a href="#block_package_prepaid" aria-controls="block_package_prepaid" role="tab" data-toggle="tab" data-type="<?php echo WPackage::PACKAGE_PREPAID?>">
                <img src="<?php echo WPackage::getPackageIconByType(WPackage::PACKAGE_PREPAID)?>">
                <?php echo Yii::t('web/portal', 'package_prepaid') ?>
            </a>
        </li>
        <li role="presentation" class="<?php echo ($activeId && $activeId == WPackage::PACKAGE_POSTPAID) ? 'active' : ''?>">
            <a href="#block_package_postpaid" aria-controls="block_package_postpaid" role="tab" data-toggle="tab" data-type="<?php echo WPackage::PACKAGE_POSTPAID?>">
                <img src="<?php echo WPackage::getPackageIconByType(WPackage::PACKAGE_POSTPAID)?>">
                <?php echo Yii::t('web/portal', 'package_postpaid') ?>
            </a>
        </li>
        <li role="presentation" class="<?php echo ($activeId && $activeId == WPackage::PACKAGE_DATA) ? 'active' : ''?>">
            <a href="#block_package_data" aria-controls="block_package_data" role="tab" data-toggle="tab" data-type="<?php echo WPackage::PACKAGE_DATA?>">
                <img src="<?php echo WPackage::getPackageIconByType(WPackage::PACKAGE_DATA)?>">
                <?php echo Yii::t('web/portal', 'package_data') ?>
            </a>
        </li>
        <li role="presentation" class="<?php echo ($activeId && $activeId == WPackage::PACKAGE_VAS) ? 'active' : ''?>">
            <a href="#block_package_vas" aria-controls="block_package_vas" role="tab" data-toggle="tab" data-type="<?php echo WPackage::PACKAGE_VAS?>">
                <img src="<?php echo WPackage::getPackageIconByType(WPackage::PACKAGE_VAS)?>">
                <?php echo Yii::t('web/portal', 'package_vas') ?>
            </a>
        </li>
        <li role="presentation" class="<?php echo ($activeId && $activeId == WPackage::PACKAGE_DATA_FLEX) ? 'active' : ''?>">
            <a href="#block_package_flexible" aria-controls="block_package_flexible" role="tab" data-toggle="tab" data-type="<?php echo WPackage::PACKAGE_DATA_FLEX?>">
                <img src="<?php echo WPackage::getPackageIconByType(WPackage::PACKAGE_DATA_FLEX)?>">
                <?php echo Yii::t('web/portal', 'package_flexible_2') ?>
            </a>
        </li>
    </ul>
</div>