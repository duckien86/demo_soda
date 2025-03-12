<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('index'),
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
<!--                <ul class="actions">-->
<!--                    <li>--><?php //echo CHtml::link(UserModule::t('List User'), array('/user')); ?><!--</li>-->
<!--                </ul>-->

                <?php
// For all users
                $attributes = array(
                    'username',
                );

                $profileFields = ProfileField::model()->forAll()->sort()->findAll();
                if ($profileFields) {
                    foreach ($profileFields as $field) {
                        array_push($attributes, array(
                            'label' => UserModule::t($field->title),
                            'name' => $field->varname,
                            'value' => $model->profile->getAttribute($field->varname),
                        ));
                    }
                }
                array_push($attributes, array(
                    'name' => 'createtime',
                    'value' => date("d.m.Y H:i:s", $model->createtime),
                        ), array(
                    'name' => 'lastvisit',
                    'value' => (($model->lastvisit) ? date("d.m.Y H:i:s", $model->lastvisit) : UserModule::t('Not visited')),
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