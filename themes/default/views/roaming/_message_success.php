<?php
    /* @var $this RoamingController */
    /* @var $modelPackage WPackage */
    /* @var $modelOrders WOrders */
    $is_RU = WPackage::checkRU($modelPackage->code);
?>
<?php if($is_RU){?>
    <div>
        <div class="font_15">
            Quý khách đã đăng ký thành công gói cước <?= CHtml::encode($modelPackage->name); ?> có dung lượng sử dụng DATA không giới hạn.
        </div>
        <div class="space_20"></div>
        <div class="font_15">
            Chi tiết xin vui lòng liên hệ: +8424.3773.1857 (miễn phí khi đang chuyển vùng Quốc tế)
            hoặc 18001091 (nếu đang ở trong nước).
        </div>
        <div class="space_20"></div>
        <div class="lbl_color_blue text-center">
            Cảm ơn đã sử dụng dịch vụ của VNPT.
        </div>
        <div class="space_20"></div>
        <div class="text-center">
            <a class="close_modal btn btn bg_btn width_100" data-dismiss="modal">Đóng</a>
        </div>
    </div>
    
<?php }else{?>
    <div>
        <div class="font_15">
            Quý khách đã đăng ký thành công gói cước <?= CHtml::encode($modelPackage->name); ?>
        </div>
        <div class="space_20"></div>
        <div class="font_15">
            Gói cước có thời hạn sử dụng 30 ngày kể từ thời điểm đăng ký. <br>
            Chi tiết xin vui lòng liên hệ: +8424.3773.1857 (miễn phí khi đang chuyển vùng Quốc tế) hoặc 18001091 (Khi đang ở trong nước).
        </div>
        <div class="space_20"></div>
        <div class="lbl_color_blue text-center">
            Cảm ơn đã sử dụng dịch vụ của VNPT.
        </div>
        <div class="space_20"></div>
        <div class="text-center">
            <a class="close_modal btn btn bg_btn width_100" data-dismiss="modal">Đóng</a>
        </div>
    </div>
    
<?php } ?>


