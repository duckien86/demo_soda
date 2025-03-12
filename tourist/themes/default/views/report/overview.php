<?php
/**
 * @var $this ReportController
 * @var $model TReport
 * @var $data CArrayDataProvider
 * @var $data_detail CArrayDataProvider
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'report_overview');
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'report_overview'),
);
?>

<div class="report">

    <?php echo $this->renderPartial('/report/_search_overview', array('model' => $model))?>

    <?php if(isset($data) && !empty($data)): ?>

    <div class="row">
        <div class="col-sm-8">
            <div class="title">
                <h5>* Tổng quan</h5>
            </div>
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
                        'header'        => 'Tổng đơn hàng',
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center;'
                        )
                    ),
                    array(
                        'header'        => 'Tổng đơn hàng tạm dừng',
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total_fails,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center'
                        )
                    ),
                    array(
                        'header'        => 'Tổng đơn hàng hoàn thành',
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total_success,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center:'
                        )
                    ),
                )
            )); ?>
        </div>
    </div>

    <?php endif; ?>

    <?php if( (isset($data_detail) && !empty($data_detail)) && $model->on_detail == 'on'): ?>

    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">

            <?php if(!empty($data_detail->rawData)) : ?>
            <div class="right">
                <form method="post" action="<?php echo Yii::app()->createUrl('excelExport/reportDetailOverview')?>" target="_blank">
                    <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
                    <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date?>"/>
                    <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date?>"/>
                    <?php echo CHtml::submitButton('Xuất ra Excel', array('style' => 'float: right; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
            </div>
            <?php endif; ?>

            <div class="title">
                <h5>* Chi tiết</h5>
            </div>
            <?php $this->widget('booster.widgets.TbGridView', array(
                'dataProvider'      => $data_detail,
                'filter'            => $model,
                'template'          => '{summary}{items}{pager}',
                'itemsCssClass'     => 'table table-bordered table-striped table-hover responsive-utilities table-order-manage',
                'htmlOptions'       => array(
                    'style' => 'padding-top: 5px',
                ),
                'columns'           => array(
                    array(
                        'header'        => 'STT',
                        'filter'        => false,
                        'type'          => 'raw',
                        'value'         => '++$row',
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center; text-align: center;'
                        ),
                        'headerHtmlOptions' => array(
                            'style' => 'text-align: center;'
                        )
                    ),
//                    array(
//                        'header'        => Yii::t('tourist/label','customer'),
//                        'type'          => 'raw',
//                        'value'         => function($data){
//                            $value = '';
//                            $user = TUsers::getUserByContract($data->contract_id);
//                            if(!empty($user)){
//                                if($user->user_type == TUsers::USER_TYPE_CTV){
//                                    $arr = explode('@',$user->username);
//                                    $value = $arr[0];
//                                }else{
//                                    $value = $user->username;
//                                }
//                            }
//                            return $value;
//                        },
//                        'htmlOptions'   => array(
//                            'style' => 'vertical-align: center'
//                        )
//                    ),
                    array(
                        'header'        => Yii::t('tourist/label','order_id'),
                        'filter'        => CHtml::activeTextField($model,'order_code',array('class' => 'form-control','id' => ''))
                            . CHtml::activeHiddenField($model,'start_date', array('id' => ''))
                            . CHtml::activeHiddenField($model,'end_date', array('id' => ''))
                            . CHtml::activeHiddenField($model,'status', array('id' => ''))
                            . CHtml::activeHiddenField($model,'on_detail', array('id' => '')),
                        'type'          => 'raw',
                        'value'         => function($data){
                            return $data->code;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center'
                        )
                    ),
                    array(
                        'header'        => 'Thời gian đặt hàng',
                        'filter'        => false,
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = date('d/m/Y H:i:s', strtotime($data->create_time));
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center'
                        )
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','total_sim_output'),
                        'filter'        => false,
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total_sim,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center'
                        )
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','total_package_output'),
                        'filter'        => false,
                        'type'          => 'raw',
                        'value'         => function($data){
                            $value = number_format($data->total_package,0,',','.');
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center'
                        )
                    ),
                    array(
                        'header'        => Yii::t('tourist/label','status'),
                        'filter'        => false,
                        'type'          => 'raw',
                        'value'         => function($data){
                            $class = TOrders::getStatusLabelClass($data->status);
                            $status = TOrders::getStatusLabel($data->status);
                            $value = "<span class='$class'>$status</span>";
                            return $value;
                        },
                        'htmlOptions'   => array(
                            'style' => 'vertical-align: center'
                        )
                    ),

                )
            )); ?>

        </div>
    </div>

    <?php endif; ?>

</div>



