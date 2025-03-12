<div class="x_panel">

    <div class="x_title">
        <h3>Báo cáo diễn đàn</h3>
    </div>
    <div class="clearfix"></div>


    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
        <?php if (isset($data) && !empty($data)):
            ?>
            <div class="col-md-12 top_col">

                <div class="table-responsive" id="table_renueve_sim">
                    <form method="post"
                          action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/socialIndex'); ?>"
                          name="fday">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[customer_id]"
                               value="<?php echo $form->customer_id ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                    <?php $this->widget('booster.widgets.TbGridView', array(
                        'id'            => 'report-social-grid',
                        'dataProvider'  => $data,
                        'enableSorting' => FALSE,
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'columns'       => array(
                            array('header' => 'STT',
                                  'value'  => '++$row',
                            ),
                            array(
                                'name'        => 'username',
                                'filter'      => TRUE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->username)',
                                'htmlOptions' => array('nowrap' => 'nowrap'),
                            ),
                            array(
                                'name'        => 'phone',
                                'filter'      => TRUE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->phone)',
                                'htmlOptions' => array('nowrap' => 'nowrap'),
                            ),
                            array(
                                'name'        => 'Ngày tham gia',
                                'filter'      => FALSE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->create_time)',
                                'htmlOptions' => array('nowrap' => 'nowrap'),
                            ),
                            array(
                                'name'        => 'Tổng điểm tích lũy',
                                'filter'      => FALSE,
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    if ($data->bonus_point != '') {
                                        return CHtml::encode($data->bonus_point);
                                    } else {
                                        return 0;
                                    }
                                },
                                'htmlOptions' => array('nowrap' => 'nowrap'),
                            ),
                            array(
                                'name'        => 'Cấp độ thành viên',
                                'filter'      => FALSE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->getLevel($data->bonus_point))',
                                'htmlOptions' => array('nowrap' => 'nowrap'),
                            ),

                        ),
                    )); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>


