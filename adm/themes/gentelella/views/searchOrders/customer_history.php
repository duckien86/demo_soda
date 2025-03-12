<div class="x_panel">
    <div class="x_title">
        <h3>Tra cứu đơn hàng</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->renderPartial('_search', array('model' => $model)); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row border-row">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="x_content">
                    <?php if (isset($data) && !empty($data)):
                        ?>
                        <div class="row">
                            <?php $this->widget('booster.widgets.TbGridView', array(
                                'id'            => 'history-detail-grid',
                                'dataProvider'  => $data,
                                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                                'columns'       => array(
                                    array(
                                        'name'        => 'Mã đơn hàng',
                                        'value'       => function ($data) {
                                            return Chtml::encode($data['id']);
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'name'        => 'Khách hàng',
                                        'value'       => function ($data) {
                                            return Chtml::encode($data['full_name']);
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'name'        => 'Ngày đặt hàng',
                                        'value'       => function ($data) {
                                            return Chtml::encode($data['create_date']);
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'name'        => 'Kiểu thanh toán',
                                        'value'       => function ($data) {
                                            return Chtml::encode(AOrders::model()->getDeliveredType($data['payment_method']));
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'name'        => 'Tỉnh',
                                        'value'       => function ($data) {
                                            return Chtml::encode(AOrders::model()->getProvince($data['province_code']));
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'name'        => 'Quận huyện',
                                        'value'       => function ($data) {
                                            return Chtml::encode(AOrders::model()->getDistrict($data['district_code']));
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'name'        => 'Trạng thái',
                                        'value'       => function ($data) {
                                            return Chtml::encode(AOrders::getStatus($data['id']));
                                        },
                                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                                    ),
                                    array(
                                        'class'    => 'booster.widgets.TbButtonColumn',
                                        'template' => '{view}',
                                        'buttons'  => array(
                                            'view' => array(
                                                'label'   => '',
                                                'options' => array('target' => '_new'),
                                                'url'     => 'Yii::app()->createUrl("searchOrders/view", array("id"=>$data[id]))',
                                            ),
                                        ),

                                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                                    ),

                                ),
                            )); ?>
                        </div>
                    <?php else: ?>
                        <div class="not-found-data">
                            <?php echo "Không có dữ liệu !"; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

