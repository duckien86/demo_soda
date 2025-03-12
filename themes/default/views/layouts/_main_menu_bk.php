<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
    if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
        $users = WCustomers::model()->findByAttributes(array('id' => Yii::app()->user->customer_id));
        if ($users) {
            if ($users->sso_id) {
                $data = array(
                    'user_id' => !empty($users->sso_id) ? $users->sso_id : '',
                );
                $data = http_build_query($data);
                $data = Utils::encrypt($data, Yii::app()->params['aes_key'].date('Ymdhi'), MCRYPT_RIJNDAEL_128);

                $url_changepass = 'http://' . SERVER_HTTP_HOST . '/sso/changepass/001?data=' . $data;
            }
        }
    }

?>
<div class="main_menu">
    <div class="container">
        <div class="row">
            <div id="menu" class="col-md-10 col-xs-10">
                <ul class="topnav">
                    <li>
                        <a href="#" title="Sản phẩm" class="parent">
                            Sản phẩm
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>

                        <div class="sub_menu">
                            <div class="container">
                                <div class="content_sub_menu">
                                    <div class="item">
                                        <a href="<?= Yii::app()->controller->createUrl('sim/index'); ?>">
                                            <div class="icon_menu">
                                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sim.png">
                                            </div>
                                            <div>
                                                <!--<div class="title">Dịch vụ</div>-->
                                                <div class="txt_strong">Sim số</div>
                                            </div>
                                            <?php if ($controller == 'sim'): ?>
                                                <div id="line_menu_1"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="<?= Yii::app()->controller->createUrl('package/index'); ?>">
                                            <div class="icon_menu">
                                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_package.png">
                                            </div>
                                            <div>
                                                <!--                                                <div class="title">Dịch vụ</div>-->
                                                <div class="txt_strong">Gói cước</div>
                                            </div>

                                            <?php if ($controller == 'package'): ?>
                                                <div id="line_menu_2"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="<?= Yii::app()->controller->createUrl('card/topup'); ?>">
                                            <div class="icon_menu">
                                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_topup.png">
                                            </div>
                                            <div>
                                                <!--                                                <div class="title">Dịch vụ</div>-->
                                                <div class="txt_strong">Nạp thẻ</div>
                                            </div>

                                            <?php if ($controller == 'card' && $action == 'topup'): ?>
                                                <div id="line_menu_3"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="<?= Yii::app()->controller->createUrl('card/buycard'); ?>">
                                            <div class="icon_menu">
                                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_card.png">
                                            </div>
                                            <div>
                                                <!--                                                <div class="title">Dịch vụ</div>-->
                                                <div class="txt_strong">Mua mã thẻ</div>
                                            </div>

                                            <?php if ($controller == 'card' && $action == 'buycard'): ?>
                                                <div id="line_menu_4"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="item_lg">
                                        <a href="<?= Yii::app()->controller->createUrl('roaming/index'); ?>">
                                            <div class="icon_menu">
                                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_roaming.png">
                                            </div>
                                            <div>
                                                <div class="txt_strong">Gói cước Roaming</div>
                                            </div>

                                            <?php if ($controller == 'roaming' && $action == 'index'): ?>
                                                <div id="line_menu_4"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>

                                    <div class="space_20"></div>

                                    <div class="item" style="width: 50%">
                                        <a href="<?= Yii::app()->controller->createUrl('prepaidtopostpaid/index'); ?>">
                                            <div class="icon_menu">
                                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sim.png">
                                            </div>
                                            <div>
                                                <div class="txt_strong">Chuyển đổi thuê bao trả sau</div>
                                            </div>
                                            <?php if ($controller == 'prepaidtopostpaid'): ?>
                                                <div id="line_menu_4"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="<?= $GLOBALS['config_common']['domain_related']['social'] ?>" title="Cộng đồng"
                           class="parent" target="_blank">
                            Cộng đồng
                        </a>
                    </li>
                    <li class="">
                        <a href="<?= $GLOBALS['config_common']['domain_related']['affiliate'] ?>" target="_blank"
                           title="Cộng tác viên" class="parent">
                            Cộng tác viên
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::app()->controller->createUrl('help/index') ?>" title="Hỗ trợ" class="parent">
                            Hỗ trợ
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::app()->controller->createUrl('news/index') ?>" title="Tin tức" class="parent">
                            Tin tức
                        </a>
                    </li>
                    <li>
                        <a href="http://my.vinaphone.com.vn/users/updatesubinfo" target="_blank" title="Tra cứu và hoàn thiện thông tin thuê bao Nghị định 49" class="parent">
                            Tra cứu và hoàn thiện TTTB NĐ49
                        </a>
                    </li>
                </ul>
            </div>
            <!--<div class="col-md-2 col-xs-3 no_pad">
                <div class="nav navbar-nav">
                    <div class="input-group stylish-input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-addon">
                        <button type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </span>
                    </div>
                </div>
            </div>-->
            <div class="col-md-2 col-xs-2">
                <div class="fr">
<!--                    <div class="info">-->
<!--                        <img src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/images/icon-2-mail.png">-->
<!--                    </div>-->
<!--                    <div class="info email">-->
<!--                        <a href="mailto:freedoo@vnpt.vn" title="">freedoo@vnpt.vn</a>-->
<!--                    </div>-->
                    <div class="info icon-user menu_user">
                        <?php if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
                            ?>
                            <a href="<?= Yii::app()->controller->createUrl('site/profile'); ?>">
                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_user.png">
                                <span class="lbl_color_pink"><?= Yii::app()->user->username; ?></span>
                            </a>
                            <ul class="sub_menu_user dropdown-menu">
                                <li>
                                    <a href="<?= Yii::app()->controller->createUrl('site/profile'); ?>" title="">
                                        Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->controller->createUrl('orders/index'); ?>" title="">
                                        Dịch vụ của tôi
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= ($url_changepass != '') ? $url_changepass : '' ?>" title="">
                                        Đổi mật khẩu
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::app()->controller->createUrl('site/logout'); ?>" title="">
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        <?php } else {
                            $url_login = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
                            ?>
                            <a href="<?= $url_login ?>">
                                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_user.png"></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>