<?php
    /* @var $this AMenuController */
    /* @var $model AMenu */
    /* @var $data */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_menu') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>
<div class="container-fluid">
    <div class="x_panel">
        <div class="x_title">
            <h2><?= Yii::t('adm/label', 'list_menu'); ?></h2>

            <div class="pull-right">
                <?= CHtml::link(Yii::t('adm/actions', 'create'), '#', array(
                    'onClick' => 'getFormMenu();',
                    'class'   => 'btn btn-success'
                )); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-6 manage_menu">
                <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => $data
                    ));
                ?>
            </div>
            <div class="col-md-6 manage_menu">
                <div id="form_menu">
                </div>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    function getFormMenu(id='', parent_id='') {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->controller->createUrl('aMenu/getFormMenu')?>',
            crossDomain: true,
            dataType: 'json',
            data: {id: id, parent_id: parent_id, YII_CSRF_TOKEN: '<?= Yii::app()->request->csrfToken ?>'},
            success: function (result) {
                $('#form_menu').html(result.content);
            }
        });
    }
</script>