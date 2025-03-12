<?php

class SiteController extends Controller
{
    private $isMobile = FALSE;
    public $item_per_page = 10;
    public $layout = '/layouts/main';

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
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        if (isset($error['code']) && $error['code'] >= 400) {
            $error['message'] = 'Không tìm thấy trang bạn yêu cầu.';
        }
        if (Yii::app()->request->isAjaxRequest) {
            echo $error['message'];
        } else {
            $this->render('error', $error);
        }
    }

    /**
     * Default action
     */
    public function actionIndex()
    {
        $this->pageTitle = 'TELMALL';
		
        $packages = WBanners::getListBannerByType(WBanners::TYPE_PACKAGE);


        $html_cache = $this->render('index', array('packages' => $packages), TRUE);
        echo $html_cache;
    } //end index

    /**
     * Đăng xuất.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect($this->createUrl('site/index'));
    }

    public function actionProfile()
    {
        $this->pageTitle = 'VNPT SHOP - Thông tin cá nhân';
        if (!Yii::app()->user->isGuest) {
            $customer = WCustomers::model()->findByAttributes(array('id' => Yii::app()->user->customer_id));
            if ($customer) {
                $data_post = array(
                    'user_id' => $customer->sso_id,
                );
                $building_query = http_build_query($data_post);
                $encrypted = Utils::encrypt($building_query, Yii::app()->params['aes_key'], $this->algorithm);
                if (Yii::app()->request->hostInfo == 'http://222.252.19.197:8694') {
                    $sso_change_pass_url = 'http://222.252.19.197:8694/changepass/' . $GLOBALS['config_common']['domain_sso']['pid'] . '?data=' . $encrypted;
                } else {
                    $sso_change_pass_url = $GLOBALS['config_common']['domain_sso']['sso'] . 'changepass/' . $GLOBALS['config_common']['domain_sso']['pid'] . '?data=' . $encrypted;
                }
                if (isset($_POST['WCustomers'])) {
                    $customer->attributes = $_POST['WCustomers'];
                    $customer->birthday = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $customer->birthday)));
                    $customer->personal_id_create_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $customer->personal_id_create_date)));

                    $customer->update();
                }
                if ($customer->birthday) { //display view
                    $customer->birthday = date('d/m/Y', strtotime($customer->birthday));
                }
                if ($customer->personal_id_create_date) { //display view
                    $customer->personal_id_create_date = date('d/m/Y', strtotime($customer->personal_id_create_date));
                }
                $this->render(
                    'profile',
                    array(
                        'id' => $customer->id,
                        'url_change_pass' => Yii::app()->params['url_oneid_changepass'],
                        'model' => $customer,
                        'sso_change_pass_url' => $sso_change_pass_url,
                    )
                );
            }
        } else {
            $this->redirect($this->createUrl('site/index'));
        }
    }

    /**
     * action get list district by province
     */
    public function actionGetDistrictByProvince()
    {
        $province_code = Yii::app()->request->getParam('province_code', '');
        $district = WDistrict::getListDistrictByProvince($province_code);
        echo "<option value=''>" . Yii::t('web/portal', 'select_district') . "</option>";
        foreach ($district as $key => $value) {
            echo CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
        }
        Yii::app()->end();
    }

    /**
     * Api lấy điểm cộng đồng
     */
    public function actionGetPoint()
    {
        $result = 'Chưa có điểm';

        $usr = Yii::app()->getRequest()->getParam('usr', FALSE);

        if ($usr) {
            $data = Yii::app()->db_wp->createCommand("select total_user_point, user_title from wp_users where user_nicename=:user_nicename")->bindParam(':user_nicename', $usr)->queryRow();
            if ($data) {
                $result = self::getLevel(intval($data['total_user_point'])) . ". Điểm: " . $data['total_user_point'];
            }
        } else {
            echo '0';
        }
        echo $result;
    }

    /**
     * @param $point
     * Lấy thứ hạng theo điểm.
     *
     * @return string
     */
    public function getLevel($point)
    {
        $result = "";
        if ($point <= 10) {
            $result = 'Thành viên mới';
        } else if ($point > 10 && $point <= 100) {
            $result = 'Thành viên xây dựng';
        } else if ($point > 100 && $point <= 500) {
            $result = 'Thành viên ưu tú';
        } else if ($point > 500) {
            $result = 'Thành viên cao cấp';
        } else {
            $result = "Thành viên mới";
        }

        return $result;
    }

    public function actionClear()
    {
        Yii::app()->cache->flush();
    }

    public function actionApp()
    {
        $qrContent = '0002010102122614908405VNPAY0015204411253037045405100005802VN5904VBAN6006LONGAN610610000062680307Vban001051801170905144419627707082QXUI4J40819Thanh%20toan%20don%20hang6304AF49?callbackurl=http%3a%2f%2fsandbox.vnpayment.vn%2fpaymentv2%2fqrback.html%3ftoken%3df19ca4e7e64544a58667b3e90eeb00510002010102122614908405VNPAY0015204411253037045405100005802VN5904VBAN6006LONGAN610610000062680307Vban001051801170905144419627707082QXUI4J40819Thanh%20toan%20don%20hang6304AF49';
        $appId = 'vietinbankmobile';
        $packId = 'com.vietinbank.ipay';
        echo CHtml::link('android', "intent://view?data=$qrContent/#Intent;scheme=$appId;package=$packId;end") . "<br>";
        //            "intent://view?data=" . $qrContent . "/#Intent;scheme=" . $appId . ";package=" . $packId . ";end";
        echo CHtml::link('ios', "$appId://$qrContent");
        //            appId + "://" + qrContent;
    }

    public function actionAbout()
    {
        $this->pageTitle = 'VNPT SHOP - Giới thiệu về VNPT SHOP';
        $this->render('about');
    }

    public function actionSupportChannel()
    {
        $this->pageTitle = 'VNPT SHOP - Các kênh hỗ trợ khách hàng';
        $this->render('support_channel');
    }

    public function actionTermCondition()
    {
        $this->pageTitle = 'VNPT SHOP - Điều khoản và điều kiện giao dịch';
        $this->render('term_condition');
    }

    public function actionDeliveryPolicy()
    {
        $this->pageTitle = 'VNPT SHOP - Chính sách giao nhận';
        $this->render('delivery_policy');
    }

    public function actionRegulationsPayment()
    {
        $this->pageTitle = 'VNPT SHOP - Quy định về hình thức thanh toán';
        $this->render('regulations_payment');
    }

    public function actionReturnPolicy()
    {
        $this->pageTitle = 'VNPT SHOP - Chính sách đổi trả hàng và hoàn tiền';
        $this->render('return_policy');
    }

    public function actionPaymentSecurity()
    {
        $this->pageTitle = 'VNPT SHOP - Chính sách bảo mật thanh toán';
        $this->render('payment_security');
    }

    public function actionProfileSecurity()
    {
        $this->pageTitle = 'VNPT SHOP - Chính sách bảo mật thông tin cá nhân';
        $this->render('profile_security');
    }

    public function actionHelpapp()
    {
        $this->pageTitle = 'VNPT SHOP - Trợ giúp';
        $this->layout = '/layouts/app_main';
        $this->render('help_app');
    }

    public function actionpaymentPolicy()
    {
        $this->pageTitle = 'VNPT SHOP - Chính sách thanh toán';
        $this->render('payment_policy');
    }
    public function actionchangeProduct()
    {
        $this->pageTitle = 'VNPT SHOP - Chính sách đổi trả sản phẩm';
        $this->render('change_product');
    }

    /**
     * @param $id
     *
     * @throws CHttpException
     */
    public function actionNews($id)
    {
        $news = WNews::model()->find('id=:id', array(':id' => $id));
        if ($news) {
            $this->pageTitle = 'VNPT SHOP - ' . $news->title;
            $this->pageDescription = $news->title;

            $this->render('news', array(
                'news' => $news,
            ));
        } else {
            throw new CHttpException(404, 'Không tồn tại bài viết.');
        }
    }

    public function actionChangeMsisdnPrefix()
    {
        $result = array(
            'msisdn_prefix_old' => '',
            'msisdn_prefix_new' => '',
            'change' => false,
        );

        if (isset($_POST['msisdn'])) {
            $msisdn = $_POST['msisdn'];
            if (is_numeric($msisdn)) {
                $msisdn_old = CFunction::makePhoneNumberStandard($msisdn);
                $msisdn_new = CFunction::convertNewMsisdn($msisdn, true, false);
                if ($msisdn_new != $msisdn_old) {
                    $result['change'] = true;
                    if (substr($msisdn, 0, 1) == '0') {
                        $msisdn_new = CFunction::makePhoneNumberBasic($msisdn_new);
                    }
                }
                $result['msisdn_prefix_old'] = $msisdn;
                $result['msisdn_prefix_new'] = $msisdn_new;
            }
        }

        echo CJSON::encode($result);
        Yii::app()->end();
    }

    /*
         * Tạo site map
         */

    public function actionSiteMap()
    {
        $this->pageTitle = 'Freedoo Site Map';
        $this->render('sitemap');
    }

    public function actionSuccess()
    {
        $this->pageTitle = 'Thành công';
        $this->render('success');
    }
}
