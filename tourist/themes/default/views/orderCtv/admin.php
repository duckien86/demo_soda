<?php
/**
 * @var $this OrderCtvController
 * @var $model TOrders
 */

$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'list_order');
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'list_order'),
);
?>
<div id="order">
    <?php $this->widget('booster.widgets.TbGridView', array(
        'id'                => 'torders-grid',
        'dataProvider'      => $model->searchOrderCtv(),
        'filter'            => $model,
        'template'          => '{items}{summary}{pager}',
        'afterAjaxUpdate'   => 'reinstallDatePicker',
        'itemsCssClass'     => 'table table-bordered table-striped table-hover responsive-utilities table-order-manage',
        'htmlOptions'       => array(
            'style' => 'padding-top: 0',
        ),
        'columns'           => array(
            array(
                'name'        => 'code',
                'value'       => 'CHtml::encode($data->code)',
                'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'promo_code',
                'value'       => 'CHtml::encode($data->promo_code)',
                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'header'      => Yii::t('tourist/label','orderer'),
                'name'        => 'orderer_name',
                'value'       => 'CHtml::encode($data->orderer_name)',
            ),
//            array(
//                'name'        => 'receiver_name',
//                'value'       => 'CHtml::encode($data->receiver_name)',
//            ),
//            array(
//                'name'        => 'orderer_phone',
//                'value'       => 'CHtml::encode($data->orderer_phone)',
//            ),
            array(
                'name'        => 'create_time',
                'filter'      => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'          => $model,
                    'attribute'      => 'create_time',
                    'language'       => 'vi',
                    'htmlOptions'    => array(
                        'class' => 'form-control',
                    ),
                    'defaultOptions' => array(
                        'showOn'            => 'focus',
                        'dateFormat'        => 'dd/mm/yy',
                        'showOtherMonths'   => TRUE,
                        'selectOtherMonths' => TRUE,
                        'changeMonth'       => TRUE,
                        'changeYear'        => TRUE,
                        'showButtonPanel'   => TRUE,
                    )
                ), TRUE),
                'value'       => 'date("d/m/Y",strtotime($data->create_time))',
            ),
            array(
                'header'      => Yii::t('tourist/label','paid'),
                'value'       => function($data){
                    $value = '';
                    if($data->status >= TOrders::ORDER_APPROVED && TFiles::getFile(TFiles::OBJECT_FILE_ACCEPT_PAYMENT, $data->id) != null){
                        $value = Yii::t('tourist/label','state_paid');
                    }else{
                        $value = Yii::t('tourist/label','state_unpaid');
                    }
                    return $value;
                },
                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'width:80px;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'status',
                'filter'      => CHtml::activeDropDownList($model, 'status', TOrders::getStatusOrders(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                'value'       => 'CHtml::encode(TOrders::getOrdersStatusLabel($data))',
            ),
            array(
                'header'      => Yii::t('tourist/label', 'report'),
                'type'        => 'raw',
                'value'       => function ($data) {
                    $class = 'btn-report';
                    $icon = '<i class="fa fa-bar-chart"></i>';
                    if($data->status >= TOrders::ORDER_ASSIGNED){
                        $href = Yii::app()->createUrl("orderCtv/report", array("id"=>$data->id));
                    }else{
                        $href = 'javascript:void(alert("'.Yii::t('tourist/message','order_has_no_report', array('{code}'=>$data->code)).'"));';
                        $class .= ' disabled';
                    }
                    return CHtml::link($icon, $href, array(
                        'class' => $class,
                        'style' => 'font-size: 18px',
                        'data-toggle' => 'tooltip',
                        'data-original-title' => Yii::t('tourist/label', 'report'),
                    ));
                },
                'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px; min-width: 80px'),
                'headerHtmlOptions' => array('style' => 'text-align:center'),
            ),
//            array(
//                'header'      => Yii::t('tourist/label', 'report'),
//                'template'    => '{report}',
//                'buttons'     => array(
//                    'report' => array
//                    (
//                        'label'   => '<i class="fa fa-bar-chart"></i>',
//                        'options' => array(
//                            'title' => Yii::t('tourist/label', 'report'),
//                            'style' => 'cursor:pointer; font-size: 18px',
//                        ),
//                        'url'     => function($data){
//                            if($data->status >= TOrders::ORDER_ASSIGNED){
//                                return Yii::app()->createUrl("orderCtv/report", array("id"=>$data->id));
//                            }else{
//                                return 'javascript:void(alert("'.Yii::t('tourist/message','order_has_no_report', array('{code}'=>$data->code)).'"));';
//                            }
//                        },
////                        'visible' => '$data->getBtnReport()',
//                    ),
//                ),
//                'class'       => 'booster.widgets.TbButtonColumn',
//                'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px; min-width: 80px'),
//            ),
            array(
                'header'      => Yii::t('tourist/label', 'detail'),
                'template'    => '{view}{update}{upload}',
                'buttons'     => array(
                    'update' => array(
                        'visible' => '$data->getBtnUpdate()',
                    ),
                    'upload' => array(
                        'label'   => '<i class="fa fa-file"></i>',
                        'options' => array(
                            'title' => Yii::t('tourist/label', 'upload_file_sim'),
                            'style' => 'cursor:pointer; font-size: 16px',
                        ),
                        'url'     => function($data){
                            return Yii::app()->createUrl('orderCtv/uploadMoreSim', array('id' => $data->id));
                        },
                        'visible' => '$data->getBtnUploadFileSim()',
                    )
                ),
                'class'       => 'booster.widgets.TbButtonColumn',
                'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px; min-width: 80px'),
            ),
        )
    ));
    //reinstall datePicker after update ajax
    Yii::app()->clientScript->registerScript('re-install-date-picker', "
        function reinstallDatePicker(id, data) {
            $('#TOrders_create_time').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
        }
    ");
    ?>
</div>
<style>
    th, th a.sort-link {
        color: black;
    }
</style>





