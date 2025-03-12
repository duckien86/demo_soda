<?php
/**
 * @var $this ReportZaloController
 * @var $model ReportZalo
 * @var $data         CArrayDataProvider - Tổng hợp thù lao sim
 * @var $data_detail  CArrayDataProvider - Chi tiết thù lao sim
 */
?>

<?php if(isset($data) && !empty($data)): ?>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-8">

            <form method="post" action="<?php echo Yii::app()->createUrl('excelExport/reportZaloRemunerationSim')?>" target="_blank">
                <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
                <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date?>"/>
                <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date?>"/>
                <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code?>"/>
                <input type="hidden" name="excelExport[order_id]" value="<?php echo $model->order_id?>"/>
                <input type="hidden" name="excelExport[msisdn]" value="<?php echo $model->msisdn?>"/>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>

            <span class="title">
                    * Tổng quan thù lao bán SIM
                </span>

            <?php
            $sum_total = 0;
            $sum_revenue = 0;
            $sum_rose = 0;
            foreach ($data->rawData as $commissionSimDetail){
                $sum_total+= $commissionSimDetail->total;
                $sum_revenue+= $commissionSimDetail->revenue;
                $sum_rose+= $commissionSimDetail->rose;
            }
            ?>

            <?php $this->widget('booster.widgets.TbGridView', array(
                'dataProvider'      => $data,
                'template'          => '{summary}{items}{pager}',
                'type'         => 'striped bordered  consended ',
                'htmlOptions'  => array(
                    'class' => 'tbl_style',
                    'style' => 'padding-top: 10px',
                ),
                'columns'           => array(
                    array(
                        'header'        => 'Kênh bán',
                        'type'          => 'raw',
                        'value'         => function($data){
                            return AReportATForm::getChannelByCode($data->affiliate_channel);
                        },
                        'footer'        => Yii::t('tourist/label','sum'),
                        'htmlOptions'       => array('style' => 'vertical-align: center;'),
                        'headerHtmlOptions' => array('style' => 'text-align: center'),
                        'footerHtmlOptions' => array('style' => 'text-align: right; font-weight: bold;'),
                    ),
                    array(
                        'header'        => 'Hình thức',
                        'type'          => 'raw',
                        'value'         => function($data){
                            return ASim::getTypeLabel($data->sub_type);
                        },
                        'footer'        => Yii::t('tourist/label','sum'),
                        'htmlOptions'   => array('style' => 'vertical-align: center;'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'quantity'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total, 0, ',', '.');
                            return $value;
                        },
                        'footer'        =>  number_format($sum_total,0,',','.'),
                        'htmlOptions'       => array('style' => 'vertical-align: center; text-align: right'),
                        'headerHtmlOptions' => array('style' => 'text-align: center'),
                        'footerHtmlOptions' => array('style' => 'text-align: right; font-weight: bold;'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label', 'revenue'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->revenue,0,',','.');
                            return $value;
                        },
                        'footer'        =>  number_format($sum_revenue,0,',','.'),
                        'htmlOptions'       => array('style' => 'vertical-align: center; text-align: right'),
                        'headerHtmlOptions' => array('style' => 'text-align: center'),
                        'footerHtmlOptions' => array('style' => 'text-align: right; font-weight: bold;'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label', 'remuneration'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->rose,0,',','.');
                            return $value;
                        },
                        'footer'        =>  number_format($sum_rose,0,',','.'),
                        'htmlOptions'       => array('style' => 'vertical-align: center; text-align: right'),
                        'headerHtmlOptions' => array('style' => 'text-align: center'),
                        'footerHtmlOptions' => array('style' => 'text-align: right; font-weight: bold;'),
                    ),
                )
            )); ?>
        </div>
    </div>

<?php endif; ?>


<?php if( isset($data_detail) && !empty($data_detail) && $model->on_detail == 'on'): ?>

    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">

            <form method="post" action="<?php echo Yii::app()->createUrl('excelExport/reportZaloRemunerationSimDetail')?>" target="_blank">
                <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
                <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date?>"/>
                <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date?>"/>
                <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code?>"/>
                <input type="hidden" name="excelExport[order_id]" value="<?php echo $model->order_id?>"/>
                <input type="hidden" name="excelExport[msisdn]" value="<?php echo $model->msisdn?>"/>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>

            <span class="title">
                * Chi tiết thù lao bán SIM
            </span>

            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'detail_remuneration_sim',
                'dataProvider'      => $data_detail,
                'template'          => '{summary}{items}{pager}',
                'type'         => 'striped bordered  consended ',
                'htmlOptions'  => array(
                    'class' => 'tbl_style',
                    'style' => 'padding-top: 10px',
                ),
                'columns'           => array(
                    array(
                        'header'        => 'Kênh bán',
                        'type'          => 'raw',
                        'value'         => function($data){
                            return AReportATForm::getChannelByCode($data->affiliate_channel);
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'order_id'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            return $data->order_id;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'province_code'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            return AProvince::getProvinceNameByCode($data->order_province_code);
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'msisdn'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            return $data->item_name;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'type_sim'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            return ASim::getTypeLabel($data->sub_type);
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'create_date'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = date('d-m-Y', strtotime(str_replace('/', '-', $data->create_date)));
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: center'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('adm/label', 'active_date'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = date('d-m-Y', strtotime(str_replace('/', '-', $data->active_time)));
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: center'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label', 'revenue'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->item_price,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: right'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label', 'rose'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->amount,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: right'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                )
            )); ?>

        </div>
    </div>

<?php endif; ?>



