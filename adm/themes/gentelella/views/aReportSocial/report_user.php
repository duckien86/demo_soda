<div class="x_panel">
    <div class="x_title">
        <h3>Báo cáo thành viên</h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12 col-xs-6">
            <?php $this->renderPartial('_search_report', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
        <?php if (isset($data) && !empty($data)):
            ?>
            <div class="col-md-12 col-xs-12">
                <div class="table-responsive" id="table_renueve_sim">
                    <div class="excel-button">
                        <form method="post"
                              action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/socialUser'); ?>"
                              name="fday">
                            <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                            <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                            <input type="hidden" name="excelExport[status]" value="<?php echo $form->status ?>">
                            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                        </form>
                    </div>
                    <?php $this->widget('booster.widgets.TbGridView', array(
                        'id'            => 'report-user-grid',
                        'dataProvider'  => $data,
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'columns'       => array(
                            array('header' => 'STT',
                                  'value'  => '++$row',
                            ),
                            array(
                                'name'        => "Tên đăng nhập",
                                'value'       => function ($data) {
                                    return CHtml::encode($data['username']);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;'),
                            ),
                            array(
                                'name'        => 'Tổng số like',
                                'type'        => 'html',
                                'value'       => function ($data) {
                                    return CHtml::encode($data['total_like']);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'name'        => 'Tổng số bình luận',
                                'type'        => 'html',
                                'value'       => function ($data) {
                                    return CHtml::encode($data['total_comment']);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'name'        => 'Tổng số bài đăng',
                                'type'        => 'html',
                                'value'       => function ($data) {
                                    return CHtml::encode($data['total_post']);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'name'        => 'Số lần vi phạm',
                                'type'        => 'html',
                                'value'       => function ($data) {
                                    return CHtml::encode($data['total_sub_point']);

                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'name'        => 'Tổng điểm đã đổi quà',
                                'type'        => 'html',
                                'value'       => function ($data) {
                                    return CHtml::encode($data['sum_redeem']);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'name'        => 'Cấp độ',
                                'value'       => function ($data) {
                                    return Chtml::encode(ACustomers::getLevel($data['total_sub_point']));
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'header'        => 'Tổng điểm đang có',
                                'value'       => function ($data) {
                                    return Chtml::encode($data['current_point']);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:right;'),
                            ),
                            array(
                                'name'        => 'Trạng thái',
                                'value'       => function ($data) {
                                    $result = '';
                                    if ($data['status'] == ACustomers::ACTIVE) {
                                        $result = "KÍCH HOẠT";
                                    } else {
                                        $result = "ẨN";
                                    }

                                    return Chtml::encode($result);
                                },
                                'htmlOptions' => array('style' => 'vertical-align:middle;text-align:center;'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<style>
    #table_renueve_sim {
        margin-top: 0px !important;
    }

    .title {
        margin-top: 50px;
    }
</style>
