<?php
    /* @var $this AUsersController */
    /* @var $model AUsers */

    $this->breadcrumbs = array(
        'Ausers'   => array('index'),
        $model->id => array('view', 'id' => $model->id),
        'Update',
    );

    $this->menu = array(
        array('label' => 'Tạo mới', 'url' => array('create')),
        array('label' => 'Chí tiết người dùng', 'url' => array('view', 'id' => $model->id)),
        array('label' => 'Quản lý người dùng', 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo Yii::t('adm/app', 'Update') ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php $this->renderPartial('_form', array('model' => $model)); ?>
            </div>
        </div>
    </div>
</div>
