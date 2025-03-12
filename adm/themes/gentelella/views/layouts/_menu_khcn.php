<?php
/**
 * @var $this Controller
 */
?>
<div class="menu_section">
<?php $this->widget('zii.widgets.CMenu', array(
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
            'url'     => array('/aSim/index'),
            'label'   => '<i class="fa fa-search"></i> Chọn số',
            'visible' => (Yii::app()->user->checkAccess('ASim.*')
            ),
        ),
        array(
            'url'     => 'javascript:;',
            'label'   => '<i class="fa fa-users"></i> ' . Yii::t('adm/menu', 'agency') . ' <span class="fa fa-chevron-down"></span>',
            'visible' => (
                (Yii::app()->user->checkAccess('ASimAgency.*') && !empty(Yii::app()->user->province_code))
                || (isset(Yii::app()->user->province_code) && Yii::app()->user->province_code == Yii::app()->params['CellPhoneS'] && Yii::app()->user->checkAccess('ASimAgency.*'))
                || Yii::app()->user->checkAccess('AAgencyContract.*')
            ),
            'items'   => array(
                array(
                    'label'   => Yii::t('adm/menu', 'search_agency_msisdn'),
                    'url'     => array('/aSimAgency/index'),
                    'visible' => ((Yii::app()->user->checkAccess('ASimAgency.*') && !empty(Yii::app()->user->province_code))
                        || (isset(Yii::app()->user->province_code) && Yii::app()->user->province_code == Yii::app()->params['CellPhoneS'] && Yii::app()->user->checkAccess('ASimAgency.*'))
                    )
                ),
                array(
                    'label'   => Yii::t('adm/menu', 'agency_contract'),
                    'url'     => array('/aAgencyContract/admin'),
                    'visible' => ((Yii::app()->user->checkAccess('AAgencyContract.*'))
                        || (Yii::app()->user->checkAccess('AAgencyContract.Admin'))
                    ),
                ),
            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-search"></i> Tra cứu <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('AOrders.*')
                || Yii::app()->user->checkAccess('ATraffic.*')
                || Yii::app()->user->checkAccess('APrepaidtopostpaid.*')
                || Yii::app()->user->checkAccess('ALogMt.*')

                || Yii::app()->user->checkAccess('AOrders.Admin')
                || Yii::app()->user->checkAccess('AOrders.Package')
                || Yii::app()->user->checkAccess('AOrders.AdminRecycle')
                || Yii::app()->user->checkAccess('ATraffic.AdminReturn')
                || Yii::app()->user->checkAccess('APrepaidtopostpaid.Admin')
                || Yii::app()->user->checkAccess('ALogMt.Admin')
            ),
            'items'     => array(
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Đơn hàng <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('AOrders.*')
                        || Yii::app()->user->checkAccess('ATraffic.*')
                        || Yii::app()->user->checkAccess('APrepaidtopostpaid.*')

                        || Yii::app()->user->checkAccess('AOrders.Admin')
                        || Yii::app()->user->checkAccess('AOrders.Package')
                        || Yii::app()->user->checkAccess('AOrders.AdminRecycle')
                        || Yii::app()->user->checkAccess('ATraffic.AdminReturn')
                        || Yii::app()->user->checkAccess('APrepaidtopostpaid.Admin')
                    ),
                    'items'     => array(
                        array(
                            'url'       => array('/aOrders/admin'),
                            'label'     => 'ĐH SIM',
                            'visible'   => (Yii::app()->user->checkAccess('AOrders.*')
                                || Yii::app()->user->checkAccess('AOrders.Admin')
                            ),

                        ),
                        array(
                            'url'       => array('/aOrders/packageSingle'),
                            'label'     => 'ĐH Gói (đơn lẻ)',
                            'visible'   => (Yii::app()->user->checkAccess('AOrders.*')
                                || Yii::app()->user->checkAccess('AOrders.Package')
                            ),

                        ),
                        array(
                            'url'       => array('/aOrders/adminRecycle'),
                            'label'     => 'ĐH rác',
                            'visible'   => (Yii::app()->user->checkAccess('AOrders.*')
                                || Yii::app()->user->checkAccess('AOrders.AdminRecycle')
                            ),
                        ),
                        array(
                            'url'       => array('/aTraffic/adminReturn'),
                            'label'     => 'ĐH gửi trả',
                            'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                                || Yii::app()->user->checkAccess('ATraffic.AdminReturn')
                            ),
                        ),
                        array(
                            'url'       => array('/aPrepaidtopostpaid/admin'),
                            'label'     => 'ĐH chuyển đổi trả sau',
                            'visible'   => (Yii::app()->user->checkAccess('APrepaidtopostpaid.*')
                                || Yii::app()->user->checkAccess('APrepaidtopostpaid.Admin')
                            ),
                        ),
                        array(
                            'url'       => array('/aOrders/searchFiber'),
                            'label'     => 'ĐH Internet & Truyền hình',
                            'visible'   => (Yii::app()->user->checkAccess('AOrders.*')
                                || Yii::app()->user->checkAccess('AOrders.SearchFiber')
                            ),
                        ),
                        array(
                            'url'       => array('/report/reportfiber'),
                            'label'     => 'Tra cứu ĐH Internet & Truyền hình',
                            'visible'   => (Yii::app()->user->checkAccess('report.*')
                                || Yii::app()->user->checkAccess('report.reportfiber')
                            ),
                        ),
                    ),
                ),
                array(
                    'url'       => array('/aLogMt/admin'),
                    'label'     => 'SMS',
                    'visible'   => (Yii::app()->user->checkAccess('ALogMt.*')
                        || Yii::app()->user->checkAccess('ALogMt.Admin')
                    ),
                ),
            ),
        ),
        array(
            'url'       => array('/aOrderWarning/admin'),
            'label'     => '<i class="fa fa-exclamation-triangle"></i> Đơn hàng cảnh báo',
            'visible'   => (Yii::app()->user->checkAccess('AOrderWarning.*')
                || Yii::app()->user->checkAccess('AOrderWarning.Admin')
            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-briefcase"></i> Quản lý nghiệp vụ <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                || Yii::app()->user->checkAccess('ACompleteOrders.*')

                || Yii::app()->user->checkAccess('ATraffic.Admin')
                || Yii::app()->user->checkAccess('ATraffic.AdminAssign')
                || Yii::app()->user->checkAccess('ATraffic.AdminAssignChange')
                || Yii::app()->user->checkAccess('ACompleteOrders.Admin')

                || Yii::app()->user->checkAccess('APosts.*')
                || Yii::app()->user->checkAccess('AComments.*')
                || Yii::app()->user->checkAccess('APostCategory.*')
                || Yii::app()->user->checkAccess('AHobbies.*')
                || Yii::app()->user->checkAccess('ACustomers.*')

                || Yii::app()->user->checkAccess('ASurvey.*')
                || Yii::app()->user->checkAccess('ASurveyQuestion.*')

                || Yii::app()->user->checkAccess('ACampaignConfigs.*')
                || Yii::app()->user->checkAccess('AAffiliateManager.*')

                || Yii::app()->user->checkAccess('ACategoryQa.*')
                || Yii::app()->user->checkAccess('AQuestionAnswer.*')
                || Yii::app()->user->checkAccess('ANewsCategories.*')
                || Yii::app()->user->checkAccess('ANews.*')
                || Yii::app()->user->checkAccess('ANewsComments.*')
                || Yii::app()->user->checkAccess('ABanners.*')
                || Yii::app()->user->checkAccess('APackage.*')
                || Yii::app()->user->checkAccess('ASubscriptionType.*')

                || Yii::app()->user->checkAccess('ANations.*')
                || Yii::app()->user->checkAccess('AProvince.*')
                || Yii::app()->user->checkAccess('ASaleOffices.*')
                || Yii::app()->user->checkAccess('ABrandOffices.*')

                || Yii::app()->user->checkAccess('ALocationVietinbank.*')
                || Yii::app()->user->checkAccess('ALocationNapas.*')
                || Yii::app()->user->checkAccess('ALocationVnptpay.*')

                || Yii::app()->user->checkAccess('AShipper.*')
            ),
            'items'     => array(
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Giao hàng <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                        || Yii::app()->user->checkAccess('ACompleteOrders.*')

                        || Yii::app()->user->checkAccess('ATraffic.AdminAssign')
                        || Yii::app()->user->checkAccess('ATraffic.AdminAssignChange')
                        || Yii::app()->user->checkAccess('ACompleteOrders.Admin')

                    ),
                    'items'     => array(
                        array(
                            'url'       => 'javascript:;',
                            'label'     => 'Giao hàng tại nhà <span class="fa fa-chevron-down"></span>',
                            'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                                || Yii::app()->user->checkAccess('ATraffic.AdminAssign')
                                || Yii::app()->user->checkAccess('ATraffic.AdminAssignChange')
                            ),
                            'items'     => array(
                                array(
                                    'url'       => array('/aTraffic/adminAssign'),
                                    'label'     => 'Phân công NV giao vận',
                                    'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                                        || Yii::app()->user->checkAccess('ATraffic.AdminAssign')
                                    ),
                                ),
                                array(
                                    'url'       => array('/aTraffic/adminAssignChange'),
                                    'label'     => 'Điều chuyển NV giao vận',
                                    'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                                        || Yii::app()->user->checkAccess('ATraffic.AdminAssignChange')
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'url'       => array('/aCompleteOrders/admin'),
                            'label'     => 'Giao hàng tại ĐGD',
                            'visible'   => (Yii::app()->user->checkAccess('ACompleteOrders.*')
                                || Yii::app()->user->checkAccess('ACompleteOrders.Admin')
                            ),
                        ),
                    ),
                ),
                array(
                    'url'       => array('/aTraffic/admin'),
                    'label'     => 'Thu tiền',
                    'visible'   => (Yii::app()->user->checkAccess('ATraffic.*')
                        || Yii::app()->user->checkAccess('ATraffic.Admin')
                    ),
                ),
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Diễn đàn (cộng đồng) <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('APosts.*')
                        || Yii::app()->user->checkAccess('AComments.*')
                        || Yii::app()->user->checkAccess('APostCategory.*')
                        || Yii::app()->user->checkAccess('AHobbies.*')
                        || Yii::app()->user->checkAccess('ACustomers.*')
                    ),
                    'items'     => array(
                        array(
                            'url'       => array('/aPosts/admin'),
                            'label'     => 'Bài đăng',
                            'visible'   => (Yii::app()->user->checkAccess('APosts.*')
                            ),
                        ),
                        array(
                            'url'       => array('/aComments/admin'),
                            'label'     => 'Bình luận',
                            'visible'   => (Yii::app()->user->checkAccess('AComments.*')
                            ),
                        ),
                        array(
                            'url'       => array('/aPostCategory/admin'),
                            'label'     => 'Danh mục chủ đề',
                            'visible'   => (Yii::app()->user->checkAccess('APostCategory.*')
                            ),
                        ),
                        array(
                            'url'       => array('/aHobbies/admin'),
                            'label'     => 'Sở thích',
                            'visible'   => (Yii::app()->user->checkAccess('AHobbies.*')
                            ),
                        ),
                        array(
                            'url'       => array('/aCustomers/admin'),
                            'label'     => 'Khách hàng',
                            'visible'   => (Yii::app()->user->checkAccess('ACustomers.*')
                            ),
                        ),
                    ),
                ),
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Khảo sát kháchh hàng <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('ASurvey.*')
                        || Yii::app()->user->checkAccess('ASurveyQuestion.*')
                    ),
                    'items'     => array(
                        array(
                            'url'       => array('/aSurvey/admin'),
                            'label'     => 'Danh sách bài khảo sát',
                            'visible'   => (Yii::app()->user->checkAccess('ASurvey.*')
                            ),
                        ),
                        array(
                            'url'       => array('/aSurveyQuestion/admin'),
                            'label'     => 'Danh sách câu hỏi',
                            'visible'   => (Yii::app()->user->checkAccess('ASurveyQuestion.*')
                            ),
                        ),

                    ),
                ),
                array(
                    'url'       => array('/aCampaignConfigs/admin'),
                    'label'     => 'Link quảng cáo',
                    'visible'   => (Yii::app()->user->checkAccess('ACampaignConfigs.*')
                    ),
                ),
                array(
                    'url'       => array('/aAffiliateManager/admin'),
                    'label'     => 'Affiliate Manager',
                    'visible'   => (Yii::app()->user->checkAccess('AAffiliateManager.*')
                    ),
                ),
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Nội dung Website <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('ACategoryQa.*')
                        || Yii::app()->user->checkAccess('AQuestionAnswer.*')
                        || Yii::app()->user->checkAccess('ANewsCategories.*')
                        || Yii::app()->user->checkAccess('ANews.*')
                        || Yii::app()->user->checkAccess('ANewsComments.*')
                        || Yii::app()->user->checkAccess('ABanners.*')
                        || Yii::app()->user->checkAccess('APackage.*')
                        || Yii::app()->user->checkAccess('AAgencyPackage.*')
                        || Yii::app()->user->checkAccess('ASubscriptionType.*')
                        || Yii::app()->user->checkAccess('AParentChildPackageCodes.*')
                    ),
                    'items'     => array(
                        array(
                            'url'     => array('/aCategoryQa/admin'),
                            'label'   => 'Danh mục câu hỏi Q&A',
                            'visible' => (Yii::app()->user->checkAccess('ACategoryQa.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aQuestionAnswer/admin'),
                            'label'   => 'Nội dung câu hỏi Q&A',
                            'visible' => (Yii::app()->user->checkAccess('AQuestionAnswer.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aNewsCategories/admin'),
                            'label'   => 'Danh mục tin tức',
                            'visible' => (Yii::app()->user->checkAccess('ANewsCategories.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aNews/admin'),
                            'label'   => 'Tin tức',
                            'visible' => (Yii::app()->user->checkAccess('ANews.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aNewsComments/admin'),
                            'label'   => 'Bình luận tin tức',
                            'visible' => (Yii::app()->user->checkAccess('ANewsComments.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aBanners/admin'),
                            'label'   => 'Banner',
                            'visible' => (Yii::app()->user->checkAccess('ABanners.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aPackage/admin'),
                            'label'   => 'Gói cước',
                            'visible' => (Yii::app()->user->checkAccess('APackage.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aAgencyPackage/admin'),
                            'label'   => Yii::t('adm/menu', 'agc_pkg'),
                            'visible' => Yii::app()->user->checkAccess('AAgencyPackage.*'),
                        ),
                        array(
                            'url'     => array('/aSubscriptionType/admin'),
                            'label'   => 'Loại thuê bao',
                            'visible' => (Yii::app()->user->checkAccess('ASubscriptionType.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aParentChildPackageCodes/admin'),
                            'label'   => Yii::t('adm/menu', 'smart_pkg'),
                            'visible' => Yii::app()->user->checkAccess('AParentChildPackageCodes.*'),
                        ),
                    ),
                ),
                array(
                    'url'     => 'javascript:;',
                    'label'   => 'Địa danh <span class="fa fa-chevron-down"></span>',
                    'visible' => (Yii::app()->user->checkAccess('ANations.*')
                        || Yii::app()->user->checkAccess('AProvince.*')
                        || Yii::app()->user->checkAccess('ASaleOffices.*')
                        || Yii::app()->user->checkAccess('ABrandOffices.*')
                    ),
                    'items'   => array(
                        array(
                            'url'     => array('/aNations/admin'),
                            'label'   => 'Quốc gia',
                            'visible' => (Yii::app()->user->checkAccess('ANations.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aProvince/admin'),
                            'label'   => 'Tỉnh thành',
                            'visible' => (Yii::app()->user->checkAccess('AProvince.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aSaleOffices/admin'),
                            'label'   => 'Phòng bán hàng',
                            'visible' => (Yii::app()->user->checkAccess('ASaleOffices.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aBrandOffices/admin'),
                            'label'   => 'Điểm giao dịch',
                            'visible' => (Yii::app()->user->checkAccess('ABrandOffices.*')
                            ),
                        ),
                    ),
                ),
                array(
                    'url'     => 'javascript:;',
                    'label'   => 'Tài khoản <span class="fa fa-chevron-down"></span>',
                    'visible' => (Yii::app()->user->checkAccess('ALocationVietinbank.*')
                        || Yii::app()->user->checkAccess('ALocationNapas.*')
                        || Yii::app()->user->checkAccess('ALocationVnptpay.*')
                    ),
                    'items'   => array(
                        array(
                            'url'     => array('/aLocationVietinbank/admin'),
                            'label'   => 'Vietinbank',
                            'visible' => (Yii::app()->user->checkAccess('ALocationVietinbank.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aLocationNapas/admin'),
                            'label'   => 'Napas',
                            'visible' => (Yii::app()->user->checkAccess('ALocationNapas.*')
                            ),
                        ),
                        array(
                            'url'     => array('/aLocationVnptpay/admin'),
                            'label'   => 'VNPTPay',
                            'visible' => (Yii::app()->user->checkAccess('ALocationVnptpay.*')
                            ),
                        ),
                    ),
                ),
                array(
                    'url'       => array('/aShipper/admin'),
                    'label'     => 'Nhân viên giao vận',
                    'visible'   => (Yii::app()->user->checkAccess('AShipper.*')
                    ),
                ),
            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-list"></i> Thống kê chi tiết <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('Report.*')
                || Yii::app()->user->checkAccess('AReportAccesstrade.*')
                || Yii::app()->user->checkAccess('APrepaidtopostpaid.*')
                || Yii::app()->user->checkAccess('ReportTraffic.*')
                || Yii::app()->user->checkAccess('ARedeemHistory.*')
                || Yii::app()->user->checkAccess('AReportSocial.*')
                || Yii::app()->user->checkAccess('ASurveyReport.*')
                || Yii::app()->user->checkAccess('ReportZalo.*')

                || Yii::app()->user->checkAccess('Report.OnlinePaid')
                || Yii::app()->user->checkAccess('Report.Card')
                || Yii::app()->user->checkAccess('Report.StatisticsSim')
                || Yii::app()->user->checkAccess('Report.StatisticsPackage')
                || Yii::app()->user->checkAccess('Report.PackageFlexible')

                || Yii::app()->user->checkAccess('AReportAccesstrade.Sim')
                || Yii::app()->user->checkAccess('AReportAccesstrade.Package')
                || Yii::app()->user->checkAccess('AReportAccesstrade.IncentivesAgency')

                || Yii::app()->user->checkAccess('APrepaidtopostpaid.Report')

                || Yii::app()->user->checkAccess('AReportSocial.Index')
                || Yii::app()->user->checkAccess('AReportSocial.ReportUser')
                || Yii::app()->user->checkAccess('AReportSocial.DetailUser')

                || Yii::app()->user->checkAccess('ReportTraffic.Index')
                || Yii::app()->user->checkAccess('Report.ActiveSim')

            ),
            'items'     => array(
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Bán hàng <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('Report.*')
                        || Yii::app()->user->checkAccess('Report.StatisticsSim')
                        || Yii::app()->user->checkAccess('Report.StatisticsPackage')
                        || Yii::app()->user->checkAccess('Report.PackageFlexible')
                        || Yii::app()->user->checkAccess('Report.Card')
                    ),
                    'items'     => array(
                        array(
                            'url'       => array('/report/statisticsSim'),
                            'label'     => 'SIM & gói kèm SIM',
                            'visible'   => (Yii::app()->user->checkAccess('Report.*')
                                || Yii::app()->user->checkAccess('Report.StatisticsSim')
                            ),
                        ),
                        array(
                            'url'       => array('/report/statisticsPackage'),
                            'label'     => 'Gói cước đơn lẻ',
                            'visible'   => (Yii::app()->user->checkAccess('Report.*')
                                || Yii::app()->user->checkAccess('Report.StatisticsPackage')
                            ),
                        ),
                        array(
                            'url'       => array('/report/packageFlexible'),
                            'label'     => 'Gói cước linh hoạt',
                            'visible'   => (Yii::app()->user->checkAccess('Report.*')
                                || Yii::app()->user->checkAccess('Report.PackageFlexible')
                            ),
                        ),
                        array(
                            'label'   => Yii::t('report/menu', 'card'),
                            'url'     => array('/report/card'),
                            'visible' => (Yii::app()->user->checkAccess('Report.Card')
                                || Yii::app()->user->checkAccess('Report.*')
                            ),
                        ),
                        array(
                            'label'   => 'Thống kê ĐH Internet & Truyền hình',
                            'url'     => array('/aOrders/detailorderfiber'),
                            'visible' => (Yii::app()->user->checkAccess('AOrders.Detailorderfiber')
                                || Yii::app()->user->checkAccess('AOrders.*')
                            ),
                        ),
                    )
                ),
                array(
                    'url'       => array('/report/onlinePaid'),
                    'label'     => 'Giao dịch thanh toán Online',
                    'visible'   => (Yii::app()->user->checkAccess('Report.*')
                        || Yii::app()->user->checkAccess('Report.OnlinePaid')
                    ),
                ),
                array(
                    'url'       => array('/report/activeSim'),
                    'label'     => 'Báo cáo thuê bao online FTP',
                    'visible'   => (Yii::app()->user->checkAccess('Report.*')
                        || Yii::app()->user->checkAccess('Report.ActiveSim')
                    ),
                ),
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Hoa hồng đại lý <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('AReportAccesstrade.*')
                        || Yii::app()->user->checkAccess('Report.*')

                        || Yii::app()->user->checkAccess('AReportAccesstrade.Sim')
                        || Yii::app()->user->checkAccess('AReportAccesstrade.Package')
                        || Yii::app()->user->checkAccess('Report.IncentivesAgency')
                    ),
                    'items'     => array(
                        array(
                            'label'     => 'Hoa hồng SIM',
                            'url'       => array('/aReportAccesstrade/sim'),
                            'visible'   => (Yii::app()->user->checkAccess('AReportAccesstrade.*')
                                || Yii::app()->user->checkAccess('AReportAccesstrade.Sim')
                            ),
                        ),
                        array(
                            'url'       => array('/aReportAccesstrade/package'),
                            'label'     => 'Hoa hồng gói',
                            'visible'   => (Yii::app()->user->checkAccess('AReportAccesstrade.*')
                                || Yii::app()->user->checkAccess('AReportAccesstrade.Package')
                            ),
                        ),
                        array(
                            'url'       => array('/report/incentivesAgency'),
                            'label'     => 'Hoa hồng khuyến khích',
                            'visible'   => (Yii::app()->user->checkAccess('Report.*')
                                || Yii::app()->user->checkAccess('Report.IncentivesAgency')
                            ),
                        ),
                        array(
                            'url'       => array('/reportZalo/index'),
                            'label'     => 'Hoa hồng TKC',
                            'visible'   => (Yii::app()->user->checkAccess('ReportZalo.*')
                                || Yii::app()->user->checkAccess('ReportZalo.Index')
                            ),

                        ),
                    ),
                ),

                array(
                    'url'       => array('/aPrepaidtopostpaid/report'),
                    'label'     => 'ĐH chuyển đổi trả sau',
                    'visible'   => (Yii::app()->user->checkAccess('APrepaidtopostpaid.*')
                        || Yii::app()->user->checkAccess('APrepaidtopostpaid.Report')
                    ),
                ),
                array(
                    'url'       => array('/reportTraffic/index'),
                    'label'     => 'Link quảng cáo',
                    'visible'   => (Yii::app()->user->checkAccess('ReportTraffic.*')
                        || Yii::app()->user->checkAccess('ReportTraffic.Index')
                    ),
                ),
                array(
                    'url'       => 'javascript:;',
                    'label'     => 'Diễn đàn <span class="fa fa-chevron-down"></span>',
                    'visible'   => (Yii::app()->user->checkAccess('AReportSocial.*')
                        || Yii::app()->user->checkAccess('AReportSocial.ReportUser')
                        || Yii::app()->user->checkAccess('AReportSocial.DetailUser')
                        || Yii::app()->user->checkAccess('ARedeemHistory.*')
                    ),
                    'items'     => array(
                        array(
                            'url'       => array('/aReportSocial/reportUser'),
                            'label'     => 'TK tổng hợp thành viên',
                            'visible'   => (Yii::app()->user->checkAccess('AReportSocial.*')
                                || Yii::app()->user->checkAccess('AReportSocial.ReportUser')
                            ),
                        ),
                        array(
                            'url'       => array('/aReportSocial/detailUser'),
                            'label'     => 'TK chi tiết thành viên',
                            'visible'   => (Yii::app()->user->checkAccess('AReportSocial.*')
                                || Yii::app()->user->checkAccess('AReportSocial.DetailUser')
                            ),
                        ),
                        array(
                            'url'       => array('/aRedeemHistory/admin'),
                            'label'     => 'Lịch sử đổi quà',
                            'visible'   => (Yii::app()->user->checkAccess('ARedeemHistory.*')
                            ),
                        ),
                    ),
                ),
                array(
                    'url'       => array('/aSurveyReport/admin'),
                    'label'     => 'Kết quả khảo sát',
                    'visible'   => (Yii::app()->user->checkAccess('ASurveyReport.*')
                    ),
                ),
            ),
        ),
        array(
            'url'     => 'javascript:;',
            'label'   => '<i class="fa fa-file-text-o"></i>Log giao dịch <span class="fa fa-chevron-down"></span>',
            'visible' => (Yii::app()->user->checkAccess('ATransactionRequest.*')
                || Yii::app()->user->checkAccess('ATransactionResponse.*')
            ),
            'items'   => array(
                array(
                    'url'       => array('/aTransactionRequest/admin'),
                    'label'     => 'Log gửi',
                    'visible'   => (Yii::app()->user->checkAccess('ATransactionRequest.*')
                    ),
                ),
                array(
                    'url'       => array('/aTransactionResponse/admin'),
                    'label'     => 'Log nhận',
                    'visible'   => (Yii::app()->user->checkAccess('ATransactionResponse.*')
                    ),
                ),

            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-arrows"></i> Tool vận hành <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('Operation.*')
                || Yii::app()->user->checkAccess('Operation.Admin')
                || Yii::app()->user->checkAccess('Operation.CheckMsisdn')
                || Yii::app()->user->checkAccess('Operation.OpenPackage')
            ),
            'items'     => array(
                array(
                    'url'       => array('/operation/admin'),
                    'label'     => 'Chỉnh sửa đơn hàng',
                    'visible'   => (Yii::app()->user->checkAccess('Operation.*')
                        || Yii::app()->user->checkAccess('Operation.Admin')
                    ),
                ),
                array(
                    'url'       => array('/operation/checkMsisdn'),
                    'label'     => 'Kiểm tra thuê bao',
                    'visible'   => (Yii::app()->user->checkAccess('Operation.*')
                        || Yii::app()->user->checkAccess('Operation.CheckMsisdn')
                    ),
                ),
                array(
                    'url'       => array('/operation/openPackage'),
                    'label'     => 'Mở gói',
                    'visible'   => (Yii::app()->user->checkAccess('Operation.*')
                        || Yii::app()->user->checkAccess('Operation.OpenPackage')
                    ),
                ),

            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-list"></i> Báo cáo doanh thu <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('Report.*')
                || Yii::app()->user->checkAccess('Report.Sim')
                || Yii::app()->user->checkAccess('Report.PackageSimKit')
                || Yii::app()->user->checkAccess('Report.Package')
            ),
            'items'     => array(
                array(
                    'url'       => array('/report/sim'),
                    'label'     => 'Doanh thu SIM',
                    'visible'   => (Yii::app()->user->checkAccess('Report.*')
                        || Yii::app()->user->checkAccess('Report.Sim')
                    ),
                ),
                array(
                    'url'       => array('/report/packageSimKit'),
                    'label'     => 'Doanh thu Gói cước kèm SIM',
                    'visible'   => (Yii::app()->user->checkAccess('Report.*')
                        || Yii::app()->user->checkAccess('Report.PackageSimKit')
                    ),
                ),
                array(
                    'url'       => array('/report/package'),
                    'label'     => 'Doanh thu Gói đơn lẻ',
                    'visible'   => (Yii::app()->user->checkAccess('Report.*')
                        || Yii::app()->user->checkAccess('Report.Package')
                    ),
                ),
            ),
        ),
    ),
)); ?>
</div>
