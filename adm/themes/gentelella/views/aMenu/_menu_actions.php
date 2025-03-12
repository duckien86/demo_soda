<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <li>
        <?= CHtml::link(Yii::t('adm/label', 'manage_menu'),
            array('admin'), array('class' => ($action == 'admin') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'create'), '#', array(
            'onClick' => 'getFormMenu();'
        )); ?>
    </li>
</div>
