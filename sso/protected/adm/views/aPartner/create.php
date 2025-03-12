<?php
    /* @var $this APartnerController */
    /* @var $model APartner */

    $this->breadcrumbs = array(
        'Apartners' => array('index'),
        'Create',
    );

    $this->menu = array(
        array('label' => 'Quản lý đôi tác', 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo Yii::t('adm/app', 'Create') ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php $this->renderPartial('_form', array('model' => $model)); ?>
            </div>
        </div>
    </div>
</div>

