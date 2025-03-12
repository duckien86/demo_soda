<div class="x_panel">
    <div class="x_title">
        <h3>Hoa hồng giới thiệu CTV</h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_introduce', array('model' => $form)); ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($data) && !empty($data)):
            ?>
            <div class="col-md-6">
                <div class="title">
                    <h5> * Doanh thu tổng quan</h5>
                </div>
                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data,

                        'type'        => 'striped bordered  consended ',
                        'htmlOptions' => array(
                            'class' => 'tbl_style',
                            'id'    => 'thongsoChung',
                        ),
                        'columns'     => array(
                            array(
                                'header'      => 'Loại',
                                'value'       => function ($data) {
                                    $return = $data->item_type;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Sản lượng',
                                'value'       => function ($data) {

                                    $return = $data->total;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px',
                                ),
                            ),
                            array(
                                'header'      => 'Hoa hồng',
                                'value'       => function ($data) {

                                    $return = number_format($data->total_renueve, 0, "", ".") . " đ";

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:right;',
                                ),
                            ),

                        ),
                    ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
