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
//                                array(
//                                    'label'   => '<i class="fa fa-list"></i> ' . Yii::t('adm/menu', 'manage_menu'),
//                                    'url'     => array('/aMenu'),
//                                    'visible' => Yii::app()->user->checkAccess('aMenu.*')
//                                ),
                                array(
                                    'label'   => '<i class="fa fa-picture-o"></i> ' . Yii::t('adm/menu', 'manage_banner'),
                                    'url'     => array('/aBanners'),
                                    'visible' => Yii::app()->user->checkAccess('aBanners.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-cog"></i> ' . Yii::t('adm/menu', 'manage_payment_method'),
                                    'url'     => array('/aPaymentMethod'),
                                    'visible' => Yii::app()->user->checkAccess('aPaymentMethod.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-shopping-cart"></i> ' . Yii::t('adm/menu', 'manage_orders'),
                                    'url'     => array('/aOrders'),
                                    'visible' => Yii::app()->user->checkAccess('aOrders.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-search"></i> ' . 'Tra cứu MT',
                                    'url'     => array('/aLogMt/admin'),
                                    'visible' => Yii::app()->user->checkAccess('aLogMt.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-shopping-cart"></i> ' . 'Đơn hàng rác',
                                    'url'     => array('/aOrders/adminRecycle'),
                                    'visible' => Yii::app()->user->checkAccess('AOrders.AdminRecycle')
                                ),
                                array(
                                    'url'     => 'javascript:;',
                                    'label'   => '<i class="fa fa-truck"></i> ' . Yii::t('adm/menu', 'manage_trafic') . ' <span class="fa fa-chevron-down"></span>',
                                    'visible' => (Yii::app()->user->checkAccess('aTraffic.*')
                                        || Yii::app()->user->checkAccess('aTraffic.AdminAssign')
                                        || Yii::app()->user->checkAccess('aTraffic.AdminAssignChange')
                                        || Yii::app()->user->checkAccess('aTraffic.AdminReturn')
                                        || Yii::app()->user->checkAccess('aShipper.Admin')
                                        || Yii::app()->user->checkAccess('aShipper.RenueveTraffic')),
                                    'items'   => array(
                                        array(
                                            'label'   => Yii::t('adm/menu', 'assignment_shipper'),
                                            'url'     => array('/aTraffic/adminAssign'),
                                            'visible' => Yii::app()->user->checkAccess('aTraffic.AdminAssign') || Yii::app()->user->checkAccess('aTraffic.*'),
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/menu', 'assignment_change_shipper'),
                                            'url'     => array('/aTraffic/adminAssignChange'),
                                            'visible' => Yii::app()->user->checkAccess('aTraffic.AdminAssignChange') || Yii::app()->user->checkAccess('aTraffic.*'),
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/menu', 'assignment_return'),
                                            'url'     => array('/aTraffic/adminReturn'),
                                            'visible' => Yii::app()->user->checkAccess('aTraffic.AdminReturn') || Yii::app()->user->checkAccess('aTraffic.*'),
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/menu', 'receive_shipper_manage'),
                                            'url'     => array('/aTraffic/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aTraffic.AdminReturn') || Yii::app()->user->checkAccess('aTraffic.*'),
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/menu', 'manage_shipper'),
                                            'url'     => array('/aShipper/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aShipper.Admin') || Yii::app()->user->checkAccess('aShipper.*'),
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/menu', 'report_renueve_traffic'),
                                            'url'     => array('/aTraffic/renueveTraffic'),
                                            'visible' => Yii::app()->user->checkAccess('aTraffic.RenueveTraffic') || Yii::app()->user->checkAccess('aTraffic.*'),
                                        ),
//                                        array(
//                                            'label' => Yii::t('adm/menu', 'renueve_shipper'),
//                                            'url'   => array('/aShipper/adminRenueve'),
//                                        ),
                                    ),
                                ),
                                array(
                                    'label'   => '<i class="fa fa-shopping-cart"></i> ' . Yii::t('adm/menu', 'complete_order'),
                                    'url'     => array('/aCompleteOrders'),
                                    'visible' => Yii::app()->user->checkAccess('aCompleteOrders.*')
                                ),
                                array(
                                    'url'     => 'javascript:;',
                                    'label'   => '<i class="fa fa-newspaper-o"></i> ' . Yii::t('adm/menu', 'manage_news') . ' <span class="fa fa-chevron-down"></span>',
                                    'visible' => (Yii::app()->user->checkAccess('aNewsCategories.*') || Yii::app()->user->checkAccess('aNews.*')),
                                    'items'   => array(
                                        array(
                                            'label'   => Yii::t('adm/menu', 'manage_news_cate'),
                                            'url'     => array('/aNewsCategories/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aNewsCategories.*')
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/menu', 'manage_news'),
                                            'url'     => array('/aNews/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aNews.*')
                                        ),
                                    ),
                                ),
                                array(
                                    'label'   => '<i class="fa fa-cube"></i> ' . Yii::t('adm/menu', 'manage_package'),
                                    'url'     => array('/aPackage'),
                                    'visible' => Yii::app()->user->checkAccess('aPackage.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-phone"></i> ' . Yii::t('adm/menu', 'manage_subscription_type'),
                                    'url'     => array('/aSubscriptionType'),
                                    'visible' => Yii::app()->user->checkAccess('aSubscriptionType.*')
                                ),

                                array(
                                    'label'   => '<i class="fa fa-map-marker"></i> ' . Yii::t('adm/menu', 'manage_brand_office'),
                                    'url'     => array('/aBrandOffices'),
                                    'visible' => Yii::app()->user->checkAccess('aBrandOffices.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-user"></i> ' . Yii::t('adm/label', 'manage_customer'),
                                    'url'     => array('/aCustomers/admin'),
                                    'visible' => Yii::app()->user->checkAccess('aCustomers.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-question-circle"></i> ' . Yii::t('adm/label', 'manage_qa'),
                                    'url'     => array('/aQuestionAnswer/admin'),
                                    'visible' => Yii::app()->user->checkAccess('aQuestionAnswer.*')
                                ),
                                array(
                                    'label'   => '<i class="fa fa-newspaper-o"></i> ' . Yii::t('adm/label', 'manage_cate_qa'),
                                    'url'     => array('/aCategoryQa/admin'),
                                    'visible' => Yii::app()->user->checkAccess('aCategoryQa.*')
                                ),
                                array(
                                    'url'     => 'javascript:;',
                                    'label'   => '<i class="fa fa-file-text-o"></i> ' . Yii::t('adm/label', 'manage_social') . ' <span class="fa fa-chevron-down"></span>',
                                    'visible' => (Yii::app()->user->checkAccess('aPosts.*')
                                        || Yii::app()->user->checkAccess('AComments.*') || Yii::app()->user->checkAccess('aPostCategory.*')
                                        || Yii::app()->user->checkAccess('aRedeemHistory.*') || Yii::app()->user->checkAccess('aHobbies.*')),
                                    'items'   => array(
                                        array(
                                            'label'   => Yii::t('adm/label', 'manage_post'),
                                            'url'     => array('/aPosts/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aPosts.*')
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/label', 'manage_comment'),
                                            'url'     => array('/aComments/admin'),
                                            'visible' => Yii::app()->user->checkAccess('AComments.*')
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/label', 'manage_post_cate'),
                                            'url'     => array('/aPostCategory/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aPostCategory.*')
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/label', 'manage_redeem'),
                                            'url'     => array('/aRedeemHistory/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aRedeemHistory.*')
                                        ),
                                        array(
                                            'label'   => Yii::t('adm/label', 'manage_hobbies'),
                                            'url'     => array('/aHobbies/admin'),
                                            'visible' => Yii::app()->user->checkAccess('aHobbies.*')
                                        ),
                                    ),
                                ),

                                array(
                                    'url'     => 'javascript:;',
                                    'label'   => '<i class="fa fa-file-text-o"></i> ' . Yii::t('adm/label', 'report_social') . ' <span class="fa fa-chevron-down"></span>',
                                    'visible' => Yii::app()->user->checkAccess('aReportSocial.*'),
                                    'items'   => array(
                                        array(
                                            'label' => Yii::t('adm/label', 'report_social_index'),
                                            'url'   => array('/aReportSocial/index'),
                                        ),
                                        array(
                                            'label' => Yii::t('adm/label', 'report_social_user'),
                                            'url'   => array('/aReportSocial/reportuser'),
                                        ),
                                        array(
                                            'label' => Yii::t('adm/label', 'report_social_detail_user'),
                                            'url'   => array('/aReportSocial/detailuser'),
                                        ),

                                    ),
                                ),

                            ),
                        )
                    );
                ?>
            </div>
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
                                    'url'     => 'javascript:;',
                                    'label'   => '<i class="fa fa-calendar"></i> ' . Yii::t('report/menu', 'report') . ' <span class="fa fa-chevron-down"></span>',
                                    'visible' => (Yii::app()->user->checkAccess('Report.*') || Yii::app()->user->checkAccess('Report.Sim')
                                        || Yii::app()->user->checkAccess('Report.Package')
                                        || Yii::app()->user->checkAccess('Report.PackageSimKit') || Yii::app()->user->checkAccess('Report.PackageFlexible')),
                                    'items'   => array(
//                                        array(
//                                            'label' => Yii::t('report/menu', 'report_index'),
//                                            'url'   => array('/report/index'),
//                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'report_sim'),
                                            'url'   => array('/report/sim'),
                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'report_package'),
                                            'url'   => array('/report/package'),
                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'packageSimKit'),
                                            'url'   => array('/report/packageSimKit'),
                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'report_flexible'),
                                            'url'   => array('/report/packageFlexible'),
                                        ),

                                    ),
                                ),
                                array(
                                    'url'     => 'javascript:;',
                                    'label'   => '<i class="fa fa-list"></i> ' . Yii::t('report/menu', 'report_ctv') . ' <span class="fa fa-chevron-down"></span>',
                                    'visible' => Yii::app()->user->checkAccess('ReportCtv.*'),
                                    'items'   => array(
                                        array(
                                            'label' => Yii::t('report/menu', 'simRenueve'),
                                            'url'   => array('/reportCtv/simRenueve'),
                                        ),
                                        array(
                                            'label' => Yii::t('/report/menu', 'packageRenueve'),
                                            'url'   => array('/reportCtv/packagerenueve'),
                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'packageMaintainRenueve'),
                                            'url'   => array('reportCtv/packageMaintainRenueve'),
                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'introduceCTV'),
                                            'url'   => array('/reportCtv/introduceRenueve'),
                                        ),
                                        array(
                                            'label' => Yii::t('report/menu', 'supportCTV'),
                                            'url'   => array('/reportCtv/supportRenueve'),
                                        ),
                                    ),
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
                            'style' => 'display: none',
                        ),
                        'items'              => array(
                            array(
                                'url'     => 'javascript:;',
                                'label'   => '<i class="fa fa-shield"></i> ' . Rights::t('core', 'Assignments') . ' <span class="fa fa-chevron-down"></span>',
                                'visible' => (Yii::app()->user->checkAccess("Admin") || Yii::app()->user->checkAccess("Rights.AuthItem.*")
                                    || Yii::app()->user->checkAccess("Rights.AuthItem.View") || Yii::app()->user->checkAccess("Rights.AuthItem.Permissions")
                                    || Yii::app()->user->checkAccess("Rights.AuthItem.Roles") || Yii::app()->user->checkAccess("Rights.AuthItem.Tasks")
                                    || Yii::app()->user->checkAccess("Rights.AuthItem.Operations") || Yii::app()->user->checkAccess("Rights.Assignment.*")),
                                'items'   => array(
                                    array(
                                        'label'   => Rights::t('core', 'Assignments'),
                                        'url'     => array('/rights/assignment/view'),
                                        'visible' => Yii::app()->user->checkAccess("Rights.Assignment.View") || Yii::app()->user->checkAccess("Admin") || Yii::app()->user->checkAccess("Rights.Assignment.*"),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Permissions'),
                                        'url'   => array('/rights/authItem/permissions'),
                                        'visible' => Yii::app()->user->checkAccess("Rights.AuthItem.Permissions") || Yii::app()->user->checkAccess("Admin") || Yii::app()->user->checkAccess("Rights.AuthItem.*"),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Roles'),
                                        'url'   => array('/rights/authItem/roles'),
                                        'visible' => Yii::app()->user->checkAccess("Rights.AuthItem.Roles") || Yii::app()->user->checkAccess("Admin") || Yii::app()->user->checkAccess("Rights.AuthItem.*"),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Tasks'),
                                        'url'   => array('/rights/authItem/tasks'),
                                        'visible' => Yii::app()->user->checkAccess("Rights.AuthItem.Tasks") || Yii::app()->user->checkAccess("Admin") || Yii::app()->user->checkAccess("Rights.AuthItem.*"),
                                    ),
                                    array(
                                        'label' => Rights::t('core', 'Operations'),
                                        'url'   => array('/rights/authItem/operations'),
                                        'visible' => Yii::app()->user->checkAccess("Rights.AuthItem.Operations") || Yii::app()->user->checkAccess("Admin") || Yii::app()->user->checkAccess("Rights.AuthItem.*"),
                                    ),
                                ),
                            ),
                            array(
                                'url'     => array('/user/admin'),
                                'label'   => '<i class="fa fa-cogs"></i> ' . Yii::app()->getModule('user')->t("Manage Users"),
                                'visible' => (Yii::app()->user->checkAccess('User.Admin.Admin') || Yii::app()->user->checkAccess('User.Admin.*')),
                            ),
                            array(
                                'url'     => array('/user'),
                                'label'   => '<i class="fa fa-users"></i> ' . Yii::app()->getModule('user')->t("List User"),
                                'visible' => ((Yii::app()->user->checkAccess('User.Default.Index') || Yii::app()->user->checkAccess('User.Default.*')) && !Yii::app()->user->checkAccess('User.Admin.Admin') && !Yii::app()->user->checkAccess('User.Admin.*')),
                            ),
                            array(
                                'url'     => 'javascript:;',
                                'label'   => '<i class="fa fa-user"></i> ' . Yii::app()->getModule('user')->t("Profile") . ' <span class="fa fa-chevron-down"></span>',
                                'visible' => !Yii::app()->user->isGuest,
                                'items'   => array(
                                    array(
                                        'url'   => Yii::app()->getModule('user')->profileUrl,
                                        'label' => Yii::app()->getModule('user')->t("Profile"),
                                    ),
                                    array(
                                        'url'   => array('/user/profile/edit'),
                                        'label' => Yii::app()->getModule('user')->t("Edit"),
                                    ),
                                    array(
                                        'url'   => array('/user/profile/changepassword'),
                                        'label' => Yii::app()->getModule('user')->t("Change password"),
                                    ),
                                ),
                            ),

                            array(
                                'url'     => array('/aClearCache/index'),
                                'label'   => '<i class="fa fa-eraser"></i> ' . Yii::t('app', 'Quản lý Cache'),
                                'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->checkAccess('AClearCache.Index') || Yii::app()->user->checkAccess('AClearCache.*')),
                            ),
                            array(
                                'url'     => array('/aBackendLogs/admin'),
                                'label'   => '<i class="fa fa-eraser"></i> ' . Yii::t('app', 'Log hệ thống'),
                                'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->checkAccess('aBackendLogs.*') || Yii::app()->user->checkAccess('AClearCache.*')),
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