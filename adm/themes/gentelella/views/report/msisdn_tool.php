<div class="x_panel">
    <div class="x_title">
        <h3>Tra cứu thuê bao</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_msisdn_tool', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($data) && !empty($data) && $post = 1):

            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="title">
                        <h5> * Tra cứu thuê bao</h5>
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
                                    'header'      => 'Số thuê bao',
                                    'value'       => function ($data) {
                                        $return = $data['MSISDN'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px',
                                    ),
                                ),
                                array(
                                    'header'      => 'Loại TB',
                                    'value'       => function ($data) {

                                        $return = $data['LOAI_TB'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Ngày hòa mạng',
                                    'value'       => function ($data) {
                                        $return = date('Y-m-d',strtotime($data['NGAY_HM']));

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:200px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Mã tỉnh',
                                    'value'       => function ($data) {
                                        $return = $data['MATINH'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Trạng thái',
                                    'value'       => function ($data) {
                                        $return = ReportForm::getStatusActive($data['STATUS']);

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                            ),
                        ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>