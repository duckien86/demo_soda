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
    <ul class="nav">
        <?php
        $this->widget('zii.widgets.CMenu',
            array(
                'encodeLabel'        => FALSE,
                'htmlOptions'        => array(
                    'class' => 'nav',
                ),
                'items'              => array(
                    array(
                        'url'     => Yii::app()->createUrl('site/index'),
                        'label'   => Yii::t('tourist/label', 'dashboard'),
                        'itemOptions' => array(
                            'class' => (Yii::app()->controller->id == 'site') ? 'active' : '',
                        )
                    ),
                    array(
                        'url'     => '',
                        'label'   => Yii::t('tourist/label', 'order_create'),
                        'itemOptions' => array(
                            'class' => (Yii::app()->controller->id == 'order' && Yii::app()->controller->action->id == 'create') ? 'active' : '',
                        ),
                        'items'   => array(
                            array(
                                'url'     => Yii::app()->createUrl('orderCtv/create'),
                                'label'   => Yii::t('tourist/label', 'order_normal'),
                            ),
                            array(
                                'url'     => Yii::app()->createUrl('orderCtv/create', array('type' => TOrders::TYPE_WITH_FILE_SIM)),
                                'label'   => Yii::t('tourist/label', 'order_file_sim'),
                            ),
                        ),
                    ),
                    array(
                        'url'     => Yii::app()->createUrl('orderCtv/admin'),
                        'label'   => Yii::t('tourist/label', 'orders'),
                        'itemOptions' => array(
                            'class' => (Yii::app()->controller->id == 'order' && Yii::app()->controller->action->id == 'admin') ? 'active' : '',
                        )
                    ),
                    array(
                        'url'     => Yii::app()->createUrl('report'),
                        'label'   => 'Báo cáo tổng quan',
                        'itemOptions' => array(
                            'class' => (Yii::app()->controller->id == 'report' && Yii::app()->controller->action->id == 'overview') ? 'active' : '',
                        ),
                        'visible' => (isset(Yii::app()->user->invite_code) && !empty(Yii::app()->user->invite_code)),
                    ),
                    array(
                        'url'     => Yii::app()->createUrl('report/remuneration'),
                        'label'   => Yii::t('tourist/label', 'report_remuneration'),
                        'itemOptions' => array(
                            'class' => (Yii::app()->controller->id == 'report' && Yii::app()->controller->action->id == 'remuneration') ? 'active' : '',
                        ),
                        'visible' => (isset(Yii::app()->user->invite_code) && !empty(Yii::app()->user->invite_code)),
                    ),
                ),
            )
        );
        ?>
    </ul>
</nav>

<?php if (!Yii::app()->user->isGuest): ?>
    <nav id="menu_right" class="left_menu">
        <ul class="nav">
            <?php
            $this->widget('zii.widgets.CMenu',
                array(
                    'encodeLabel'        => FALSE,
                    'htmlOptions'        => array(
                        'class' => 'nav',
                    ),
                    'items'              => array(
                        array(
                            'url'     => Yii::app()->createUrl('user/info'),
                            'label'   => Yii::t('tourist/label', 'enterprises_info'),
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'user' && Yii::app()->controller->action->id == 'info') ? 'active' : '',
                            ),
                            'visible' => (Yii::app()->user->user_type != TUsers::USER_TYPE_CTV)
                        ),
                        array(
                            'url'     => Yii::app()->createUrl('user/changePassword'),
                            'label'   => Yii::t('tourist/label', 'change_password'),
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'user' && Yii::app()->controller->action->id == 'ChangePassword') ? 'active' : '',
                            ),
                            'visible' => (Yii::app()->user->user_type != TUsers::USER_TYPE_CTV)
                        ),
                        array(
                            'url'     => Yii::app()->createUrl('user/logout'),
                            'label'   => Yii::t('tourist/label', 'logout'),
                        ),
                    ),
                )
            );
            ?>
        </ul>
    </nav>
<?php endif; ?>

<?php $this->renderPartial('/order/_modal_choose_order_ctv'); ?>

<script type="text/javascript">
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