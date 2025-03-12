<?php
/**
 * @var $this OrderCtvController
 * @var $model TOrders
 * @var $province array
 * @var $district array
 * @var $ward array
 * @var $list_packages array
 * @var $type int
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'order_update');
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'order_update'),
);
$form = ($type && $type == TOrders::TYPE_WITH_FILE_SIM) ? '_form2' : '_form';
?>

<div id="order">
    <?php $this->renderPartial("/orderCtv/$form", array(
        'model'         => $model,
        'province'      => $province,
        'district'      => $district,
        'ward'          => $ward,
        'list_packages' => $list_packages,
    )); ?>
</div>