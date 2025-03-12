<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <li>
        <?= CHtml::link(Yii::t('adm/label', 'news'),
            array('aNews/admin'), array('class' => ($controller == 'aNews' && $action == 'admin') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'create'), array('aNews/create'), array('class' => ($controller == 'aNews' && $action == 'create') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'view'), '#', array('class' => ($controller == 'aNews' && $action == 'view') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/label', 'news_categories'),
            array('aNewsCategories/admin'), array('class' => ($controller == 'aNewsCategories' && $action == 'admin') ? 'select' : '')); ?>
    </li>
    <li>
        <?= CHtml::link(Yii::t('adm/actions', 'create_cate'), array('aNewsCategories/create'), array('class' => ($controller == 'aNewsCategories' && $action == 'create') ? 'select' : '')); ?>
    </li>
</div>
