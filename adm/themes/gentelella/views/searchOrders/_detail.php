<div class="x_panel">
    <div class="x_content">
        <div class="row">
            <div class="col-md-12">
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'           => 'order-detail-grid',
                    'dataProvider' => $order_detail,
                    'columns'      => array(
                        array(
                            'name'        => "Loại sản phẩm",
                            'value'       => function ($data) {
                                return Chtml::encode(AOrderState::getNameType($data['type']));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('adm/label', 'item_name'),
                            'value'       => function ($data) {
                                return Chtml::encode($data['item_name']);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('web/portal', 'quantity'),
                            'value'       => function ($data) {
                                return Chtml::encode($data['quantity']);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('web/portal', 'price'),
                            'value'       => function ($data) {
                                return number_format($data['price'], 0, "", ".") . 'đ';
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),

                    ),
                )); ?>
            </div>
            <div class="space_30"></div>
        </div>
    </div>
</div>