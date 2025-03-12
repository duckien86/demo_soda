<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'report_import_card_store') => array('reportImport'),
);

$show = ( ( isset($_REQUEST['ACardStoreBusiness'])
        || ( isset($_REQUEST['ajax'])
            && $_REQUEST['ajax'] == 'csb_report_import-grid' )
    ) && !$model->hasErrors()
) ? true : false;
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/menu', 'report_import_card_store') ?></h2>

        <div class="clearfix"></div>
    </div>

    <?php echo $this->renderPartial('/aCardStoreBusiness/_filter_area_report', array('model' => $model, 'type' => 'import')) ?>


    <?php if($show) : ?>

    <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/cardStoreBusinessReportImport'); ?>">
        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
        <input type="hidden" name="excelExport[import_code]" value="<?php echo $model->import_code ?>">
        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
    </form>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'csb_report_import-grid',
                'dataProvider'      => $model->searchReportImport(),
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'      => 'STT',
                        'type'        => 'raw',
                        'value'       => '++$row',
                        'htmlOptions' => array('style' => 'width:50px;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y H:i:s', strtotime($data->create_date));;
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'name'        => 'import_code',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->import_code);
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label', 'card_price'),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            $list_cards = ACardStoreBusiness::getListCardImport($data->import_code, 'value');
                            foreach ($list_cards as $card){
                                $value .= '<div>'.CHtml::encode(number_format($card->value,0,',','.') . ' VND').'</div>';
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label', 'quantity'),
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            $list_cards = ACardStoreBusiness::getListCardImport($data->import_code, 'value');
                            foreach ($list_cards as $card){
                                $value .= '<div>'.CHtml::encode(number_format($card->quantity,0,',','.')).'</div>';
                            }
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;', 'class' => 'text-center'),
                        'headerHtmlOptions' => array('class' => 'text-center'),
                    ),
                    array(
                        'header'      => 'User thực hiện',
                        'type'        => 'raw',
                        'value'       => '$data->user_create',
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