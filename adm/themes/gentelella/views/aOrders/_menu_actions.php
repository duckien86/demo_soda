<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <li>
        <?= CHtml::link(Yii::t('adm/label', 'orders'),
            array('admin'), array('class' => ($action == 'admin') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'view'), '#', array('class' => ($action == 'view') ? 'select' : '')); ?>
    </li>
</div>
