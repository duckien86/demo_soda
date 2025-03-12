<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','forum'),
        Yii::t('adm/label', 'manage_post') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_post'); ?></h2>

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
                            'label'   => Yii::t('adm/label', APosts::ACTIVE),
                            'content' => $this->renderPartial('_tab_list_by_status', array('model' => $model, 'type' => APosts::ACTIVE), TRUE),
                            'active'  => TRUE,
                        ),
                        array(
                            'label'   => Yii::t('adm/label', APosts::INACTIVE),
                            'content' => $this->renderPartial('_tab_list_by_status', array('model' => $model, 'type' => APosts::INACTIVE), TRUE),
                        ),
                        array(
                            'label'   => Yii::t('adm/label', APosts::PENDING),
                            'content' => $this->renderPartial('_tab_list_by_status', array('model' => $model, 'type' => APosts::PENDING), TRUE),
                        ),
                        array(
                            'label'   => Yii::t('adm/label', APosts::NOCOMMENT),
                            'content' => $this->renderPartial('_tab_list_by_status', array('model' => $model, 'type' => APosts::NOCOMMENT), TRUE),
                        ),
                    ),
                    'htmlOptions' => array('class' => 'site_manager')
                )
            );
            ?>
        </div>
    </div>
    <div class="show-popup-abc"></div>
</div>
<script language="javascript">
    function changeStatus(id, status, sso_id) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->controller->createUrl('aPosts/changeStatus')?>',
            crossDomain: true,
            data: {id: id, status: status, sso_id: sso_id},
            success: function (result) {
                if (status == 'pending') {
                    window.location.reload();
                }
                $('.show-popup-abc').html(result);
                var modal_id = 'modal_' + id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }
</script>
