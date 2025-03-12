<?php

class PrepaidtopostpaidController extends Controller
{
    public $layout = '/layouts/main';

    public $isMobile = FALSE;

    public $defaultAction = 'index';

    public function init()
    {
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/mobile_main';
        }
    }

    public function actionIndex()
    {
        $model          = new WPrepaidToPostpaid();
        $province       = WProvince::getListProvince(true);
        $district       = array();
        $ward           = array();

//        WPrepaidToPostpaid::unsetSession();
        $this->checkAccessOTP();

        if(isset(Yii::app()->session['ptp'])) {
            $model = Yii::app()->session['ptp'];
        }
        $model->scenario = 'create';

        //validate ajax
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prepaidtopostpaid-form') {
            $model->attributes = $_POST['WPrepaidToPostpaid'];
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['WPrepaidToPostpaid'])) {
            $model->attributes = $_POST['WPrepaidToPostpaid'];
            if($model->validate()){
                $model->status = WPrepaidToPostpaid::PTP_APPROVE;
                Yii::app()->session['ptp'] = $model;
                $this->redirect(Yii::app()->createUrl('prepaidtopostpaid/choosePackage'));
            }
        }

        if($model->province_code){
            $district = WDistrict::getListDistrictByProvince($model->province_code);
        }
        if($model->district_code){
            $ward = WWard::getListWardDistrict($model->district_code);
        }

        $this->render('index', array(
            'model'         => $model,
            'province'      => $province,
            'district'      => $district,
            'ward'          => $ward,
        ));
    }

    public function actionChoosePackage()
    {
        if(!isset(Yii::app()->session['ptp'])){
            throw new CHttpException(404, Yii::t('web/portal', 'page_not_found'));
        }
        $model              = Yii::app()->session['ptp'];
        $model->scenario    = 'choose_package';

        if(WPackage::checkSimFreedoo($model->msisdn)){
            $list_package   = WPackage::getListPackagePtp();
        }else{
            $list_package   = WPackage::getListPackagePtp(WPackage::OTHER_PACKAGE);
        }

        if (isset($_POST['WPrepaidToPostpaid'])) {
            $model->package_code = $_POST['WPrepaidToPostpaid']['package_code'];

            $package = WPackage::model()->findByAttributes(array('code' => $model->package_code));
            if($package && $package->type != WPackage::PACKAGE_POSTPAID){
                $model->addError('package_code', 'Yêu cầu chọn gói trả sau');
            }

            if($model->validate(false)){
                $otp_form  = new OtpForm();
                $token_key = $otp_form->getTokenKey($model->msisdn, 4);
                if($token_key){
                    // Kiểm tra session Lưu OTP
                    if(!isset(Yii::app()->session['token_key_ptp'])){
                        Yii::app()->session['send_token_number_ptp'] = 1;  //số lần gửi mã
                    }else{
                        Yii::app()->session['send_token_number_ptp']+= 1;
                    }

                    $this->checkAccessOTP();

                    if(Yii::app()->session['send_token_number_ptp'] <= 3){
                        Yii::app()->session['token_key_ptp']        = $token_key;   //mã OTP
                        Yii::app()->session['verify_number_ptp']    = 1;            //số lần xác thực mã
                        Yii::app()->session['send_token_time_ptp']  = time();       //thời gian gửi
                        $model->otp = $token_key;
                        if (!YII_DEBUG) {
                            $mt_content = Yii::t('web/mt_content', 'otp_register_package_ptp', array(
                                '{token_key}'    => $token_key,
                                '{msisdn}'       => $model->msisdn,
                                '{package_name}' => $package->name,
                            ));
                            if($otp_form->sentMtVNP($model->msisdn, $mt_content, 'prepaidtopostpaid')){
                                Yii::app()->session['ptp'] = $model;
                                $this->redirect($this->createUrl('prepaidtopostpaid/verifyTokenKey'));
                            }else{
                                $msg = Yii::t('web/portal', 'send_mt_fail');
                                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                                $this->redirect($this->createUrl('prepaidtopostpaid/message', array('t' => 9, 'msg' => $msg)));
                            }
                        }else{
                            Yii::app()->session['ptp'] = $model;
                            $this->redirect($this->createUrl('prepaidtopostpaid/verifyTokenKey'));
                        }
                    }
                }
            }
        }

        $this->render('choose_package', array(
            'model'         => $model,
            'list_package'  => $list_package,
        ));
    }

    public function actionVerifyTokenKey()
    {
        if(!isset(Yii::app()->session['ptp'])){
            throw new CHttpException(404, Yii::t('web/portal', 'page_not_found'));
        }
        $model = Yii::app()->session['ptp'];

        if(WPackage::checkSimFreedoo($model->msisdn)){
            $list_package   = WPackage::getListPackagePtp();
        }else{
            $list_package   = WPackage::getListPackagePtp(WPackage::OTHER_PACKAGE);
        }

        $otpForm = new OtpForm();
        $otpForm->scenario = 'checkTokenKey';

        if(isset($_POST['OtpForm'])){
            $otpForm->attributes = $_POST['OtpForm'];

            if(Yii::app()->session['verify_number_ptp'] > 3){
                $msg = Yii::t('web/portal', 'verify_limited');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('prepaidtopostpaid/message', array('t' => 7, 'msg' => $msg)));
            }else{
                if($otpForm->validate()){
                    if($otpForm->token == $model->otp){
                        $model->create_date = date('Y-m-d H:i:s');
                        $model->id = WPrepaidToPostpaid::generatePtpId();

                        //call API ...
                        $orderData = new OrdersData();

                        $data = array(
                            'id'                => $model->id,
                            'msisdn'            => $model->msisdn,
                            'order_id'          => $model->order_id,
                            'package_code'      => $model->package_code,
                            'full_name'         => $model->full_name,
                            'personal_id'       => $model->personal_id,
                            'province_code'     => $model->province_code,
                            'district_code'     => $model->district_code,
                            'ward_code'         => $model->ward_code,
                            'address_detail'    => $model->address_detail,
                            'promo_code'        => $model->promo_code,
                            'otp'               => $model->otp,
                            'receive_date'      => $model->receive_date,
                            'finish_date'       => $model->finish_date,
                            'request_id'        => $model->request_id,
                            'create_date'       => $model->create_date,
                            'status'            => $model->status,
                            'user_id'           => $model->user_id,
                        );

                    $response_arr = $orderData->registerPrepaidToPostPaid($data);

                    $response_code = isset($response_arr['code']) ? $response_arr['code'] : '';
                    $response_msg  = isset($response_arr['msg']) ? $response_arr['msg'] : '';
                    $response_msg  = self::EncryptMsg($response_msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);

//                        $response_code = 1;
//                        $response_msg = 'Thành công';
//                        $response_msg = self::EncryptMsg($response_msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);

                        WPrepaidToPostpaid::unsetSession();

                        $this->redirect(Yii::app()->createUrl('prepaidtopostpaid/message', array('t' => $response_code, 'msg' => $response_msg, 'encrypt' => 1)));
                    }else{
                        $otpForm->addError('token', Yii::t('web/portal','verify_fail'));
                        Yii::app()->session['verify_number_ptp']+= 1;
                    }
                }
            }
        }

        $this->render('verify_otp', array(
            'model'         => $model,
            'otpForm'       => $otpForm,
            'list_package'  => $list_package,
        ));
    }

    public function actionMessage($t, $msg = '', $encrypt = false)
    {
        if(isset(Yii::app()->session['ptp'])){
            unset(Yii::app()->session['ptp']);
        }

        $msg     = self::DecryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
        $arr_msg = explode('*_', $msg);//function EncryptMsg()
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

        $model          = new WPrepaidToPostpaid();
        $province       = WProvince::getListProvince(true);
        $district       = array();
        $ward           = array();

        $this->render('complete', array(
            'model'         => $model,
            'province'      => $province,
            'district'      => $district,
            'ward'          => $ward,
            'response_code' => $t,
            'response_msg'  => $msg,
        ));
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
     * action get list ward by district
     */
    public function actionGetWardBrandOfficesByDistrict()
    {
        $district_code = Yii::app()->request->getParam('district_code', '');
        $ward = WWard::getListWardDistrict($district_code);
        $html_ward = "<option value=''>" . Yii::t('web/portal', 'select_ward') . "</option>";
        foreach ($ward as $key => $value) {
            $html_ward .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
        }

        echo CJSON::encode(array(
            'html_ward' => $html_ward,
        ));
        Yii::app()->end();
    }


    public static function DecryptMsg($msg, $key, $alg)
    {
        return Utils::decrypt($msg, md5($key), $alg);
    }

    public static function EncryptMsg($msg, $key, $alg)
    {
        return Utils::encrypt('freedoo*_' . $msg, md5($key), $alg);
    }

    protected function checkAccessOTP(){
        // Kiểm tra số lần gửi mã
        if(isset(Yii::app()->session['send_token_number_ptp']) && Yii::app()->session['send_token_number_ptp'] > 3){
            $time = date('Y-m-d H:i:s', Yii::app()->session['send_token_time_ptp']);
            $now = date('Y-m-d H:i:s');
            if($now < date('Y-m-d H:i:s', strtotime($time . '+ 30 minutes'))){
                $msg = Yii::t('web/portal', 'send_mt_limited');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('prepaidtopostpaid/message', array('t' => 8, 'msg' => $msg)));
            }else{
                Yii::app()->session['send_token_number_ptp'] = 1;
            }
        }
        if(isset(Yii::app()->session['verify_number_ptp']) && Yii::app()->session['verify_number_ptp'] > 3){
            $time = date('Y-m-d H:i:s', Yii::app()->session['send_token_time_ptp']);
            $now = date('Y-m-d H:i:s');
            if($now < date('Y-m-d H:i:s', strtotime($time . '+ 30 minutes'))){
                $msg = Yii::t('web/portal', 'send_mt_limited');
                $msg = self::EncryptMsg($msg, Yii::app()->params['msg_aes_key'], MCRYPT_RIJNDAEL_256);
                $this->redirect($this->createUrl('prepaidtopostpaid/message', array('t' => 8, 'msg' => $msg)));
            }else{
                Yii::app()->session['verify_number_ptp'] = 1;
            }
        }
    }

}