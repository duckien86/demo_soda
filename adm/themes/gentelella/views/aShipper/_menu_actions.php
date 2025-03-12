<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <li>
        <?= CHtml::link(Yii::t('adm/label', 'shipper'),
            array('admin'), array('class' => ($action == 'admin') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => ($action == 'create') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'update'), '#', array('class' => ($action == 'update') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/label', 'list_order'), '#', array('class' => ($action == 'view') ? 'select' : '')); ?>
    </li>
</div>
