<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class='tabtracuusddv'>
    <ul class="actions">
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'report_index'), array('index'), array('class' => ($action == 'index') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'report_sim'), array('sim'), array('class' => ($action == 'sim') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'report_package'), array('package'), array('class' => ($action == 'package') ? 'select' : '')); ?>
        </li>
        <li>
            <?= CHtml::link(Yii::t('report/menu', 'report_card'), array('card'), array('class' => ($action == 'card') ? 'select' : '')); ?>
        </li>

    </ul><!-- actions -->
</div>