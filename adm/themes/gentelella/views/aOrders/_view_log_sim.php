<div class="x_panel">
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'order-state-grid',
                'dataProvider'  => $logs_sim,
                'enableSorting' => FALSE,
                'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'create_date',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'msisdn',
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return $data->getStatus($data->status);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'user_id',
                        'type'        => 'raw',
                        'value'       => function ($data) {

                            return $data->getUserName($data->user_id, $data->type_user);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'type_user',
                        'value'       => function ($data) {
                            return $data->getUserType($data->user_id);
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