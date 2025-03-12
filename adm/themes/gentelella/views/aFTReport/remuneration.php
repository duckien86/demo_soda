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
?>

<div class="x_panel">

    <div class="x_title">
        <h3>Báo cáo hoa hồng - Sim KHDN</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">

        <div class="col-md-12">
            <?php $this->renderPartial('_search_commission', array('model' => $model)); ?>
        </div>


        <div class="col-md-6">
            <?php if(isset($data) && !empty($data)): ?>
                <span class="title"> * Tổng quan</span>

                <?php
                $sum_rose = 0;
                $sum_revenue = 0;
                foreach ($data->rawData as $order){
                    $sum_revenue+= $order->revenue;
                    $sum_rose+= $order->rose;
                }
                ?>

                <?php $this->widget('booster.widgets.TbGridView', array(
                    'dataProvider'      => $data,
//                'filter'            => $model,
                    'template'          => '{items}',
                    'type'         => 'striped bordered  consended ',
                    'htmlOptions'  => array(
                        'class' => 'tbl_style',
                        'style' => 'padding-top: 10px',
                    ),
                    'columns'           => array(
                        array(
                            'header'        => Yii::t('tourist/label','type'),
                            'type'          => 'raw',
                            'value'         => function($data){
                                $value = '';
                                switch ($data->campaign_category_id){
                                    case AFTActions::CAMPAIGN_CATEGORY_ID_SIM:
                                        $value = 'Bán SIM';
                                        break;
                                    case AFTActions::CAMPAIGN_CATEGORY_ID_PACKAGE:
                                        $value = 'Bán gói';
                                        break;
                                    case AFTActions::CAMPAIGN_CATEGORY_ID_CONSUME:
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

            <?php endif; ?>
        </div>

        <div class="col-md-12">

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
                        'htmlOptions' => array('style' => 'margin-top: 30px', 'class' => 'site_manager')
                    )
                );
                ?>

            <?php endif; ?>

        </div>


    </div>
</div>




