<?php
    $url_login = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
    if (isset(Yii::app()->user->sso_id) && Yii::app()->user->sso_id != '') {
        $data = array(
            'user_id' => Yii::app()->user->sso_id,
        );
        $data = http_build_query($data);
        $data = Utils::encrypt($data, Yii::app()->params['aes_key'], MCRYPT_RIJNDAEL_128);

        $url_changepass = 'http://' . SERVER_HTTP_HOST . '/sso/changepass/001?data=' . $data;
    }
?>
<nav id="menu" class="left_menu">
    <ul>
        <?php if (isset(Yii::app()->session['session_data']->current_msisdn)): ?>
            <li class="mparent"><a href="javascript:void(0);"><i
                            class="fa fa-mobile i_mn_phone"></i> <?= Yii::app()->session['session_data']->current_msisdn ?>
                </a></li>
        <?php endif; ?>
        <li class="mparent">
            <a href="<?= Yii::app()->controller->createUrl('site/index'); ?>">
                <p>TRANG CHỦ</p>
            </a>
        </li>
        <li class="mparent"><a href="javascript:void(0);">
                <p>DI DỘNG</p></a>
            <ul>
                <li>
                    <a href="<?= Yii::app()->controller->createUrl('sim/index'); ?>">
                        Sim số
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::app()->controller->createUrl('package/index'); ?>">
                        Gói cước
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::app()->controller->createUrl('card/buycard'); ?>">
                        Mua mã thẻ
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::app()->controller->createUrl('card/topup'); ?>">
                        Nạp thẻ
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::app()->controller->createUrl('roaming/index'); ?>">
                        Gói cước Roaming
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::app()->controller->createUrl('prepaidtopostpaid/index'); ?>">
                        Chuyển đổi thuê bao trả sau
                    </a>
                </li>
            </ul>
        </li>

        <li class="mparent">
            <a href="<?= $GLOBALS['config_common']['domain_related']['affiliate'] ?>" title="" target="_blank">
                <p>CỘNG TÁC VIÊN</p>
            </a>
        </li>
        <li class="mparent">
            <a href="<?= Yii::app()->controller->createUrl('help/index') ?>">
                <p>HỖ TRỢ</p>
            </a>
        </li>
        <li class="mparent">
            <a href="<?= Yii::app()->controller->createUrl('news/index') ?>">
                <p>TIN TỨC</p>
            </a>
        </li>
<!--        <li class="mparent">-->
<!--            <a href="http://my.vinaphone.com.vn/users/updatesubinfo" target="_blank">-->
<!--                <p>TRA CỨU & HOÀN THIỆN TTTB NĐ49</p>-->
<!--            </a>-->
<!--        </li>-->
        <li class="mparent">
            <a href="http://sohuong.vinaphone.com.vn/home/index.jsp?gclid=EAIaIQobChMIwePUrerX3gIVTI6PCh2VAQ65EAAYASAAEgLe1fD_BwE"
               title="Chuyển mạng giữ số" target="_blank">
                Chuyển mạng giữ số
            </a>
        </li>
    </ul>
</nav>

<?php if (!Yii::app()->user->isGuest): ?>
    <nav id="menu_right" class="left_menu">
        <ul>
            <li class="mparent">
                <a href="<?= Yii::app()->controller->createUrl('site/profile'); ?>">
                    <p>Thông tin cá nhân</p>
                </a>
            </li>
            <li class="mparent">
                <a href="<?= Yii::app()->controller->createUrl('orders/index'); ?>">
                    <p>Dịch vụ của tôi</p>
                </a>
            </li>
            <li class="mparent">
                <a href="<?= $url_changepass ?>">
                    <p>Thay đổi mật khẩu</p>
                </a>
            </li>
            <li class="mparent">
                <a href="<?= Yii::app()->controller->createUrl('site/logout'); ?>">
                    <p>Đăng xuất</p>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
<script>
    $(function () {
        $('nav#menu').mmenu({
            extensions: true,
            searchfield: false,
            counters: false,
            openingInterval: 0,
            transitionDuration: 5,
            navbar: {
                title: ''
            },
            slidingSubmenus: true
        });
    });
    $(function () {
        $('nav#menu_right').mmenu({
            extensions: true,
            searchfield: false,
            counters: false,
            openingInterval: 0,
            transitionDuration: 5,
            navbar: {
                title: ''
            },
            offCanvas: {
                position: "right"
            }
        });
    });
</script>