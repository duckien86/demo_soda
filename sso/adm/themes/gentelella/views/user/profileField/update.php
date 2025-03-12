<?php
$this->breadcrumbs = array(
    UserModule::t('Profile Fields') => array('admin'),
    $model->title => array('view', 'id' => $model->id),
    UserModule::t('Update'),
);
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t('Update ProfileField ') . $model->id; ?></h2>
                <div class="clearfix"></div>
            </div>                

            <div class="x_content">
                <?php
                echo $this->renderPartial('_menu', array(
                    'list' => array(
                        CHtml::link(UserModule::t('Create Profile Field'), array('create')),
                        CHtml::link(UserModule::t('View Profile Field'), array('view', 'id' => $model->id)),
                    ),
                ));
                ?>

                <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
            </div>
        </div>
    </div>
</div>