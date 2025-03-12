<?php
    $action = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <li>
        <?= CHtml::link(Rights::t('core', 'Assignments'), array('/rights/assignment/view'),
            array('class' => ($action == 'view') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Rights::t('core', 'Permissions'), array('/rights/authItem/permissions'),
            array('class' => ($action == 'permissions') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Rights::t('core', 'Roles'), array('/rights/authItem/roles'),
            array('class' => ($action == 'roles') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Rights::t('core', 'Tasks'), array('/rights/authItem/tasks'),
            array('class' => ($action == 'tasks') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Rights::t('core', 'Operations'), array('/rights/authItem/operations'),
            array('class' => ($action == 'operations') ? 'select' : '')); ?>
    </li>
</div>
