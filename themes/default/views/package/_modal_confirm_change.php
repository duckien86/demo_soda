<?php
    /* @var $oldPackage WPackage */
    /* @var $newPackage WPackage */
?>

<p class="font_16">
    Bạn đang thực hiện chuyển đổi gói cước <span class="lbl_color_blue"><?= CHtml::encode($oldPackage->name); ?></span>
    sang gói <span class="lbl_color_blue"><?= CHtml::encode($newPackage->name); ?></span>
</p>
<div class="space_30"></div>
<div class="text-center">
    <?= CHtml::link(Yii::t('web/portal', 'cancel'), '#', array('class' => 'btn btn_green', 'data-dismiss' => 'modal')) ?>
    <?= CHtml::link(Yii::t('web/portal', 'confirm'),
        $this->createUrl('package/change', array('old_code' => $oldPackage->code, 'new_code' => $newPackage->code)),
        array('class' => 'btn btn_green', 'id' => 'btn_change_pack')) ?>
</div>
