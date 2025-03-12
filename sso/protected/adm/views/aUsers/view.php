<?php
    /* @var $this AUsersController */
    /* @var $model AUsers */

    $this->breadcrumbs = array(
        'Ausers' => array('index'),
        $model->id,
    );

    $this->menu = array(
        array('label' => 'Tạo mới', 'url' => array('create')),
        array('label' => 'Cập nhật người dùng', 'url' => array('update', 'id' => $model->id)),
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
                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $model,
                    'attributes' => array(
                        'username',
                        'fullname',
                        'email',
                        'phone',
                        'genre',
                        'birthday',
                        'address',
                        'description',
                        'created_at',
                        'updated_at',
                        'cp_id',
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>

