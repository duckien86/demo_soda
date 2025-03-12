<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'report_remain_card_store') => array('reportRemain'),
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/menu', 'report_remain_card_store') ?></h2>

        <div class="clearfix"></div>
    </div>

    <?php echo $this->renderPartial('/aCardStoreBusiness/_filter_area_report', array('model' => $model, 'type' => 'remain')) ?>

    <?php if((isset($_REQUEST['ACardStoreBusiness']) || isset($_REQUEST['ACardStoreBusiness_page'])) && !$model->hasErrors()) : ?>

    <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardStoreBusinessReportRemain'); ?>">
        <input type="hidden" name="excelExport[create_date]" value="<?php echo $model->create_date ?>">
        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
    </form>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'card_store_business_remain_export-grid',
                'dataProvider'      => $model->searchReportRemain(),
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
//                    array(
//                        'header'      => 'STT',
//                        'type'        => 'raw',
//                        'value'       => '++$row',
//                        'htmlOptions' => array('style' => 'width:50px;vertical-align:middle;', 'class' => 'text-center'),
//                        'headerHtmlOptions' => array('class' => 'text-center'),
//                    ),
                    array(
                        'name'        => 'value',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->value,0,',','.') . " VND";
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label', 'quantity_inventory'),
                        'name'        => 'quantity',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $date = $_POST['ACardStoreBusiness']['create_date'];
                            $value = ACardStoreBusiness::getCardQuantityByValue($data->value, null, 'remain_before', $date, $date);
                            return CHtml::encode(number_format($value,0,',','.'));
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                ),
            ));
            ?>
        </div>
    </div>

    <?php endif ?>

</div>