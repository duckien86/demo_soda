<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <li>
        <?= CHtml::link(Yii::app()->getModule('user')->t("Profile"),
            Yii::app()->getModule('user')->profileUrl, array('class' => ($action == 'profile') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::app()->getModule('user')->t("Edit"),
            array('/user/profile/edit'), array('class' => ($action == 'edit') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::app()->getModule('user')->t("Change Password"),
            array('/user/profile/changepassword'), array('class' => ($action == 'changepassword') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::app()->getModule('user')->t("Manage User"),
            array('/user/admin'),
            array('class' => (($action == 'admin' || $action == 'view' || $action == 'create' || $action == 'update') && ($controller == 'admin')) ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::app()->getModule('user')->t("Logout"),
            Yii::app()->getModule('user')->logoutUrl); ?>
    </li>
</div>
