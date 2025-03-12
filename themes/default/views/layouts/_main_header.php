<!--top-header-new-->
<div class="top-head-new">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">

            </div>
            <div class="col-lg-9">
                <div class="right-top-new">
                    <ul>
                        <!-- <li>
                            <a href="<? /*= Yii::app()->controller->createUrl('sim/index'); */ ?>"><span><img
                                            src="<? /*= Yii::app()->theme->baseUrl; */ ?>/images/search-min.png"
                                            alt="icon-search-destop-new"></span> <span
                                        class="text-li">Tìm kiếm</span></a>
                        </li>-->
                        <li>
                            <a href="<?= Yii::app()->controller->createUrl('orders/searchOrder'); ?>"><span><img
                                            src="<?= Yii::app()->theme->baseUrl; ?>/images/check-order-min.png"
                                            alt="icon-check-order"></span> <span
                                        class="text-li">Kiểm tra đơn hàng</span></a>
                        </li>
                        <!-- <li>
                            <a href="javascript:$zopim.livechat.window.show();"><span><img
                                            src="<? /*= Yii::app()->theme->baseUrl; */ ?>/images/contact-min.png"
                                            alt="icon-contact"></span> <span class="text-li">Liên hệ</span></a>
                        </li>-->
                        <li>
                            <a href="<?= Yii::app()->controller->createUrl('site/supportChannel'); ?>"><span><img
                                            src="<?= Yii::app()->theme->baseUrl; ?>/images/support-min.png"
                                            alt="icon-support"></span> <span class="text-li">Hỗ trợ</span></a>
                        </li>
                        <?php if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
                            ?>
                            <li>
                                <a style="margin-top: -4px" href="#" class="btn  dropdown-toggle" type="button"
                                   data-toggle="dropdown"><span><img
                                                src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_user_.png"
                                                alt="User"> </span><span
                                            class="text-li"><?= Yii::app()->user->username; ?></span></a>
                                <ul class="dropdown-menu drop-custom" id="sub-custom">
                                    <li><a href="<?= Yii::app()->controller->createUrl('site/profile'); ?>">Thông tin cá
                                            nhân</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('orders/index'); ?>">Dịch vụ của
                                            tôi</a></li>
                                    <li><a href="<?= ($url_changepass != '') ? $url_changepass : '' ?>">Đổi mật khẩu</a>
                                    </li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('site/logout'); ?>">Đăng xuất</a>
                                    </li>
                                </ul>
                            </li>
                        <?php } else {
                            $url_login = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
                            ?>

                            <li>
                                <a href="<?php echo $GLOBALS['config_common']['domain_sso']['ssoreg'] . $GLOBALS['config_common']['domain_sso']['pid'] ?>"
                                   class="btn btn-custom-log-new">Đăng ký</a>
                            </li>
                            <li style="margin-left: 10px">
                                <a href="<?= $url_login ?>" class="btn btn-custom-log-new">Đăng nhập</a>
                            </li>

                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end top-header-new-->
<style>
    .right-top-new ul {
        float: right !important;
        margin: 0 !important;
        padding: 0 !important;
        text-align: right !important;
        position: relative;
        right: 0;
    }
    .right-top-new ul li{
        margin-top: 6px;
    }
</style>

