<div class="top_nav">

    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <?php
            $this->widget('zii.widgets.CMenu', array(
                'encodeLabel' => false,
                'htmlOptions' => array(
                    'class' => 'nav navbar-nav navbar-right',
                ),
                'submenuHtmlOptions' => array(
                    'class' => 'dropdown-menu dropdown-usermenu animated fadeInDown pull-right',
                ),
                'items' => array(
                    array(
                        'url' => Yii::app()->getModule('user')->loginUrl,
                        'label' => '<i class="fa fa-sign-in"></i> ' . Yii::app()->getModule('user')->t("Login"),
                        'visible' => Yii::app()->user->isGuest,
                    ),
                    array(
                        'url' => 'javascript:;',
                        'linkOptions' => array(
                            'class' => 'user-profile dropdown-toggle',
                            'data-toggle' => 'dropdown',
                            'aria-expanded' => 'false',
                        ),
                        'label' => '<img src="' . Yii::app()->theme->baseUrl . '/images/img.jpg" alt="">' . Yii::app()->user->name . ' <span class=" fa fa-angle-down"></span>',
                        'visible' => !Yii::app()->user->isGuest,
                        'items' => array(
                            array(
                                'url' => Yii::app()->getModule('user')->profileUrl,
                                'label' => Yii::app()->getModule('user')->t("Profile"),
                            ),
                            array(
                                'url' => Yii::app()->getModule('user')->logoutUrl,
                                'label' => '<i class="fa fa-sign-out pull-right"></i> ' . Yii::app()->getModule('user')->t("Logout"),
                            ),
                        ),
                    ),
                    array(
                        'url' => 'javascript:;',
                        'linkOptions' => array(
                            'class' => 'user-profile dropdown-toggle',
                            'data-toggle' => 'dropdown',
                            'aria-expanded' => 'false',
                        ),
                        'label' => '<i class="fa fa-file"></i> Tài liệu' . ' <span class=" fa fa-angle-down"></span>',
                        'visible' => !Yii::app()->user->isGuest,
                        'items' => array(
                            array(
                                'url' => Yii::app()->createUrl('download/index', array(
                                    'folder' => 'document',
                                    'file' => 'tai_lieu_hoan_thien_don_hang_chon_so',
                                    'type' => 'docx'
                                )),
                                'label' => '<i class="fa fa-file"></i> Tài liệu HT ĐH chọn số',
                            ),
                            array(
                                'url' => Yii::app()->createUrl('download/index', array(
                                    'folder' => 'document',
                                    'file' => 'huong_dan_khoi_tao_esim_tren_freedoo',
                                    'type' => 'docx'
                                )),
                                'label' => '<i class="fa fa-file"></i> HD Khởi Tạo ESIM trên Freedoo',
                            ),
                        ),
                    ),
                ),
            ));
            ?>
        </nav>
    </div>

</div>