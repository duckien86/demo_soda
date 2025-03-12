<?php
    /* @var $package WPackage */
    $url_register = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid_aff'];
    $url_login    = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'confirm')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
</div>
<div class="modal-body text-center">
    <?php if (Yii::app()->user->isGuest): //guest ?>
        <p class="font_16">
            Gói cước ưu đãi này chỉ dành riêng cho Cộng tác viên dùng SIM Freedoo. Nếu bạn là CTV hãy đăng nhập hoặc gia
            nhập cùng Freedoo để sở hữu gói cước này.
        </p>
        <div class="space_30"></div>
        <div class="text-center">
            <?= CHtml::link('Gia nhập với chúng tôi', $url_register, array('class' => 'btn btn_green')) ?>
            <?= CHtml::link('Đăng nhập', $url_login, array('class' => 'btn btn_green')) ?>
        </div>
    <?php else: //login ?>
        <?php if (Yii::app()->user->customer_type == WPackage::VIP_USER
            && Yii::app()->user->sim_freedoo != WCustomers::SIM_FREEDOO
        ): //CTV && !sim_freedoo
            ?>
            <p class="font_16">
                Gói cước ưu đãi này chỉ dành riêng cho Cộng tác viên dùng SIM Freedoo. Để hưởng ưu đãi gói cước này vui lòng
                đăng ký 01 SIM Freedoo và thực hiện cập nhật lại thông tin hồ sơ Cộng tác viên.
            </p>
        <?php else: //login !CTV ?>
            <p class="font_16">
                Gói cước ưu đãi này chỉ dành riêng cho Cộng tác viên dùng SIM Freedoo, hãy gia
                nhập cùng Freedoo để sở hữu gói cước này.
            </p>
            <div class="space_30"></div>
            <div class="text-center">
                <?= CHtml::link('Gia nhập với chúng tôi', $url_register, array('class' => 'btn btn_green')) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $this->endWidget(); ?>
