<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'order_change') => array('admin'),
    );

?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('cskh/menu', 'order_change'); ?></h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_search_change', array('model' => $model_search, 'model_validate' => $model)); ?>

    <?php if (isset($show) && $show == TRUE): ?>
        <div class="x_content">

            <div class="table-responsive tbl_style center">
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'atraffic-grid',
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'dataProvider'  => $model->searchChange(),
                    'filter'        => $model,
                    'enableSorting' => FALSE,
                    'type'          => 'post',
                    'columns'       => array(

                        array(
                            'name'        => 'province_code',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                return CHtml::encode(ATraffic::model()->getProvince($data->province_code));
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'name'        => 'sale_office_code',
                            'filter'      => FALSE,
                            'value'       => function ($data) {
                                $sale = SaleOffices::model()->getSaleOfficesByOrder($data->id);

                                return CHtml::encode($sale);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'name'        => 'id',
                            'value'       => function ($data) {
                                return CHtml::encode($data->id);
                            },
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:130px;'),
                        ),
                        array(
                            'name'        => 'item_name',
                            'filter'      => FALSE,
                            'value'       => 'CHtml::encode($data->item_name)',
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'name'        => 'phone_contact',
                            'filter'      => FALSE,
                            'value'       => 'CHtml::encode($data->phone_contact)',
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'name'        => 'full_name',
                            'filter'      => FALSE,
                            'value'       => 'CHtml::encode($data->full_name)',
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'name'        => 'create_date',
                            'filter'      => FALSE,
                            'value'       => 'CHtml::encode($data->create_date)',
                            'htmlOptions' => array('style' => 'text-align: left;word-break: break-word;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header'      => '',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                return CHtml::link('Điều chuyển', 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_" . $data->id, 'style' => 'color:blue;',
                                          'onclick'     => 'getFormChange("' . $data->id . '")'));
                            },
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),

                        array(
                            'template'    => '{view}',
                            'buttons'     => array(
                                'view' => array(
                                    'options' => array('target' => '_blank'),
                                ),
                            ),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '50px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                        ),
                    ),
                )); ?>
            </div>
            <div class="popup_data">
                <?php if (isset($result) && isset($id)): ?>
                    <?= $result ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });

    var modal_pagintion = 'modal_' + <?= isset($id) ? $id : 1 ?>;
    $('#' + modal_pagintion).modal('show');

    function getFormChange(id) {
        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aChanges/showForm') ?>',
            crossDomain: true,
            data: {
                order_id: id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function (result) {
                $('.modal-backdrop').remove();
                $('.popup_data').html(result);

                var modal_id = 'modal_' + id;
                $('#' + modal_id).modal('show');

                return false;
            }
        });
    }


</script>
<style type="text/css">
    .search_enhance {
        display: none;
    }

    #thutien_overview th {
        width: 300px !important;
    }
</style>