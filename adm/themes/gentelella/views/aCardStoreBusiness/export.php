<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model AFTOrders
 */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_card_store_business'),
    Yii::t('adm/menu', 'card_store_business_export') => array('export'),
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'card_store_business_export') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="pull-right">
        <?php echo CHtml::link(CHtml::encode(Yii::t('adm/label','export_card')), Yii::app()->createUrl('aCardStoreBusiness/create'), array(
            'class' => 'btn btn-warning'
        ));?>
    </div>

    <?php echo $this->renderPartial('/aCardStoreBusiness/_filter_area_export', array('model' => $model)) ?>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'                => 'cardbusinessexport-grid',
                'dataProvider'      => $model->searchExport(),
                'filter'            => $model,
                'itemsCssClass'     => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'      => 'STT',
                        'type'        => 'raw',
                        'value'       => '++$row',
                        'htmlOptions' => array(
                            'style'     => 'width:50px;vertical-align:middle;',
                        ),
                    ),
                    array(
                        'header'      => Yii::t('adm/label', 'agency'),
                        'name'        => 'company',
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            $user = AFTUsers::getUserByContract($data->contract_id);
                            if($user){
                                $value = $user->company;
                            }
                            return CHtml::encode($value);
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'code',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->code);
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'contract_code',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode(AFTContracts::model()->getContractCode($data->contract_id));
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','create_date'),
                        'name'        => 'create_time',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y H:i:s', strtotime($data->create_time));
                        },
                        'htmlOptions' => array('style' => 'width:160px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList($model,'status', AFTOrders::getListStatusOrderCard(), array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
                        )),
                        'value'       => function ($data) {
                            $class = AFTOrders::getActiveStatusClassOrderCard($data->status);
                            $event = '';
                            if($data->status == AFTOrders::ORDER_CARD_CREATE){
                                $event = "confirmOrderCard($data->id, '$data->code')";
                            }else{
                                $event = "showModalExport($data->id)";
                            }
                            $text = CHtml::encode(AFTOrders::getStatusOrderCard($data->status));
                            $value = CHtml::link($text, 'javascript:void(0);', array(
                                'class'       => $class,
                                'onclick'     => $event,
                                'style'       => 'width: 100%'
                            ));
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'width: 120px; vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label', 'detail'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view}',
                        'buttons'      => array(
                            'view'  => array(
                                'options' => array(
                                    'target' => '_blank',
                                ),
                                'url' => function($data){
                                    return Yii::app()->createUrl('aCardStoreBusiness/viewExport', array('id' => $data->id));
                                }
                            ),
                        ),
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'width:100px;text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            ));
            ?>
        </div>

    </div>
</div>

<?php $this->renderPartial('/aCardStoreBusiness/_modal_export')?>

<script>
    function confirmOrderCard(order_id, order_code){
        if(confirm("Xác nhận đơn hàng " + order_code)){
            $.ajax({
                url: '<?php echo Yii::app()->controller->createUrl('aCardStoreBusiness/confirm')?>',
                type: 'post',
                dataType: 'json',
                data: {
                    order_id : order_id,
                    YII_CSRF_TOKEN : '<?php echo Yii::app()->request->csrfToken?>'
                },
                success: function (result) {
                    if(!result['error']){
                        $.fn.yiiGridView.update('cardbusinessexport-grid');
                    }
                    alert(result['msg']);

                }
            });
        }
    }

    function showModalExport(order_id)
    {
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('aCardStoreBusiness/getModalExportContent')?>',
            type: 'post',
            dataType: 'json',
            data: {
                order_id : order_id,
                YII_CSRF_TOKEN : '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (result) {
                $('#modal_export_card_store .modal-title').html(result.data_title);
                $('#modal_export_card_store .modal-body').html(result.data_html);
                $('#modal_export_card_store').modal('show');
            }
        });

    }
</script>
