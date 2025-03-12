<?php
    $this->pageTitle   = Yii::app()->name . ' - ' . UserModule::t("Profile");
    $this->breadcrumbs = array(
        UserModule::t("Profile"),
    );
?>
<div class="x_panel">
    <div class="x_content">
        <?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
            <div class="alert alert-success alert-dismissible fade in">
                <button aria-label="<?php echo Yii::t('app', 'Close') ?>" data-dismiss="alert" class="close"
                        type="button"><span aria-hidden="true">×</span></button>
                <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
            </div>
        <?php endif; ?>
        <table class="dataGrid table table-bordered table-striped table-hover">
            <tr>
                <td class="text-right">
                    <strong><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></strong>
                </td>
                <td><?php echo CHtml::encode($model->username); ?>
                </td>
            </tr>
            <?php
                $profileFields = ProfileField::model()->forOwner()->sort()->findAll();
                if ($profileFields) {
                    foreach ($profileFields as $field) {
                        //echo "<pre>"; print_r($profile); die();
                        ?>
                        <tr>
                            <td class="text-right">
                                <strong><?php echo CHtml::encode(UserModule::t($field->title)); ?></strong>
                            </td>
                            <td><?php echo(($field->widgetView($profile)) ? $field->widgetView($profile) : CHtml::encode((($field->range) ? Profile::range($field->range, $profile->getAttribute($field->varname)) : $profile->getAttribute($field->varname)))); ?>
                            </td>
                        </tr>
                        <?php
                    }//$profile->getAttribute($field->varname)
                }
            ?>
            <tr>
                <td class="text-right"><strong><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></strong>
                </td>
                <td><?php echo CHtml::encode($model->email); ?>
                </td>
            </tr>
            <tr>
                <td class="text-right"><strong><?php echo 'Số ELoad' ?></strong>
                </td>
                <td><?php echo CHtml::encode($model->phone); ?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    <strong><?php echo CHtml::encode($model->getAttributeLabel('phone_2')); ?></strong>
                </td>
                <td><?php echo CHtml::encode($model->phone_2); ?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    <strong><?php echo CHtml::encode($model->getAttributeLabel('createtime')); ?></strong>
                </td>
                <td><?php echo date("d.m.Y H:i:s", $model->createtime); ?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    <strong><?php echo CHtml::encode($model->getAttributeLabel('lastvisit')); ?></strong>
                </td>
                <td><?php echo date("d.m.Y H:i:s", $model->lastvisit); ?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    <strong><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></strong>
                </td>
                <td><?php echo CHtml::encode(User::itemAlias("UserStatus", $model->status));
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>