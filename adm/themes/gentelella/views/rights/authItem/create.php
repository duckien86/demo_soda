<?php
    $this->breadcrumbs = array(
        'Rights' => Rights::getBaseUrl(),
        Rights::t('core', 'Create :type', array(':type' => Rights::getAuthItemTypeName($_GET['type']))),
    );
?>


<div class="x_panel container-fluid">
    <div class="createAuthItem">
        <div class="x_title">
            <h2><?php
                    echo Rights::t('core', 'Create :type', array(
                        ':type' => Rights::getAuthItemTypeName($_GET['type']),
                    ));
                ?></h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <?php $this->renderPartial('_form', array('model' => $formModel)); ?>
        </div>
    </div>
</div>
