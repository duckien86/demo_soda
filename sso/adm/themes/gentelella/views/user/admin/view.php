<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('admin'),
    $model->username,
);
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t('View User') . ' "' . $model->username . '"'; ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php
                echo $this->renderPartial('_menu', array(
                    'list' => array(
                        CHtml::link(UserModule::t('Create User'), array('create')),
                        CHtml::link(UserModule::t('Update User'), array('update', 'id' => $model->id)),
                        CHtml::linkButton(UserModule::t('Delete User'), array('submit' => array('delete', 'id' => $model->id), 'confirm' => UserModule::t('Are you sure to delete this item?'))),
                    ),
                ));


                $attributes = array(
                    'id',
                    'username',
                );

                $profileFields = ProfileField::model()->forOwner()->sort()->findAll();
                if ($profileFields) {
                    foreach ($profileFields as $field) {
                        array_push($attributes, array(
                            'label' => UserModule::t($field->title),
                            'name' => $field->varname,
                            'type' => 'raw',
                            'value' => (($field->widgetView($model->profile)) ? $field->widgetView($model->profile) : (($field->range) ? Profile::range($field->range, $model->profile->getAttribute($field->varname)) : $model->profile->getAttribute($field->varname))),
                        ));
                    }
                }

                array_push($attributes, 'password', 'email', 'activkey', array(
                    'name' => 'createtime',
                    'value' => date("d.m.Y H:i:s", $model->createtime),
                        ), array(
                    'name' => 'lastvisit',
                    'value' => (($model->lastvisit) ? date("d.m.Y H:i:s", $model->lastvisit) : UserModule::t("Not visited")),
                        ), array(
                    'name' => 'superuser',
                    'value' => User::itemAlias("AdminStatus", $model->superuser),
                        ), array(
                    'name' => 'status',
                    'value' => User::itemAlias("UserStatus", $model->status),
                        )
                );

                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => $attributes,
                    'htmlOptions' => array(
                        'class' => 'table table-bordered table-striped table-hover th-right',
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>