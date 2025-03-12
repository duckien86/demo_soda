<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<section class="block_actions">
    <div class="container">
        <div class="box">
            <div class="service">
                <a href="<?= Yii::app()->controller->createUrl('sim/index'); ?>">
                    <div class="item">
                        <div class="icon_ss border_right">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sv_sim.png" class="icon_menu">
                            <span class="title uppercase">Sim số</span>
                        </div>
                    </div>
                    <?php if ($controller == 'sim'): ?>
                        <div class="line_bottom line_ss"></div>
                    <?php endif; ?>
                </a>
            </div>
            <div class="service no_pad">
                <a href="<?= Yii::app()->controller->createUrl('package/index'); ?>">
                    <div class="item">
                        <div class="border_right">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sv_package.png" class="icon_menu">
                            <span class="title uppercase">Gói cước</span>
                        </div>
                    </div>
                    <?php if ($controller == 'package'): ?>
                        <div class="line_bottom line_pack"></div>
                    <?php endif; ?>
                </a>
            </div>
            <div class="service">
                <a href="<?= Yii::app()->controller->createUrl('card/topup'); ?>">
                    <div class="item">
                        <div class="icon_topup border_right">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sv_topup.png" class="icon_menu">
                            <span class="title uppercase">Nạp thẻ</span>
                        </div>
                    </div>

                    <?php if ($controller == 'card' && $action == 'topup'): ?>
                        <div class="line_bottom line_topup"></div>
                    <?php endif; ?>
                </a>
            </div>

            <div class="service_lg">
                <a href="<?= Yii::app()->controller->createUrl('roaming/index'); ?>">
                    <div class="item">
                        <div class="icon_card  border_right">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sv_roaming.png" class="icon_menu">
                            <span class="title uppercase">Gói cước roaming</span>
                        </div>
                    </div>
                    <?php if ($controller == 'roaming' && $action == 'index'): ?>
                        <div class="line_bottom line_card"></div>
                    <?php endif; ?>
                </a>
            </div>

            <div class="service">
                <a href="<?= Yii::app()->controller->createUrl('prepaidtopostpaid/index'); ?>">
                    <div class="item">
                        <div class="icon_card">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sv_card.png" class="icon_menu">
                            <span class="title uppercase">CĐ TB Trả sau</span>
                        </div>
                    </div>
                    <?php if ($controller == 'prepaidtopostpaid' && $action == 'index'): ?>
                        <div class="line_bottom line_card"></div>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</section>
