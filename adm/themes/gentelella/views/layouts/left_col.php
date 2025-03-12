<?php
$controller_id = Yii::app()->controller->id;
$action_id = Yii::app()->controller->action->id;
?>

<div class="col-md-3 left_col">
    <div class="left_col scroll-view unselectable">

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
                    <span>Welcome</span>
                    <h2><?php echo Yii::app()->user->name ?></h2>
                </div>
            </div>
        <?php } ?>
        <div class="clearfix"></div>

        <!-- /menu prile quick info -->
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <?php
            $active_menu_khcn = (in_array($controller_id, array('aSim', 'aTokenLinks', 'aSimAgency', 'aAgencyContract'))
                ||  in_array($controller_id, array('aOrders', 'aLogMt', 'aOrderWarning','aChanges','aCompleteOrders'))
                ||  in_array($controller_id, array('aTraffic'))
                ||  in_array($controller_id, array('aPrepaidtopostpaid'))
                ||  in_array($controller_id, array('aCardStore'))
                ||  in_array($controller_id, array('aTransactionRequest', 'aTransactionResponse'))
                ||  in_array($controller_id, array('report', 'reportTraffic', 'aReportAccesstrade', 'operation'))

                ||  in_array($controller_id, array('aPackage', 'aNewsCategories', 'aNews' , 'aNewsComments', 'aBanners', 'aCategoryQa', 'aQuestionAnswer'))
                ||  in_array($controller_id, array('aNations', 'aProvince', 'aSaleOffices', 'aBrandOffices'))
                ||  in_array($controller_id, array('aLocationVietinbank', 'aLocationNapas', 'aLocationVnptpay'))
                ||  in_array($controller_id, array('aAffiliateManager', 'aCampaignConfigs'))
                ||  in_array($controller_id, array('aWCMatch', 'aWCReport'))
                ||  in_array($controller_id, array('aPosts', 'aComments', 'aPostCategory', 'aRedeemHistory', 'aHobbies', 'aCustomers'))
                ||  in_array($controller_id, array('aSurvey', 'aSurveyQuestion', 'aSurveyReport'))

            ) ? true : false;

            $active_menu_khdn = (in_array($controller_id, array('aFTUsers', 'aFTContracts', 'aFTPackage', 'aFTOrders', 'aFTReport'))
            ) ? true : false;

            if(!$active_menu_khcn && !$active_menu_khdn) {
                $active_menu_khcn = true;
            }
            ?>

            <?php $this->widget(
                'booster.widgets.TbTabs',
                array(
                    'type'        => 'tabs',
                    'encodeLabel' => false,
                    'tabs'        => array(
                        array(
                            'label'   => '<b>KHCN</b>',
                            'content' => $this->renderPartial('//layouts/_menu_khcn',array(), TRUE),
                            'active'  => $active_menu_khcn,
                        ),
                        array(
                            'label'   => '<b>KHDN</b>',
                            'content' => $this->renderPartial('//layouts/_menu_khdn',array(), TRUE),
                            'active'  => $active_menu_khdn,
                        ),
                    ),
                )
            );
            ?>

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
                            'url'     => array('/user/admin'),
                            'label'   => '<i class="fa fa-cogs"></i> ' . Yii::app()->getModule('user')->t("Manage Users"),
                            'visible' => ((Yii::app()->user->checkAccess('User.Admin.Admin') || Yii::app()->user->checkAccess('User.Admin.*'))),
                        ),
                        array(
                            'url'     => 'javascript:;',
                            'label'   => '<i class="fa fa-user"></i> ' . Yii::app()->getModule('user')->t("Profile") . ' <span class="fa fa-chevron-down"></span>',
                            'visible' => !Yii::app()->user->isGuest || (!ADMIN_CSKH),
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
                            'url'     => 'javascript:;',
                            'label'   => '<i class="fa fa-shield"></i> ' . Rights::t('core', 'Assignments') . ' <span class="fa fa-chevron-down"></span>',
                            'visible' => Yii::app()->user->checkAccess("Admin"),
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
                    ),
                ));
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
                            'url'     => array('/aClearCache/index'),
                            'label'   => '<i class="fa fa-eraser"></i> ' . Yii::t('app', 'Quản lý Cache'),
                            'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->checkAccess('AClearCache.Index') || Yii::app()->user->checkAccess('AClearCache.*')),
                        ),
                        array(
                            'url'     => array('/aBackendLogs/admin'),
                            'label'   => '<i class="fa fa-file-text-o"></i> ' . Yii::t('app', 'Log hệ thống'),
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

