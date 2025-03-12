<div class="x_panel">
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'order-state-grid',
                'dataProvider' => $order_state,
                'enableSorting' => FALSE,
                'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'order_id',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'confirm',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return $data->getConfirmLabel($data->confirm);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'paid',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return $data->getPaidLabel($data->paid);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'delivered',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return $data->getDeliveredLabel($data->delivered);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'note',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>