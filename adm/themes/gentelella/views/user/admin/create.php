<?php
    $this->breadcrumbs = array(
        UserModule::t('Users') => array('admin'),
        UserModule::t('Create'),
    );
?>

<div class="x_panel container-fluid">
    <div class="x_title">
        <h2><?php echo UserModule::t("Create User"); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
            <div class="col-md-8">
                <?php
                    echo $this->renderPartial('_form', array('model' => $model, 'profile' => $profile));
                ?>
            </div>
        </div>
    </div>
</div>