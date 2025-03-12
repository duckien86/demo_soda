<?php
    /* @var $this AAgencyContractController */
    /* @var $model AAgencyContract */
    /* @var $modelDetail AAgencyContractDetail */
    /* @var $modelFiles AAgencyFile */
    /* @var $packages APackage[] */
    /* @var $details array */

    $this->breadcrumbs = array(
        'Đại lý tổ chức',
        'Hợp dồng' => array('admin'),
        Yii::t('adm/actions', 'create'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'create') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array(
            'model'       => $model,
            'modelDetail' => $modelDetail,
            'modelFiles'  => $modelFiles,
            'packages'    => $packages,
            'details'     => $details,
        )); ?>
    </div>
</div>