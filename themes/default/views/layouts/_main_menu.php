<?php
$controller = Yii::app()->controller->id;
$action = strtolower(Yii::app()->controller->action->id);
if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
    $users = WCustomers::model()->findByAttributes(array('id' => Yii::app()->user->customer_id));
    if ($users) {
        if ($users->sso_id) {
            $data = array(
                'user_id' => !empty($users->sso_id) ? $users->sso_id : '',
            );
            $data = http_build_query($data);
            $data = Utils::encrypt($data, Yii::app()->params['aes_key'] . date('Ymdhi'), MCRYPT_RIJNDAEL_128);

            $url_changepass = 'http://' . SERVER_HTTP_HOST . '/sso/changepass/001?data=' . $data;
        }
    }
}

?>
<div class="menu-main-desktop-new">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <?php
                $controller = Yii::app()->getController();
                $default_controller = Yii::app()->defaultController;
                $isHome = (($controller->id === 'site') && ($controller->action->id === 'index')) ? true : false;
                ?>
                <?php if ($isHome) { ?>
                    <div class="logo-destop-new">
                        <a href="<?= Yii::app()->controller->createUrl('site/index') ?>">
                            <h1 class="logo-h1"><img src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_vnpt.png" alt=""></h1>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="logo-destop-new">
                        <a href="<?= Yii::app()->controller->createUrl('site/index') ?>"><img src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_vnpt.png" alt="">
                        </a>
                    </div>
                <?php } ?>

            </div>
            <div class="col-lg-10">
                <div class="menu-destop-new">
                    <nav class="navbar navbar-inverse">
                        <div class="container-fluid">
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Khách hàng cá nhân">Di động
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu drop-custom-menu">
                                        <li><a href="<?= Yii::app()->controller->createUrl('sim/index'); ?>">Sim số</a>
                                        </li>
                                        <li><a href="<?= Yii::app()->controller->createUrl('package/index'); ?>">Gói
                                                cước</a>
                                        </li>
                                        <li><a href="<?= Yii::app()->controller->createUrl('card/topup'); ?>">Nạp
                                                thẻ</a></li>
                                        <li><a href="<?= Yii::app()->controller->createUrl('roaming/index'); ?>">Gói
                                                cước roaming</a></li>
                                        <li>
                                            <a href="<?= Yii::app()->controller->createUrl('prepaidtopostpaid/index'); ?>">Chuyển
                                                đổi trả trước sang trả sau</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Khách hàng cá nhân">Internet - truyền hình
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu drop-custom-menu">
                                        <li>
                                            <a href="<?= Yii::app()->controller->createUrl('package/indexfiber'); ?>">Internet
                                                cáp quang</a>
                                        </li>
                                        <li>
                                            <a href="<?= Yii::app()->controller->createUrl('package/indexmytv'); ?>">Truyền
                                                hình MyTV</a>
                                        </li>
                                        <li>
                                            <a href="<?= Yii::app()->controller->createUrl('package/indexCombo'); ?>">Internet & Truyền hình</a>
                                        </li>
                                        <li>
                                            <a href="<?= Yii::app()->controller->createUrl('package/indexHomeBundle'); ?>">Internet truyền hình & Di động</a>
                                        </li>
                                    </ul>
                                </li>

                                <!--<li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Khách hàng doanh nghiệp">Khách hàng doanh nghiệp
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Page 1-1</a></li>
                                        <li><a href="#">Page 1-2</a></li>
                                        <li><a href="#">Page 1-3</a></li>
                                    </ul>
                                </li>-->
                                <li>
                                    <a href="http://sohuong.vinaphone.com.vn/home/index.jsp?gclid=EAIaIQobChMIwePUrerX3gIVTI6PCh2VAQ65EAAYASAAEgLe1fD_BwE" title="Chuyển mạng giữ số" target="_blank">
                                        Chuyển mạng giữ số
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $GLOBALS['config_common']['domain_related']['affiliate'] ?>" title="Kiếm tiền online">
                                        Kiếm tiền online
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>