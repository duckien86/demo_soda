<?php
/**
 * @var $this AFTReportController
 * @var $model AFTReport
 * @var $data         CArrayDataProvider - Tổng hợp thù lao sim
 * @var $data_detail  CArrayDataProvider - Chi tiết thù lao sim
 */
?>

<?php if(isset($data) && !empty($data)): ?>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-8">

            <form method="post" action="<?php echo Yii::app()->createUrl('excelExport/touristReportDetailRemunerationSim')?>" target="_blank">
                <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
                <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date?>"/>
                <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date?>"/>
                <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code?>"/>
                <input type="hidden" name="excelExport[promo_code_prefix]" value="<?php echo $model->promo_code_prefix?>"/>
                <input type="hidden" name="excelExport[promo_code]" value="<?php echo $model->promo_code?>"/>
                <input type="hidden" name="excelExport[order_code]" value="<?php echo $model->order_code?>"/>
                <input type="hidden" name="excelExport[msisdn]" value="<?php echo $model->msisdn?>"/>
                <input type="hidden" name="excelExport[package_id]" value="<?php echo $model->package_id?>"/>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>

            <span class="title">
                    * Tổng quan thù lao bán SIM
                </span>

            <?php
            $sum_output = 0;
            $sum_revenue = 0;
            $sum_rose = 0;
            foreach ($data->rawData as $order){
                $sum_output += $order->total;
                $sum_revenue += $order->revenue;
                $sum_rose+= $order->rose;
            }
            ?>

            <?php $this->widget('booster.widgets.TbGridView', array(
                'dataProvider'      => $data,
                //                'filter'            => $model,
                'template'          => '{summary}{items}{pager}',
                'type'         => 'striped bordered  consended ',
                'htmlOptions'  => array(
                    'class' => 'tbl_style',
                    'style' => 'padding-top: 10px',
                ),
                'columns'           => array(
//                        array(
//                            'header'        => Yii::t('tourist/label','type'),
//                            'type'          => 'raw',
//                            'value'         => function($data){
//                                $value = TCtvActions::getType($data->type);
//                                return $value;
//                            },
//                            'footer'        => Yii::t('tourist/label','sum'),
//                            'htmlOptions'       => array('style' => 'vertical-align: center'),
//                        ),
                    array(
                        'header'        => 'Tên CTV',
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = ACtvUsers::getNameByCode($data->promo_code);
                            return $value;
                        },
                        'footer'        => Yii::t('tourist/label','sum'),
                        'htmlOptions'       => array('style' => 'vertical-align: center;'),
                        'headerHtmlOptions' => array('style' => 'text-align: center'),
                        'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                    ),
                    array(
                        'header'        => 'Mã CTV',
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = $data->promo_code;
                            return $value;
                        },
                        'htmlOptions'       => array('style' => 'vertical-align: center;'),
                        'headerHtmlOptions' => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label', 'output'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total, 0, ',', '.');
                            return $value;
                        },
                        'footer'        =>  number_format($sum_output,0,',','.'),
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

            <form method="post" action="<?php echo Yii::app()->createUrl('excelExport/touristReportDetailRemunerationSimDetail')?>" target="_blank">
                <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
                <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date?>"/>
                <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date?>"/>
                <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code?>"/>
                <input type="hidden" name="excelExport[promo_code_prefix]" value="<?php echo $model->promo_code_prefix?>"/>
                <input type="hidden" name="excelExport[promo_code]" value="<?php echo $model->promo_code?>"/>
                <input type="hidden" name="excelExport[order_code]" value="<?php echo $model->order_code?>"/>
                <input type="hidden" name="excelExport[msisdn]" value="<?php echo $model->msisdn?>"/>
                <input type="hidden" name="excelExport[package_id]" value="<?php echo $model->package_id?>"/>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>

            <span class="title">
                    * Chi tiết thù lao bán SIM
                </span>

            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'detail_remuneration_sim',
                'dataProvider'      => $data_detail,
//                    'filter'            => $model,
                'template'          => '{summary}{items}{pager}',
                'type'         => 'striped bordered  consended ',
                'htmlOptions'  => array(
                    'class' => 'tbl_style',
                    'style' => 'padding-top: 10px',
                ),
                'columns'           => array(
                    array(
                        'header'        => 'Tên CTV',
                        'type'          => 'raw',
                        'value'         => function($data){
                            return ACtvUsers::getNameByCode($data->inviter_code);
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => 'Mã CTV',
                        'type'          => 'raw',
                        'value'         => function($data){
                            return $data->inviter_code;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','order_id'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            return AFTOrders::getOrderCodeById($data->order_code);
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => 'TTKD',
                        'type'          => 'raw',
                        'value'         => function($data){
                            return AFTOrders::getProvinceCodeById($data->order_code);
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label', 'msisdn'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = $data->msisdn;
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','active_time'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = date('d-m-Y', strtotime(str_replace('/', '-', $data->active_date)));
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: center'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => 'Thời gian tính',
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = date('d-m-Y', strtotime(str_replace('/', '-', $data->created_on)));
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: center'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','revenue'),
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->price,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array('style' => 'vertical-align: center; text-align: right'),
                        'headerHtmlOptions'   => array('style' => 'text-align: center'),
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','remuneration'),
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



