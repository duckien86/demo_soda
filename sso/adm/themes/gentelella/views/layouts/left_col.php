<div class="col-md-3 left_col">
    <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo $this->createUrl('/aSite/index') ?>" class="site_title"><i
                        class="fa fa-star-half-o"></i>
                <span><?php echo CHtml::encode(Yii::app()->name); ?></span></a>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <?php if (!Yii::app()->user->isGuest) { ?>
            <div class="profile">
                <div class="profile_pic">
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/img.jpg" alt="..."
                         class="img-circle profile_img">
                </div>
                <div class="profile_info">
                    <span>Welcome,</span>

                    <h2><?php echo Yii::app()->user->name ?></h2>
                </div>
            </div>
        <?php } ?>
        <!-- /menu prile quick info -->

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
                <?php
                    $this->widget('zii.widgets.CMenu',
                        array(
                            'encodeLabel'        => FALSE,
                            'htmlOptions'        => array(
                                'class' => 'nav side-menu',
                            ),
                            'submenuHtmlOptions' => array(
                                'class' => 'nav child_menu',
                                'style' => 'display: none',
                            ),
                            'items'              => array(
                                array(
                                    'label'   => '<i class="fa fa-user"></i> Quản lý người dùng',
                                    'url'     => array('/aUsers/admin'),
                                    'visible' => Yii::app()->user->checkAccess('ACategories.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-list" ></i> Quản lý đối tác',
                                    'url'     => array('/aPartner/admin'),
                                    'visible' => Yii::app()->user->checkAccess('AMedia.*'),
                                ),
                            ),
                        )
                    );
                ?>
            </div>

            <div class="menu_section">
                <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'encodeLabel'        => FALSE,
                        'htmlOptions'        => array(
                            'class' => 'nav side-menu',
                        ),
                        'submenuHtmlOptions' => array(
                            'class' => 'nav child_menu',
                        ),
                        'items'              => array(
                            array(
                                'url'     => 'javascript:;',
                                'label'   => '<i class="fa fa-shield"></i> ' . 'Phân quyền' . ' <span class="fa fa-chevron-down"></span>',
                                'visible' => Yii::app()->user->checkAccess('Admin'),
                                'items'   => array(
                                    array(
                                        'label' => Rights::t('core', 'Assignments'),
                                        'url'   => array('/rights/assignment/view'),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Permissions'),
                                        'url'   => array('/rights/authItem/permissions'),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Roles'),
                                        'url'   => array('/rights/authItem/roles'),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Tasks'),
                                        'url'   => array('/rights/authItem/tasks'),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Operations'),
                                        'url'   => array('/rights/authItem/operations'),
                                    ),
                                ),
                            ),
                            array(
                                'url'     => array('/user/admin'),
                                'label'   => '<i class="fa fa-users"></i> ' . "Người dùng hệ thống",
                                'visible' => (Yii::app()->user->checkAccess('User.Admin.Admin') || Yii::app()->user->checkAccess('User.Admin.*')),
                            ),
                            array(
                                'url'     => array('/user'),
                                'label'   => '<i class="fa fa-users"></i> ' . Yii::app()->getModule('user')->t("List User"),
                                'visible' => ((Yii::app()->user->checkAccess('User.Default.Index') || Yii::app()->user->checkAccess('User.Default.*')) && !Yii::app()->user->checkAccess('User.Admin.Admin') && !Yii::app()->user->checkAccess('User.Admin.*')),
                            ),
                            array(
                                'url'     => array('/aClearCache/index'),
                                'label'   => '<i class="fa fa-eraser"></i> ' . Yii::t('app', 'Quản lý Cache'),
                                'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->checkAccess('AClearCache.Index') || Yii::app()->user->checkAccess('AClearCache.*')),
                            ),
                            array(
                                'url'     => Yii::app()->getModule('user')->loginUrl,
                                'label'   => '<i class="fa fa-sign-in"></i> ' . Yii::app()->getModule('user')->t("Login"),
                                'visible' => Yii::app()->user->isGuest,
                            ),
                            array(
                                'url'     => Yii::app()->getModule('user')->logoutUrl,
                                'label'   => '<i class="fa fa-sign-out"></i> ' . Yii::app()->getModule('user')->t("Logout"),
                                'visible' => !Yii::app()->user->isGuest,
                            ),
                        ),
                    ));
                ?>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <?php if (!Yii::app()->user->isGuest) { ?>
            <div class="sidebar-footer hidden-small">
                <a href="<?php echo Yii::app()->request->url ?>" data-toggle="tooltip" data-placement="top"
                   title="<?php echo Yii::t('app', 'Refresh') ?>">
                    <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                </a>
                <a href="<?php echo $this->createUrl('/rights/authItem/generate') ?>" data-toggle="tooltip"
                   data-placement="top"
                   title="<?php echo Yii::t('app', 'Generate items') ?>">
                    <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
                </a>
                <a href="<?php echo $this->createUrl(Yii::app()->getModule('user')->profileUrl[0]) ?>"
                   data-toggle="tooltip" data-placement="top"
                   title="<?php echo Yii::app()->getModule('user')->t("Profile") ?>">
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                </a>
                <a href="<?php echo $this->createUrl(Yii::app()->getModule('user')->logoutUrl[0]) ?>"
                   data-toggle="tooltip" data-placement="top"
                   title="<?php echo Yii::app()->getModule('user')->t("Logout") ?>">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </a>
            </div>
        <?php } ?>
        <!-- /menu footer buttons -->
    </div>
</div>