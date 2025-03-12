<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('admin'),
    UserModule::t('Manage'),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t("Manage Users"); ?></h2>
                <div class="clearfix"></div>
            </div>                

            <div class="x_content">
                <?php
                echo $this->renderPartial('_menu', array(
                    'list' => array(
                        CHtml::link(UserModule::t('Create User'), array('create')),
                    ),
                ));
                ?>

                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns' => array(
                        array(
                            'name' => 'id',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
                        ),
                        array(
                            'name' => 'username',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->username),array("admin/view","id"=>$data->id))',
                        ),
                        array(
                            'name' => 'email',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->email), "mailto:".$data->email)',
                        ),
                        array(
                            'name' => 'createtime',
                            'value' => 'date("d.m.Y H:i:s",$data->createtime)',
                        ),
                        array(
                            'name' => 'lastvisit',
                            'value' => '(($data->lastvisit)?date("d.m.Y H:i:s",$data->lastvisit):UserModule::t("Not visited"))',
                        ),
                        array(
                            'name' => 'status',
                            'value' => 'User::itemAlias("UserStatus",$data->status)',
                        ),
                        array(
                            'name' => 'superuser',
                            'value' => 'User::itemAlias("AdminStatus",$data->superuser)',
                        ),
                        array(
                            'class' => 'CButtonColumn',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>