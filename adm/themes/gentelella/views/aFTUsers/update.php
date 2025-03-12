<?php
/**
 * @var $this AFTUsersController
 * @var $model AFTUsers
 */

$this->breadcrumbs = array(
    Yii::t('adm/label', 'manage_ft_users') => array('admin'),
    Yii::t('adm/actions','update')
);

$name = ($model->user_type == AFTUsers::USER_TYPE_AGENCY) ? $model->company : $model->username

?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo $name ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
