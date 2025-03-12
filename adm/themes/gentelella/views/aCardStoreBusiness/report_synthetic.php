<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 * @var $data array ACardStoreBusiness
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'report_synthetic_card_store') => array('reportSynthetic'),
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/menu', 'report_synthetic_card_store') ?></h2>

        <div class="clearfix"></div>
    </div>

    <?php echo $this->renderPartial('/aCardStoreBusiness/_filter_area_report', array('model' => $model, 'type' => 'synthetic')) ?>


    <?php if((isset($_REQUEST['ACardStoreBusiness']) || isset($_REQUEST['ACardStoreBusiness_page'])) && !$model->hasErrors()) : ?>

    <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardStoreBusinessReportSynthetic'); ?>">
        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
    </form>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php echo $this->renderPartial('/aCardStoreBusiness/_table_report_synthetic', array(
                'model' => $model,
                'data' => $data
            )); ?>
        </div>
    </div>
    <?php endif ?>

</div>