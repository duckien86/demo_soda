<?php
    /* @var $this CskhShipperController */
    /* @var $model CskhShipper */

    $this->breadcrumbs = array(
        'Quản lý shipper' => array('admin'),
        'Danh sách',
    );

    $this->menu = array(
        array('label' => Yii::t('cskh/menu', 'create'), 'url' => array('create')),
    );

    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cskh-shipper-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Doanh thu giao vận</h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_search', array('model' => $model_search, 'model_validate' => $model)); ?>
    <div class="x_content">
        <?php if ($show == 1): ?>
            <div class="col-md-4 col-xs-12 top_col">
                <span class="title"> * Báo cáo tổng quan:</span>

                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $data_overview,
                    'type'       => '',
                    'attributes' => array(

                        array(
                            'name'        => "total_order",
                            'value'       => function ($data) {
                                return Chtml::encode($data['total_order']);
                            },
                            'htmlOptions' => array('style' => 'text-align:right !important;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => "total_shipper",
                            'value'       => function ($data) {

                                return CHtml::encode($data['total_shipper']);
                            },
                            'htmlOptions' => array('style' => 'text-align:right !important;vertical-align:middle;'),
                            'visible'     => $model_search->id == ''
                        ),
                        array(
                            'name'        => "total_renueve_order",
                            'value'       => function ($data) {
                                if ($data['total_renueve_order'] != NULL) {
                                    return number_format(CHtml::encode($data['total_renueve_order'])) . " đ";
                                }

                                return "0đ";

                            },
                            'htmlOptions' => array('style' => 'text-align:right !important;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => "Tổng phí vận chuyển",
                            'value'       => function ($data) {
                                if ($data['total_order'] != NULL) {
                                    return number_format(CHtml::encode($data['total_order'] * 20000), 0, '', '.') . " đ";
                                }

                                return "0đ";

                            },
                            'htmlOptions' => array('style' => 'text-align:right !important;vertical-align:middle;'),
                        ),

                        array(
                            'name'        => "Tổng tiền hoa hồng",
                            'value'       => function ($data) {
                                if ($data['total_order'] != NULL) {
                                    return number_format(CHtml::encode($data['total_order'] * 15000), 0, '', '.') . " đ";
                                }

                                return "0đ";
                            },
                            'htmlOptions' => array('style' => 'text-align:right !important;vertical-align:middle;'),
                        ),

                    ),
                )); ?>
            </div>

        <?php endif; ?>
    </div>
</div>
<style>
    .table td {
        text-align: right;
    }
</style>