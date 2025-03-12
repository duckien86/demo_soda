<?php
$this->breadcrumbs = array(
    UserModule::t("Users"),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t("List User"); ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php //if (UserModule::isAdmin()) {?>
<!--                    <ul class="actions">-->
<!--                        <li>--><?php //echo CHtml::link(UserModule::t('Manage User'), array('/user/admin')); ?><!--</li>-->
<!--                        <li>--><?php //echo CHtml::link(UserModule::t('Manage Profile Field'), array('profileField/admin')); ?><!--</li>-->
<!--                    </ul>-->
                <?php //}?>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns' => array(
                        array(
                            'name' => 'username',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
                        ),
                        array(
                            'name' => 'createtime',
                            'value' => 'date("d.m.Y H:i:s",$data->createtime)',
                        ),
                        array(
                            'name' => 'lastvisit',
                            'value' => '(($data->lastvisit)?date("d.m.Y H:i:s",$data->lastvisit):UserModule::t("Not visited"))',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
