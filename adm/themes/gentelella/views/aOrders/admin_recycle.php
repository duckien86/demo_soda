<?php
/* @var $this AOrdersController */
/* @var $model AOrders */

$this->breadcrumbs = array(
    Yii::t('adm/menu','search'),
    Yii::t('adm/menu','order'),
    'ĐH rác' => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= 'Danh sách đơn hàng rác' ?></h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area_recycle', array('model' => $model_search, 'model_validate' => $model)); ?>

    <div class="x_content">

        <div class="row note">
            <div class="left">
                <span class="prepaid"></span> Trả trước <br/>
                <span class="postpaid"></span> Trả sau
            </div>
        </div>

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'aorders-grid',
                'dataProvider'  => $model->search_recycle(TRUE),
                'filter'        => $model,
                'enableSorting' => FALSE,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:140px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone_contact',
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'sim',
                        'value'       => function ($data) {
                            return $data->sim;
                        },
                        'cssClassExpression' => ' 
                            $data->type_sim == 1 ? "prepaid" : "postpaid" 
                        ',
                        'htmlOptions' => array('style' => 'width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'full_name',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:110px;'),
                    ),
                    array(
                        'name'        => 'address_detail',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return $data->getAddress();
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:150px;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'width:100px;text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),

                    array(
                        'name'        => 'status_end',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return Chtml::encode(AOrders::getStatus($data->id));
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                    ),
//                    array(
//                        'name'        => 'time_left',
//                        'type'        => 'raw',
//                        'filter'      => FALSE,
//                        'value'       => function ($data) {
//                            $status = AOrders::getStatus($data->id);
//                            if ($status != "Hoàn thành") {
//                                return CHtml::encode(AOrders::model()->getTimeLeft($data->create_date));
//                            } else {
//                                return "Hoàn thành";
//                            }
//                        },
//                        'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'Trạng thái GV',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            if ($data->delivery_type == 1) {
                                return CHtml::encode(AOrders::model()->getStatusTraffic($data->getTrafficStatus($data->id)));
                            } else {
                                return 'Nhận tại ĐGD';
                            }
                        },
                        'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'template'    => '{view}',
                        'buttons'     => array(
                            'view' => array(
                                'options' => array('target' => '_blank'),
                            ),
                        ),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>

<style>
    .prepaid{
        color: #ed0678;
    }
    .postpaid{
        color: #00a1e4;
    }
    .note{
        padding-left: 15px;
        padding-right: 15px;
    }
    .note span{
        display: inline-block;
        width: 16px;
        height: 10px;
    }
    .note span.prepaid{
        background: #ed0678;
    }
    .note span.postpaid{
        background: #00a1e4;
    }
</style>


<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
</script>