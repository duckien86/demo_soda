<?php

class PackageController extends Controller
{
    public $layout = '/layouts/main';

    public $isMobile = FALSE;

    public function init()
    {
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/mobile_main';
        }
        $this->pageImage = 'http://' . SERVER_HTTP_HOST . Yii::app()->theme->baseUrl . '/images/slider1.jpg';
        $this->pageDescription = Yii::t('web/portal', 'page_description');
    }

    /**
     * Performs the AJAX validation.
     *
     * @param $model
     *
     * @return string
     */
    protected function performAjaxValidation($model)
    {
        $msg = '';
        if (isset($_POST['WOrders'])) {
            $msg = CActiveForm::validate($model);
        }

        return CJSON::decode($msg);
    }

    /**
     * list package
     */
    public function actionIndex()
    {
        $this->pageTitle = 'Sản phẩm - Gói cước';

        //            $category_package = WPackage::getAllPackages();
        $searchPackageForm = new SearchPackageForm();

        $list_package_hot = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_prepaid = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_postpaid = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_vas = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        //            $list_package_flexible = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);

        $list_package_hot_other = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_prepaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_postpaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_data_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_vas_other = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        //            $list_package_flexible_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);

        $this->render('index', array(
            //                'category_package' => $category_package,
            'searchPackageForm' => $searchPackageForm,
            'list_package_hot' => $list_package_hot,
            'list_package_prepaid' => $list_package_prepaid,
            'list_package_postpaid' => $list_package_postpaid,
            'list_package_data' => $list_package_data,
            'list_package_vas' => $list_package_vas,
            //                'list_package_flexible' => $list_package_flexible,
            'list_package_hot_other' => $list_package_hot_other,
            'list_package_prepaid_other' => $list_package_prepaid_other,
            'list_package_postpaid_other' => $list_package_postpaid_other,
            'list_package_data_other' => $list_package_data_other,
            'list_package_vas_other' => $list_package_vas_other,
            //                'list_package_flexible_other' => $list_package_flexible_other,
        ));
    }

    public function actionIndexfiber()
    {
        $this->pageTitle = 'Sản phẩm - Gói cước Internet cáp quang';
        $source = $_GET['m'];
        if ($source && $source != '' && $source == 'mytv') {
            Yii::app()->session['source_mytv'] = $source;
        } else {
            unset(Yii::app()->session['source_mytv']);
        }
        //            $category_package = WPackage::getAllPackages();
        $searchPackageForm = new SearchPackageForm();
        $list_package_all_province = WPackage::getListFiberToanQuoc();
        $list_package_hot = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_prepaid = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_postpaid = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_vas = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        //            $list_package_flexible = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);

        $list_package_hot_other = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_prepaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_postpaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_data_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_vas_other = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        //            $list_package_flexible_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $province = new WProvince();
        $list_province = $province->getListProvincePackage();

        $this->render('index_fiber', array(
            //                'category_package' => $category_package,
            'searchPackageForm' => $searchPackageForm,
            'list_package_hot' => $list_package_hot,
            'list_package_prepaid' => $list_package_prepaid,
            'list_package_postpaid' => $list_package_postpaid,
            'list_package_data' => $list_package_data,
            'list_package_vas' => $list_package_vas,
            //                'list_package_flexible' => $list_package_flexible,
            'list_package_hot_other' => $list_package_hot_other,
            'list_package_prepaid_other' => $list_package_prepaid_other,
            'list_package_postpaid_other' => $list_package_postpaid_other,
            'list_package_data_other' => $list_package_data_other,
            'list_package_vas_other' => $list_package_vas_other,
            'list_package_all_province' => $list_package_all_province,
            //                'list_package_flexible_other' => $list_package_flexible_other,
            'list_province' => $list_province
        ));
    }


    /*
     *  Gói combo
     */
    public function actionIndexCombo()
    {
        $this->pageTitle = 'Sản phẩm - Gói cước Internet & Truyền hình';
        $source = $_GET['m'];
        if ($source && $source != '' && $source == 'mytv') {
            Yii::app()->session['source_mytv'] = $source;
        } else {
            unset(Yii::app()->session['source_mytv']);
        }
        //            $category_package = WPackage::getAllPackages();
        $searchPackageForm = new SearchPackageForm();
        $list_package_all_province = WPackage::getListComboToanQuoc();
        $list_package_hot = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_prepaid = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_postpaid = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_vas = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        //            $list_package_flexible = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);

        $list_package_hot_other = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_prepaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_postpaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_data_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_vas_other = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        //            $list_package_flexible_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $province = new WProvince();
        $list_province = $province->getListProvincePackage();

        $this->render('index_combo', array(
            //                'category_package' => $category_package,
            'searchPackageForm' => $searchPackageForm,
            'list_package_hot' => $list_package_hot,
            'list_package_prepaid' => $list_package_prepaid,
            'list_package_postpaid' => $list_package_postpaid,
            'list_package_data' => $list_package_data,
            'list_package_vas' => $list_package_vas,
            //                'list_package_flexible' => $list_package_flexible,
            'list_package_hot_other' => $list_package_hot_other,
            'list_package_prepaid_other' => $list_package_prepaid_other,
            'list_package_postpaid_other' => $list_package_postpaid_other,
            'list_package_data_other' => $list_package_data_other,
            'list_package_vas_other' => $list_package_vas_other,
            'list_package_all_province' => $list_package_all_province,
            //                'list_package_flexible_other' => $list_package_flexible_other,
            'list_province' => $list_province
        ));
    }


    /*
     * MyTV
     */

    public function actionIndexmytv()
    {
        $this->pageTitle = 'Sản phẩm - Gói cước truyền hình mytv';

        //            $category_package = WPackage::getAllPackages();
        $searchPackageForm = new SearchPackageForm();
        $list_package_all_province = WPackage::getListMyTV_SmartTV_Home();
        $list_package_hot = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_prepaid = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_postpaid = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_vas = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        //            $list_package_flexible = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);

        $list_package_hot_other = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_prepaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_postpaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_data_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_vas_other = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        //            $list_package_flexible_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $province = new WProvince();
        $list_province = $province->getListProvincePackage();

        $this->render('index_mytv', array(
            //                'category_package' => $category_package,
            'searchPackageForm' => $searchPackageForm,
            'list_package_hot' => $list_package_hot,
            'list_package_prepaid' => $list_package_prepaid,
            'list_package_postpaid' => $list_package_postpaid,
            'list_package_data' => $list_package_data,
            'list_package_vas' => $list_package_vas,
            //                'list_package_flexible' => $list_package_flexible,
            'list_package_hot_other' => $list_package_hot_other,
            'list_package_prepaid_other' => $list_package_prepaid_other,
            'list_package_postpaid_other' => $list_package_postpaid_other,
            'list_package_data_other' => $list_package_data_other,
            'list_package_vas_other' => $list_package_vas_other,
            'list_package_all_province' => $list_package_all_province,
            //                'list_package_flexible_other' => $list_package_flexible_other,
            'list_province' => $list_province
        ));
    }


    /*
     *  Gói home bundle
     */
    public function actionIndexHomeBundle()
    {
        $this->pageTitle = 'Sản phẩm - Gói cước Home Bundle';
        $source = $_GET['m'];
        if ($source && $source != '' && $source == 'mytv') {
            Yii::app()->session['source_mytv'] = $source;
        } else {
            unset(Yii::app()->session['source_mytv']);
        }
        //            $category_package = WPackage::getAllPackages();
        $searchPackageForm = new SearchPackageForm();
        $list_package_all_province = WPackage::getListHomeBundleToanQuoc();
        $list_package_hot = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_prepaid = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_postpaid = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        $list_package_vas = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);
        //            $list_package_flexible = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE);

        $list_package_hot_other = WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_prepaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_postpaid_other = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_data_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $list_package_vas_other = WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        //            $list_package_flexible_other = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, 0, false, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE);
        $province = new WProvince();
        $list_province = $province->getListProvincePackage();

        $this->render('index_home_bundle', array(
            //                'category_package' => $category_package,
            'searchPackageForm' => $searchPackageForm,
            'list_package_hot' => $list_package_hot,
            'list_package_prepaid' => $list_package_prepaid,
            'list_package_postpaid' => $list_package_postpaid,
            'list_package_data' => $list_package_data,
            'list_package_vas' => $list_package_vas,
            //                'list_package_flexible' => $list_package_flexible,
            'list_package_hot_other' => $list_package_hot_other,
            'list_package_prepaid_other' => $list_package_prepaid_other,
            'list_package_postpaid_other' => $list_package_postpaid_other,
            'list_package_data_other' => $list_package_data_other,
            'list_package_vas_other' => $list_package_vas_other,
            'list_package_all_province' => $list_package_all_province,
            //                'list_package_flexible_other' => $list_package_flexible_other,
            'list_province' => $list_province
        ));
    }

    /**
     * detail package
     *
     * @param $id
     */
    public function actionCategory($id)
    {
        $model = new WPackage();
        $category = $model->getPackageTypeLabel($id);
        $package = WPackage::getListPackageByType($id);
        $this->pageTitle = 'Gói cước - ' . $category;
        $this->render('category', array(
            'category' => $category,
            'package' => $package,
        ));
    }

    /**
     * detail package
     *
     * @param $slug string
     */
    public function actionDetail($slug)
    {
        $package = WPackage::model()->find('slug=:slug AND status= 1', array(':slug' => $slug));
        if ($package) {
            $sub_packages = WPackage::getSubPackageByParentCode($package->code);
            //
            if ($sub_packages) {
                $related_package = $sub_packages;
            } else {
                $related_package = WPackage::getListPackageByType($package->type, $package->id, FALSE, NULL, 0, 0, 0, $package->freedoo);
            }

            $this->pageTitle = 'Gói cước - ' . $package->name;
            $this->render('detail', array(
                'package' => $package,
                'related_package' => $related_package,
            ));
        } else {
            $this->redirect($this->createUrl('package/index'));
        }
    }

    /**
     * register package
     *
     * @param $package
     */
    public function actionRegister($package)
    {

        OtpForm::unsetSessionHtmlOrder();
        OtpForm::unsetCookieHtml();
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        if ($modelPackage) {
            $this->pageTitle = 'Đăng ký gói cước - ' . $modelPackage->name;
            //check price_discount
            if ($modelPackage->price_discount > 0) {
                $modelPackage->price = $modelPackage->price_discount;
            } elseif ($modelPackage->price_discount == -1) {
                $modelPackage->price = 0;
            }

            $orders_data = new OrdersData();
            $modelOrder = new WOrders();
            $orderDetails = new WOrderDetails();
            $orders_data->package = $modelPackage; //display view panel_order
            $modelOrder->scenario = 'register_package';

            $modelOrder->id = $modelOrder->generateOrderId();
            $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);

            //check detect 3G
            if (isset(Yii::app()->session['session_data']->current_msisdn) && !empty(Yii::app()->session['session_data']->current_msisdn)) {
                $modelOrder->phone_contact = CFunction::makePhoneNumberBasic(Yii::app()->session['session_data']->current_msisdn);
            }

            //sso_id, phone_contact
            //                $customer = array();
            //                if (WPackage::checkVipUser() && $modelPackage->vip_user >= WPackage::VIP_USER) { //check aff->login +sim freedoo + vip_user(package)
            //                    $customer = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
            //                    if ($customer) {
            //                        $modelOrder->phone_contact = CFunction::makePhoneNumberBasic($customer->phone);
            //                    }
            //                }

            //validate ajax
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'register_package') {
                echo CActiveForm::validate($modelOrder);
                Yii::app()->end();
            }

            if (isset($_POST['WPackage']) && isset($_POST['WOrders'])) {
                $modelOrder->attributes = $_POST['WOrders'];
                $data_input = array(
                    'so_tb' => $_POST['WOrders']['phone_contact']
                );
                $orderdata = new OrdersData();
                //Call API đối tác
                $data_output = $orderdata->checkPhoneKHDN($data_input);
                if ($data_output['status']['code'] == 1) {
                    $modelOrder->promo_code = '';
                }
                //sso_id, phone_contact(after submit)
                if (!Yii::app()->user->isGuest) {
                    $modelOrder->sso_id = Yii::app()->user->sso_id;
                    //                        if (WPackage::checkVipUser() && $modelPackage->vip_user >= WPackage::VIP_USER && $customer) {
                    //                            $modelOrder->phone_contact = CFunction::makePhoneNumberBasic($customer->phone);
                    //                        }
                }
                //check cookie campaign
                if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                    if ($data_output['status']['code'] == 1) {
                        $modelOrder->campaign_source = '';
                    } else {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                }
                if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                    if ($data_output['status']['code'] == 1) {
                        $modelOrder->campaign_id = '';
                    } else {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }
                }

                if ($modelOrder->validate()) {
                    //if empty promo_code: check cookie affiliate
                    if (empty($modelOrder->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                            $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                            $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                        }
                    }
                    //check package freedoo
                    if (
                        $modelPackage->freedoo != WPackage::FREEDOO_PACKAGE
                        || ($modelPackage->freedoo == WPackage::FREEDOO_PACKAGE /*&& WPackage::checkSimFreedoo($modelOrder->phone_contact) == TRUE*/)
                    ) {
                        Yii::app()->session['phone_contact'] = $modelOrder->phone_contact;
                        //get token key
                        $otp_form = new OtpForm();
                        $token_key = $otp_form->getTokenKey($modelOrder->phone_contact);
                        if ($token_key) {
                            Yii::app()->session['verify_number'] = 1;
                            Yii::app()->session['time_reset'] = time();
                            Yii::app()->session['token_key'] = $token_key;

                            $modelOrder->otp = $token_key;

                            //order state
                            $orderState = new WOrderState();
                            $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                            $orders_data->order_state = $orderState;

                            //set cache order
                            $cache_key = 'orders_data_package_' . $modelOrder->id;
                            $pack_cache_key = new CHttpCookie('package_cache_key', $cache_key);
                            $pack_cache_key->expire = time() + 60 * 30; //3'
                            Yii::app()->request->cookies['package_cache_key'] = $pack_cache_key;

                            //check detect 3G
                            $current_msisdn = '';
                            if (isset(Yii::app()->session['session_data']->current_msisdn) && !empty(Yii::app()->session['session_data']->current_msisdn)) {
                                $current_msisdn = CFunction::makePhoneNumberBasic(Yii::app()->session['session_data']->current_msisdn);
                            }
                            if ($current_msisdn == $modelOrder->phone_contact) {
                                $orders_data->orders = $modelOrder;
                                $orders_data->order_details['packages'] = $orderDetails->attributes;

                                //set cache order
                                Yii::app()->cache->set($cache_key, $orders_data);

                                $this->redirect($this->createUrl('package/confirmRegister'));
                            } else {
                                if (YII_DEBUG == TRUE) {
                                    $orders_data->orders = $modelOrder;
                                    $orders_data->order_details['packages'] = $orderDetails->attributes;

                                    //set cache order
                                    Yii::app()->cache->set($cache_key, $orders_data);

                                    $this->redirect($this->createUrl('package/verifyTokenKey'));
                                } else {
                                    //send MT token key
                                    $mt_content = Yii::t('web/mt_content', 'otp_register_package', array(
                                        '{token_key}' => $token_key,
                                        '{package_name}' => $modelPackage->name,
                                    ));
                                    if ($otp_form->sentMtVNP($modelOrder->phone_contact, $mt_content, 'package')) {
                                        $orders_data->orders = $modelOrder;
                                        $orders_data->order_details['packages'] = $orderDetails->attributes;

                                        //set cache order
                                        Yii::app()->cache->set($cache_key, $orders_data);

                                        $this->redirect($this->createUrl('package/verifyTokenKey'));
                                    } else {
                                        $msg = Yii::t('web/portal', 'send_mt_fail');
                                        $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                                        $this->redirect($this->createUrl('package/message', array('t' => 9, 'msg' => $msg)));
                                    }
                                }
                            }
                        } else { //get token key fail
                            //redirect to message
                            $msg = Yii::t('web/portal', 'get_token_key_fail');
                            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                            $this->redirect($this->createUrl('package/message', array('t' => 8, 'msg' => $msg)));
                        }
                    } else {
                        $modelOrder->addError('phone_contact', Yii::t('web/portal', 'err_cannot_register_package_freedoo'));
                    }
                }
            }

            $this->render('register', array(
                'modelPackage' => $modelPackage,
                'modelOrder' => $modelOrder,
                'orderDetails' => $orderDetails,
            ));
        }
    }


    /**
     * action verify token key (check token key)
     * insert order
     */
    public function actionVerifyTokenKey()
    {
        //            if (WOrders::checkOrdersSessionExists() === FALSE) {
        //                $msg = Yii::t('web/portal', 'session_timeout');
        //                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        //            } else {
        //check cookie
        if (
            isset(Yii::app()->request->cookies['package_cache_key'])
            && !empty(Yii::app()->request->cookies['package_cache_key']->value)
        ) {
            $cache_key = Yii::app()->request->cookies['package_cache_key']->value;
            $orders_data = Yii::app()->cache->get($cache_key);
            if ($orders_data) {
                $orders = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state = $orders_data->order_state;
                $modelPackage = $orders_data->package; //display view panel_order
                $package_flexible = $orders_data->package_flexible; //list package flexible
                $orders->payment_method = (string)WPaymentMethod::PM_AIRTIME;

                $this->pageTitle = 'Đăng ký gói cước - ' . $modelPackage->name;

                $otpModel = new OtpForm();
                $otpModel->scenario = 'checkTokenKey';
                $msg = '';

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'otp_form') {
                    echo CActiveForm::validate($otpModel);
                    Yii::app()->end();
                }

                if (isset($_POST['OtpForm'])) {
                    $otpModel->attributes = $_POST['OtpForm'];
                    //                    $otpModel->msisdn     = Yii::app()->session['phone_contact'];
                    $otpModel->msisdn = $orders->phone_contact;
                    if ($otpModel->validate()) {
                        //check timeout OTP confirm
                        if (((time() - Yii::app()->session['time_reset']) / 60) <= Yii::app()->params['verify_config']['times_reset']) {
                            //check max verify number
                            if (isset(Yii::app()->session['verify_number']) && Yii::app()->session['verify_number'] != '') {
                                if (Yii::app()->session['verify_number'] > Yii::app()->params['verify_config']['verify_number']) {
                                    $msg = Yii::t(
                                        'web/portal',
                                        'err_verify_limited',
                                        array(
                                            '{link_back}' => CHtml::link('vào đây', Yii::app()->controller->createUrl('package/index'))
                                        )
                                    );
                                    $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                                    $this->redirect($this->createUrl('package/message', array('t' => 3, 'msg' => $msg)));
                                } else {
                                    //set time and ++ verify_number
                                    Yii::app()->session['verify_number'] += 1;
                                    Yii::app()->session['time_reset'] = time();
                                }
                            } else {
                                //not exist-->set time and verify_number
                                Yii::app()->session['verify_number'] = 1;
                                Yii::app()->session['time_reset'] = time();
                            }

                            if ($otpModel->checkTokenKey()) { //Check Token key
                                $data = array(
                                    'orders' => $orders->attributes,
                                    'order_details' => $order_details,
                                    'order_state' => $order_state->attributes,
                                );

                                //call api
                                $checkSubmitting = $this->packageSubmitting($cache_key);
                                if (!$checkSubmitting) {
                                    if ($modelPackage->type == WPackage::PACKAGE_FLEXIBLE) {
                                        $response_arr = $orders_data->registerPackageFlexible($data);
                                    } else {
                                        $response_arr = $orders_data->registerPackage($data);
                                    }

                                    $response_code = isset($response_arr['code']) ? $response_arr['code'] : '';
                                    $response_msg = isset($response_arr['msg']) ? $response_arr['msg'] : '';
                                    $response_msg = self::EncryptMsg($response_msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                                    //redirect to message
                                    $this->redirect($this->createUrl('package/message', array('t' => $response_code, 'msg' => $response_msg, 'encrypt' => 1)));
                                } else {
                                    $otpModel->addError('token', 'Đơn hàng đang được xử lý. Vui lòng kiểm tra tin nhắn');
                                }
                            } else {
                                $msg = Yii::t('web/portal', 'verify_fail');
                            }
                        } else {
                            $msg = Yii::t(
                                'web/portal',
                                'verify_exp_pack',
                                array(
                                    '{link_back}' => CHtml::link('Đồng ý', Yii::app()->controller->createUrl('package/index'))
                                )
                            );
                            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                            $this->redirect($this->createUrl('package/message', array('t' => 7, 'msg' => $msg)));
                        }
                    }
                }

                $this->render('verify_otp', array(
                    'otpModel' => $otpModel,
                    'msg' => $msg,
                    'modelOrder' => $orders,
                    'modelPackage' => $modelPackage,
                    'package_flexible' => $package_flexible,
                ));
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
            }
        } else {
            $msg = Yii::t('web/portal', 'session_timeout');
            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
            $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        }
    }

    /**
     * confirm register package with detect 3g
     */
    public function actionConfirmRegister()
    {
        if (
            isset(Yii::app()->request->cookies['package_cache_key'])
            && !empty(Yii::app()->request->cookies['package_cache_key']->value)
        ) {
            $cache_key = Yii::app()->request->cookies['package_cache_key']->value;
            $orders_data = Yii::app()->cache->get($cache_key);
            if ($orders_data) {
                $orders = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state = $orders_data->order_state;
                $modelPackage = $orders_data->package; //display view panel_order
                $package_flexible = $orders_data->package_flexible; //list package flexible
                $orders->payment_method = (string)WPaymentMethod::PM_AIRTIME;

                $this->pageTitle = 'Xác nhận đăng ký gói cước - ' . $modelPackage->name;
                $msg = '';

                if (isset($_POST['confirm_register'])) {
                    $data = array(
                        'orders' => $orders->attributes,
                        'order_details' => $order_details,
                        'order_state' => $order_state->attributes,
                    );

                    //call api
                    if ($modelPackage->type == WPackage::PACKAGE_FLEXIBLE) {
                        $response_arr = $orders_data->registerPackageFlexible($data);
                    } else {
                        $response_arr = $orders_data->registerPackage($data);
                    }

                    $response_code = isset($response_arr['code']) ? $response_arr['code'] : '';
                    $response_msg = isset($response_arr['msg']) ? $response_arr['msg'] : '';
                    $response_msg = self::EncryptMsg($response_msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                    //redirect to message
                    $this->redirect($this->createUrl('package/message', array('t' => $response_code, 'msg' => $response_msg, 'encrypt' => 1)));
                }
                $this->render('confirm_register', array(
                    'msg' => $msg,
                    'modelOrder' => $orders,
                    'modelPackage' => $modelPackage,
                    'package_flexible' => $package_flexible,
                ));
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
            }
        } else {
            $msg = Yii::t('web/portal', 'session_timeout');
            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
            $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        }
    }

    public function actionPackageFlexible($period = '')
    {
        $this->pageTitle = 'Gói cước - Gói linh hoạt';
        OtpForm::unsetSessionHtmlOrder();
        $orders_data = new OrdersData();
        $modelOrder = new WOrders();
        $package_flexible = array(); //list package flexible order info
        $modelOrder->scenario = 'register_package_ff';
        $modelPackage = new WPackage(); //display view panel_order
        $modelPackage->period = $period;
        if ($period != WPackage::PERIOD_30) { //period=1||30
            $modelPackage->period = WPackage::PERIOD_1;
        }

        //validate ajax
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'package_flexible') {
            echo CActiveForm::validate($modelOrder);
            Yii::app()->end();
        }

        if (isset($_POST['WPackage']) && isset($_POST['WOrders'])) {
            $modelOrder->attributes = $_POST['WOrders'];
            $modelOrder->id = $modelOrder->generateOrderId();
            $modelPackage->attributes = $_POST['WPackage'];
            //sso_id
            if (!Yii::app()->user->isGuest) {
                $modelOrder->sso_id = Yii::app()->user->sso_id;
            }
            //check cookie campaign
            if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
            }
            if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
            }

            //order detail: array (order_detail)
            $orderDetails = array();
            $amount = 0; //amount package
            if (isset($_POST['WOrders']['package']) && is_array($_POST['WOrders']['package']) && !empty($_POST['WOrders']['package'])) {
                //get amount
                $amount = $modelPackage->getAmountPackage($_POST['WOrders']['package']);
                //set order detail
                WOrderDetails::setOrderDetailsPackageFlexible($_POST['WOrders']['package'], $modelOrder, $orderDetails);
                $package_flexible = $modelPackage->getListPackageFlexible($_POST['WOrders']['package']);
            }
            //amount order
            $modelOrder->amount = $amount;

            if ($modelOrder->validate() && $orderDetails) {
                //if empty promo_code: check cookie affiliate
                if (empty($modelOrder->promo_code)) {
                    if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                        $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                        $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                    }
                }
                Yii::app()->session['phone_contact'] = $modelOrder->phone_contact;
                //get token key
                $otp_form = new OtpForm();
                $token_key = $otp_form->getTokenKey($modelOrder->phone_contact);
                if ($token_key) {
                    Yii::app()->session['verify_number'] = 1;
                    Yii::app()->session['time_reset'] = time();
                    Yii::app()->session['token_key'] = $token_key;

                    $modelOrder->otp = $token_key;

                    //order state
                    $orderState = new WOrderState();
                    $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                    $orders_data->order_state = $orderState;

                    //amount package
                    $modelPackage->price = $amount;
                    $modelPackage->name = Yii::t('web/portal', strtolower(WPackage::PACKAGE_FLEXIBLE));
                    $modelPackage->type = WPackage::PACKAGE_FLEXIBLE;
                    $orders_data->package = $modelPackage; //display view panel_order
                    $orders_data->package_flexible = $package_flexible; //list package flexible

                    //set cache order
                    $cache_key = 'orders_data_package_flexible_' . $modelOrder->id;
                    $pack_cache_key = new CHttpCookie('package_flexible_cache_key', $cache_key);
                    $pack_cache_key->expire = time() + 60 * 30; //3'
                    Yii::app()->request->cookies['package_flexible_cache_key'] = $pack_cache_key;

                    if (YII_DEBUG == TRUE) {
                        $orders_data->orders = $modelOrder;
                        $orders_data->order_details['packages'] = $orderDetails;
                        //set session Order
                        //                            Yii::app()->session['session_cart'] = time();
                        //                            Yii::app()->session['orders_data']  = $orders_data;

                        //set cache order
                        Yii::app()->cache->set($cache_key, $orders_data);
                        $this->redirect($this->createUrl('package/verifyTokenKeyFlexible'));
                    } else {
                        $mt_content = Yii::t('web/mt_content', 'otp_register_package', array(
                            '{token_key}' => $token_key,
                            '{package_name}' => $modelPackage->name,
                        ));
                        if ($otp_form->sentMtVNP($modelOrder->phone_contact, $mt_content, 'package')) {
                            $orders_data->orders = $modelOrder;
                            $orders_data->order_details['packages'] = $orderDetails;
                            //set session Order
                            //                                Yii::app()->session['session_cart'] = time();
                            //                                Yii::app()->session['orders_data']  = $orders_data;

                            //set cache order
                            Yii::app()->cache->set($cache_key, $orders_data);
                            $this->redirect($this->createUrl('package/verifyTokenKeyFlexible'));
                        } else {
                            $msg = Yii::t('web/portal', 'send_mt_fail');
                            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                            $this->redirect($this->createUrl('package/message', array('t' => 9, 'msg' => $msg)));
                        }
                    }
                } else { //get token key fail
                    $msg = Yii::t('web/portal', 'get_token_key_fail');
                    //redirect to message
                    $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                    $this->redirect($this->createUrl('package/message', array('t' => 8, 'msg' => $msg)));
                }
            } else {
                $package_flexible = array();
                $orderDetails = array();
                $modelOrder->amount = 0;
            }
        }

        $this->render('package_flexible', array(
            'modelOrder' => $modelOrder,
            'modelPackage' => $modelPackage,
            'package_flexible' => $package_flexible,
        ));
    }

    /**
     * action verify token key (check token key) with flexible
     * insert order
     */
    public function actionVerifyTokenKeyFlexible()
    {
        //            if (WOrders::checkOrdersSessionExists() === FALSE) {
        //                $msg = Yii::t('web/portal', 'session_timeout');
        //                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        //            } else {
        //check cookie
        if (
            isset(Yii::app()->request->cookies['package_flexible_cache_key'])
            && !empty(Yii::app()->request->cookies['package_flexible_cache_key']->value)
        ) {
            $cache_key = Yii::app()->request->cookies['package_flexible_cache_key']->value;
            $orders_data = Yii::app()->cache->get($cache_key);
            if ($orders_data) {
                $orders = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state = $orders_data->order_state;
                $modelPackage = $orders_data->package; //display view panel_order
                $package_flexible = $orders_data->package_flexible; //list package flexible
                $orders->payment_method = (string)WPaymentMethod::PM_AIRTIME;

                $this->pageTitle = 'Đăng ký gói cước - ' . $modelPackage->name;

                $otpModel = new OtpForm();
                $otpModel->scenario = 'checkTokenKey';
                $msg = '';

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'otp_form') {
                    echo CActiveForm::validate($otpModel);
                    Yii::app()->end();
                }

                if (isset($_POST['OtpForm'])) {
                    $otpModel->attributes = $_POST['OtpForm'];
                    //                    $otpModel->msisdn     = Yii::app()->session['phone_contact'];
                    $otpModel->msisdn = $orders->phone_contact;
                    if ($otpModel->validate()) {
                        //check timeout OTP confirm
                        if (((time() - Yii::app()->session['time_reset']) / 60) <= Yii::app()->params['verify_config']['times_reset']) {
                            //check max verify number
                            if (isset(Yii::app()->session['verify_number']) && Yii::app()->session['verify_number'] != '') {
                                if (Yii::app()->session['verify_number'] > Yii::app()->params['verify_config']['verify_number']) {
                                    $msg = Yii::t(
                                        'web/portal',
                                        'err_verify_limited',
                                        array(
                                            '{link_back}' => CHtml::link('vào đây', Yii::app()->controller->createUrl('package/index'))
                                        )
                                    );
                                    $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                                    $this->redirect($this->createUrl('package/message', array('t' => 3, 'msg' => $msg)));
                                } else {
                                    //set time and ++ verify_number
                                    Yii::app()->session['verify_number'] += 1;
                                    Yii::app()->session['time_reset'] = time();
                                }
                            } else {
                                //not exist-->set time and verify_number
                                Yii::app()->session['verify_number'] = 1;
                                Yii::app()->session['time_reset'] = time();
                            }

                            if ($otpModel->checkTokenKey()) { //Check Token key
                                $data = array(
                                    'orders' => $orders->attributes,
                                    'order_details' => $order_details,
                                    'order_state' => $order_state->attributes,
                                );

                                //call api
                                $checkSubmitting = $this->packageSubmitting($cache_key);
                                if (!$checkSubmitting) {
                                    $response_arr = $orders_data->registerPackageFlexible($data);

                                    $response_code = isset($response_arr['code']) ? $response_arr['code'] : '';
                                    $response_msg = isset($response_arr['msg']) ? $response_arr['msg'] : '';
                                    $response_msg = self::EncryptMsg($response_msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);

                                    //redirect to message
                                    $this->redirect($this->createUrl('package/message', array('t' => $response_code, 'msg' => $response_msg, 'encrypt' => 1)));
                                } else {
                                    $otpModel->addError('token', 'Đơn hàng đang được xử lý. Vui lòng kiểm tra tin nhắn sau');
                                }
                            } else {
                                $msg = Yii::t('web/portal', 'verify_fail');
                            }
                        } else {
                            $msg = Yii::t(
                                'web/portal',
                                'verify_exp_pack',
                                array(
                                    '{link_back}' => CHtml::link('Đồng ý', Yii::app()->controller->createUrl('package/index'))
                                )
                            );
                            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                            $this->redirect($this->createUrl('package/message', array('t' => 7, 'msg' => $msg)));
                        }
                    }
                }

                $this->render('verify_otp', array(
                    'otpModel' => $otpModel,
                    'msg' => $msg,
                    'modelOrder' => $orders,
                    'modelPackage' => $modelPackage,
                    'package_flexible' => $package_flexible,
                ));
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
            }
        } else {
            $msg = Yii::t('web/portal', 'session_timeout');
            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
            $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        }
    }

    /**
     * get amount by ajax
     */
    public function actionGetAmountPackage()
    {
        $amount = 0;
        if (isset($_POST['WOrders']['package']) && is_array($_POST['WOrders']['package']) && !empty($_POST['WOrders']['package'])) {
            $packages = $_POST['WOrders']['package'];
            $modelPackage = new WPackage();
            //get amount
            $amount = $modelPackage->getAmountPackage($packages);
        }
        echo CJSON::encode($amount);
        Yii::app()->end();
    }

    /**
     * get amount by ajax
     */
    public function actionGetOrderPackageFlexible()
    {
        $modelOrder = new WOrders();
        $modelOrder->scenario = 'register_package';
        $modelPackage = new WPackage(); //display view panel_order
        $package_flexible = array(); //list package flexible
        if (isset($_POST['WPackage']) && isset($_POST['WOrders'])) {
            $modelOrder->attributes = $_POST['WOrders'];
            $modelPackage->attributes = $_POST['WPackage'];
            //get amount
            $modelOrder->amount = $modelPackage->getAmountPackage($_POST['WOrders']['package']);
            $package_flexible = $modelPackage->getListPackageFlexible($_POST['WOrders']['package']);
        }
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_order_flexible_table', array(
                    'modelOrder' => $modelOrder,
                    'modelPackage' => $modelPackage,
                    'package_flexible' => $package_flexible,
                ), TRUE)
            )
        );
        Yii::app()->end();
    }

    /**
     * call api cancel package
     */
    public function actionCancelPackage()
    {
        $package_code = Yii::app()->request->getParam('package_code', '');
        $result = array(
            'status' => FALSE,
            'msg' => Yii::t('web/portal', 'error_exception'),
        );
        if (!Yii::app()->user->isGuest) {
            $customer = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
            if ($customer && $customer->phone && $package_code) {
                $orders_data = new OrdersData();
                $data_input = array(
                    'msisdn' => $customer->phone,
                    'package_code' => $package_code,
                );

                //call api web_remove_package
                $data_output = $orders_data->cancelPackage($data_input);
                if (isset($data_output['code']) && $data_output['code'] == 1) {
                    $package_name = $package_code;
                    $package = WPackage::model()->find('code=:code', array(':code' => $package_code));
                    if ($package) {
                        $package_name = $package->name;
                    }
                    $result['status'] = TRUE;
                    $result['msg'] = Yii::t('web/portal', 'cancel_package_success', array('{package_name}' => $package_name));
                } else {
                    if (isset($data_output['msg']) && !empty($data_output['msg'])) {
                        $result['msg'] = $data_output['msg'];
                    }
                }
            }
        }

        if ($result['status']) {
            Yii::app()->user->setFlash('success', $result['msg']);
        } else {
            Yii::app()->user->setFlash('danger', $result['msg']);
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }

    /**
     * with price discount
     * check discount price package with msisdn
     */
    public function actionCheckDiscountPrice()
    {
        $result = array(
            'status' => FALSE,
            'content' => '',
        );

        if (isset($_POST['WPackage']) && isset($_POST['WOrders'])) {
            $package_id = (isset($_POST['WPackage']['id'])) ? $_POST['WPackage']['id'] : '';
            $package_code = (isset($_POST['WPackage']['code'])) ? $_POST['WPackage']['code'] : '';

            $modelPackage = WPackage::model()->find('id=:id AND code=:code', array(':id' => $package_id, ':code' => $package_code));
            if ($modelPackage) {
                //                    if (($modelPackage->vip_user >= WPackage::VIP_USER) && (WPackage::checkVipUser() == FALSE)) { //cannot register
                //
                //                        $this->redirect($this->createUrl('package/detail', array('id' => $modelPackage->id)));
                //                    } else {//vip_user=0 || vip_user=1(aff ->login + sim freedoo)
                $orders_data = new OrdersData();
                $modelOrder = new WOrders();
                $orderDetails = new WOrderDetails();
                $modelOrder->attributes = $_POST['WOrders'];
                $modelOrder->scenario = 'register_package';
                $modelOrder->id = $modelOrder->generateOrderId();

                //sso_id, phone_contact(after submit)
                if (!Yii::app()->user->isGuest) {
                    $modelOrder->sso_id = Yii::app()->user->sso_id;
                    $customer = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
                    //                        if (WPackage::checkVipUser() && $modelPackage->vip_user >= WPackage::VIP_USER && $customer) {
                    //                            $modelOrder->phone_contact = CFunction::makePhoneNumberBasic($customer->phone);
                    //                        }
                }
                //check cookie campaign
                if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                    $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                    $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                }

                if ($modelOrder->validate()) {
                    //if empty promo_code: check cookie affiliate
                    if (empty($modelOrder->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                            $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                            $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                        }
                    }
                    //check package freedoo
                    if (
                        $modelPackage->freedoo != WPackage::FREEDOO_PACKAGE
                        || ($modelPackage->freedoo == WPackage::FREEDOO_PACKAGE && WPackage::checkSimFreedoo($modelOrder->phone_contact) == TRUE)
                    ) {
                        //call api web_check_ctkm: price_discount
                        $modelPackage->checkDiscountPricePackage($orders_data, $modelOrder, $modelPackage);
                        //set order detail
                        $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);
                        $orders_data->package = $modelPackage; //display view panel_order

                        $orders_data->orders = $modelOrder;
                        $orders_data->order_details['packages'] = $orderDetails->attributes;
                        //set session Order
                        //                            Yii::app()->session['session_cart'] = time();
                        //                            Yii::app()->session['orders_data']  = $orders_data;
                        //set cache order
                        $cache_key = 'orders_data_package_' . $modelOrder->id;
                        $pack_cache_key = new CHttpCookie('package_cache_key', $cache_key);
                        $pack_cache_key->expire = time() + 60 * 3; //3'
                        Yii::app()->request->cookies['package_cache_key'] = $pack_cache_key;
                        Yii::app()->cache->set($cache_key, $orders_data);
                        //content modal confirm
                        $result['status'] = TRUE;
                        $result['content'] = Yii::t('web/portal', 'confirm_register', array(
                            '{package_name}' => $modelPackage->name,
                            '{package_price}' => number_format($modelPackage->price, 0, "", ".")
                        ));
                    } else {
                        echo CJSON::encode(array('WOrders_phone_contact' => Yii::t('web/portal', 'err_cannot_register_package_freedoo')));
                        Yii::app()->end();
                    }
                } else {
                    $error = CActiveForm::validate($modelOrder);
                    if ($error != '[]')
                        echo $error;
                    Yii::app()->end();
                }
            }
        }

        echo CJSON::encode($result);
        Yii::app()->end();
    }

    /**
     * with price discount
     */
    public function actionRegisterPriceDiscount()
    {
        //            if (WOrders::checkOrdersSessionExists() === FALSE) {
        //                $msg = Yii::t('web/portal', 'session_timeout');
        //                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        //            } else {
        if (
            isset(Yii::app()->request->cookies['package_cache_key'])
            && !empty(Yii::app()->request->cookies['package_cache_key']->value)
        ) {
            $cache_key = Yii::app()->request->cookies['package_cache_key']->value;
            $orders_data = Yii::app()->cache->get($cache_key);
            if ($orders_data) {
                $modelOrder = $orders_data->orders;
                $modelPackage = $orders_data->package; //display view panel_order
                //get token key
                $otp_form = new OtpForm();
                $token_key = $otp_form->getTokenKey($modelOrder->phone_contact);
                if ($token_key) {
                    Yii::app()->session['verify_number'] = 1;
                    Yii::app()->session['time_reset'] = time();
                    Yii::app()->session['token_key'] = $token_key;

                    $modelOrder->otp = $token_key;

                    //order state
                    $orderState = new WOrderState();
                    $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                    $orders_data->order_state = $orderState;

                    if (YII_DEBUG == TRUE) {
                        $orders_data->orders = $modelOrder;
                        //set cache order
                        Yii::app()->cache->set($cache_key, $orders_data);

                        $this->redirect($this->createUrl('package/verifyTokenKey'));
                    } else {
                        //send MT token key
                        $mt_content = Yii::t('web/mt_content', 'otp_register_package', array(
                            '{token_key}' => $token_key,
                            '{package_name}' => $modelPackage->name,
                        ));
                        if ($otp_form->sentMtVNP($modelOrder->phone_contact, $mt_content, 'package')) {
                            $orders_data->orders = $modelOrder;

                            //set cache order
                            Yii::app()->cache->set($cache_key, $orders_data);

                            $this->redirect($this->createUrl('package/verifyTokenKey'));
                        } else {
                            $msg = Yii::t('web/portal', 'send_mt_fail');
                            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                            $this->redirect($this->createUrl('package/message', array('t' => 9, 'msg' => $msg)));
                        }
                    }
                } else { //get token key fail
                    //redirect to message
                    $msg = Yii::t('web/portal', 'get_token_key_fail');
                    $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                    $this->redirect($this->createUrl('package/message', array('t' => 8, 'msg' => $msg)));
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
            }
        } else {
            $msg = Yii::t('web/portal', 'session_timeout');
            $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
            $this->redirect($this->createUrl('package/message', array('t' => 2, 'msg' => $msg)));
        }
    }

    public function actionListChangePackage($package)
    {
        $this->pageTitle = 'Gói cước - Danh sách gói cước chuyển đổi';
        $modelPackage = WPackage::model()->find('code=:code', array(':code' => $package));
        if ($modelPackage) {
            $packages = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, $modelPackage->id);
            $this->render('change_package', array(
                'modelPackage' => $modelPackage,
                'packages' => $packages
            ));
        } else {
            $this->redirect('site/error');
        }
    }

    /**
     * get modal confirm change package
     */
    public function actionGetFormChangePackage()
    {
        $old_package_code = Yii::app()->request->getParam('old_package_code', '');
        $new_package_code = Yii::app()->request->getParam('new_package_code', '');
        $oldPackage = WPackage::model()->find('code=:code', array(':code' => $old_package_code));
        $newPackage = WPackage::model()->find('code=:code', array(':code' => $new_package_code));
        if (!Yii::app()->user->isGuest) {
            if ($oldPackage && $newPackage) {
                if ((WPackage::checkVipUser() == FALSE) && ($newPackage->vip_user >= WPackage::VIP_USER)) { //vip_user=0 || vip_user=1(aff ->login + sim freedoo)
                    //cannot change
                    $html_content = Yii::t('web/portal', 'msg_change_package_vip_user');
                } else {
                    $html_content = $this->renderPartial('_modal_confirm_change', array(
                        'oldPackage' => $oldPackage,
                        'newPackage' => $newPackage,
                    ), TRUE);
                }
            } else {
                $html_content = Yii::t('web/portal', 'package_not_exist');
            }
        } else {
            $html_content = Yii::t('web/portal', 'not_logged');
        }

        echo CJSON::encode(array('html_content' => $html_content));
        Yii::app()->end();
    }

    /**
     * Change postpaid package
     *
     * @param $old_code
     * @param $new_code
     */
    public function actionChange($old_code, $new_code)
    {
        $result = array(
            'status' => FALSE,
            'msg' => Yii::t('web/portal', 'error_exception'),
        );
        if (!Yii::app()->user->isGuest && $old_code && $new_code) {
            $customer = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
            $oldPackage = WPackage::model()->find('code=:code', array(':code' => $old_code));
            $newPackage = WPackage::model()->find('code=:code', array(':code' => $new_code));
            if ($customer && $oldPackage && $newPackage && !empty($newPackage->code) && !empty($customer->phone)) {
                if ((WPackage::checkVipUser() == FALSE) && ($newPackage->vip_user >= WPackage::VIP_USER)) { //vip_user=0 || vip_user=1(aff ->login + sim freedoo)
                    //cannot change
                    $result['msg'] = Yii::t('web/portal', 'msg_change_package_vip_user');
                } else {
                    $orders_data = new OrdersData();
                    $data_input = array(
                        'msisdn' => $customer->phone,
                        'package_code' => $newPackage->code,
                    );

                    //call api web_post_change
                    $data_output = $orders_data->changePackage($data_input);
                    if (isset($data_output['code']) && $data_output['code'] == 1) {
                        $result['status'] = TRUE;
                        $result['msg'] = Yii::t('web/portal', 'change_package_success', array('{package_name}' => CHtml::encode($newPackage->name)));
                    } else {
                        if (isset($data_output['msg']) && !empty($data_output['msg'])) {
                            $result['msg'] = $data_output['msg'];
                        }
                    }
                }
            } else {
                $result['msg'] = Yii::t('web/portal', 'package_not_exist');
            }
        } else {
            $result['msg'] = Yii::t('web/portal', 'not_logged');
        }

        if ($result['status']) {
            $package_code = $new_code;
            Yii::app()->user->setFlash('success', $result['msg']);
        } else {
            $package_code = $old_code;
            Yii::app()->user->setFlash('danger', $result['msg']);
        }

        $this->redirect($this->createUrl('package/listChangePackage', array('package' => $package_code)));
    }

    /*public function actionMessage($t)
    {
        switch ($t) {
            case 1:
                $this->render('message_success');
                break;
            case 2:
                //timeout
                $this->render('message_fail', array(
                    'msg' => Yii::t('web/portal', 'session_timeout')
                ));
                break;
            case 3:
                $this->render('message_fail', array(
                    'msg' => Yii::t('web/portal', 'err_verify_limited',
                        array(
                            '{link_back}' => CHtml::link('vào đây', Yii::app()->controller->createUrl('package/index'))
                        )
                    )
                ));
                break;
            case 6:
                $this->render('message_fail', array(
                    'msg' => Yii::t('web/portal', 'verify_fail')
                ));
                break;
            case 7:
                $this->render('message_fail', array(
                    'msg' => Yii::t('web/portal', 'verify_exp',
                        array(
                            '{link_back}' => CHtml::link('Đồng ý', Yii::app()->controller->createUrl('package/index'))
                        ))
                ));
                break;
            case 8:
                $this->render('message_fail', array(
                    'msg' => Yii::t('web/portal', 'get_token_key_fail')
                ));
                break;
            case 9:
                $this->render('message_fail', array(
                    'msg' => Yii::t('web/portal', 'send_mt_fail')
                ));
                break;
            default:
                $this->render('message_fail');
        }
    }*/

    public function actionMessage($t, $msg = '', $encrypt = FALSE)
    {
        $this->pageTitle = 'Gói cước - Thông báo';
        $order_id = '';
        if (
            isset(Yii::app()->request->cookies['package_cache_key'])
            && !empty(Yii::app()->request->cookies['package_cache_key']->value)
        ) {
            $cache_key = Yii::app()->request->cookies['package_cache_key']->value;
            $orders_data = Yii::app()->cache->get($cache_key);
            if ($orders_data) {
                $orders_data = Yii::app()->session['orders_data'];
                $order_id = $orders_data->orders->id;
            }
        }

        OtpForm::unsetSession();
        OtpForm::unsetCookie();
        if ($t == 1) {
            OtpForm::unsetCookieUtmSource();
            $this->render('message_success', array('order_id' => $order_id));
        } else {
            $msg = self::DecryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
            $arr_msg = explode('*_', $msg); //function EncryptMsg()
            if (isset($arr_msg[0]) && $arr_msg[0] == 'freedoo') {
                $msg = '';
                if (!empty($arr_msg[1])) {
                    if ($encrypt) {
                        $msg = CHtml::encode($arr_msg[1]);
                    } else {
                        $msg = $arr_msg[1];
                    }
                }
            } else {
                throw new CHttpException(404, 'Không tìm thấy trang bạn yêu cầu.');
            }

            $this->render('message_fail', array(
                'msg' => $msg
            ));
        }
    }

    public static function DecryptMsg($msg, $key, $alg)
    {
        return Utils::decrypt($msg, md5($key), $alg);
    }

    public static function EncryptMsg($msg, $key, $alg)
    {
        return Utils::encrypt('freedoo*_' . $msg, md5($key), $alg);
    }


    /**
     * call api search package by msisdn
     */
    public function actionSearchPackage()
    {
        $this->pageTitle = 'Sản phẩm - Tìm kiếm gói cước';
        $searchPackageForm = new SearchPackageForm();
        $msg = '';
        if (isset($_POST['SearchPackageForm'])) {
            $searchPackageForm->msisdn = isset($_POST['SearchPackageForm']['msisdn']) ? $_POST['SearchPackageForm']['msisdn'] : '';
            $searchPackageForm->scenario = 'search_by_msisdn';

            if (!Utils::googleVerify(Yii::app()->params->secret_key)) {
                $msg = Yii::t('web/portal', 'captcha_error');
                $searchPackageForm->addError('captcha', $msg);
            }

            $data_package = array();
            if (!$searchPackageForm->hasErrors()) {
                $data_input = array(
                    'msisdn' => $searchPackageForm->msisdn,
                );

                //call api
                $orders_data = new OrdersData();
                $data_output = $orders_data->searchPackage($data_input);
                if (isset($data_output['status'])) {
                    $status = $data_output['status'];
                    if (isset($status['code']) && $status['code'] == 1) {
                        if (!empty($data_output['data'])) {
                            $data = CJSON::decode($data_output['data']);
                            if (!empty($data['list_package'])) {
                                $data_package = $data['list_package'];
                            }
                        }
                    } else {
                        if (!empty($data_output['msg'])) {
                            $msg = $data_output['msg'];
                        }
                    }
                }
            }
            $list_package_hot = array();
            $list_package_prepaid = array();
            $list_package_postpaid = array();
            $list_package_data = array();
            $list_package_vas = array();
            $list_package_hot_other = array();
            $list_package_prepaid_other = array();
            $list_package_postpaid_other = array();
            $list_package_data_other = array();
            $list_package_vas_other = array();

            //                CVarDumper::dump($data_package, 10, true);
            //                die();

            if ($data_package) {
                foreach ($data_package as $item) {
                    $package = new WPackage();
                    $package->attributes = $item;
                    switch ($package->type) {
                        case WPackage::PACKAGE_PREPAID:
                            if ($package->freedoo == WPackage::FREEDOO_PACKAGE) {
                                $list_package_prepaid[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot[] = $package;
                                }
                            } else {
                                $list_package_prepaid_other[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot_other[] = $package;
                                }
                            }
                            break;
                        case WPackage::PACKAGE_POSTPAID:
                            if ($package->freedoo == WPackage::FREEDOO_PACKAGE) {
                                $list_package_postpaid[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot[] = $package;
                                }
                            } else {
                                $list_package_postpaid_other[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot_other[] = $package;
                                }
                            }
                            break;
                        case WPackage::PACKAGE_DATA:
                            if ($package->freedoo == WPackage::FREEDOO_PACKAGE) {
                                $list_package_data[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot[] = $package;
                                }
                            } else {
                                $list_package_data_other[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot_other[] = $package;
                                }
                            }
                            break;
                        case WPackage::PACKAGE_VAS:
                            if ($package->freedoo == WPackage::FREEDOO_PACKAGE) {
                                $list_package_vas[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot[] = $package;
                                }
                            } else {
                                $list_package_vas_other[] = $package;
                                if ($package->hot == WPackage::HOT) {
                                    $list_package_hot_other[] = $package;
                                }
                            }
                            break;
                        default:
                    }
                }
            } else {
                $msg = Yii::t('web/portal', 'search_package_empty');
            }

            $this->render('search_package', array(
                'searchPackageForm' => $searchPackageForm,
                'msg' => $msg,
                'list_package_hot' => $list_package_hot,
                'list_package_prepaid' => $list_package_prepaid,
                'list_package_postpaid' => $list_package_postpaid,
                'list_package_data' => $list_package_data,
                'list_package_vas' => $list_package_vas,
                'list_package_hot_other' => $list_package_hot_other,
                'list_package_prepaid_other' => $list_package_prepaid_other,
                'list_package_postpaid_other' => $list_package_postpaid_other,
                'list_package_data_other' => $list_package_data_other,
                'list_package_vas_other' => $list_package_vas_other,
            ));
        } else {
            $this->redirect($this->createUrl('package/index'));
        }
    }

    public function actionTest()
    {
        $data_input = array(
            'msisdn' => '0886915898',
        );

        //call api
        $orders_data = new OrdersData();
        $data_output = $orders_data->searchPackage($data_input);
        if (isset($data_output['status'])) {
            $status = $data_output['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                if (!empty($data_output['data'])) {
                    $data = CJSON::decode($data_output['data']);
                    if (!empty($data['list_package'])) {
                        $list_package = $data['list_package'];
                    }
                }
            } else {
                if (!empty($data_output['msg'])) {
                    $msg = $data_output['msg'];
                }
            }
        }
    }

    public function actionAjaxSearchPackage()
    {
        $searchPackageForm = new SearchPackageForm();
        $result = '';
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['SearchPackageForm'])) {
                $searchPackageForm->attributes = $_POST['SearchPackageForm'];
                $key = (!empty($searchPackageForm->key)) ? $searchPackageForm->key : NULL;

                $orderBy = NULL;
                $order = $searchPackageForm->sortOrder;
                if ($order != SearchPackageForm::SORT_DEFAULT) {
                    switch ($searchPackageForm->sortType) {
                        case SearchPackageForm::SORT_PRICE:
                            $orderBy = 'price';
                            break;
                        case SearchPackageForm::SORT_CALL_INTERNAL:
                            $orderBy = 'call_internal';
                            break;
                        case SearchPackageForm::SORT_CALL_EXTERNAL:
                            $orderBy = 'call_external';
                            break;
                        case SearchPackageForm::SORT_SMS_INTERNAL:
                            $orderBy = 'sms_internal';
                            break;
                        case SearchPackageForm::SORT_SMS_EXTERNAL:
                            $orderBy = 'sms_external';
                            break;
                        case SearchPackageForm::SORT_DATA:
                            $orderBy = 'data';
                            break;
                    }
                }

                $activeId = (isset($_POST['activeId'])) ? $_POST['activeId'] : '';

                $result .= $this->renderPartial('/package/_block_package', array(
                    'list_package' => WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE, $key, $orderBy, $order),
                    'list_package_other' => WPackage::getListPackageHot(0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE, $key, $orderBy, $order),
                    'type' => WPackage::PACKAGE_HOT,
                    'activeId' => $activeId,
                ), TRUE);
                $result .= $this->renderPartial('/package/_block_package', array(
                    'list_package' => WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE, $key, $orderBy, $order),
                    'list_package_other' => WPackage::getListPackageByType(WPackage::PACKAGE_PREPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE, $key, $orderBy, $order),
                    'type' => WPackage::PACKAGE_PREPAID,
                    'activeId' => $activeId,
                ), TRUE);
                $result .= $this->renderPartial('/package/_block_package', array(
                    'list_package' => WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE, $key, $orderBy, $order),
                    'list_package_other' => WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE, $key, $orderBy, $order),
                    'type' => WPackage::PACKAGE_POSTPAID,
                    'activeId' => $activeId,
                ), TRUE);
                $result .= $this->renderPartial('/package/_block_package', array(
                    'list_package' => WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE, $key, $orderBy, $order),
                    'list_package_other' => WPackage::getListPackageByType(WPackage::PACKAGE_DATA, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE, $key, $orderBy, $order),
                    'type' => WPackage::PACKAGE_DATA,
                    'activeId' => $activeId,
                ), TRUE);
                $result .= $this->renderPartial('/package/_block_package', array(
                    'list_package' => WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::FREEDOO_PACKAGE, $key, $orderBy, $order),
                    'list_package_other' => WPackage::getListPackageByType(WPackage::PACKAGE_VAS, 0, FALSE, NULL, 0, 0, 0, WPackage::OTHER_PACKAGE, $key, $orderBy, $order),
                    'type' => WPackage::PACKAGE_VAS,
                    'activeId' => $activeId,
                ), TRUE);
                $result .= $this->renderPartial('/package/_block_package_flexible', array(
                    'type' => WPackage::PACKAGE_DATA_FLEX,
                    'activeId' => $activeId,
                ), TRUE);
            }
        }
        echo $result;
        Yii::app()->end();
    }


    public function actionCheckInfoPhone()
    {
        $msisdn = Yii::app()->request->getParam('msisdn');
        $data = new WPackage();
        $data_input = array(
            'so_tb' => $msisdn
        );
        $data_output = $data->getInfoPhone($data_input);

        echo CJSON::encode($data_output);
        Yii::app()->end();
    }

    public function actionCheckKHDN()
    {
        $msisdn = Yii::app()->request->getParam('msisdn');
        $data = new WPackage();
        $data_input = array(
            'so_tb' => $msisdn
        );
        $data_output = $data->checkKHDN($data_input);
        echo CJSON::encode($data_output);
        Yii::app()->end();
    }

    /**
     * action get list district by province
     */
    public function actionGetDistrictByProvince()
    {
        $province_id = Yii::app()->request->getParam('tinh_id', '');
        Yii::app()->session['fiber_province_id_session'] = $province_id;
        $district = WDistrict::getListDistrictByProvince($province_id);
        echo "<option value=''>" . Yii::t('web/portal', 'select_district') . "</option>";
        foreach ($district as $key => $value) {
            echo CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
        }
        Yii::app()->end();
    }

    /**
     * action get list ward by district theo gói từng tỉnh
     */
    public function actionGetWardBrandOfficesByDistrict()
    {
        $district_code = Yii::app()->request->getParam('quan_id', '');
        $province_code = Yii::app()->session['province_code_session_fiber_final'];
        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($district_code);
        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
        $data_input = array(
            'tinh_id' => $fiber_province_id,
            'quan_id' => $fiber_district_id
        );
        $orderdata = new OrdersData();
        $data_output = $orderdata->getlistwards($data_input);
        $ward = $data_output['Data'];
        Yii::app()->session['wards_session_fiber_final'] = $ward;
        $html_ward = "<option value=''>" . Yii::t('web/portal', 'select_ward') . "</option>";
        for ($i = 0; $i < count($ward); $i++) {
            $data = $ward[$i];
            $html_ward .= CHtml::tag('option', array('value' => $data['phuong_id']), CHtml::encode($data['ten_phuong']), TRUE);
        }
        echo CJSON::encode(array(
            'html_ward' => $html_ward,
        ));
        Yii::app()->end();
    }

    /**
     * action get list ward by district theo gói từng tỉnh
     */
    public function actionGetWardBrandOfficesByDistrict_mytv()
    {
        $district_code = Yii::app()->request->getParam('quan_id', '');
        $province_code = Yii::app()->request->getParam('tinh_id', '');
        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($district_code);
        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
        $data_input = array(
            'tinh_id' => $fiber_province_id,
            'quan_id' => $fiber_district_id
        );
        $orderdata = new OrdersData();
        $data_output = $orderdata->getlistwards($data_input);
        $ward = $data_output['Data'];
        Yii::app()->session['wards_session_fiber_final'] = $ward;
        $html_ward = "<option value=''>" . Yii::t('web/portal', 'select_ward') . "</option>";
        for ($i = 0; $i < count($ward); $i++) {
            $data = $ward[$i];
            $html_ward .= CHtml::tag('option', array('value' => $data['phuong_id']), CHtml::encode($data['ten_phuong']), TRUE);
        }
        echo CJSON::encode(array(
            'html_ward' => $html_ward,
        ));
        Yii::app()->end();
    }

    /**
     * action get list ward by district theo gói từng tỉnh
     */
    public function actionGetWardBrandOfficesByDistrictAllProvince()
    {
        $district_code = Yii::app()->request->getParam('quan_id', '');
        $province_code = Yii::app()->session['fiber_province_id_session'];
        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($district_code);
        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
        $data_input = array(
            'tinh_id' => $fiber_province_id,
            'quan_id' => $fiber_district_id
        );
        $orderdata = new OrdersData();
        $data_output = $orderdata->getlistwards($data_input);
        $ward = $data_output['Data'];
        Yii::app()->session['wards_session_fiber_final'] = $ward;
        $html_ward = "<option value=''>" . Yii::t('web/portal', 'select_ward') . "</option>";
        for ($i = 0; $i < count($ward); $i++) {
            $data = $ward[$i];
            $html_ward .= CHtml::tag('option', array('value' => $data['phuong_id']), CHtml::encode($data['ten_phuong']), TRUE);
        }
        echo CJSON::encode(array(
            'html_ward' => $html_ward,
        ));
        Yii::app()->end();
    }

    /*
     * Lấy street theo gói fiber từng tỉnh
    */
    public function actionGetStreetFiber()
    {

        $chose = Yii::app()->request->getParam('chose', '');
        if ($chose == 1) {
            $province_code = Yii::app()->session['province_code_session_fiber_final'];
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getliststreet($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['street_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        } elseif ($chose == 2) {
            $province_code = Yii::app()->session['province_code_session_fiber_final'];
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getlistap($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['ap_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street1') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        } elseif ($chose == 3) {
            $province_code = Yii::app()->session['province_code_session_fiber_final'];
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getlistkhu($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['khu_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street2') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        }
    }

    public function actionGetStreetFiberTQ()
    {

        $chose = Yii::app()->request->getParam('chose', '');
        if ($chose == 1) {
            $province_code = Yii::app()->request->getParam('tinh_id', '');
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getliststreet($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['street_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        } elseif ($chose == 2) {
            $province_code = Yii::app()->request->getParam('tinh_id', '');
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getlistap($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['ap_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street1') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        } elseif ($chose == 3) {
            $province_code = Yii::app()->request->getParam('tinh_id', '');
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getlistkhu($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['khu_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street2') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        }
    }

    public function actionGetStreetMyTV()
    {

        $chose = Yii::app()->request->getParam('chose', '');
        if ($chose == 1) {
            $province_code = Yii::app()->request->getParam('tinh_id', '');
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getliststreet($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['street_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        } elseif ($chose == 2) {
            $province_code = Yii::app()->request->getParam('tinh_id', '');
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getlistap($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['ap_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street1') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['ap_id']), CHtml::encode($data['ten_ap']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        } elseif ($chose == 3) {
            $province_code = Yii::app()->request->getParam('tinh_id', '');
            $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
            $fiber_province_id = $province_fiber[0]['fiber_province_id'];
            $ward_code = Yii::app()->request->getParam('phuong_id', '');
            $data_input = array(
                'tinh_id' => $fiber_province_id,
                'phuong_id' => $ward_code
            );
            $orderdata = new OrdersData();
            $data_output = $orderdata->getlistkhu($data_input);
            $street = $data_output['Data'];
            Yii::app()->session['khu_session_fiber_final'] = $street;
            $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street2') . "</option>";
            for ($i = 0; $i < count($street); $i++) {
                $data = $street[$i];
                $html_street .= CHtml::tag('option', array('value' => $data['khu_id']), CHtml::encode($data['ten_khu']), TRUE);
            }
            echo CJSON::encode(array(
                'html_street' => $html_street,
            ));
            Yii::app()->end();
        }
    }

    /*
     * Lấy street theo gói fiber toàn quốc
    */
    public function actionGetStreetFiberAllProvince()
    {
        $province_code = Yii::app()->session['fiber_province_id_session'];
        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
        $ward_code = Yii::app()->request->getParam('phuong_id', '');
        $data_input = array(
            'tinh_id' => $fiber_province_id,
            'phuong_id' => $ward_code
        );
        $orderdata = new OrdersData();
        $data_output = $orderdata->getliststreet($data_input);
        $street = $data_output['Data'];
        Yii::app()->session['street_session_fiber_final'] = $street;
        $html_street = "<option value=''>" . Yii::t('web/portal', 'select_street') . "</option>";
        for ($i = 0; $i < count($street); $i++) {
            $data = $street[$i];
            $html_street .= CHtml::tag('option', array('value' => $data['pho_id']), CHtml::encode($data['ten_pho']), TRUE);
        }
        echo CJSON::encode(array(
            'html_street' => $html_street,
        ));
        Yii::app()->end();
    }


    /*
     * Package Fiber
     */

    public function actionFiber()
    {
        $this->pageTitle = 'Gói cước Fiber';

        $data_province = new WProvince();
        $list_province = $data_province->getListProvinceFiber();
        Yii::app()->session['provincefiber_session'] = $list_province;
        $this->render('fiber', array(
            'list_province' => $list_province,
        ));
    }

    public function actionFibervnn()
    {
        $this->pageTitle = 'Đăng ký gói cước Fiber';
        $modelRegFiber = new WRegFiber();
        $orderdata = new OrdersData();
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        $province_data = $orderdata->getprovince();
        $province = $province_data['Data'];
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_fiber') {
            echo CActiveForm::validate($modelRegFiber);
            Yii::app()->end();
        }
        if (isset($_POST['WRegFiber'])) {
            $modelRegFiber->attributes = $_POST['WRegFiber'];
            $modelRegFiber->save();
            $listpackagefiber = Yii::app()->session['listpackagefiber_session'];
            for ($i = 0; $i < count($listpackagefiber); $i++) {
                if ($listpackagefiber[$i]['dichvu_id'] == $modelRegFiber->dichvu_id) {
                    $datapackagefiber = $listpackagefiber[$i];
                }
            }
            for ($i = 0; $i < count($province); $i++) {
                if ($province[$i]['tinh_id'] == $modelRegFiber->tinh_id) {
                    $dataprovince = $province[$i];
                }
            }
            $wardsfiber = Yii::app()->session['wardsfiber_session'];
            $streetfiber = Yii::app()->session['streetfiber_session'];
            for ($i = 0; $i < count($wardsfiber); $i++) {
                if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                    $datawardsfiber = $wardsfiber[$i];
                }
            }
            for ($i = 0; $i < count($streetfiber); $i++) {
                if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                    $datastreetfiber = $streetfiber[$i];
                }
            }
            $modelprovince = WProvince::model()->find('fiber_province_id=:id', array(':id' => $modelRegFiber->tinh_id));
            $modeldistrict = WDistrict::model()->find('fiber_district_id=:id', array(':id' => $modelRegFiber->quan_id));
            $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'] . ' - ' . $dataprovince['tentinh'];
            if ($modelRegFiber->validate()) {
                //data gửi sang đối tác
                $data_input_fiber = array(
                    'tinh_id' => $modelRegFiber->tinh_id,
                    'hdkh_id' => 0,
                    'khachhang_id' => 0,
                    'thuebao_id' => 0,
                    'ngay_yc' => date('Y-m-d H:i:s'),
                    'ten_kh' => $modelRegFiber->ten_kh,
                    'diachi' => $address_detail,
                    'so_dt' => $modelRegFiber->so_dt,
                    'quan_id' => $modelRegFiber->quan_id,
                    'phuong_id' => $modelRegFiber->phuong_id,
                    'pho_id' => $modelRegFiber->pho_id,
                    'khu_id' => '',
                    'ap_id' => '',
                    'dacdiem_id' => '',
                    'so_nha' => $modelRegFiber->so_nha,
                    'so_gt' => $modelRegFiber->so_gt,
                    'ngay_cap' => $modelRegFiber->ngay_cap,
                    'noi_cap' => $modelRegFiber->noi_cap,
                    'mota' => '',
                    'ma_nd' => 'ws_htkd_cskh ',
                    'Loai' => $modelRegFiber->loai,
                    'mota_hs' => '',
                    'dichvu_id' => $modelRegFiber->dichvu_id,
                    'loaitb_id' => $modelRegFiber->loaitb_id,
                    'ghichu' => $modelRegFiber->mota,
                );

                $orderdata = new OrdersData();
                //Call API đối tác
                $data_output = $orderdata->receive($data_input_fiber);
                $orderdetail = $data_output['Data'];
                //data gửi vào api freedoo
                $data_input_fiber_freedoo = array(
                    'orders' => array(
                        'full_name' => $modelRegFiber->ten_kh,
                        'address_detail' => $address_detail,
                        'email' => '',
                        'otp' => '',
                        'id' => $modelOrder->id,
                        'delivery_type' => 1,
                        'phone_contact' => $modelRegFiber->so_dt,
                        'province_code' => $modelprovince->code,
                        'district_code' => $modeldistrict->code,
                        'ward_code' => null,
                        'customer_note' => $modelRegFiber->mota,
                        'promo_code' => '',
                        'sso_id' => null,
                        'invitation' => null,
                        'create_date' => null,
                        'last_update' => null,
                        'shipper_id' => null,
                        'delivery_date' => null,
                        'payment_method' => null,
                        'affiliate_transaction_id' => null,
                        'affiliate_source' => null,
                        'sale_office_code' => null,
                        'receive_cash_by' => null,
                        'receive_cash_date' => null,
                        'campaign_source' => null,
                        'campaign_id' => null,
                        'pre_order_date' => null,
                        'agency_contract_id' => null,
                        'status' => null,
                    ),
                    'order_details' => array(
                        'packages' => array(
                            'quantity' => 1,
                            'status' => 1,
                            'order_id' => $modelOrder->id,
                            'item_id' => $modelRegFiber->dichvu_id,
                            'item_name' => $datapackagefiber['ten_dichvu'],
                            'price' => null,
                            'type' => 'package',
                            'transaction_id' => null,
                        ),
                    ),
                    'order_state' => array(
                        'confirm' => 0,
                        'paid' => 0,
                        'delivered' => '',
                        'order_id' => $modelOrder->id,
                        'id' => null,
                        'create_date' => null,
                        'note' => null,
                    ),
                );
                //Call API freedoo
                $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);
                $this->redirect($this->createUrl('site/success', array('orderdetail' => $orderdetail)));
            }
        }
        $this->render('register-fiber-vnn', array(
            'modelRegFiber' => $modelRegFiber,
            'province' => $province,
            'datapackagefiber' => $datapackagefiber
        ));
    }

    public function actionGetdetailfiber()
    {
        $dichvu_id = Yii::app()->request->getParam('dichvu_id');
        $data_list_package = Yii::app()->session['listpackagefiber_session'];
        for ($i = 0; $i < count($data_list_package); $i++) {
            if ($data_list_package[$i]['dichvu_id'] == $dichvu_id) {
                $datapackagefiber = $data_list_package[$i];
            }
        }
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_detail_package_fiber', array(
                    'datapackagefiber' => $datapackagefiber,
                ), TRUE)
            )
        );
    }

    public function actionRegisterfiber()
    {
        $source_mytv = Yii::app()->session['source_mytv'];
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        $listpackagefiber = Yii::app()->session['listpackagefiber_session'];
        $provincefiber = Yii::app()->session['provincefiber_session'];
        $id = Yii::app()->request->getParam('package');
        $ten_dichvu = Yii::app()->request->getParam('ten_dichvu');
        $tinh_id = Yii::app()->request->getParam('tinh_id');
        for ($i = 0; $i < count($listpackagefiber); $i++) {
            if ($listpackagefiber[$i]['dichvu_id'] == $id) {
                $datapackagefiber = $listpackagefiber[$i];
            }
        }
        for ($i = 0; $i < count($provincefiber); $i++) {
            if ($provincefiber[$i]['fiber_province_id'] == $tinh_id) {
                $dataprovincefiber = $provincefiber[$i];
            }
        }

        $orderdata = new OrdersData();
        $data_output_district = $orderdata->getlistdistrict($tinh_id);
        $data_list_district = $data_output_district['Data'];
        $data_input_get_type = array(
            'tinh_id' => $tinh_id,
            'dichvu_id' => $id,
        );
        $data_output_type = $orderdata->getlisttype($data_input_get_type);
        $data_list_type = $data_output_type['Data'];
        $modelRegFiber = new WRegFiber();

        $this->pageTitle = 'Đăng ký gói cước fiber';
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_fiber') {
            echo CActiveForm::validate($modelRegFiber);
            Yii::app()->end();
        }
        if (isset($_POST['WRegFiber'])) {
            $modelRegFiber->attributes = $_POST['WRegFiber'];
            $wardsfiber = Yii::app()->session['wardsfiber_session'];
            $streetfiber = Yii::app()->session['streetfiber_session'];
            for ($i = 0; $i < count($wardsfiber); $i++) {
                if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                    $datawardsfiber = $wardsfiber[$i];
                }
            }
            for ($i = 0; $i < count($streetfiber); $i++) {
                if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                    $datastreetfiber = $streetfiber[$i];
                }
            }
            $modelprovince = WProvince::model()->find('fiber_province_id=:id', array(':id' => $tinh_id));
            $modeldistrict = WDistrict::model()->find('fiber_district_id=:id', array(':id' => $modelRegFiber->quan_id));
            $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'] . ' - ' . $dataprovincefiber['name'];
            if ($modelRegFiber->validate()) {
                //data gửi sang đối tác
                $data_input_fiber = array(
                    'tinh_id' => $_POST['WRegFiber']['tinh_id'],
                    'hdkh_id' => 0,
                    'khachhang_id' => 0,
                    'thuebao_id' => 0,
                    'ngay_yc' => date('Y-m-d H:i:s'),
                    'ten_kh' => $modelRegFiber->ten_kh,
                    'diachi' => $address_detail,
                    'so_dt' => $modelRegFiber->so_dt,
                    'quan_id' => $modelRegFiber->quan_id,
                    'phuong_id' => $modelRegFiber->phuong_id,
                    'pho_id' => $modelRegFiber->pho_id,
                    'khu_id' => '',
                    'ap_id' => '',
                    'dacdiem_id' => '',
                    'so_nha' => $modelRegFiber->so_nha,
                    'so_gt' => $modelRegFiber->so_gt,
                    'ngay_cap' => $modelRegFiber->ngay_cap,
                    'noi_cap' => $modelRegFiber->noi_cap,
                    'mota' => $modelRegFiber->mota,
                    'ma_nd' => 'ws_htkd_cskh ',
                    'Loai' => $modelRegFiber->loai,
                    'mota_hs' => '',
                    'dichvu_id' => $modelRegFiber->dichvu_id,
                    'loaitb_id' => $modelRegFiber->loaitb_id,
                    'ghichu' => '',
                );

                $orderdata = new OrdersData();
                //Call API đối tác
                $data_output = $orderdata->receive($data_input_fiber);
                $orderdetail = $data_output['Data'];
                //data gửi vào api freedoo
                $data_input_fiber_freedoo = array(
                    'orders' => array(
                        'full_name' => $modelRegFiber->ten_kh,
                        'address_detail' => $address_detail,
                        'email' => '',
                        'otp' => '',
                        'id' => $modelOrder->id,
                        'delivery_type' => 1,
                        'phone_contact' => $modelRegFiber->so_dt,
                        'province_code' => $modelprovince->code,
                        'district_code' => $modeldistrict->code,
                        'ward_code' => null,
                        'customer_note' => $modelRegFiber->mota,
                        'promo_code' => '',
                        'sso_id' => null,
                        'invitation' => null,
                        'create_date' => null,
                        'last_update' => null,
                        'shipper_id' => null,
                        'delivery_date' => null,
                        'payment_method' => null,
                        'affiliate_transaction_id' => null,
                        'affiliate_source' => null,
                        'sale_office_code' => null,
                        'receive_cash_by' => null,
                        'receive_cash_date' => null,
                        'campaign_source' => null,
                        'campaign_id' => null,
                        'pre_order_date' => null,
                        'agency_contract_id' => null,
                        'status' => null,
                    ),
                    'order_details' => array(
                        'packages' => array(
                            'quantity' => 1,
                            'status' => 1,
                            'order_id' => $modelOrder->id,
                            'item_id' => $modelRegFiber->dichvu_id,
                            'item_name' => $datapackagefiber['ten_dichvu'],
                            'price' => null,
                            'type' => 'package',
                            'transaction_id' => null,
                        ),
                    ),
                    'order_state' => array(
                        'confirm' => 10,
                        'paid' => 0,
                        'delivered' => '',
                        'order_id' => $modelOrder->id,
                        'id' => null,
                        'create_date' => null,
                        'note' => null,
                    ),
                );
                //Call API freedoo
                $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);
                if (!$source_mytv && $source_mytv == '') {
                    $this->redirect($this->createUrl('site/success', array('orderdetail' => $orderdetail)));
                } else {
                    $this->redirect($this->createUrl('package/success'));
                }
            }
        }
        $this->render('register-fiber', array('modelRegFiber' => $modelRegFiber, 'id' => $id, 'ten_dichvu' => $ten_dichvu, 'tinh_id' => $tinh_id, 'data_list_district' => $data_list_district, 'data_list_type' => $data_list_type, 'datapackagefiber' => $datapackagefiber, 'dataprovincefiber' => $dataprovincefiber));
    }


    /*
     * Lấy ra danh sách gói fiber từ api
     */

    public function actionGetlistfiber()
    {
        $tinh_id = Yii::app()->request->getParam('tinh_id');

        $data_input = array(
            'tinh_id' => $tinh_id
        );
        $orderdata = new OrdersData();
        $data_output = $orderdata->getlistfiber($data_input);
        $data_list_package = $data_output['Data'];
        Yii::app()->session['listpackagefiber_session'] = $data_list_package;
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_package_fiber', array(
                    'data_list_package' => $data_list_package,
                    'tinh_id' => $tinh_id,
                ), TRUE)
            )
        );
    }


    /*
    * Lấy ra danh sách quận huyện
    */
    public function actionGetdistrict()
    {
        $tinh_id = Yii::app()->request->getParam('tinh_id');
        $data_input = array(
            'tinh_id' => $tinh_id,
        );
        $orderdata = new OrdersData();
        $data_output_district = $orderdata->getlistdistrict($data_input);
        $data_list_district = $data_output_district['Data'];
        Yii::app()->session['districtfiber_session'] = $data_output_district;
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_district', array(
                    'data_list_district' => $data_list_district,
                    'tinh_id' => $tinh_id,
                ), TRUE)
            )
        );
    }

    /*
     * Lấy ra danh sách phường xã
     */
    public function actionGetwards()
    {
        $tinh_id = Yii::app()->request->getParam('tinh_id');
        $quan_id = Yii::app()->request->getParam('quan_id');
        $data_input = array(
            'tinh_id' => $tinh_id,
            'quan_id' => $quan_id,
        );
        $orderdata = new OrdersData();
        $data_output_wards = $orderdata->getlistwards($data_input);
        $data_list_wards = $data_output_wards['Data'];
        Yii::app()->session['wardsfiber_session'] = $data_list_wards;
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_wards', array(
                    'data_list_wards' => $data_list_wards,
                    'tinh_id' => $tinh_id,
                ), TRUE)
            )
        );
    }

    /*
     * Lấy ra danh sách phố
     */
    public function actionGetstreet()
    {
        $tinh_id = Yii::app()->request->getParam('tinh_id');
        $phuong_id = Yii::app()->request->getParam('phuong_id');
        $data_input = array(
            'tinh_id' => $tinh_id,
            'phuong_id' => $phuong_id,
        );
        $orderdata = new OrdersData();
        $data_output_street = $orderdata->getliststreet($data_input);
        $data_list_street = $data_output_street['Data'];
        Yii::app()->session['streetfiber_session'] = $data_list_street;
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_street', array(
                    'data_list_street' => $data_list_street,
                ), TRUE)
            )
        );
    }

    /*
     * Lấy ra danh sách loại hình thuê bao
     */

    public function actionGettype()
    {
        $tinh_id = Yii::app()->request->getParam('tinh_id');
        $dichvu_id = Yii::app()->request->getParam('dichvu_id');
        $data_input = array(
            'tinh_id' => $tinh_id,
            'dichvu_id' => $dichvu_id,
        );
        $orderdata = new OrdersData();
        $data_output_type = $orderdata->getlisttype($data_input);
        $data_list_type = $data_output_type['Data'];
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_type', array(
                    'data_list_type' => $data_list_type,
                ), TRUE)
            )
        );
    }

    /*
     * Lấy ra danh sách loại hình thuê bao theo gói toàn quốc
     */

    public function actionGettypeAllProvince()
    {
        $tinh_id = Yii::app()->request->getParam('tinh_id');
        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($tinh_id);
        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
        $dichvu_id = Yii::app()->request->getParam('dichvu_id');
        $data_input = array(
            'tinh_id' => $fiber_province_id,
            'dichvu_id' => $dichvu_id,
        );
        $orderdata = new OrdersData();
        $data_output_type = $orderdata->getlisttype($data_input);
        $data_list_type = $data_output_type['Data'];
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_type', array(
                    'data_list_type' => $data_list_type,
                ), TRUE)
            )
        );
    }

    /*
     * Checkout Fiber
     */

    public function actionCheckoutfiber()
    {
        $modelRegFiber = new WRegFiber();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_fiber') {
            echo CActiveForm::validate($modelRegFiber);
            Yii::app()->end();
        }
        if (isset($_POST['WRegFiber'])) {

            if ($modelRegFiber->validate()) {
                $modelRegFiber->attributes = $_POST['WRegFiber'];
                $data_input = array(
                    'tinh_id' => $_POST['WRegFiber']['tinh_id'],
                    'hdkh_id' => 0,
                    'khachhang_id' => 0,
                    'thuebao_id' => 0,
                    'ngay_yc' => date('Y-m-d H:i:s'),
                    'ten_kh' => $_POST['WRegFiber']['ten_kh'],
                    'diachi' => '',
                    'so_dt' => $_POST['WRegFiber']['so_dt'],
                    'quan_id' => $_POST['WRegFiber']['quan_id'],
                    'phuong_id' => $_POST['WRegFiber']['phuong_id'],
                    'pho_id' => $_POST['WRegFiber']['pho_id'],
                    'khu_id' => '',
                    'ap_id' => '',
                    'dacdiem_id' => '',
                    'so_nha' => $_POST['WRegFiber']['so_nha'],
                    'so_gt' => $_POST['WRegFiber']['so_gt'],
                    'ngay_cap' => $_POST['WRegFiber']['ngay_cap'],
                    'noi_cap' => $_POST['WRegFiber']['noi_cap'],
                    'mota' => $_POST['WRegFiber']['mota'],
                    'ma_nd' => 'ws_htkd_cskh ',
                    'Loai' => $_POST['WRegFiber']['Loai'],
                    'mota_hs' => '',
                    'dichvu_id' => $_POST['WRegFiber']['dichvu_id'],
                    'loaitb_id' => $_POST['WRegFiber']['loaitb_id'],
                    'ghichu' => '',
                );
                //Call API
                $orderdata = new OrdersData();
                $data_output = $orderdata->receive($data_input);
                $orderdetail = $data_output['Data'];
                //                $this->render('checkoutfiber', array('orderdetail' => $orderdetail));
            }
        }
    }


    public function actionRegisterfibervnn()
    {
        $this->pageTitle = 'Đăng ký gói cước Fiber';
        $province = new WProvince();
        $list_province = $province->getListProvince();
        //Package freedo
        $package_freedoo = new WPackage();
        $list_package_freedoo = $package_freedoo->getListPackageFiberFreedoo();
        $list_package_national = $package_freedoo->getListPackageFiberNational();
        $this->render('register-fiber', array(
            'list_province' => $list_province,
            'list_package_freedoo' => $list_package_freedoo,
            'list_package_national' => $list_package_national
        ));
    }

    public function actionListfiber()
    {
        $province_code = Yii::app()->request->getParam('province_code');
        $package = new WPackage();
        if ($province_code == 1000) {
            $list_package = WPackage::getListFiberToanQuoc();
        } else {
            $list_package = $package->getListFiberFreedooLocalFinal($province_code);
            //            $list_package_local = $package->getListFiberLocalFinal($province_code);
        }
        Yii::app()->session['province_code_session_fiber_final'] = $province_code;
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_package_fiber', array(
                    'list_package' => $list_package,
                    //                    'list_package_local' => $list_package_local,
                    'province_code' => $province_code,
                ), TRUE)
            )
        );
    }

    public function actionListcombo()
    {
        $province_code = Yii::app()->request->getParam('province_code');
        $package = new WPackage();
        if ($province_code == 1000) {
            $list_package = WPackage::getListComboToanQuoc();
        } else {
            $list_package = $package->getListComboLocalFinal($province_code);
            //            $list_package_local = $package->getListFiberLocalFinal($province_code);
        }
        Yii::app()->session['province_code_session_fiber_final'] = $province_code;
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_package_combo', array(
                    'list_package' => $list_package,
                    //                    'list_package_local' => $list_package_local,
                    'province_code' => $province_code,
                ), TRUE)
            )
        );
    }

    public function actionListmytv()
    {
        $package = new WPackage();
        $list_package = $package->getListMyTV();
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_package_mytv', array(
                    'list_package' => $list_package,

                ), TRUE)
            )
        );
    }

    public function actionListnomalmytv()
    {
        $package = new WPackage();
        $list_package = $package->getListMyTV_NomalTV();
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_package_mytv', array(
                    'list_package' => $list_package,

                ), TRUE)
            )
        );
    }

    public function actionListfiberallprovince()
    {
        $package = new WPackage();
        $list_package = $package::model()->getListFiberToanQuoc();
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_package_fiber_all_province', array(
                    'list_package' => $list_package,
                ), TRUE)
            )
        );
    }

    public function actionGetdetailpackagefiber()
    {
        $package_id = Yii::app()->request->getParam('package_id');
        $package = new WPackage();
        $detail_package = $package->getDetailFiber($package_id);
        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_detail_package_fiber', array(
                    'detail_package' => $detail_package,
                ), TRUE)
            )
        );
    }

    /*
     * Đăng ký gói fiber final
     */
    public function actionRegisterfibers($package)
    {
        $source_mytv = Yii::app()->session['source_mytv'];
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        if ($modelPackage) {
            $modelRegFiber = new WRegFiber();
            $province_code = Yii::app()->session['province_code_session_fiber_final'];
            if (isset($province_code)) {
                $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);

                $fiber_province_id = $province_fiber[0]['fiber_province_id'];

                $data_input = array(
                    'tinh_id' => $fiber_province_id,
                    'dichvu_id' => $modelPackage['code_vnpt'],
                );
                $orderdata = new OrdersData();
                $data_output_type = $orderdata->getlisttype($data_input);
                $data_list_type = $data_output_type['Data'];
            } else {
                $list_province = WProvince::getListProvince();
            }
            $district = WDistrict::getListDistrictByProvince($province_code);

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_fiber') {
                echo CActiveForm::validate($modelRegFiber);
                Yii::app()->end();
            }
            if ($_POST['WRegFiber']) {
                $modelRegFiber->attributes = $_POST['WRegFiber'];
                //check cookie campaign
                if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                    $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                    $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                }
                if (empty($modelRegFiber->promo_code)) {
                    if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                        $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                        $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                    }
                }
                $modelRegFiber->tinh_id = $fiber_province_id;
                $modelRegFiber->freedoo_order_id = $modelOrder->id;
                $modelRegFiber->dichvu_id = $modelPackage->code_vnpt;
                if ($modelRegFiber->ten_yc == '' || !isset($modelRegFiber->ten_yc)) {
                    $modelRegFiber->ten_yc = $modelRegFiber->ten_kh;
                }
                if ($modelRegFiber->so_dt_yc == '' || !isset($modelRegFiber->so_dt_yc)) {
                    $modelRegFiber->so_dt_yc = $modelRegFiber->so_dt;
                }
                if ($modelRegFiber->validate()) {
                    $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($modelRegFiber->quan_id);
                    $fiber_district_id = $district_fiber[0]['fiber_district_id'];
                    $wardsfiber = Yii::app()->session['wards_session_fiber_final'];
                    for ($i = 0; $i < count($wardsfiber); $i++) {
                        if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                            $datawardsfiber = $wardsfiber[$i];
                        }
                    }
                    $streetfiber = Yii::app()->session['street_session_fiber_final'];
                    for ($i = 0; $i < count($streetfiber); $i++) {
                        if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                            $datastreetfiber = $streetfiber[$i];
                        }
                    }

                    $apfiber = Yii::app()->session['ap_session_fiber_final'];
                    for ($i = 0; $i < count($apfiber); $i++) {
                        if ($apfiber[$i]['pho_id'] == $modelRegFiber->ap_id) {
                            $dataapfiber = $apfiber[$i];
                        }
                    }
                    $khufiber = Yii::app()->session['khu_session_fiber_final'];
                    for ($i = 0; $i < count($khufiber); $i++) {
                        if ($khufiber[$i]['pho_id'] == $modelRegFiber->khu_id) {
                            $datakhufiber = $khufiber[$i];
                        }
                    }
                    if ($datastreetfiber['ten_pho'] != '' || $datawardsfiber['ten_phuong'] != '') {
                        $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'];
                    }
                    if ($dataapfiber['ten_pho'] != '') {
                        $address_detail = $modelRegFiber->so_nha . ' - ' . $dataapfiber['ten_pho'];
                    }
                    if ($datakhufiber['ten_pho'] != '') {
                        $address_detail = $modelRegFiber->so_nha . ' - ' . $datakhufiber['ten_pho'];
                    }

                    $data_input_fiber = array(
                        'tinh_id' => $fiber_province_id,
                        'ngay_yc' => date('d/m/Y'),
                        'ten_kh' => $modelRegFiber->ten_kh,
                        'diachi' => $address_detail,
                        'so_dt' => $modelRegFiber->so_dt,
                        'quan_id' => $fiber_district_id,
                        'phuong_id' => $modelRegFiber->phuong_id,
                        'pho_id' => $modelRegFiber->pho_id,
                        'ap_id' => $modelRegFiber->ap_id,
                        'khu_id' => $modelRegFiber->khu_id,
                        'ten_yc' => $modelRegFiber->ten_yc,
                        'so_dt_yc' => $modelRegFiber->so_dt_yc,
                        'ma_nd' => 'freedoo',
                        'loai' => 1,
                        'dichvu_id' => 4,
                        'loaitb_id' => 58,
                        'ghichu' => $modelPackage->name,
                    );

                    $orderdata = new OrdersData();
                    //Call API đối tác
                    $data_output = $orderdata->receive($data_input_fiber);
                    $orderdetail = $modelOrder->id;
                    Yii::app()->session['success_register_fiber'] = $orderdetail;
                    $orderdetailfromapi = $data_output['Data'];
                    if ($data_output['errorCode'] <> 0) {
                        $mes = 'Không thành công! Đã xảy ra lỗi, xin vui lòng thử lại';
                        //                        $this->render('register-fiber-vnn', array(
                        //                            'modelPackage' => $modelPackage,
                        //                            'modelRegFiber' => $modelRegFiber,
                        //                            'district' => $district,
                        //                            'data_list_type' => $data_list_type,
                        //                            'list_province' => $list_province,
                        //                            'mes' => $mes
                        //                        ));
                    } elseif ($data_output['errorCode'] == 0) {
                        $modelRegFiber->fiber_order_id = $orderdetailfromapi['ma_gd'];
                        $modelRegFiber->hdkh_id = $orderdetailfromapi['hdkh_id'];

                        //data gửi vào api freedoo
                        $data_input_fiber_freedoo = array(
                            'orders' => array(
                                'full_name' => $modelRegFiber->ten_kh,
                                'address_detail' => $address_detail,
                                'email' => '',
                                'otp' => '',
                                'id' => $modelOrder->id,
                                'delivery_type' => 1,
                                'phone_contact' => $modelRegFiber->so_dt,
                                'province_code' => $province_code,
                                'district_code' => $modelRegFiber->quan_id,
                                'ward_code' => null,
                                'customer_note' => $modelRegFiber->mota,
                                'promo_code' => $modelRegFiber->promo_code,
                                'sso_id' => null,
                                'invitation' => null,
                                'create_date' => null,
                                'last_update' => null,
                                'shipper_id' => null,
                                'delivery_date' => null,
                                'payment_method' => null,
                                'affiliate_transaction_id' => $modelOrder->affiliate_transaction_id,
                                'affiliate_source' => $modelOrder->affiliate_source,
                                'sale_office_code' => null,
                                'receive_cash_by' => null,
                                'receive_cash_date' => null,
                                'campaign_source' => $modelOrder->campaign_source,
                                'campaign_id' => $modelOrder->campaign_id,
                                'pre_order_date' => null,
                                'agency_contract_id' => null,
                                'status' => null,
                                'product_type' => 'fiber',
                            ),
                            'order_details' => array(
                                'packages' => array(
                                    'quantity' => 1,
                                    'status' => 1,
                                    'order_id' => $modelOrder->id,
                                    'item_id' => $modelPackage->code,
                                    'item_name' => $modelPackage->name,
                                    'price' => $modelPackage->price,
                                    'type' => 'package_fiber',
                                    'transaction_id' => null,
                                ),
                            ),
                            'order_state' => array(
                                'confirm' => 10,
                                'paid' => 0,
                                'delivered' => '',
                                'order_id' => $modelOrder->id,
                                'id' => null,
                                'create_date' => null,
                                'note' => null,
                            ),
                        );
                        //Call API freedoo
                        $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);
                        $modelRegFiber->save();
                        $this->redirect($this->createUrl('site/success'));
                    }
                }
            }
        }
        $this->render('register-fiber-vnn', array(
            'modelPackage' => $modelPackage,
            'modelRegFiber' => $modelRegFiber,
            'district' => $district,
            'data_output_fiber_freedoo' => $data_output_fiber_freedoo,
            'list_province' => $list_province,
            'mes' => $mes

        ));
    }

    /*
     * Đăng ký fiber toàn quốc của freedoo
     */
    public function actionRegisterfiberallprovince($package)
    {
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        $packagePrice = empty($modelPackage->price_discount) ? $modelPackage->price : $modelPackage->price_discount;
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        if ($modelPackage) {
            $modelRegFiber = new WRegFiber();
            $blackListPhone = WBlackListPhone::model()->findAll();
            $arrPhone = CHtml::listData($blackListPhone, 'id', 'phone');
            if (!in_array($modelRegFiber->so_dt, $arrPhone) || !in_array($modelRegFiber->so_dt_yc, $arrPhone)) {

                $province = new WProvince();
                $list_province = $province->getListProvince();

                if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_fiber_all_province') {
                    echo CActiveForm::validate($modelRegFiber);
                    Yii::app()->end();
                }
                if ($_POST['WRegFiber']) {
                    $modelRegFiber->attributes = $_POST['WRegFiber'];
                    //check cookie campaign
                    if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }
                    if (empty($modelRegFiber->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                            $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                            $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                        }
                    }
                    if ($_POST['WRegFiber']['tinh_id']) {
                        $province_code = $_POST['WRegFiber']['tinh_id'];
                        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
                        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
                        $modelRegFiber->tinh_id = $fiber_province_id;
                    }
                    $modelRegFiber->freedoo_order_id = $modelOrder->id;
                    $modelRegFiber->dichvu_id = $modelPackage->code_vnpt;
                    if ($modelRegFiber->ten_yc == '' || !isset($modelRegFiber->ten_yc)) {
                        $modelRegFiber->ten_yc = $modelRegFiber->ten_kh;
                    }
                    if ($modelRegFiber->so_dt_yc == '' || !isset($modelRegFiber->so_dt_yc)) {
                        $modelRegFiber->so_dt_yc = $modelRegFiber->so_dt;
                    }
                    if ($modelRegFiber->validate()) {
                        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($modelRegFiber->quan_id);
                        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
                        $wardsfiber = Yii::app()->session['wards_session_fiber_final'];
                        for ($i = 0; $i < count($wardsfiber); $i++) {
                            if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                                $datawardsfiber = $wardsfiber[$i];
                            }
                        }
                        $streetfiber = Yii::app()->session['street_session_fiber_final'];
                        for ($i = 0; $i < count($streetfiber); $i++) {
                            if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                                $datastreetfiber = $streetfiber[$i];
                            }
                        }
                        $apfiber = Yii::app()->session['ap_session_fiber_final'];
                        for ($i = 0; $i < count($apfiber); $i++) {
                            if ($apfiber[$i]['pho_id'] == $modelRegFiber->ap_id) {
                                $dataapfiber = $apfiber[$i];
                            }
                        }
                        $khufiber = Yii::app()->session['khu_session_fiber_final'];
                        for ($i = 0; $i < count($khufiber); $i++) {
                            if ($khufiber[$i]['pho_id'] == $modelRegFiber->khu_id) {
                                $datakhufiber = $khufiber[$i];
                            }
                        }
                        if ($datastreetfiber['ten_pho'] != '' || $datawardsfiber['ten_phuong'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'];
                        }
                        if ($dataapfiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $dataapfiber['ten_pho'];
                        }
                        if ($datakhufiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datakhufiber['ten_pho'];
                        }

                        $data_input_fiber = array(
                            'tinh_id' => $fiber_province_id,
                            'ngay_yc' => date('d/m/Y'),
                            'ten_kh' => $modelRegFiber->ten_kh,
                            'diachi' => $address_detail,
                            'so_dt' => $modelRegFiber->so_dt,
                            'quan_id' => $fiber_district_id,
                            'phuong_id' => $modelRegFiber->phuong_id,
                            'pho_id' => $modelRegFiber->pho_id,
                            'ap_id' => $modelRegFiber->ap_id,
                            'khu_id' => $modelRegFiber->khu_id,
                            'ten_yc' => $modelRegFiber->ten_yc,
                            'so_dt_yc' => $modelRegFiber->so_dt_yc,
                            'ma_nd' => 'freedoo',
                            'loai' => 1,
                            'dichvu_id' => 4,
                            'loaitb_id' => 58,
                            'ghichu' => $modelPackage->name,
                        );

                        $orderdata = new OrdersData();
                        //Call API đối tác
                        $data_output = $orderdata->receive($data_input_fiber);
                        $orderdetail = $modelOrder->id;
                        Yii::app()->session['success_register_fiber'] = $orderdetail;
                        $orderdetailfromapi = $data_output['Data'];
                        if ($data_output['errorCode'] <> 0) {
                            $mes = $data_output['Message'];
                            //                        $this->render('register-fiber-vnn', array(
                            //                            'modelPackage' => $modelPackage,
                            //                            'modelRegFiber' => $modelRegFiber,
                            //                            'list_province' => $list_province,
                            //                            'mes' => $mes
                            //                        ));
                        } elseif ($data_output['errorCode'] == 0) {
                            $modelRegFiber->fiber_order_id = $orderdetailfromapi['ma_gd'];
                            $modelRegFiber->hdkh_id = $orderdetailfromapi['hdkh_id'];

                            //data gửi vào api freedoo
                            $data_input_fiber_freedoo = array(
                                'orders' => array(
                                    'full_name' => $modelRegFiber->ten_kh,
                                    'address_detail' => $address_detail,
                                    'email' => '',
                                    'otp' => '',
                                    'id' => $modelOrder->id,
                                    'delivery_type' => 1,
                                    'phone_contact' => $modelRegFiber->so_dt,
                                    'province_code' => $province_code,
                                    'district_code' => $modelRegFiber->quan_id,
                                    'ward_code' => null,
                                    'customer_note' => $modelRegFiber->mota,
                                    'promo_code' => $modelRegFiber->promo_code,
                                    'sso_id' => null,
                                    'invitation' => null,
                                    'create_date' => null,
                                    'last_update' => null,
                                    'shipper_id' => null,
                                    'delivery_date' => null,
                                    'payment_method' => null,
                                    'affiliate_transaction_id' => $modelOrder->affiliate_transaction_id,
                                    'affiliate_source' => $modelOrder->affiliate_source,
                                    'sale_office_code' => null,
                                    'receive_cash_by' => null,
                                    'receive_cash_date' => null,
                                    'campaign_source' => $modelOrder->campaign_source,
                                    'campaign_id' => $modelOrder->campaign_id,
                                    'pre_order_date' => null,
                                    'agency_contract_id' => null,
                                    'status' => null,
                                    'product_type' => 'fiber',
                                ),
                                'order_details' => array(
                                    'packages' => array(
                                        'quantity' => 1,
                                        'status' => 1,
                                        'order_id' => $modelOrder->id,
                                        'item_id' => $modelPackage->code,
                                        'item_name' => $modelPackage->name,
                                        'price' => $modelPackage->price,
                                        'type' => 'package_fiber',
                                        'transaction_id' => null,
                                    ),
                                ),
                                'order_state' => array(
                                    'confirm' => 10,
                                    'paid' => 0,
                                    'delivered' => '',
                                    'order_id' => $modelOrder->id,
                                    'id' => null,
                                    'create_date' => null,
                                    'note' => null,
                                ),
                            );
                            if (isset($_POST['WRegFiber']['restricted_apartment_id']) && $_POST['WRegFiber']['restricted_apartment_id'] < 1) { // không nằm trong danh sách hạn chế
                                $data_input_fiber_freedoo['order']['payment_method'] = null;
                                $data_input_fiber_freedoo['order_state']['confirm'] = 0;
                                $data_input_fiber_freedoo['order_state']['paid'] = 0;
                            }

                            //Call API freedoo
                            $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);
                            $modelRegFiber->save();

                            if (isset($_POST['WRegFiber']['restricted_apartment_id']) && $_POST['WRegFiber']['restricted_apartment_id'] < 1) { // không nằm trong danh sách hạn chế

                                $location_vnptpay = WLocationVnptpay::model()->find('id=:id', array(':id' => $fiber_province_id));
                                // chuyen thanh toan VNPT Pay
                                $model_pm = new WPaymentMethod();
                                $ary_payment = $model_pm->paymentViaVnptPay($data_input_fiber_freedoo, $packagePrice, $location_vnptpay);
                                $this->redirect($ary_payment['urlRequest']);
                            } else {
                                $this->redirect($this->createUrl('site/success'));
                            }
                        }
                    }
                }
            }
        }
        $this->render('register-fiber-vnn-all-province', array(
            'modelPackage' => $modelPackage,
            'modelRegFiber' => $modelRegFiber,
            'list_province' => $list_province,
            'mes' => $mes
        ));
    }

    public function packageSubmitting($cache_key)
    {
        $packageSubmitting = !empty(Yii::app()->session['packageSubmitting']) ? Yii::app()->session['packageSubmitting'] : -1;
        if ($cache_key == $packageSubmitting) {
            return true;
        } else {
            Yii::app()->session['packageSubmitting'] = $cache_key;
        }
        return false;
    }

    public function actionRegistermytv($package)
    {

        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        if ($modelPackage) {
            $modelRegFiber = new WRegFiber();
            $modelOrder = new WOrders();
            $modelOrder->id = $modelOrder->generateOrderId();
            $province = new WProvince();
            $list_province = $province->getListProvince(true);
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_mytv') {
                echo CActiveForm::validate($modelRegFiber);
                Yii::app()->end();
            }
            if ($_POST['WRegFiber']) {
                $blackListPhone = WBlackListPhone::model()->findAll();
                $arrPhone = CHtml::listData($blackListPhone, 'id', 'phone');
                if (!in_array($modelRegFiber->so_dt, $arrPhone) || !in_array($modelRegFiber->so_dt_yc, $arrPhone)) {
                    $modelRegFiber->attributes = $_POST['WRegFiber'];
                    $stb = $_POST['WRegFiber']['stb_use'];
                    $stbox = '';
                    if ($stb == 1) {
                        $price_sale = $modelPackage->price_stb;
                        $stbox = 'yes';
                    } elseif ($stb == 0) {
                        $stbox = 'no';
                        $price_sale = $modelPackage->price_no_stb;
                    }
                    //check cookie campaign
                    if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }
                    if (empty($modelRegFiber->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                            $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                            $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                        }
                    }
                    if ($_POST['WRegFiber']['tinh_id']) {
                        $province_code = $_POST['WRegFiber']['tinh_id'];
                        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
                        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
                        $modelRegFiber->tinh_id = $fiber_province_id;
                    }
                    if ($_POST['WRegFiber']['quan_id']) {
                        $quan_id = $_POST['WRegFiber']['quan_id'];
                        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($quan_id);
                        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
                        $modelRegFiber->quan_id = $fiber_district_id;
                    }
                    if ($modelRegFiber->validate()) {
                        $streetfiber = Yii::app()->session['street_session_fiber_final'];
                        for ($i = 0; $i < count($streetfiber); $i++) {
                            if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                                $datastreetfiber = $streetfiber[$i];
                            }
                        }

                        $apfiber = Yii::app()->session['ap_session_fiber_final'];
                        for ($i = 0; $i < count($apfiber); $i++) {
                            if ($apfiber[$i]['ap_id'] == $modelRegFiber->ap_id) {
                                $dataapfiber = $apfiber[$i];
                            }
                        }
                        $khufiber = Yii::app()->session['khu_session_fiber_final'];
                        for ($i = 0; $i < count($khufiber); $i++) {
                            if ($khufiber[$i]['khu_id'] == $modelRegFiber->khu_id) {
                                $datakhufiber = $khufiber[$i];
                            }
                        }
                        if ($datastreetfiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'];
                        }
                        if ($dataapfiber['ten_ap'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $dataapfiber['ten_ap'];
                        }
                        if ($datakhufiber['ten_khu'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datakhufiber['ten_khu'];
                        }

                        $data_input_fiber = array(
                            'tinh_id' => $fiber_province_id,
                            'ngay_yc' => date('d/m/Y'),
                            'ten_kh' => $modelRegFiber->ten_kh,
                            'diachi' => $address_detail,
                            'so_dt' => $modelRegFiber->so_dt,
                            'quan_id' => $fiber_district_id,
                            'phuong_id' => $modelRegFiber->phuong_id,
                            'pho_id' => $modelRegFiber->pho_id,
                            'ap_id' => $modelRegFiber->ap_id,
                            'khu_id' => $modelRegFiber->khu_id,
                            'ten_yc' => $modelRegFiber->ten_kh,
                            'so_dt_yc' => $modelRegFiber->so_dt,
                            'ma_nd' => 'freedoo',
                            'loai' => 1,
                            'dichvu_id' => 4,
                            'loaitb_id' => 61,
                            'ghichu' => $modelPackage->name,
                        );

                        $orderdata = new OrdersData();
                        //Call API đối tác
                        $data_output = $orderdata->receive($data_input_fiber);
                        $orderdetail = $modelOrder->id;
                        Yii::app()->session['success_register_fiber'] = $orderdetail;
                        $orderdetailfromapi = $data_output['Data'];

                        if ($data_output['errorCode'] <> 0) {
                            $mes = 'Không thành công! Đã xảy ra lỗi, xin vui lòng thử lại';
                            //                        $this->render('register-fiber-vnn', array(
                            //                            'modelPackage' => $modelPackage,
                            //                            'modelRegFiber' => $modelRegFiber,
                            //                            'district' => $district,
                            //                            'data_list_type' => $data_list_type,
                            //                            'list_province' => $list_province,
                            //                            'mes' => $mes
                            //                        ));

                        } elseif ($data_output['errorCode'] == 0) {
                            $modelRegFiber->fiber_order_id = $orderdetailfromapi['ma_gd'];
                            $modelRegFiber->hdkh_id = $orderdetailfromapi['hdkh_id'];
                            $modelRegFiber->freedoo_order_id = $modelOrder->id;
                            $modelRegFiber->dichvu_id = $modelPackage->code_vnpt;
                            $modelRegFiber->stb_use = $stbox;
                            //data gửi vào api freedoo
                            $data_input_fiber_freedoo = array(
                                'orders' => array(
                                    'full_name' => $modelRegFiber->ten_kh,
                                    'address_detail' => $address_detail,
                                    'email' => '',
                                    'otp' => '',
                                    'id' => $modelOrder->id,
                                    'delivery_type' => 1,
                                    'phone_contact' => $modelRegFiber->so_dt,
                                    'province_code' => $province_code,
                                    'district_code' => $quan_id,
                                    'ward_code' => null,
                                    'customer_note' => $modelRegFiber->mota,
                                    'promo_code' => $modelRegFiber->promo_code,
                                    'sso_id' => null,
                                    'invitation' => null,
                                    'create_date' => null,
                                    'last_update' => null,
                                    'shipper_id' => null,
                                    'delivery_date' => null,
                                    'payment_method' => null,
                                    'affiliate_transaction_id' => $modelOrder->affiliate_transaction_id,
                                    'affiliate_source' => $modelOrder->affiliate_source,
                                    'sale_office_code' => null,
                                    'receive_cash_by' => null,
                                    'receive_cash_date' => null,
                                    'campaign_source' => $modelOrder->campaign_source,
                                    'campaign_id' => $modelOrder->campaign_id,
                                    'pre_order_date' => null,
                                    'agency_contract_id' => null,
                                    'status' => null,
                                    'product_type' => 'mytv',
                                ),
                                'order_details' => array(
                                    'packages' => array(
                                        'quantity' => 1,
                                        'status' => 1,
                                        'order_id' => $modelOrder->id,
                                        'item_id' => $modelPackage->code,
                                        'item_name' => $modelPackage->name,
                                        'price' => $price_sale,
                                        'type' => 'package_mytv',
                                        'transaction_id' => null,
                                        'stbox' => $stbox,
                                    ),
                                ),
                                'order_state' => array(
                                    'confirm' => 10,
                                    'paid' => 0,
                                    'delivered' => '',
                                    'order_id' => $modelOrder->id,
                                    'id' => null,
                                    'create_date' => null,
                                    'note' => null,
                                ),
                            );
                            //Call API freedoo

                            $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);

                            $modelRegFiber->save();
                            $this->redirect($this->createUrl('site/success', array('t' => 2)));
                        }
                    }
                }
            }
        }
        $this->render('register-mytv', array(
            'modelPackage' => $modelPackage,
            'modelRegFiber' => $modelRegFiber,
            'list_province' => $list_province,
            'mes' => $mes
        ));
    }

    /*
     * Đăng ký fiber toàn quốc của freedoo
     */
    public function actionRegistercomboallprovince($package)
    {
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        $packagePrice = empty($modelPackage->price_discount) ? $modelPackage->price : $modelPackage->price_discount;
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        if ($modelPackage) {
            $modelRegFiber = new WRegFiber();
            $province = new WProvince();
            $list_province = $province->getListProvince();

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_combo_all_province') {
                echo CActiveForm::validate($modelRegFiber);
                Yii::app()->end();
            }
            if ($_POST['WRegFiber']) {

                $blackListPhone = WBlackListPhone::model()->findAll();
                $arrPhone = CHtml::listData($blackListPhone, 'id', 'phone');
                if (!in_array($modelRegFiber->so_dt, $arrPhone) || !in_array($modelRegFiber->so_dt_yc, $arrPhone)) {
                    $modelRegFiber->attributes = $_POST['WRegFiber'];
                    //check cookie campaign
                    if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }
                    if (empty($modelRegFiber->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                            $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                            $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                        }
                    }
                    if ($_POST['WRegFiber']['tinh_id']) {
                        $province_code = $_POST['WRegFiber']['tinh_id'];
                        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
                        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
                        $modelRegFiber->tinh_id = $fiber_province_id;
                    }
                    $modelRegFiber->freedoo_order_id = $modelOrder->id;
                    $modelRegFiber->dichvu_id = $modelPackage->code_vnpt;
                    if ($modelRegFiber->ten_yc == '' || !isset($modelRegFiber->ten_yc)) {
                        $modelRegFiber->ten_yc = $modelRegFiber->ten_kh;
                    }
                    if ($modelRegFiber->so_dt_yc == '' || !isset($modelRegFiber->so_dt_yc)) {
                        $modelRegFiber->so_dt_yc = $modelRegFiber->so_dt;
                    }
                    // CVarDumper::dump($_POST['WRegFiber'], 10, true);
                    // die;
                    if ($modelRegFiber->validate()) {
                        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($modelRegFiber->quan_id);
                        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
                        $wardsfiber = Yii::app()->session['wards_session_fiber_final'];
                        for ($i = 0; $i < count($wardsfiber); $i++) {
                            if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                                $datawardsfiber = $wardsfiber[$i];
                            }
                        }
                        $streetfiber = Yii::app()->session['street_session_fiber_final'];
                        for ($i = 0; $i < count($streetfiber); $i++) {
                            if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                                $datastreetfiber = $streetfiber[$i];
                            }
                        }
                        $apfiber = Yii::app()->session['ap_session_fiber_final'];
                        for ($i = 0; $i < count($apfiber); $i++) {
                            if ($apfiber[$i]['pho_id'] == $modelRegFiber->ap_id) {
                                $dataapfiber = $apfiber[$i];
                            }
                        }
                        $khufiber = Yii::app()->session['khu_session_fiber_final'];
                        for ($i = 0; $i < count($khufiber); $i++) {
                            if ($khufiber[$i]['pho_id'] == $modelRegFiber->khu_id) {
                                $datakhufiber = $khufiber[$i];
                            }
                        }
                        if ($datastreetfiber['ten_pho'] != '' || $datawardsfiber['ten_phuong'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'];
                        }
                        if ($dataapfiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $dataapfiber['ten_pho'];
                        }
                        if ($datakhufiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datakhufiber['ten_pho'];
                        }

                        $data_input_fiber = array(
                            'tinh_id' => $fiber_province_id,
                            'ngay_yc' => date('d/m/Y'),
                            'ten_kh' => $modelRegFiber->ten_kh,
                            'diachi' => $address_detail,
                            'so_dt' => $modelRegFiber->so_dt,
                            'quan_id' => $fiber_district_id,
                            'phuong_id' => $modelRegFiber->phuong_id,
                            'pho_id' => $modelRegFiber->pho_id,
                            'ap_id' => $modelRegFiber->ap_id,
                            'khu_id' => $modelRegFiber->khu_id,
                            'ten_yc' => $modelRegFiber->ten_yc,
                            'so_dt_yc' => $modelRegFiber->so_dt_yc,
                            'ma_nd' => 'freedoo',
                            'loai' => 1,
                            'dichvu_id' => 4,
                            'loaitb_id' => 58,
                            'ghichu' => $modelPackage->name,
                            'mota_hs' => 'Khách hàng có nhu cầu lắp đặt thêm MyTV'
                        );

                        $orderdata = new OrdersData();
                        //Call API đối tác
                        $data_output = $orderdata->receive($data_input_fiber);
                        $orderdetail = $modelOrder->id;
                        Yii::app()->session['success_register_fiber'] = $orderdetail;
                        $orderdetailfromapi = $data_output['Data'];
                        if ($data_output['errorCode'] <> 0) {
                            $mes = $data_output['Message'];
                            //                        $this->render('register-fiber-vnn', array(
                            //                            'modelPackage' => $modelPackage,
                            //                            'modelRegFiber' => $modelRegFiber,
                            //                            'list_province' => $list_province,
                            //                            'mes' => $mes
                            //                        ));
                        } elseif ($data_output['errorCode'] == 0) {
                            $modelRegFiber->fiber_order_id = $orderdetailfromapi['ma_gd'];
                            $modelRegFiber->hdkh_id = $orderdetailfromapi['hdkh_id'];

                            //data gửi vào api freedoo
                            $data_input_fiber_freedoo = array(
                                'orders' => array(
                                    'full_name' => $modelRegFiber->ten_kh,
                                    'address_detail' => $address_detail,
                                    'email' => '',
                                    'otp' => '',
                                    'id' => $modelOrder->id,
                                    'delivery_type' => 1,
                                    'phone_contact' => $modelRegFiber->so_dt,
                                    'province_code' => $province_code,
                                    'district_code' => $modelRegFiber->quan_id,
                                    'ward_code' => null,
                                    'customer_note' => $modelRegFiber->mota,
                                    'promo_code' => $modelRegFiber->promo_code,
                                    'sso_id' => null,
                                    'invitation' => null,
                                    'create_date' => null,
                                    'last_update' => null,
                                    'shipper_id' => null,
                                    'delivery_date' => null,
                                    'payment_method' => null,
                                    'affiliate_transaction_id' => $modelOrder->affiliate_transaction_id,
                                    'affiliate_source' => $modelOrder->affiliate_source,
                                    'sale_office_code' => null,
                                    'receive_cash_by' => null,
                                    'receive_cash_date' => null,
                                    'campaign_source' => $modelOrder->campaign_source,
                                    'campaign_id' => $modelOrder->campaign_id,
                                    'pre_order_date' => null,
                                    'agency_contract_id' => null,
                                    'status' => null,
                                    'product_type' => 'combo_fiber_mytv',
                                ),
                                'order_details' => array(
                                    'packages' => array(
                                        'quantity' => 1,
                                        'status' => 1,
                                        'order_id' => $modelOrder->id,
                                        'item_id' => $modelPackage->code,
                                        'item_name' => $modelPackage->name,
                                        'price' => $packagePrice,
                                        'type' => 'package_combo',
                                        'transaction_id' => null,
                                    ),
                                ),
                                'order_state' => array(
                                    'confirm' => 10,
                                    'paid' => 0,
                                    'delivered' => '',
                                    'order_id' => $modelOrder->id,
                                    'id' => null,
                                    'create_date' => null,
                                    'note' => null,
                                ),
                            );
                            if (isset($_POST['WRegFiber']['restricted_apartment_id']) && $_POST['WRegFiber']['restricted_apartment_id'] < 1) { // không nằm trong danh sách hạn chế
                                $data_input_fiber_freedoo['order']['payment_method'] = null;
                                $data_input_fiber_freedoo['order_state']['confirm'] = 0;
                                $data_input_fiber_freedoo['order_state']['paid'] = 0;
                            }
                            //Call API freedoo
                            $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);

                            $modelRegFiber->save();

                            if (isset($_POST['WRegFiber']['restricted_apartment_id']) && $_POST['WRegFiber']['restricted_apartment_id'] < 1) { // không nằm trong danh sách hạn chế

                                $location_vnptpay = WLocationVnptpay::model()->find('id=:id', array(':id' => $fiber_province_id));
                                // chuyen thanh toan VNPT Pay
                                $model_pm = new WPaymentMethod();
                                $ary_payment = $model_pm->paymentViaVnptPay($data_input_fiber_freedoo, $packagePrice, $location_vnptpay);
                                $this->redirect($ary_payment['urlRequest']);
                            } else {
                                $this->redirect($this->createUrl('site/success', array('t' => 3)));
                            }
                        }
                    }
                }
            }
        }
        $this->render('register-combo-all-province', array(
            'modelPackage' => $modelPackage,
            'modelRegFiber' => $modelRegFiber,
            'list_province' => $list_province,
            'mes' => $mes
        ));
    }

    /*
     * Đăng ký gói fiber final
     */
    public function actionRegistercombo($package)
    {
        $source_mytv = Yii::app()->session['source_mytv'];
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        if ($modelPackage) {
            $modelRegFiber = new WRegFiber();
            $province_code = Yii::app()->session['province_code_session_fiber_final'];
            if (isset($province_code)) {
                $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);

                $fiber_province_id = $province_fiber[0]['fiber_province_id'];

                $data_input = array(
                    'tinh_id' => $fiber_province_id,
                    'dichvu_id' => $modelPackage['code_vnpt'],
                );
                $orderdata = new OrdersData();
                $data_output_type = $orderdata->getlisttype($data_input);
                $data_list_type = $data_output_type['Data'];
            } else {
                $list_province = WProvince::getListProvince();
            }
            $district = WDistrict::getListDistrictByProvince($province_code);

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_combo') {
                echo CActiveForm::validate($modelRegFiber);
                Yii::app()->end();
            }
            if ($_POST['WRegFiber']) {
                $modelRegFiber->attributes = $_POST['WRegFiber'];
                //check cookie campaign
                if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                    $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                    $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                }
                if (empty($modelRegFiber->promo_code)) {
                    if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                        $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                        $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                    }
                }
                $modelRegFiber->tinh_id = $fiber_province_id;
                $modelRegFiber->freedoo_order_id = $modelOrder->id;
                $modelRegFiber->dichvu_id = $modelPackage->code_vnpt;
                if ($modelRegFiber->ten_yc == '' || !isset($modelRegFiber->ten_yc)) {
                    $modelRegFiber->ten_yc = $modelRegFiber->ten_kh;
                }
                if ($modelRegFiber->so_dt_yc == '' || !isset($modelRegFiber->so_dt_yc)) {
                    $modelRegFiber->so_dt_yc = $modelRegFiber->so_dt;
                }
                if ($modelRegFiber->validate()) {
                    $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($modelRegFiber->quan_id);
                    $fiber_district_id = $district_fiber[0]['fiber_district_id'];
                    $wardsfiber = Yii::app()->session['wards_session_fiber_final'];
                    for ($i = 0; $i < count($wardsfiber); $i++) {
                        if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                            $datawardsfiber = $wardsfiber[$i];
                        }
                    }
                    $streetfiber = Yii::app()->session['street_session_fiber_final'];
                    for ($i = 0; $i < count($streetfiber); $i++) {
                        if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                            $datastreetfiber = $streetfiber[$i];
                        }
                    }

                    $apfiber = Yii::app()->session['ap_session_fiber_final'];
                    for ($i = 0; $i < count($apfiber); $i++) {
                        if ($apfiber[$i]['pho_id'] == $modelRegFiber->ap_id) {
                            $dataapfiber = $apfiber[$i];
                        }
                    }
                    $khufiber = Yii::app()->session['khu_session_fiber_final'];
                    for ($i = 0; $i < count($khufiber); $i++) {
                        if ($khufiber[$i]['pho_id'] == $modelRegFiber->khu_id) {
                            $datakhufiber = $khufiber[$i];
                        }
                    }
                    if ($datastreetfiber['ten_pho'] != '' || $datawardsfiber['ten_phuong'] != '') {
                        $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'];
                    }
                    if ($dataapfiber['ten_pho'] != '') {
                        $address_detail = $modelRegFiber->so_nha . ' - ' . $dataapfiber['ten_pho'];
                    }
                    if ($datakhufiber['ten_pho'] != '') {
                        $address_detail = $modelRegFiber->so_nha . ' - ' . $datakhufiber['ten_pho'];
                    }

                    $data_input_fiber = array(
                        'tinh_id' => $fiber_province_id,
                        'ngay_yc' => date('d/m/Y'),
                        'ten_kh' => $modelRegFiber->ten_kh,
                        'diachi' => $address_detail,
                        'so_dt' => $modelRegFiber->so_dt,
                        'quan_id' => $fiber_district_id,
                        'phuong_id' => $modelRegFiber->phuong_id,
                        'pho_id' => $modelRegFiber->pho_id,
                        'ap_id' => $modelRegFiber->ap_id,
                        'khu_id' => $modelRegFiber->khu_id,
                        'ten_yc' => $modelRegFiber->ten_yc,
                        'so_dt_yc' => $modelRegFiber->so_dt_yc,
                        'ma_nd' => 'freedoo',
                        'loai' => 1,
                        'dichvu_id' => 4,
                        'loaitb_id' => 58,
                        'ghichu' => $modelPackage->name,
                        'mota_hs' => 'Khách hàng có nhu cầu lắp đặt thêm MyTV'
                    );

                    $orderdata = new OrdersData();
                    //Call API đối tác
                    $data_output = $orderdata->receive($data_input_fiber);
                    $orderdetail = $modelOrder->id;
                    Yii::app()->session['success_register_fiber'] = $orderdetail;
                    $orderdetailfromapi = $data_output['Data'];
                    if ($data_output['errorCode'] <> 0) {
                        $mes = 'Không thành công! Đã xảy ra lỗi, xin vui lòng thử lại';
                        //                        $this->render('register-fiber-vnn', array(
                        //                            'modelPackage' => $modelPackage,
                        //                            'modelRegFiber' => $modelRegFiber,
                        //                            'district' => $district,
                        //                            'data_list_type' => $data_list_type,
                        //                            'list_province' => $list_province,
                        //                            'mes' => $mes
                        //                        ));
                    } elseif ($data_output['errorCode'] == 0) {
                        $modelRegFiber->fiber_order_id = $orderdetailfromapi['ma_gd'];
                        $modelRegFiber->hdkh_id = $orderdetailfromapi['hdkh_id'];

                        //data gửi vào api freedoo
                        $data_input_fiber_freedoo = array(
                            'orders' => array(
                                'full_name' => $modelRegFiber->ten_kh,
                                'address_detail' => $address_detail,
                                'email' => '',
                                'otp' => '',
                                'id' => $modelOrder->id,
                                'delivery_type' => 1,
                                'phone_contact' => $modelRegFiber->so_dt,
                                'province_code' => $province_code,
                                'district_code' => $modelRegFiber->quan_id,
                                'ward_code' => null,
                                'customer_note' => $modelRegFiber->mota,
                                'promo_code' => $modelRegFiber->promo_code,
                                'sso_id' => null,
                                'invitation' => null,
                                'create_date' => null,
                                'last_update' => null,
                                'shipper_id' => null,
                                'delivery_date' => null,
                                'payment_method' => null,
                                'affiliate_transaction_id' => $modelOrder->affiliate_transaction_id,
                                'affiliate_source' => $modelOrder->affiliate_source,
                                'sale_office_code' => null,
                                'receive_cash_by' => null,
                                'receive_cash_date' => null,
                                'campaign_source' => $modelOrder->campaign_source,
                                'campaign_id' => $modelOrder->campaign_id,
                                'pre_order_date' => null,
                                'agency_contract_id' => null,
                                'status' => null,
                                'product_type' => 'combo_fiber_mytv',
                            ),
                            'order_details' => array(
                                'packages' => array(
                                    'quantity' => 1,
                                    'status' => 1,
                                    'order_id' => $modelOrder->id,
                                    'item_id' => $modelPackage->code,
                                    'item_name' => $modelPackage->name,
                                    'price' => $modelPackage->price,
                                    'type' => 'package_combo',
                                    'transaction_id' => null,
                                ),
                            ),
                            'order_state' => array(
                                'confirm' => 10,
                                'paid' => 0,
                                'delivered' => '',
                                'order_id' => $modelOrder->id,
                                'id' => null,
                                'create_date' => null,
                                'note' => null,
                            ),
                        );
                        //Call API freedoo
                        $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);
                        $modelRegFiber->save();
                        $this->redirect($this->createUrl('site/success', array('t' => 3)));
                    }
                }
            }
        }
        $this->render('register-combo', array(
            'modelPackage' => $modelPackage,
            'modelRegFiber' => $modelRegFiber,
            'district' => $district,
            'data_output_fiber_freedoo' => $data_output_fiber_freedoo,
            'list_province' => $list_province,
            'mes' => $mes

        ));
    }


    /*
    * Đăng ký gói fiber final
    */
    public function actionRegisterHomeBundle($package)
    {
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package));
        $modelOrder = new WOrders();
        $modelOrder->id = $modelOrder->generateOrderId();
        if ($modelPackage) {
            $modelRegFiber = new WRegFiber();
            $province = new WProvince();
            $list_province = $province->getListProvince();

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_register_combo_all_province') {
                echo CActiveForm::validate($modelRegFiber);
                Yii::app()->end();
            }
            if ($_POST['WRegFiber']) {
                $blackListPhone = WBlackListPhone::model()->findAll();
                $arrPhone = CHtml::listData($blackListPhone, 'id', 'phone');
                if (!in_array($modelRegFiber->so_dt, $arrPhone) || !in_array($modelRegFiber->so_dt_yc, $arrPhone)) {
                    $modelRegFiber->attributes = $_POST['WRegFiber'];
                    //check cookie campaign
                    if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }
                    if (empty($modelRegFiber->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                            $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                            $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                        }
                    }
                    if ($_POST['WRegFiber']['tinh_id']) {
                        $province_code = $_POST['WRegFiber']['tinh_id'];
                        $province_fiber = WProvince::getFiberProvinceIdByProvinceCode($province_code);
                        $fiber_province_id = $province_fiber[0]['fiber_province_id'];
                        $modelRegFiber->tinh_id = $fiber_province_id;
                    }
                    $modelRegFiber->freedoo_order_id = $modelOrder->id;
                    $modelRegFiber->dichvu_id = $modelPackage->code_vnpt;
                    if ($modelRegFiber->ten_yc == '' || !isset($modelRegFiber->ten_yc)) {
                        $modelRegFiber->ten_yc = $modelRegFiber->ten_kh;
                    }
                    if ($modelRegFiber->so_dt_yc == '' || !isset($modelRegFiber->so_dt_yc)) {
                        $modelRegFiber->so_dt_yc = $modelRegFiber->so_dt;
                    }
                    if ($modelRegFiber->validate()) {
                        $district_fiber = WDistrict::getFiberDistrictIdByDistrictCode($modelRegFiber->quan_id);
                        $fiber_district_id = $district_fiber[0]['fiber_district_id'];
                        $wardsfiber = Yii::app()->session['wards_session_fiber_final'];
                        for ($i = 0; $i < count($wardsfiber); $i++) {
                            if ($wardsfiber[$i]['phuong_id'] == $modelRegFiber->phuong_id) {
                                $datawardsfiber = $wardsfiber[$i];
                            }
                        }
                        $streetfiber = Yii::app()->session['street_session_fiber_final'];
                        for ($i = 0; $i < count($streetfiber); $i++) {
                            if ($streetfiber[$i]['pho_id'] == $modelRegFiber->pho_id) {
                                $datastreetfiber = $streetfiber[$i];
                            }
                        }
                        $apfiber = Yii::app()->session['ap_session_fiber_final'];
                        for ($i = 0; $i < count($apfiber); $i++) {
                            if ($apfiber[$i]['pho_id'] == $modelRegFiber->ap_id) {
                                $dataapfiber = $apfiber[$i];
                            }
                        }
                        $khufiber = Yii::app()->session['khu_session_fiber_final'];
                        for ($i = 0; $i < count($khufiber); $i++) {
                            if ($khufiber[$i]['pho_id'] == $modelRegFiber->khu_id) {
                                $datakhufiber = $khufiber[$i];
                            }
                        }
                        if ($datastreetfiber['ten_pho'] != '' || $datawardsfiber['ten_phuong'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datastreetfiber['ten_pho'] . ' - ' . $datawardsfiber['ten_phuong'];
                        }
                        if ($dataapfiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $dataapfiber['ten_pho'];
                        }
                        if ($datakhufiber['ten_pho'] != '') {
                            $address_detail = $modelRegFiber->so_nha . ' - ' . $datakhufiber['ten_pho'];
                        }

                        $data_input_fiber = array(
                            'tinh_id' => $fiber_province_id,
                            'ngay_yc' => date('d/m/Y'),
                            'ten_kh' => $modelRegFiber->ten_kh,
                            'diachi' => $address_detail,
                            'so_dt' => $modelRegFiber->so_dt,
                            'quan_id' => $fiber_district_id,
                            'phuong_id' => $modelRegFiber->phuong_id,
                            'pho_id' => $modelRegFiber->pho_id,
                            'ap_id' => $modelRegFiber->ap_id,
                            'khu_id' => $modelRegFiber->khu_id,
                            'ten_yc' => $modelRegFiber->ten_yc,
                            'so_dt_yc' => $modelRegFiber->so_dt_yc,
                            'ma_nd' => 'freedoo',
                            'loai' => 1,
                            'dichvu_id' => 4,
                            'loaitb_id' => 58,
                            'ghichu' => $modelPackage->code_vnpt,
                            'mota_hs' => ''
                        );

                        $orderdata = new OrdersData();
                        //Call API đối tác
                        $data_output = $orderdata->receive($data_input_fiber);
                        $orderdetail = $modelOrder->id;
                        Yii::app()->session['success_register_fiber'] = $orderdetail;
                        $orderdetailfromapi = $data_output['Data'];
                        if ($data_output['errorCode'] <> 0) {
                            $mes = $data_output['Message'];
                            //                        $this->render('register-fiber-vnn', array(
                            //                            'modelPackage' => $modelPackage,
                            //                            'modelRegFiber' => $modelRegFiber,
                            //                            'list_province' => $list_province,
                            //                            'mes' => $mes
                            //                        ));
                        } elseif ($data_output['errorCode'] == 0) {
                            $modelRegFiber->fiber_order_id = $orderdetailfromapi['ma_gd'];
                            $modelRegFiber->hdkh_id = $orderdetailfromapi['hdkh_id'];

                            //data gửi vào api freedoo
                            $data_input_fiber_freedoo = array(
                                'orders' => array(
                                    'full_name' => $modelRegFiber->ten_kh,
                                    'address_detail' => $address_detail,
                                    'email' => '',
                                    'otp' => '',
                                    'id' => $modelOrder->id,
                                    'delivery_type' => 1,
                                    'phone_contact' => $modelRegFiber->so_dt,
                                    'province_code' => $province_code,
                                    'district_code' => $modelRegFiber->quan_id,
                                    'ward_code' => null,
                                    'customer_note' => $modelRegFiber->mota,
                                    'promo_code' => $modelRegFiber->promo_code,
                                    'sso_id' => null,
                                    'invitation' => null,
                                    'create_date' => null,
                                    'last_update' => null,
                                    'shipper_id' => null,
                                    'delivery_date' => null,
                                    'payment_method' => null,
                                    'affiliate_transaction_id' => $modelOrder->affiliate_transaction_id,
                                    'affiliate_source' => $modelOrder->affiliate_source,
                                    'sale_office_code' => null,
                                    'receive_cash_by' => null,
                                    'receive_cash_date' => null,
                                    'campaign_source' => $modelOrder->campaign_source,
                                    'campaign_id' => $modelOrder->campaign_id,
                                    'pre_order_date' => null,
                                    'agency_contract_id' => null,
                                    'status' => null,
                                    'product_type' => 'home_bundle',
                                ),
                                'order_details' => array(
                                    'packages' => array(
                                        'quantity' => 1,
                                        'status' => 1,
                                        'order_id' => $modelOrder->id,
                                        'item_id' => $modelPackage->code,
                                        'item_name' => $modelPackage->name,
                                        'price' => $modelPackage->price,
                                        'type' => 'package_home_bundle',
                                        'transaction_id' => null,
                                    ),
                                ),
                                'order_state' => array(
                                    'confirm' => 10,
                                    'paid' => 0,
                                    'delivered' => '',
                                    'order_id' => $modelOrder->id,
                                    'id' => null,
                                    'create_date' => null,
                                    'note' => null,
                                ),
                            );
                            //Call API freedoo
                            $data_output_fiber_freedoo = $orderdata->checkoutfiber($data_input_fiber_freedoo);
                            $modelRegFiber->save();
                            $this->redirect($this->createUrl('site/success', array('t' => 4)));
                        }
                    }
                }
            }
        }
        $this->render('register-home-bundle', array(
            'modelPackage' => $modelPackage,
            'modelRegFiber' => $modelRegFiber,
            'district' => $district,
            'data_output_fiber_freedoo' => $data_output_fiber_freedoo,
            'list_province' => $list_province,
            'mes' => $mes

        ));
    }

    public function actionKitTraSua()
    {
        $list_package_tra_sua = WPackage::getListTrasua();

        //       CVarDumper::dump($list_package_tra_sua,10, true);
        $this->render('index_kit_tra_sua', array(

            'list_package_tra_sua' => $list_package_tra_sua,

        ));
    }
    public function actionKitBanhMy()
    {
        $list_package_banh_my = WPackage::getListBanhMy();

        //       CVarDumper::dump($list_package_tra_sua,10, true);
        $this->render('index_kit_banh_my', array(

            'list_package_banh_my' => $list_package_banh_my,

        ));
    }

    /*
    * Lấy ra danh sách quận huyện
    */
    public function actionGetRestricApartment()
    {
        $district_id = Yii::app()->request->getParam('district_id');
        $restrictedApartment = WBrcdRestrictedLocation::getList($district_id);
        // var_dump($restrictedApartment);
        // die;
        $str = '<option value="">--- Chọn chung cư nơi bạn đang ở ---</option>';
        foreach ($restrictedApartment as $key => $value) {
            $str = $str . "<option value='$key'>$value</option>";
        }
        $str .= '<option value="-1">--- Không có trong danh sách ---</option>';

        echo CJSON::encode(
            array('content' => $str)
        );
    }
} //end class