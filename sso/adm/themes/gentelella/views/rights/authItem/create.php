<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Create :type', array(':type' => Rights::getAuthItemTypeName($_GET['type']))),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
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
    </div>
</div>