<?php
    /* @var $this RoamingController */
    /* @var $data */
?>
<div class="font_15">
    Quý khách sử dụng gói <?= CHtml::encode($data['service_code']); ?> - Data roaming. Dung lượng còn lại
    là <?= CHtml::encode($data['remain_amount']) . ' ' . CHtml::encode($data['unit']); ?>.
</div>
<div class="space_20"></div>
<div class="font_15">
    Gói cước của Quý khách có hiệu lực
    đến <?= CHtml::encode($data['time_end']); ?>
</div>
<div class="space_20"></div>
<div class="lbl_color_blue text-center">
    Cảm ơn đã sử dụng dịch vụ của Vinaphone
</div>
<div class="space_20"></div>
<div class="text-center">
    <a class="close_modal btn btn bg_btn width_100" data-dismiss="modal">Đóng</a>
</div>

