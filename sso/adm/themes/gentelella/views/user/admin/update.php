<?php
$this->breadcrumbs = array(
    (UserModule::t('Users')) => array('admin'),
    $model->username => array('view', 'id' => $model->id),
    (UserModule::t('Update')),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t('Update User') . " " . $model->id; ?></h2>
                <div class="clearfix"></div>
            </div>                

            <div class="x_content">
                <?php
                echo $this->renderPartial('_menu', array(
                    'list' => array(
                        CHtml::link(UserModule::t('Create User'), array('create')),
                        CHtml::link(UserModule::t('View User'), array('view', 'id' => $model->id)),
                    ),
                ));

                echo $this->renderPartial('_form', array('model' => $model, 'profile' => $profile));
                ?>
            </div>
        </div>
    </div>
</div>