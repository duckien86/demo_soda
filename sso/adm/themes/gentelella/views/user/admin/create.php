<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('admin'),
    UserModule::t('Create'),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t("Create User"); ?></h2>
                <div class="clearfix"></div>
            </div>                

            <div class="x_content">
                <?php
                echo $this->renderPartial('_menu', array(
                    'list' => array(),
                ));
                echo $this->renderPartial('_form', array('model' => $model, 'profile' => $profile));
                ?>
            </div>
        </div>
    </div>
</div>