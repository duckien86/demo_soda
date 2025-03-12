<?php
/**
 * @var $this ReportController
 * @var $model TReport
 * @var $data                   CArrayDataProvider - Tổng hợp thù lao
 * @var $data_sim               CArrayDataProvider - Tổng hợp thù lao sim
 * @var $data_package           CArrayDataProvider - Tổng hợp thù lao gói
 * @var $data_detail_sim        CArrayDataProvider - Chi tiết thù lao sim
 * @var $data_detail_package    CArrayDataProvider - Chi tiết thù lao gói
 * @var $data_consume           CArrayDataProvider - Tổng hợp hoa hồng tiêu dùng tài khoản chính
 * @var $data_detail_consume    CArrayDataProvider - Chi tiết hoa hồng tiêu dùng tài khoản chính
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'report_remuneration');
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'report_remuneration'),
);
?>

<div class="report">

    <?php echo $this->renderPartial('/report/_search_remuneration', array('model' => $model))?>

    <?php if(isset($data) && !empty($data)): ?>

        <div class="row">
            <div class="col-sm-8">
                <div class="title">
                    <h5>* Tổng quan</h5>
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
                    'template'          => '{items}',
                    'itemsCssClass'     => 'table table-bordered table-striped table-hover responsive-utilities table-order-manage',
                    'htmlOptions'       => array(
                        'style' => 'padding-top: 5px',
                    ),
                    'columns'           => array(
                        array(
                            'header'        => Yii::t('tourist/label','type'),
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = '';
                                switch ($data->campaign_category_id){
                                    case TActions::CAMPAIGN_CATEGORY_ID_SIM:
                                        $value = 'Bán SIM';
                                        break;
                                    case TActions::CAMPAIGN_CATEGORY_ID_PACKAGE:
                                        $value = 'Bán gói';
                                        break;
                                    case TActions::CAMPAIGN_CATEGORY_ID_CONSUME:
                                        $value = 'Tiêu dùng tài khoản chính';
                                        break;
                                }
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
                            'htmlOptions'       => array('style' => 'vertical-align: center; text-align: right'),
                            'headerHtmlOptions' => array('style' => 'text-align: center'),
                        ),
                        array(
                            'header'        => Yii::t('tourist/label', 'revenue'),
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = number_format($data->revenue,0,',','.');
                                return $value;
                            },
                            'footer'        => number_format($sum_revenue, 0, ',', '.'),
                            'htmlOptions'       => array('style' => 'vertical-align: center; text-align: right'),
                            'headerHtmlOptions' => array('style' => 'text-align: center'),
                            'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: right'),
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
                            'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: right'),
                        ),
                    )
                )); ?>
            </div>
        </div>

    <?php endif; ?>

    <?php if( (isset($data_sim) && !empty($data_sim))
        || (isset($data_package) && !empty($data_package))
        || (isset($data_consume) && !empty($data_consume))
    ): ?>

    <?php $this->widget(
        'booster.widgets.TbTabs',
        array(
            'type'        => 'tabs',
            'tabs'        => array(
                array(
                    'label'   => Yii::t('tourist/label','remuneration_sim'),
                    'content' => $this->renderPartial('_remuneration_sim', array('model' => $model, 'data' => $data_sim, 'data_detail' => $data_detail_sim), TRUE),
                    'active'  => TRUE,
                ),
                array(
                    'label'   => Yii::t('tourist/label','remuneration_package'),
                    'content' => $this->renderPartial('_remuneration_package', array('model' => $model, 'data' => $data_package, 'data_detail' => $data_detail_package), TRUE),
                ),
                array(
                    'label'   => Yii::t('tourist/label','remuneration_consume'),
                    'content' => $this->renderPartial('_remuneration_consume', array('model' => $model, 'data' => $data_consume, 'data_detail' => $data_detail_consume), TRUE),
                ),
            ),
            'htmlOptions' => array('style' => 'margin-top: 30px')
        )
    );
    ?>

    <?php endif; ?>

</div>



