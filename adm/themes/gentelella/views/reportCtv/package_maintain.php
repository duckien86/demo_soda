<div class="x_panel">
    <div class="x_title">
        <h3>Hoa hồng duy trì gói cước</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_package', array('model' => $form)); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <?php if (isset($data_renueve) && !empty($data_renueve)): ?>
            <div class="col-md-6 top_col">
                <span class="title"> * Thống kê tổng quan:</span>
                <div class="table-responsive" id="table_renueve_sim">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= "Gói cước"; ?></td>
                            <td><?= "Sản lượng"; ?></td>
                            <td><?= "Doanh thu"; ?></td>
                            <td><?= "Hoa hồng"; ?></td>
                        </tr>
                        <?php if (isset($data_renueve) && !empty($data_renueve)): ?>
                            <?php
                            $total         = 0;
                            $total_renueve = 0;
                            foreach ($data_renueve as $renueve) {
                                $total += isset($renueve['total_package']) ? $renueve['total_package'] : 0;
                                $total_renueve += isset($renueve['total_renueve']) ? $renueve['total_renueve'] : 0;
                                ?>
                                <tr>
                                    <td><?= isset($renueve['package_name']) ? $renueve['package_name'] : ''; ?></td>
                                    <td style="text-align: right; display: none;"><?= isset($renueve['package_price']) ? number_format($renueve['package_price'], 0, "", ".") . " đ" : ''; ?></td>
                                    <td style="text-align: right;"><?= isset($renueve['total_package']) ? $renueve['total_package'] : ''; ?></td>
                                    <td><?= (isset($renueve['total_package']) && isset($renueve['package_price'])) ? $renueve['total_package'] * $renueve['package_price'] : ''; ?></td>
                                    <td style="text-align: right;"><?= isset($renueve['total_renueve']) ? number_format($renueve['total_renueve'], 0, "", ".") . " đ" : ''; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td>Tổng</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;"><?= number_format($total_renueve, 0, "", ".") . " đ" ?></td>
                            </tr>

                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if (isset($on_detail) && ($on_detail == TRUE)): ?>
        <div class="row" style="margin-top: 10px;">
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

                                    $return = $data->phone_contact;

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
                                'header'      => 'Tên gói',
                                'value'       => function ($data) {

                                    $return = $data->package_name;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px',
                                ),
                            ),
                            array(
                                'header'      => 'Giá gói',
                                'value'       => function ($data) {

                                    $return = number_format($data->package_price, 0, "", ".") . " đ";

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
</div>

