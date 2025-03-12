<div class="x_panel">
    <div class="x_title">
        <h3>Báo cáo chi tiết thành viên</h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12 col-xs-6">
            <?php $this->renderPartial('_search_user', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
        <?php if (isset($data) && !empty($data)):
        ?>
        <div class="col-md-12 col-xs-12">
            <span class="title"> * Thống kê tổng quan:</span>
            <div class="table-responsive" id="table_renueve_sim">
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'report-social-grid',
                    'dataProvider'  => $data,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => "Tên đăng nhập",
                            'value'       => function ($data) {
                                echo CHtml::link($data['username'], 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_sub_point" . $data['sso_id'], 'style' => 'color:red;',
                                          'onclick'     => 'getDetail("' . $data['sso_id'] . '")'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
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
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Tổng số like',
                            'type'        => 'html',
                            'value'       => function ($data) {
                                echo CHtml::link($data['total_like'], 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_like" . $data['sso_id'], 'style' => 'color:red;',
                                          'onclick'     => 'getDetail("like","' . $data['sso_id'] . '")'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Tổng số bình luận',
                            'type'        => 'html',
                            'value'       => function ($data) {
                                echo CHtml::link($data['total_comment'], 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_comment" . $data['sso_id'], 'style' => 'color:red;',
                                          'onclick'     => 'getDetail("comment","' . $data['sso_id'] . '")'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Tổng số bài đăng',
                            'type'        => 'html',
                            'value'       => function ($data) {
                                echo CHtml::link($data['total_post'], 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_post" . $data['sso_id'], 'style' => 'color:red;',
                                          'onclick'     => 'getDetail("post","' . $data['sso_id'] . '")'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Số lần vi phạm',
                            'type'        => 'html',
                            'value'       => function ($data) {
                                echo CHtml::link($data['total_sub_point'], 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_sub_point" . $data['sso_id'], 'style' => 'color:red;',
                                          'onclick'     => 'getDetail("sub_point","' . $data['sso_id'] . '")'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Tổng điểm đã đổi quà',
                            'type'        => 'html',
                            'value'       => function ($data) {
                                echo CHtml::link($data['sum_redeem'], 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_redeem" . $data['sso_id'], 'style' => 'color:red;',
                                          'onclick'     => 'getDetail("redeem","' . $data['sso_id'] . '")'));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Cấp độ',
                            'value'       => function ($data) {
                                return Chtml::encode(ACustomers::getLevel($data['total_sub_point']));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                    ),
                )); ?>
            </div>
        </div>
        <div class="col-md-12 col-xs-12" style="margin-top: 25px;">
            <div class="table-responsive tbl_style center">
                <?php $this->widget(
                    'booster.widgets.TbTabs',
                    array(
                        'type'        => 'tabs',
                        'tabs'        => array(
                            array(
                                'label'   => Yii::t('adm/label', 'tab_likes_list'),
                                'content' => $this->renderPartial('_like_list', array('data_likes_list' => $data_likes_list), TRUE),
                                'active'  => TRUE,
                            ),
                            array(
                                'label'   => Yii::t('adm/label', 'tab_comment_list'),
                                'content' => $this->renderPartial('_comment_list', array('data_comment_list' => $data_comment_list), TRUE),
                            ),
                            array(
                                'label'   => Yii::t('adm/label', 'tab_post_list'),
                                'content' => $this->renderPartial('_post_list', array('data_post_list' => $data_post_list), TRUE),
                            ),
                            array(
                                'label'   => Yii::t('adm/label', 'tab_sub_point_list'),
                                'content' => $this->renderPartial('_sub_point_list', array('data_sub_point_list' => $data_sub_point_list), TRUE),
                            ),
                            array(
                                'label'   => Yii::t('adm/label', 'tab_redeem_list'),
                                'content' => $this->renderPartial('_redeem_list', array('data_redeem_list' => $data_redeem_list), TRUE),
                            ),
                            array(
                                'label'   => Yii::t('adm/label', 'tab_point_list'),
                                'content' => $this->renderPartial('_point_list', array('data_point_list' => $data_point_list), TRUE),
                            ),

                        ),
                        'htmlOptions' => array('class' => 'site_manager')
                    )
                );
                ?>
            </div>
            <div class="result-report-ajax">

            </div>
            <?php endif; ?>
        </div>

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

<script type="text/javascript">
    function getDetail(sso_id) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aReportSocial/getDetail')?>',
            crossDomain: true,
            data: {
                sso_id: sso_id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (result) {
                $('.result-report-ajax').html(result);
                var modal = "modal_" + sso_id;
                $('#' + modal).modal('show');
                return false;
            }
        });
    }
</script>
