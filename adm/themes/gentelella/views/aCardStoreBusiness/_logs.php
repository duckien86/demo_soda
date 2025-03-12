<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model AFTOrders
 * @var $model_logs AFTLogs
 */
?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'aftorders-logs-grid',
    'dataProvider'  => $model_logs->search($model->id),
    'filter'        => $model_logs,
    'enableSorting' => FALSE,
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'columns'       => array(

        array(
            'header'      => 'Mã đơn hàng',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function($data){
                $value = '';
                if($data->object_name == AFTLogs::OBJECT_ORDER){
                    $value = AFTOrders::model()->getCodeOfOrders($data->object_id);
                }
                return $value;
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),

        array(
            'header'      => 'Trạng thái trước',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                $return = '';
                if ($data->data_json_before) {
                    $return = CJSON::decode($data->data_json_before);
                    if ($return) {
                        if (isset($return['status'])) {
                            $return = AFTOrders::getNameStatusOrders($return['status']);
                        }
                    }
                }

                return CHtml::encode($return);
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'header'      => 'Trạng thái sau',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                $return = '';
                if ($data->data_json_after) {
                    $return = CJSON::decode($data->data_json_after);
                    if ($return) {
                        if (isset($return['status'])) {
                            $return = AFTOrders::getActiveStatusOrderCard($return['status']);
                        }
                    }
                }

                return CHtml::encode($return);
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'header'      => 'Thời gian',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->create_time)',
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),
        array(
            'header'      => 'Người thực hiện',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                $result = '';
                if ($data->active_by != '') {

                    $user = User::model()->findByAttributes(array('id' => $data->active_by));
                    if ($user) {
                        $result = $user->username;
                    }
                }

                return CHtml::encode($result);
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
        ),
    ),
)); ?>