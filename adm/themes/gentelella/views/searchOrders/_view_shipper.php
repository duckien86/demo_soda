<div class="x_panel">
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'order-state-grid',
                'dataProvider' => $order_shipper,
                'columns'      => array(
                    array(
                        'name'        => 'username',
                        'value'       => function ($data) {
                            return CHtml::encode($data->username);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'full_name',
                        'value'       => function ($data) {
                            return CHtml::encode($data->full_name);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone_1',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode($data->phone_1);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone_2',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode($data->phone_2);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'address_detail',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode($data->address_detail);
                        },
                        'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'email',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode($data->email);
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