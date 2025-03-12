<?php
    $controller = Yii::app()->controller->id;
?>

<div id="menutabs1" class='mt10'>
    <?= CHtml::link("<img class='icon4' src='" . Yii::app()->theme->baseUrl . "/images/icon4.png'><span>" . Yii::app()->getModule('user')->t("Profile") . "</span>",
        array('/user/profile'), array('class' => ($controller == 'profile' || $controller == 'admin') ? 'selected' : '')); ?>
    <?= CHtml::link("<img class='icon3' src='" . Yii::app()->theme->baseUrl . "/images/icon3.png'><span>" . Rights::t('core', 'Assignments') . "</span>",
        array('/rights/assignment/view'), array('class' => ($controller == 'assignment' || $controller == 'authItem') ? 'selected' : '')); ?>
    <?= CHtml::link("<img class='icon2' src='" . Yii::app()->theme->baseUrl . "/images/icon2.png'><span>" . Yii::t('report/menu', 'report_ctv') . "</span>",
        array('/reportCtv/simRenueve'), array('class' => ($controller == 'reportCtv' || $controller == 'reportCtv') ? 'selected' : '')); ?>
    <?= CHtml::link("<img class='icon2' src='" . Yii::app()->theme->baseUrl . "/images/icon2.png'><span>" . Yii::t('report/menu', 'report') . "</span>",
        array('/report/index'), array('class' => ($controller == 'report' || $controller == 'report') ? 'selected' : '')); ?>
</div>
