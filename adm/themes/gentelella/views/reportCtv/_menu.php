<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <ul class="actions">
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'simRenueve'), array('simrenueve'), array('class' => ($action == 'simrenueve') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'packageRenueve'), array('packagerenueve'), array('class' => ($action == 'packagerenueve') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'packageMaintainRenueve'), array('packagemaintainrenueve'), array('class' => ($action == 'packagemaintainrenueve') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'introduceCTV'), array('introducerenueve'), array('class' => ($action == 'introducerenueve') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'supportCTV'), array('supportrenueve'), array('class' => ($action == 'supportrenueve') ? 'select' : '')); ?>
        </li>

    </ul><!-- actions -->
</div>