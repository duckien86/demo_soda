<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/menu', 'complete_order'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget(
                'booster.widgets.TbTabs',
                array(
                    'type'        => 'tabs',
                    'tabs'        => array(
                        array(
                            'label'       => Yii::t('adm/label', 'register_info'),
                            'content'     => $this->renderPartial('_form_register_info',
                                array('complete_form'          => $complete_form,
                                      'complete_form_validate' => $complete_form_validate,
                                      'customer_type'          => $customer_type,
                                      'personal_id_type'       => $personal_id_type,
                                      'national'               => $national,
                                      'order_id'               => $order_id,
                                      'post'                   => $post,
                                      'provinces'              => $provinces,
                                      'order'                  => $order,
                                      'sim'                    => $sim,
                                      'package'                => $package,
                                      'shipper'                => $shipper,
                                      'user'                   => $user,

                                ), TRUE),
                            'active'      => ($tab == '_form_register_info') ? TRUE : FALSE,
                            'linkOptions' => array('id' => 'tab_form_register_info')
                        ),
                        array(
                            'label'       => Yii::t('adm/label', 'upload_complete'),
                            'content'     => $this->renderPartial('_form_upload',
                                array('upload_form'          => $upload_form,
                                      'upload_form_validate' => $upload_form_validate,
                                      'order_id'             => $order_id,
                                      'post'                 => $post,
                                    'msg' =>$msg,

                                ), TRUE),
                            'active'      => ($tab == '_form_upload') ? TRUE : FALSE,
                            'linkOptions' => array('id' => 'tab_form_upload')
                        ),
                    ),
                    'htmlOptions' => array('class' => 'site_manager')
                )
            );
            ?>
        </div>
    </div>
    <div class="popup_register_result">
        <?php if (isset($msg) && !empty($msg) && isset($response) && !empty($response) ): ?>
            <?= $this->renderPartial('_popup_result_register_sim',
                array('order_id' => $order_id,
                      'msg'      => $msg,
                      'response' => $response)); ?>
        <?php endif; ?>
    </div>
</div>
<?php if (isset($msg) && isset($response)): ?>
    <script type="text/javascript">
        $('#modal_result_'+<?=$order_id?>).modal('show');
    </script>
<?php endif; ?>
<?php if ($tab == '_form_upload'): ?>
    <script type="text/javascript">
        $('#tab_form_register_info').css("background", "grey");
        $("#tab_form_register_info").removeAttr('href');
    </script>
<?php else: ?>
    <script type="text/javascript">
        $('#tab_form_upload').css("background", "grey");
        $("#tab_form_upload").removeAttr('href');

    </script>
<?php endif; ?>


