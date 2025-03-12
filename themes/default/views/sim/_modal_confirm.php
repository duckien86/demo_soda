<?php
    /* @var $package WPackage */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'confirm_add_cart')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="lbl_color_blue">Lưu ý với thuê bao có cam kết</h4>
</div>
<div class="modal-body">
    <p class="font_16 data_note_1">
        1. Thuê bao phải đặt cọc và thanh toán 01 tháng cước cam kết và sẽ được khấu trừ vào tháng cuối cùng của
        thời gian cam kết
    </p>
    <p class="font_16 data_note_2">
        2. Thuê bao chỉ được chuyển quyền sử dụng và chuyển tỉnh sau 6 tháng hòa mạng
    </p>
    <p class="font_16 data_note_4">
        3. Thuê bao không được hủy số hoặc thanh lý hợp đồng trong thời gian cam kết
    </p>
    <p class="font_16 data_note_5">
        4. Thuê bao phải thanh toán đầy đủ cước cam kết trong thời gian cam kết.
    </p>
    <div class="space_30"></div>
    <div class="text-center">
        <?= CHtml::link(Yii::t('web/portal', 'close'), '#', array('class' => 'btn bg_blue color_white', 'data-dismiss' => 'modal')) ?>
        <?= CHtml::link(Yii::t('web/portal', 'continue'), $url_register, array('id' => 'btn_add_cart', 'class' => 'btn btn_green')) ?>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>
