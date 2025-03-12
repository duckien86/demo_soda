<?php
    /* @var $this OrdersController */
    /* @var $orders */
    /* @var $msg */
?>

<?php if (isset($msg) && !empty($msg)): ?>
    <div class="help-block"><?= $msg; ?></div>
<?php endif; ?>
<?php if ($orders):
    ?>
    <div class="table-responsive center">
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'orders-grid',
            'dataProvider'  => $orders,
            'enableSorting' => FALSE,
            'columns'       => array(
                array(
                    'name'        => Yii::t('web/portal', 'order_id'),
                    'value'       => function ($data) {
                        return Chtml::encode($data['order_id']);
                    },
                    'htmlOptions' => array('style' => 'width:150px;vertical-align:middle;'),
                ),
                array(
                    'name'        => Yii::t('web/portal', 'amount'),
                    'value'       => function ($data) {
                        return number_format($data['total_price'], 0, '', '.') . 'đ';
                    },
                    'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => Yii::t('web/portal', 'create_date'),
                    'value'       => function ($data) {
                        return date('d/m/Y', strtotime($data['create_date']));
                    },
                    'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => Yii::t('web/portal', 'status'),
                    'type'        => 'raw',
                    'value'       => function ($data) {
                        return WOrders::getStatusLabel($data['status']);
                    },
                    'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => Yii::t('web/portal', 'address_detail'),
                    'value'       => function ($data) {
                        $address_detail = $data['address_detail'];
                        if ($data['district_code']) {
                            $address_detail .= '-' . $data['district_code'];
                        }
                        if ($data['province_code']) {
                            $address_detail .= '-' . $data['province_code'];
                        }

                        return CHtml::encode($address_detail);
                    },
                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                ),
                array(
                    'name'        => '',
                    'type'        => 'html',
                    'value'       => function ($data) {
                        return CHtml::link(Yii::t('web/portal', 'view'), Yii::app()->controller->createUrl('orders/detail', array('id' => $data['order_id'])));
                    },
                    'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                ),
            ),
        )); ?>
    </div>
<?php endif; ?>
