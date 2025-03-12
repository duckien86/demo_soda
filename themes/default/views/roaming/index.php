<?php
/* @var $this RoamingController */
/* @var $packages WPackage */
?>

<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section id="banner_roaming">
        <img class="img" src="<?= Yii::app()->theme->baseUrl; ?>/images/banner_roaming.jpg">
        <div class="container">
            <div class="col-md-6"></div>
            <div class="col-md-6">
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-11 col-xs-12 no_pad_xs">
                <div id="list_btn">
                    <div class="txt">Bạn có thể kiểm tra thông tin/ hủy các gói cước Roaming đang sử dụng tại đây
                    </div>
                    <a href="#" onclick="return false" class="btn btn_search_rx">Tra cứu</a>
                    <a id="btn_confirm_cancel_ir_rx" href="#" onclick="return false" class="btn btn_confirm_cancel">Hủy</a>
                    <div class="col-xs-12">
                        <a id="btn_register_ir_mobile" href="#" onclick="return false" class="btn btn_yellow">Đăng
                            ký<br>CVQT</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="list_roaming">
        <div class="space_30"></div>
        <div class="container">
            <?php
            if ($packages) :
                $this->widget(
                    'booster.widgets.TbThumbnails',
                    array(
                        'dataProvider' => $packages,
                        'template' => "{items}{pager}",
                        'enablePagination' => TRUE,
                        'viewData' => array('class_col' => TRUE),
                        'itemView' => '_block_package',
                        //                          'ajaxType'         => 'POST',
                        'emptyText' => '',
                    )
                );
            endif;
            ?>
        </div>
        <div class="space_30"></div>
    </section>
    <section class="policy_roaming">
        <div class="container">
            <div id="tit_policy" class="top_title">
                Quy định sử dụng
            </div>
            <div class="policy" id="ru1_policy">
                <div class="title">
                    <img class="icon" src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_pr2.png">
                    <span>Quy định đối với các gói Data không giới hạn RU1 -> RU3</span>
                </div>
                <div class="space_10"></div>
                <div class="description">
                    <p class="uppercase font_bold">Quy định sử dụng gói cước:</p>
                    <p>
                    <ul>
                        <li>
                            - Gói cước không giới hạn dung lượng DATA chỉ áp dụng cho hội viên hạng Vàng và Kim cương của VinaPhone.
                        </li>
                        <li>
                            - Ưu đãi của gói cước chỉ có hiệu lực trong mạng thuộc phạm vi cung cấp của gói.
                        </li>
                        <li>
                            - Gói cước không gia hạn tự động và không được tính vào ngưỡng cảnh báo 5 triệu của Data Roaming.
                        </li>
                        <li>
                            - Không đăng ký đồng thời các gói cước với nhau. Muốn đăng ký gói tiếp theo phải hủy ưu đãi hiện tại.
                        </li>
                        <li>
                            - Tra cứu gói cước: Soạn tin nhắn theo cú pháp : “DataRx” gửi 9123.
                        </li>
                    </ul>
                    </p>

                </div>
            </div>
            <div class="space_10"></div>
            <div class="policy" id="r1_policy">
                <div class="title">
                    <img class="icon" src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_pr1.png">
                    <span>Quy định đối với các gói R1=>R15</span>
                </div>
                <div class="space_10"></div>
                <div class="description">
                    <p class="uppercase font_bold">QUY ĐỊNH SỬ DỤNG CÁC GÓI CƯỚC RX:</p>
                    <p>
                    <ul>
                        <li>- Chỉ sử dụng được gói Rx khi thuê bao đã đăng ký dịch vụ CVQT thành công. Soạn “DK CVQT”
                            gửi 9123 hoặc “IR ON” gửi 9123 hoặc đăng ký tại địa chỉ website:
                        </li>
                        <a href="http://freedoo.vnpt.vn/goi-cuoc-roaming.html" target="_blank" class="lbl_color_blue">http://freedoo.vnpt.vn/goi-cuoc-roaming.html.</a>
                        <li>- Khi thuê bao đăng ký dịch vụ CVQT thành công, dịch vụ Data được mở mặc định. Thuê bao có thể sử dụng data thông thường tính theo Blook 10KB+ 10KB hoặc dùng gói Rx.
                        </li>
                        <li>- Gói cước có thời hạn sử dụng 30 ngày kể từ ngày đăng ký
                        </li>

                        </p>
                </div>
            </div>
            <div class="space_10"></div>
            <div class="policy" id="r500_policy">
                <div class="title">
                    <img class="icon" src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_pr2.png">
                    <span>Quy định đối với các gói R500</span>
                </div>
                <div class="space_10"></div>
                <div class="description">
                    <p class="uppercase font_bold">Quy định sử dụng gói cước:</p>
                    <p>
                    <ul>
                        <li>
                            - Chỉ sử dụng được gói R500 khi thuê bao đã đăng ký dịch vụ CVQT thành công. Soạn “DK CVQT”
                            gửi 9123 hoặc “IR ON” gửi 9123.
                        </li>
                        <li>
                            - Khi thuê bao trả trước đăng ký dịch vụ CVQT thành công:
                            <ul>
                                <li>
                                    Đối với thuê bao trả trước, dịch vụ Data bị khóa mặc định, chỉ sử dụng được dịch vụ
                                    thoại, SMS. Thuê bao có nhu cầu sử dụng dịch vụ Data thì phải đăng ký gói Rx hoặc
                                    gói R500 và được sử dụng trong phạm vi các hướng/mạng mà gói Rx quy định
                                </li>
                                <li>
                                    Đối với thuê bao trả sau khi đăng ký thành công R500, dịch vụ Data Roaming thông
                                    thường cũng sẽ bị khóa. Muốn sử dụng Data Roaming thông thường phải soạn GIR ON gửi
                                    888
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>
                                    + Gói cước có thời hạn sử dụng 30 ngày kể từ ngày đăng ký
                                </li>
                            </ul>
                        </li>
                    </ul>
                    </p>

                </div>
            </div>
        </div>
        <div class="space_30"></div>
    </section>
</div>
<?php $this->renderPartial('_modal_nations'); ?>
<?php $this->renderPartial('_modal_roaming'); ?>