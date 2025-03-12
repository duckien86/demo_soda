<?php
    $this->breadcrumbs = array(
        UserModule::t('Profile Fields') => array('admin'),
        UserModule::t('Create'),
    );
?>
<div class="x_panel container-fluid">
    <div class="x_title">
        <h2><?php echo UserModule::t('Create Profile Field'); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php
            echo $this->renderPartial('_menu', array(
                'list' => array(),
            ));
        ?>

        <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>