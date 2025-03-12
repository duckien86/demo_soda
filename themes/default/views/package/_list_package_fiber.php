<?php
/**
 * @var $this PackageController
 * @var $list_package array
 * @var $list_package_other array
 * @var $type int
 * @var $activeId int
 */
$tabId = 'block_package_';
switch ($type) {
    case WPackage::PACKAGE_HOT:
        $tabId .= 'hot';
        break;
    case WPackage::PACKAGE_PREPAID:
        $tabId .= 'prepaid';
        break;
    case WPackage::PACKAGE_POSTPAID:
        $tabId .= 'postpaid';
        break;
    case WPackage::PACKAGE_DATA:
        $tabId .= 'data';
        break;
    case WPackage::PACKAGE_VAS:
        $tabId .= 'vas';
        break;
    case WPackage::PACKAGE_DATA_FLEX:
        $tabId .= 'flexible';
        break;
}
$active = ($activeId && $activeId == $type) ? 'active' : '';

$open = '<div class="row">';
$openHidden = '<div class="row row-plus hidden">';
$close = '</div>';
$isOpen = false;
$start = 1;
$limit = 3;
$rowLimit = 2;
$rowStart = 1;

?>
<div role="tabpanel" class="tab-pane <?php echo $active ?>" id="<?php echo $tabId ?>" data-type="<?php echo $type ?>">

    <div id="list-item-package">
        <?php if (!empty($list_package)) { ?>
            <div class="package_freedoo">
                <div class="container">
                    <div class="title text-center">
                        <h3>Các gói cước internet truyền hình dành cho <span style="color: #ed0677">free</span><span style="color: #0aa1df">doo</span></h3>
                    </div>
                    <div class="content">
                        <div class="block_package">
                            <?php foreach ($list_package as $package) {
                                $this->renderPartial('/package/_item_package_fiber', array(
                                    'model' => $package
                                ));
                                ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if (!empty($list_package_other)) { ?>
        <!--    <div class="package_other">-->
        <!--        <div class="container">-->
        <!--            <div class="title text-center">-->
        <!--                <h3>--><?php //echo Yii::t('web/portal', 'package_other') ?><!--</h3>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="content">-->
        <!--                --><?php //$this->renderPartial('/package/_block_package_other', array(
//                    'list_package' => $list_package_other,
//                    'type'         => $type,
//                )) ?>
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
    <?php } ?>

    <?php if (empty($list_package) && empty($list_package_other)) { ?>
        <div class="container not-found"><?php echo Yii::t('web/portal', 'package_not_found_text'); ?></div>
    <?php } ?>

</div>

