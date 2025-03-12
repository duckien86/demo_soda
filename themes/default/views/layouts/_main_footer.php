<div class="page-custom" style="background: #f7f7f7">
    <div class="container">
        <div class="row">

            <div class="col-lg-6" style="text-align: left; border-right: #ccc 1px solid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="fb-page fb_iframe_widget" data-href="https://www.facebook.com/gshop.centech.vn/" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="adapt_container_width=true&amp;app_id=&amp;container_width=350&amp;hide_cover=true&amp;href=https%3A%2F%2Fwww.facebook.com%2Fgshop.centech.vn%2F&amp;locale=vi_VN&amp;sdk=joey&amp;show_facepile=true&amp;small_header=true"><span style="vertical-align: bottom; width: 340px; height: 154px;"><iframe name="f3d68975ab130c" width="1000px" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" title="fb:page Facebook Social Plugin" src="https://www.facebook.com/v2.10/plugins/page.php?adapt_container_width=true&amp;app_id=&amp;channel=http%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2FK6RbmRhg2m2.js%3Fversion%3D42%23cb%3Df36f438b5955f94%26domain%3Dgshop.centech.vn%26origin%3Dhttp%253A%252F%252Fgshop.centech.vn%252Ff71040b63420fc%26relation%3Dparent.parent&amp;container_width=350&amp;hide_cover=true&amp;href=https%3A%2F%2Fwww.facebook.com%2Fgshop.centech.vn%2F&amp;locale=vi_VN&amp;sdk=joey&amp;show_facepile=true&amp;small_header=true" style="border: none; visibility: visible; width: 340px; height: 154px;" class=""></iframe></span></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align: left">
                <div class="row">
                    <!--<div class="col-lg-12">
                        <div style="font-weight: bold;margin-bottom: 10px">ĐƯỢC CHỨNG NHẬN</div>
                    </div>
                </div>-->
                <div class="row">
                    <!--<div class="col-lg-12">
                        <a target="_blank" style="display: block;" href="http://online.gov.vn/CustomWebsiteDisplay.aspx?DocId=37327">
                            <img src="<?php echo Yii::app()->request->baseUrl ; ?>/themes/default/images/certify1.png">
                        </a>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .page-custom{
        width: 100%;
        float: left;
        background: #fff;
        padding: 25px 0px;
    }
</style>
<div id="footer">
    <div class="column">
        <div class="space_10"></div>
        <div class="container">

            <!--<div class="newsletter">
                <div id="newsletter_tit">
                    Đăng ký nhận<br>
                    bản tin GSHOP
                </div>
                <div id="newsletter_des">
                    Cập nhật thông tin khuyến mãi nhanh nhất<br>
                    Hưởng quyền lợi giảm giá riêng biệt
                </div>

                <form id="frmNewsLetter">
                    <input name="newsleterInput" id="newsleterInput" type="text" placeholder="Email của bạn"
                           class="form-control" maxlength="255">
                    <input type="submit" id="btn_newsleter" class="form-control adr button" value="Gửi">
                </form>
                <div class="space_1"></div>
            </div>-->
            <div class="space_30"></div>
            <div class="company">
                <div class="address">Công ty cổ phần truyền thông GAPIT</div>
                <div class="space_10"></div>
                <div class="address">Trụ sở chính</div>
                <div class="space_10"></div>
                <div class="address">
                    Tầng 9, Tòa Lake View, D10 Giảng Võ, Ba Đình, Hà Nội<br>
                </div>
                <div class="address">
                    Tổng đài: (+84) 2435121928
                </div>
                <div class="space_10"></div>
                <div class="address">
                    Email: cskh@telmall.vn
                </div>
                <div class="space_10"></div>
                <div class="address">
                    Mã số doanh nghiệp: 0106869738 do Sở Kế hoạch và Đầu tư TP. Hà Nội cấp lần đầu ngày 11/06/2015. Đăng
                    ký thay đổi lần thứ 01 do Sở Kế hoạch và Đầu tư TP. Hà Nội ngày 14/01/2016.
                </div>
            </div>
        </div>
        <div class="space_30"></div>
    </div>
    <div class="copy_right">
        <div class="space_5"></div>
        <div class="container">
            <div class="uppercase copy_right_tit text-center">
                TELMALL.VN – WEBSITE BÁN HÀNG ONLINE CHÍNH THỨC CỦA GAPIT
            </div>
            <div class="copy_right_txt text-center">
                Copyright CENTECH <?= date('Y') ?>. All rights reserved.
            </div>
        </div>
        <div class="space_5"></div>
    </div>
</div>

<div class="young-fix" style="display: block">
    <div>
        <a href="tel:18001166" style="display: block; z-index: 99999">
            <img class="icon_call" src="<?= Yii::app()->theme->baseUrl; ?>/images/call_.png" alt="Hotline">
        </a>
    </div>
    <?php
    $detect   = new MyMobileDetect();
    $isMobile = $detect->isMobile();
    ?>
    <?php if(!$isMobile){ ?>
        <div>
            <a href="#" data-toggle="modal" data-target="#mycall">
                <img style="margin-bottom: 10px" class="human_banner" src="<?= Yii::app()->theme->baseUrl; ?>/images/call_.png" alt="Hotline">
            </a>
        </div>

    <?php }?>

    <div>

    </div>
    <div>
        <a href="#" onclick="goToTop();">
            <img class="top_banner" src="<?= Yii::app()->theme->baseUrl; ?>/images/top_banner_.png" alt="Scroll To Top">
        </a>
    </div>
</div>

<?php
echo Utils::genGA(Yii::app()->params->google_analytics_code);
?>
<div id="fb-root"></div>
<!-- Modal -->
<div class="modal fade" id="mycall" role="dialog">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center; color: #2fa0d7">Thông tin liên hệ</h4>
            </div>
            <div class="modal-body" style="text-align: center">
                <p><span>Mời quý khách liên hệ tổng đài</span> <span style="color: red;font-weight: bold;
font-size: 18px">18001166 </span> <span>để được tư vấn hỗ trợ</span></p>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button style="background: #2fa0d7 !important; color: #fff" type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.10";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    });
</script>
