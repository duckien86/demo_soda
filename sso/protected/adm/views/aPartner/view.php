<?php
    /* @var $this APartnerController */
    /* @var $model APartner */

    $this->breadcrumbs = array(
        'Apartners' => array('index'),
        $model->name,
    );

    $this->menu = array(
        array('label' => 'Tạo mới', 'url' => array('create')),
        array('label' => 'Cập nhật', 'url' => array('update', 'id' => $model->id)),
        array('label' => 'Quản lý', 'url' => array('admin')),
    );
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo Yii::t('adm/app', 'View') ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $model,
                    'attributes' => array(
                        'name',
                        'description',
                        'phone',
                        'email',
                        'created_at',
                        'status',
                        'cp_id',
                        'aes_key',
                        'return_url',
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>

