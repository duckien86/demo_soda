<div class="x_panel">

    <div class="x_title">
        <h3>Hoa hồng sim số</h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?= $this->renderPartial('_search', array('model' => $form)); ?>
        </div>
    </div>

    <?php if (isset($data_renueve) && !empty($data_renueve)): ?>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-4">
                <span class="title"> * Thống kê tổng quan:</span>
                <div class="table-responsive" id="table_renueve_sim">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        </thead>
                        <tbody>
                        <?php if (isset($data_renueve) && !empty($data_renueve)): ?>
                            <?php
                            $total         = 0;
                            $total_renueve = 0;
                            foreach ($data_renueve as $renueve) {
                                $total += isset($renueve['total_sim']) ? $renueve['total_sim'] : 0;
                                $total_renueve += isset($renueve['total_renueve']) ? $renueve['total_renueve'] : 0;
                                ?>
                                <tr>
                                    <td><?= isset($renueve['type']) ? $model->getTypeSim($renueve['type']) : ''; ?></td>
                                    <td style="text-align: right;"><?= isset($renueve['total_sim']) ? $renueve['total_sim'] . " sim" : ''; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td>Tổng</td>
                                <td style="text-align: right;"><?= $total . " sim" ?></td>
                            </tr>
                            <tr>
                                <td>Doanh thu</td>
                                <td style="text-align: right;"><?= number_format($total_renueve, 0, "", ".") . " đ" ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if (isset($on_detail) && ($on_detail == TRUE)): ?>
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12 top_col">
                    <span class="title"> * Danh sách chi tiết:</span>
                    <?php
                        $this->widget('booster.widgets.TbGridView', array(
                            'dataProvider' => $data_renueve_detail,

                            'type'        => 'striped bordered  consended ',
                            'htmlOptions' => array(
                                'class' => 'tbl_style',
                                'id'    => 'thongsoChung',
                            ),
                            'columns'     => array(
                                array(
                                    'header'      => 'STT',
                                    'value'       => '++$row',
                                    'htmlOptions' => array('width' => '50px', 'class' => 'text-center'),
                                ),
                                array(
                                    'header'      => 'Số điện thoại',
                                    'value'       => function ($data) {

                                        $return = $data->msisdn;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px',
                                    ),
                                ),
                                array(
                                    'header'      => 'Ngày mua',
                                    'value'       => function ($data) {

                                        $return = $data->create_date_sell;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px',
                                    ),
                                ),
                                array(
                                    'header'      => 'Khách hàng',
                                    'value'       => function ($data) {

                                        $return = $data->customer_name;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px',
                                    ),
                                ),
                                array(
                                    'header'      => 'Mã CTV giới thiệu',
                                    'value'       => function ($data) {

                                        $return = $data->invitation;

                                        return $return;
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px',
                                    ),
                                ),

                            ),
                        ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
