<?php
/**
 * @var $this AFTReportController
 * @var $model AFTReport
 * @var $data CArrayDataProvider
 * @var $data_detail CArrayDataProvider
 */
?>

<div class="x_panel">

    <div class="x_title">
        <h3><?php echo Yii::t('adm/menu', 'report_tourist') . ' - Sim KHDN' ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">

        <div class="col-md-12">
            <?php $this->renderPartial('_search', array('model' => $model)); ?>
        </div>

        <div class="col-md-6" style="margin-top: 20px">
            <?php if (isset($data) && !empty($data)): ?>
                <span class="title"> * Tổng quan:</span>
                <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'dataProvider' => $data,
                    'type'         => 'striped bordered  consended ',
                    'template'     => '{items}',
                    'htmlOptions'  => array(
                        'class' => 'tbl_style',
                        'id'    => 'thongsoChung',
                        'style' => 'margin-top: 20px;'
                    ),
                    'columns'      => array(
                        array(
                            'header'      => 'Số lượng',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $value = number_format($data->total,0,',','.');
                                return $value;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px;text-align:right;',
                            ),
                        ),
                        array(
                            'header'      => 'Tổng thành công',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $value = number_format($data->total_success,0,',','.');
                                return $value;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px;text-align:right;',
                            ),
                        ),
                        array(
                            'header'      => 'Tổng thất bại',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $value = number_format($data->total_fails,0,',','.');
                                return $value;
                            },
                            'htmlOptions' => array(
                                'style' => 'width:100px;text-align:right;',
                            ),
                        ),
                    ),
                ));
                ?>

            <?php endif; ?>

        </div>

        <?php if (isset($data_detail) && !empty($data_detail)):?>
            <div class="col-md-12" style="margin-top: 20px">
            <span class="title"> * Chi tiết:</span>
            <?php
            $this->widget('booster.widgets.TbGridView', array(
                'dataProvider'  => $data_detail,
                'filter'        => $model,
                'type'          => 'striped bordered  consended ',
                'enableSorting' => FALSE,
                'htmlOptions'   => array(
                    'class' => 'tbl_style',
                    'id'    => 'order-tourist-renueve',
                ),
                'columns'       => array(
                    array(
                        'header'      => 'STT',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => '++$row',
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;')
                    ),
                    array(
                        'header'      => 'Mã đơn hàng',
                        'filter'      => CHtml::activeTextField($model,'order_code', array('class' => 'form-control'))
                            . CHtml::activeHiddenField($model,'start_date', array('id' => ''))
                            . CHtml::activeHiddenField($model,'end_date', array('id' => ''))
                            . CHtml::activeHiddenField($model,'province_code', array('id' => ''))
                            . CHtml::activeHiddenField($model,'order_type', array('id' => ''))
                            . CHtml::activeHiddenField($model,'status_order', array('id' => ''))
                            . CHtml::activeHiddenField($model,'customer_id', array('id' => ''))
                        ,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return $data->code;
                        },
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: left; width: 160px'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: left;'),
                    ),
                    array(
                        'header'      => 'Số lượng',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $value  = number_format($data->total, 0, ',', '.');
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: right; width: 160px'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                    ),
                    array(
                        'header'      => 'Tổng thành công',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $value  = number_format($data->total_success, 0, ',', '.');
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: right; width: 160px'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                    ),
                    array(
                        'header'      => 'Tổng thất bại',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $value  = number_format($data->total_fails, 0, ',', '.');
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: right; width: 160px'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                    ),
                    array(
                        'header'      => 'Trạng thái',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function ($data){
                            $status = AFTOrders::getStatusLabelOrderSim($data->status);
                            $class = AFTOrders::getStatusClassOrderSim($data->status);
                            $value = "<span class='$class'>$status</span>";
                            return $value;
                        },
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                    ),
                    array(
                        'header'      => 'Thao tác',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            if($data->user_type == AFTUsers::USER_TYPE_CTV){
                                $url = Yii::app()->createUrl('excelExport/exportDetailSimTourist', array(
                                    'show_contract' => FALSE
                                ));
                            }else{
                                $url = Yii::app()->createUrl('excelExport/exportDetailSimTourist', array(
                                    'show_contract' => TRUE
                                ));
                            }
                            $value = '<form method="post" action="' . $url . '" name="f" target="_blank">
                                    <input type="hidden" name="order_id" value="' . $data->id . '">
                                    <button name="submit" type="submit" class="btn btn-primary" value="Xuất Excel">Chi tiết</button>
                                </form>';

                            return $value;

                        },
                        'htmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                        'headerHtmlOptions' => array('style' => 'vertical-align: middle; text-align: center;'),
                    ),
                ),
            ));
            ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<script type="text/javascript">

</script>
