<?php
/**
 * @var $this ReportController
 * @var $model TReport
 * @var $data         CArrayDataProvider - Tổng hợp thù lao sim
 * @var $data_detail  CArrayDataProvider - Chi tiết thù lao sim
 */
?>


    <?php if(isset($data) && !empty($data)): ?>

        <div class="row" style="margin-top: 20px">
            <div class="col-sm-8">
                <div class="title">
                    <h5>* Tổng quan thù lao bán gói</h5>
                </div>

                <?php
                $sum_total = 0;
                $sum_revenue = 0;
                $sum_rose = 0;
                foreach ($data->rawData as $order){
                    $sum_total+= $order->total;
                    $sum_revenue+= $order->revenue;
                    $sum_rose+= $order->rose;
                }
                ?>

                <?php $this->widget('booster.widgets.TbGridView', array(
                    'dataProvider'      => $data,
//                'filter'            => $model,
                    'template'          => '{items}{pager}',
                    'itemsCssClass'     => 'table table-bordered table-striped table-hover responsive-utilities table-order-manage',
                    'htmlOptions'       => array(
                        'style' => 'padding-top: 5px',
                    ),
                    'columns'           => array(
                        array(
                            'header'        => Yii::t('tourist/label','package_name'),
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = $data->package_name;
                                return $value;
                            },
                            'footer'        => Yii::t('tourist/label','sum'),
                            'htmlOptions'       => array('style' => 'vertical-align: center'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold;'),
                        ),
                        array(
                            'header'        => Yii::t('tourist/label', 'output'),
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = number_format($data->total, 0, ',', '.');
                                return $value;
                            },
                            'footer'        =>  number_format($sum_total,0,',','.'),
                            'htmlOptions'       => array('style' => 'vertical-align: center; text-align: right'),
                            'headerHtmlOptions' => array('style' => 'text-align: center'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: right;'),
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
                            'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: right;'),
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
                            'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: right;'),
                        ),
                    )
                )); ?>
            </div>
        </div>

    <?php endif; ?>


    <?php if( $model->on_detail == 'on' ): ?>

    <div class="row" style="margin-top: 20px">
            <div class="col-sm-12">

                <div class="right">
                    <form method="post" action="<?php echo Yii::app()->createUrl('excelExport/reportDetailRemunerationPackage')?>" target="_blank">
                        <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date?>"/>
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date?>"/>
                        <?php echo CHtml::submitButton('Xuất ra Excel', array('style' => 'float: right; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                </div>

                <div class="title">
                    <h5>* Chi tiết thù lao bán gói</h5>
                </div>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'                => 'detail_remuneration_package',
                    'dataProvider'      => $data_detail,
                    'filter'            => $model,
                    'template'          => '{summary}{items}{pager}',
                    'itemsCssClass'     => 'table table-bordered table-striped table-hover responsive-utilities table-order-manage',
                    'htmlOptions'       => array(
                        'style' => 'padding-top: 5px',
                    ),
                    'columns'           => array(
                        array(
                            'header'        => Yii::t('tourist/label','order_id'),
                            'filter'        => CHtml::activeTextField($model,'order_code',array('class' => 'form-control', 'id' => ''))
                                . CHtml::activeHiddenField($model,'start_date', array('id' => ''))
                                . CHtml::activeHiddenField($model,'end_date', array('id' => ''))
                                . CHtml::activeHiddenField($model,'on_detail', array('id' => '')),
                            'type'          => 'raw',
                            'value'         => function($data){
                                return TOrders::getOrderCodeById($data->order_code);
                            },
                            'htmlOptions'   => array(
                                'style' => 'vertical-align: center'
                            )
                        ),
                        array(
                            'header'        => Yii::t('tourist/label','msisdn'),
                            'filter'        => CHtml::activeTextField($model,'msisdn',array('class' => 'form-control', 'id' => '')),
                            'type'          => 'raw',
                            'value'         => function($data){
                                return $data->msisdn;
                            },
                            'htmlOptions'   => array(
                                'style' => 'vertical-align: center'
                            )
                        ),
                        array(
                            'header'        => 'Thời gian tính',
                            'filter'        => false,
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = date('d-m-Y', strtotime(str_replace('/', '-', $data->created_on)));
                                return $value;
                            },
                            'htmlOptions'   => array('style' => 'vertical-align: center; text-align: center'),
                            'headerHtmlOptions'   => array('style' => 'text-align: center'),
                        ),
                        array(
                            'header'        => Yii::t('tourist/label','package_name'),
                            'filter'        => CHtml::activeTextField($model,'package_name',array('class' => 'form-control', 'id' => '')),
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = $data->product_name;
                                return $value;
                            },
                            'htmlOptions'   => array('style' => 'vertical-align: center; text-align: center'),
                            'headerHtmlOptions'   => array('style' => 'text-align: center'),
                        ),
                        array(
                            'header'        => Yii::t('tourist/label','revenue'),
                            'filter'        => false,
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
                            'filter'        => false,
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




