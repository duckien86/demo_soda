<?php

    class SiteController extends Controller
    {
        public $layout = '/layouts/main';

        private $isMobile         = FALSE;
        public  $algorithm        = MCRYPT_RIJNDAEL_128;
        public  $changestatus_key = "qanahanahannnbvgtyijhuij12345432";

        const AGENCY = 2;
        const CTV    = 1;


        public function init()
        {
            parent::init();

        }

        public function actions()
        {
            // return external action classes, e.g.:
//            return array(
//                // captcha action renders the CAPTCHA image displayed on the contact page
//                'captcha' => array(
//                    'class'        => 'CaptchaExtendedAction',
//                    'density'      => 0,
//                    'lines'        => 0,
//                    'fillSections' => 0,
//                    //'backColor'=>0xFFFFFF,
//                ),
//            );
        }

        /**
         * Default action
         */
        public function actionIndex()
        {

        }

        //end index
        public function actionLogin()
        {

            //B1: Khởi tạo và nhận dữ liệu.
//            Yii::app()->request->cookies->clear(); // Reset lại csrf token
            if (isset($_GET['t'])) {
                if ($_GET['t'] == 1) {
                    if (Yii::app()->user->isGuest) {
                        die("Chưa đăng nhập");
                    } else {
                        die("Đã đăng nhập");
                    }
                }
            }

            //Check session login spam
            if (isset(Yii::app()->session['last_active_login'])) {
                if ((time() - Yii::app()->session['last_active_login'] > 900)) {
                    unset(Yii::app()->session['last_active_login']);
                    unset(Yii::app()->session['login_pass']);
                } else {
                    throw new CHttpException(999, 'Bạn đã đăng nhập quá 5 lần! Hãy đợi 15 phút để được nhập lại!');

                }
            }
            $error = '';
            $msg   = '';
            $pid   = Yii::app()->getRequest()->getParam('pid', '');
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {
                $partner = WPartner::model()->checkExist($pid);
                if (!$partner) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                }
            }

            if (isset($_POST['WloginForm'])) {

                //Check session login spam
                Yii::app()->session['login_pass'] += 1;
                if (Yii::app()->session['login_pass'] > 5) {
                    Yii::app()->session['last_active_login'] = time();
                }

                $start_begin_time = date('h:i:s');
                //B1.1: Check điều kiện partner.
                if (!empty($pid)) {
//                    $partner = WPartner::model()->checkExist($pid);
                    if ($partner) {
                        //B2: Kiểm tra đăng nhập.
                        $model             = new WLoginForm();
                        $model->username   = $_POST['WloginForm']['username'];
                        $model->password   = $_POST['WloginForm']['password'];
                        $model->rememberMe = TRUE;

                        if ($model->validate()) {

                            $user_login = $model->login((isset($_GET['t'])) ? $_GET['t'] : '');
                            if ($user_login) {

                                if ($user_login->status == WUsers::ACTIVE) {
                                    //B3: Encrypt dữ liệu và url.
                                    $return_url = '';
                                    if (isset($_GET['return_url'])) {
                                        if (!empty($_GET['return_url'])) {
                                            $return_url = $_GET['return_url'];
                                        }
                                    }
                                    $data_post = array(
                                        'user_id'     => $user_login->id,
                                        'username'    => $user_login->username,
                                        'email'       => $user_login->email,
                                        'phone'       => $user_login->phone,
                                        'password'    => $user_login->password,
                                        'status'      => $user_login->status,
                                        'token'       => $user_login->token,
                                        'created_at'  => $user_login->created_at,
                                        'updated_at'  => $user_login->updated_at,
                                        'cp_id'       => $user_login->cp_id,
                                        'is_admin'    => $user_login->is_admin,
                                        'type'        => isset($user_login->type) ? $user_login->type : 0,
                                        'invite_code' => isset($user_login->invite_code) ? $user_login->invite_code : 0,
                                        'return_url'  => $return_url,
                                    );


                                    $key_aes = $partner->aes_key . date('Ymdhi');
//                                    $key_aes = $partner->aes_key;
                                    //                                B3.1: Encrypt url.
                                    $building_query = http_build_query($data_post);
                                    $encrypted      = $this->encrypt($building_query, $key_aes, $this->algorithm);
                                    $url_return     = $partner->return_url . '?data=' . $encrypted;
                                    if (isset($_GET['t'])) {
                                        if ($_GET['t'] == '2') {
                                            $end_time = date('h:i:s');
                                            CVarDumper::dump($start_begin_time, 10, TRUE);
                                            CVarDumper::dump($end_time, 10, TRUE);
                                            die();
                                        }
                                    }

                                    return $this->redirect($url_return);

                                } else {
                                    $msg = 'Tài khoản chưa được kích hoạt!';
                                }
                            } else {
                                $msg = 'Tài khoản hoặc mật khẩu không đúng!';
                            }
                        } else {
                            $msg = "Tài khoản và mật khẩu không được phép rỗng!";
                        }
                    } else {
                        $error = 'Partner không tồn tại hoặc thiếu điều kiện truy cập!!';
                    }
                }
                //B4: Trả về dữ liệu form Ajax.
            }
            $this->pageTitle = "Đăng nhập";

            return $this->render('login', array('error' => $error, 'pid' => $pid, 'msg' => $msg));

        } //end index

        /**
         * This is the action to handle external exceptions.
         */
        /**
         * Displays the register page
         */
        public function actionRegister()
        {
            $accept_capcha = FALSE;
            $model         = new WRegisterForm;
            $newUser       = new Users;

            $pid           = Yii::app()->getRequest()->getParam('pid', '');
            $register_code = Yii::app()->getRequest()->getParam('register_code', '');
            $campaign_id   = Yii::app()->getRequest()->getParam('campaign_id', '');
            //Đăng ký qua link giới thiệu (ctv)
            if (!empty($register_code) && !empty($campaign_id)) {
                Yii::app()->request->cookies['register_code'] = new CHttpCookie('register_code', $register_code);
                Yii::app()->request->cookies['campaign_id']   = new CHttpCookie('campaign_id', $campaign_id);
            }
            $error = "";
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {
                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                }
            }
            $this->pageTitle = "Đăng ký";
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
                Yii::app()->clientScript->registerScript('verifyCode',
                    '$(document).ready(function(){$(".refresh-image").click();});');

                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if (isset($_POST['WRegisterForm'])) {
                if (!Utils::googleVerify(Yii::app()->params->secret_key) && $accept_capcha) {
                    $msg = Yii::t('web/portal', 'captcha_error');
                    $model->addError('verifyCode', $msg);
                }
                $agree = TRUE;
                if (isset($_POST['WRegisterForm']['agree']) && $_POST['WRegisterForm']['agree'] == 0) {
                    $agree = FALSE;
                }
                if (!empty($pid)) {
                    $checkExist = WPartner::model()->checkExist($pid);
                    if ($checkExist) {

                        $model->attributes = $_POST['WRegisterForm'];
                        if ($model->validate()) {

                            $partner = WPartner::model()->findByAttributes(array('cp_id' => $pid));
                            if ($partner) {
                                $otp                 = self::genOtpKey(6);
                                $user                = new WUsers();
                                $user->username      = $model->username;
                                $user->email         = $model->email;
                                $user->phone         = $model->phone;
                                $user->password      = CPasswordHelper::hashPassword($model->password);
                                $user->created_at    = date('Y-m-d H:i:s');
                                $user->token         = self::genTokenKey(32);
                                $user->cp_id         = $pid;
                                $user->updated_at    = date('Y-m-d H:i:s');
                                $user->register_code = isset(Yii::app()->request->cookies['register_code']->value) ? Yii::app()->request->cookies['register_code']->value : '';
                                $user->campaign_id   = isset(Yii::app()->request->cookies['campaign_id']->value) ? Yii::app()->request->cookies['campaign_id']->value : '';
                                if ($user->cp_id == '002') {
                                    $user->status = WUsers::ACTIVE;
                                    $user->type   = 1;
                                } else {
                                    $user->status = WUsers::INACTIVE;
                                    $user->type   = 0;
                                }
                                $user->is_admin    = WUsers::NOT_ADMIN_SOCIAL;
                                $user->invite_code = self::genIntroduceKey(7); // Sinh mã mặc định khi CTV
                                $user->otp         = $otp;
//                                if ($pid == "001") {
//                                    $user->invite_code = 'W' . self::genIntroduceKey(7);
//                                }
//                                if ($pid == "002") {
//                                    $user->invite_code = 'P' . self::genIntroduceKey(7);
//                                }
//                                if ($pid == "003") {
//                                    $user->invite_code = 'S' . self::genIntroduceKey(7);
//                                }
                                if (self::sendMT($user->otp, $model->phone, $pid)) {

                                    if ($user->validate()) {
                                        if ($agree == TRUE) {
                                            if ($user->save()) {
                                                $data_post = array(
                                                    'user_id'       => $user->id,
                                                    'username'      => $user->username,
                                                    'password'      => $user->password,
                                                    'fullname'      => $user->fullname,
                                                    'email'         => $user->email,
                                                    'phone'         => $user->phone,
                                                    'genre'         => $user->genre,
                                                    'birthday'      => $user->birthday,
                                                    'address'       => $user->address,
                                                    'description'   => $user->description,
                                                    'status'        => $user->status,
                                                    'token'         => $user->token,
                                                    'avatar'        => $user->avatar,
                                                    'created_at'    => $user->created_at,
                                                    'updated_at'    => $user->updated_at,
                                                    'otp'           => $user->otp,
                                                    'is_new'        => 1,
                                                    'type'          => 0,
                                                    'invite_code'   => isset($user->invite_code) ? $user->invite_code : 0,
                                                    'register_code' => $user->register_code,
                                                    'campaign_id'   => $user->campaign_id,
                                                );

                                                $key_aes = $partner->aes_key . date('Ymdhi');

                                                //B3.1: Encrypt url.
                                                $building_query = http_build_query($data_post);
                                                $encrypted      = $this->encrypt($building_query, $key_aes, $this->algorithm);
                                                if ($pid == "002") {
                                                    $url_return = $partner->return_url . '?data=' . $encrypted;
                                                } else {
                                                    $url_return = '/sso/otp/' . $pid . '?data=' . $user->id;
//                                                    $url_return = $partner->return_url . '?data=' . $encrypted;
                                                }

                                                return $this->redirect($url_return);
                                            }
                                        } else {
                                            $model->addError('agree', 'Bạn phải đồng ý với điều khoản của Vinaphone');
                                        }
                                    } else {
                                        $user->getErrors();
                                    }
                                }
                            }
                        } else {
                            $model->getErrors();
                        }
                    } else {
                        $error = "Đối tác không được nhận diện!";
                    }
                } else {
                    $error = "Đối tác không được nhận diện!";
                }
            }

            // display the register for
            return $this->render('register', array('model' => $model, 'pid' => $pid, 'error' => $error, 'accept_capcha' => $accept_capcha));
        }

        /**
         * @return string|void
         * @throws CHttpException
         * Xác nhận OTP
         */
        public function actionOtp()
        {
            //B1: Khởi tạo và nhận dữ liệu.
            $error   = '';
            $msg     = '';
            $otp     = '';
            $pid     = Yii::app()->getRequest()->getParam('pid', '');
            $user_id = Yii::app()->getRequest()->getParam('data', FALSE);
//            CVarDumper::dump($user_id, 10, TRUE);
//            die();
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {
                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                }
            }
            $model_otp = new WOtpForm();
            $partner   = WPartner::model()->findByAttributes(array('cp_id' => $pid));

            if ($partner && $user_id) {
//                $key_aes      = $partner->aes_key . date('Ymdhi');
//                $data_decrypt = self::decrypt($_GET['data'], $key_aes, $this->algorithm);

//                parse_str($data_decrypt, $data_parse_str);

                if (isset($_POST['WOtpForm'])) {

                    $model_otp->attributes = $_POST['WOtpForm'];

                    if ($model_otp->validate()) {

                        if (isset($model_otp->user_id) && $model_otp->otp) {
                            $user = WUsers::model()->findByAttributes(array('id' => $model_otp->user_id, 'otp' => $model_otp->otp));
                            if ($user) {
                                $user->status = WUsers::ACTIVE;
                                if ($user->update()) {

                                    $url_return = Yii::app()->createUrl('/login') . '/' . $pid;

                                    return $this->redirect($url_return);
                                }
                            }
                        }
                    }
                }

                return $this->render('otp', array('model' => $model_otp, 'pid' => $pid, 'error' => $error, 'user_id' => $user_id, 'otp' => $otp));
            }


        }

        public function actionChangeForgetPass()
        {
            $error       = '';
            $data        = Yii::app()->getRequest()->getParam('data', '');
            $pid         = Yii::app()->getRequest()->getParam('pid', '');
            $partner     = WPartner::model()->findByAttributes(array('cp_id' => $pid));
            $model       = new WChangeForgetPass();
            $current_url = Yii::app()->request->hostInfo . Yii::app()->request->requestUri;
            $link        = WLinkChangePass::model()->findByAttributes(array('url' => $current_url));

            if ($link) {
                $sub_time = strtotime(date('Y-m-d H:i:s')) - strtotime($link->create_time);
                $hour     = floor($sub_time / (60 * 60));
//
                if ($hour >= 1) {
                    $link->status = WLinkChangePass::BLOCK;
                    $link->update();
                }
                if ($link->status == WLinkChangePass::ACTIVE) {
                    if (empty($pid)) {
                        $error = "Đối tác không được nhận diện!";
                    } else {
                        $checkExist = WPartner::model()->checkExist($pid);
                        if (!$checkExist) {
                            throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                        }
                    }
                    $this->pageTitle = "Thay đổi mật khẩu";
                    // if it is ajax validation request
                    if (isset($_POST['ajax']) && $_POST['ajax'] === 'changePassword-form') {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                    }
                    if ($partner) {
                        $model->user_id = $link->user_id;
                        if (isset($_POST['WChangeForgetPass'])) {
                            $model->attributes = $_POST['WChangeForgetPass'];

                            if ($model->validate()) {

                                $model->new_password = CPasswordHelper::hashPassword($model->new_password);
                                $user                = WUsers::model()->findByAttributes(array('id' => $model->user_id, 'status' => WUsers::ACTIVE));
                                if ($user) {
                                    $user->password = $model->new_password;
                                    if ($user->update()) {
                                        return $this->redirect('/sso/login/' . $pid . "");
                                    }
                                }
                            }
                        }
                    } else {
                        $error = "Đối tác không được nhận diện hoặc thiếu dữ liệu định danh!";
                    }

                    return $this->render('forget_change_password', array('pid' => $pid, 'error' => $error, 'model' => $model, 'return_url' => $partner->return_url, 'type' => isset($data_parse_str['type']) ? $data_parse_str['type'] : ''));
                } ELSE {
                    $error = "Đường link này đã quá hạn sử dụng!";
                    throw new CHttpException(404, 'Đường link này không tồn tại!');
                }
            }
        }

        /**
         * @return string
         * @throws CHttpException
         * Quên mật khẩu
         */
        public function actionForgetPassword()
        {
            $accept_capcha = FALSE;
            $user          = new Users;
            $model         = new WForgetPassword();
            $pid           = Yii::app()->getRequest()->getParam('pid', '');
            $error         = "";
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {
                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                }
            }

            if (!empty($pid)) {
                $partner     = WPartner::model()->findByAttributes(array('cp_id' => $pid));
                $change_pass = new WChangeForgetPass();
                if (isset($_POST['WForgetPassword'])) {

                    if (!Utils::googleVerify(Yii::app()->params->secret_key) && $accept_capcha) {
                        $msg = 'Bạn phải xác nhận capcha';
                        $model->addError('verifyCode', $msg);
                    } else {
                        $model->attributes = $_POST['WForgetPassword'];

                        if (isset($_POST['WForgetPassword']['select_box'])) {

                            if ($model->validate()) {
                                $criteria       = new CDbCriteria();
                                $standard_phone = '';
                                if ($_POST['WForgetPassword']['select_box'] == 'phone') { // Luồng email
                                    $standard_phone      = WForgetPassword::makePhoneNumberStandard($model->input_text);
                                    $criteria->condition = "email='" . $model->input_text . "' OR phone ='" . $model->input_text . "' OR phone ='$standard_phone'";
                                } else {
                                    $criteria->condition = "email='" . $model->input_text . "' OR phone ='" . $model->input_text . "'";
                                }

                                $user        = Users::model()->find($criteria);
                                $newpassword = self::genTokenKey(8);

                                if ($user) {

//                                    $user->password = CPasswordHelper::hashPassword($newpassword);
                                    if ($_POST['WForgetPassword']['select_box'] == 'email') { // Luồng email
                                        //B3: Encrypt dữ liệu và url.
                                        // B3.1: Encrypt url.
                                        $encrypted         = self::genTokenKey(50);
                                        $url_changepass    = Yii::app()->request->hostInfo . '/sso/changeforgetpass/' . $pid . '/' . $encrypted;
                                        $link              = new WLinkChangePass();
                                        $link->user_id     = $user->id;
                                        $link->url         = $url_changepass;
                                        $link->create_time = date('Y-m-d H:i:s');
                                        $link->status      = WLinkChangePass::ACTIVE;

                                        $start = date('Y-m-d') . ' 00:00:00';
                                        $end   = date('Y-m-d') . ' 23:59:59';
                                        // Đếm số lần lấy lại mật khẩu trong 1 ngày.
                                        $criteria            = new CDbCriteria();
                                        $criteria->condition = "user_id ='$user->id' and create_time >='$start' and create_time <='$end'";
                                        $link_check          = WLinkChangePass::model()->count($criteria);
                                        if ($link_check < 3) {
                                            if ($user->update()) {
                                                $email_content = $this->renderPartial('_email_content_forget_pass', array('url_changepass' => $url_changepass, 'username' => $user->username), TRUE);
                                                if (self::sendEmail('Freedoo - Khôi phục mật khẩu', $model->input_text, '', '', $email_content, 'web.views.site') && $link->save()) {
                                                    $error = "Mật khẩu đã thay đổi thành công! Truy cập email để lấy lại mật khẩu !";

                                                    return $this->render('change_pass_result', array('pid' => $pid, 'email' => $user->email));
                                                } else {
                                                    $error = "Gửi email thất bại";
                                                }
                                            }
                                        } else {
                                            $limit = TRUE;

                                            return $this->render('change_pass_result', array('limit' => $limit, 'pid' => $pid, 'email' => $model->input_text));
                                        }
                                    } else { // Luồng số điện thoại.
                                        // Check quá 3 lần.
                                        $criteria_otp            = new CDbCriteria();
                                        $start                   = date('Y-m-d') . ' 00:00:00';
                                        $end                     = date('Y-m-d') . ' 23:59:59';
                                        $criteria_otp->condition = "user_id ='$user->id' and create_time >='$start' and create_time <='$end'";
                                        $check_otp               = WOtpChangePass::model()->count($criteria_otp);
                                        $otp                     = self::genOtpKey(6);
                                        if ($check_otp < 3) {

                                            $user->otp = $otp;
                                            if ($user->update()) {
                                                if (self::sendMtForget($otp, $model->input_text)) {
                                                    $otp_change_pass              = new WOtpChangePass();
                                                    $otp_change_pass->user_id     = $user->id;
                                                    $otp_change_pass->otp         = $user->otp;
                                                    $otp_change_pass->create_time = date('Y-m-d H:i:s');
                                                    $otp_change_pass->save();

                                                    $change_pass->user_id = $user->id;

                                                    return $this->render('forget_change_password', array('pid' => $pid, 'error' => $error, 'model' => $change_pass, 'return_url' => $partner->return_url, 'type' => 'phone'));
                                                }
                                            }
                                        } else {
                                            $limit = TRUE;

                                            return $this->render('change_pass_result', array('limit' => $limit, 'pid' => $pid, 'email' => $model->input_text));
                                        }

                                    }
                                } else {

                                    return $this->render('change_pass_result', array('pid' => $pid, 'email' => $model->input_text));
                                }
                            }
                        }
                    }
                }


                if (isset($_POST['WChangeForgetPass'])) {
                    $change_pass->attributes = $_POST['WChangeForgetPass'];

                    if (isset($_POST['WChangeForgetPass']['otp'])) {
                        if ($_POST['WChangeForgetPass']['otp'] == '') {
                            $error = 'Bạn phải nhập OTP';

                            return $this->render('forget_change_password', array('pid' => $pid, 'error' => $error, 'model' => $change_pass, 'type' => 'phone', 'return_url' => $partner->return_url));
                        }
                    }

                    if ($change_pass->validate()) {

                        $change_pass->new_password = CPasswordHelper::hashPassword($change_pass->new_password);
                        $user                      = WUsers::model()->findByAttributes(array('id' => $change_pass->user_id, 'status' => WUsers::ACTIVE));
                        if ($user) {
                            $user->password = $change_pass->new_password;
                            if ($user->update()) {
                                return $this->render('change_pass_result', array('pid' => $pid, 'phone' => $model->input_text, 'otp_result' => TRUE));
                            }
                        }
                    } else {
                        return $this->render('forget_change_password', array('pid' => $pid, 'error' => $error, 'model' => $change_pass, 'type' => 'phone', 'return_url' => $partner->return_url));
                    }
                }
                $this->pageTitle = "Nhận lại mật khẩu của bạn!";
            }

            return $this->render('forget_password', array('pid' => $pid, 'error' => $error, 'model' => $model, 'accept_capcha' => $accept_capcha));
            // if it is ajax validation request

        }

        /**
         * @return string|void
         * @throws CHttpException
         * Thay đổi mật khẩu.
         */
        public function actionChangePassword()
        {
            $model = new WChangePassWord();
            $error = '';
            $pid   = Yii::app()->getRequest()->getParam('pid', '');
            $data  = Yii::app()->getRequest()->getParam('data', '');
            $error = "";
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {
                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                }
            }
            $this->pageTitle = "Thay đổi mật khẩu";
            // if it is ajax validation request

            $partner      = WPartner::model()->findByAttributes(array('cp_id' => $pid));
            $key_aes      = $partner->aes_key . date('Ymdhi');
            $data_decrypt = self::decrypt($data, $key_aes, $this->algorithm);

            parse_str($data_decrypt, $data_parse_str);
            $data_key   = array('user_id');
            $checkExist = self::validateDecryptData($data_key, $data_parse_str);

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'changePassword-form') {
                $model->user_id = $data_parse_str ['user_id'];
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($partner && $checkExist) {
                $model->user_id = $data_parse_str ['user_id'];
                if (isset($_POST['WChangePassWord'])) {
                    $model->attributes = $_POST['WChangePassWord'];
                    if ($model->validate()) {
                        $model->new_password = CPasswordHelper::hashPassword($model->new_password);
                        $user                = WUsers::model()->findByAttributes(array('phone' => $model->phone, 'status' => WUsers::ACTIVE));
                        if ($user) {
                            $user->password = $model->new_password;
                            if ($user->update()) {
                                return $this->redirect('/sso/login/' . $pid . "?is_new=1");
                            }
                        }
                    }
                }
            } else {
                $error = "Đối tác không được nhận diện hoặc thiếu dữ liệu định danh!";
            }

            return $this->render('change_password', array('pid' => $pid, 'error' => $error, 'model' => $model, 'return_url' => $partner->return_url));
        }

        /**
         * @throws CHttpException
         *  Hiện tại mới update số điện thoại.
         */
        public function actionUpdateInfo()
        {
            $data  = Yii::app()->getRequest()->getParam('data', '');
            $pid   = Yii::app()->getRequest()->getParam('pid', '');
            $error = "";
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {
                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                } else {
                    $partner = WPartner::model()->findByAttributes(array('cp_id' => $pid));
                    $key_aes = $partner->aes_key . date('Ymdhi');
//                    $key_aes      = $partner->aes_key;
                    $data_decrypt = self::decrypt($data, $key_aes, $this->algorithm);

                    $data_key_phone = array('user_id', 'phone');
                    $data_key       = array('user_id', 'email', 'phone');
                    parse_str($data_decrypt, $data_parse_str);
                    $checkData       = self::validateDecryptData($data_key, $data_parse_str);
                    $checkData_phone = self::validateDecryptData($data_key_phone, $data_parse_str);
                    if ($checkData_phone) {
                        $user = Users::model()->findByAttributes(array('id' => $data_parse_str['user_id']));
                        if ($user) {
                            $user->phone      = $data_parse_str['phone'];
                            $user->updated_at = date('Y-m-d H:i:s');
                            if ($user->update()) {
                                echo 1;
                            }
                        }
                    } else if ($checkData) {
                        $user = Users::model()->findByAttributes(array('id' => $data_parse_str['user_id']));
                        if ($user) {
                            $user->phone      = $data_parse_str['phone'];
                            $user->email      = $data_parse_str['email'];
                            $user->updated_at = date('Y-m-d H:i:s');
                            if ($user->update()) {
                                echo 1;
                            }
                        }
                    } else {
                        echo "Không đủ dữ liệu !";
                    }
                }
            }
        }


        /**
         * $type =1 CTV || =2  AGENCY
         * Test gọi Api
         */
        public function actionTestApiRegister()
        {
            $data = array(
                'user_id' => '0bhs587tfpywdrc69maji31vqxgenl2z',
                'phone'   => '012345678',
            );
            $data = http_build_query($data);
            $data = self::encrypt($data, 'tbwliozj4e2a5rxdvm19gkhfn6us8yq7', MCRYPT_RIJNDAEL_128);
            $url  = "http://sso.dev/updateinfo/002?data=" . $data;
            echo Utils::cUrlGet($url, 15, $http_status);
        }

        /**
         * Api đồng bộ ctv và agency với affiliate.
         */
        public function actionApiRegister()
        {
            $data   = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data
            $pid    = Yii::app()->request->getParam('pid', FALSE); // Lấy cp_id
            $result = array(
                'status' => '',
                'msg'    => '',
            );
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {

                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                }
            }

            if ($data) {

                $partner      = WPartner::model()->findByAttributes(array('cp_id' => $pid));
                $key_aes      = $partner->aes_key . date('Ymdhi');
                $data_decrypt = self::decrypt($data, $key_aes, $this->algorithm);
                parse_str($data_decrypt, $data_parse_str);
                $data_key   = array('username', 'email', 'phone', 'password', 'type', 'fullname');
                $checkExist = self::validateDecryptData($data_key, $data_parse_str);
                if ($checkExist) {
                    $users = Users::model()->findByAttributes(array('username' => $data_parse_str['username']));
                    if ($users) {
                        $result['status'] = 401;
                        $result['msg']    = 'Tài khoản này đã tồn tại!';
                    } else {
                        $users             = new WUsers();
                        $users->attributes = $data_parse_str;

                        $users->scenario   = 'api';
                        $users->updated_at = date('Y-m-d H:i:s');
                        $users->created_at = date('Y-m-d H:i:s');
                        $users->cp_id      = $pid;
                        $users->otp        = self::genOtpKey(6);
                        if ($users->validate()) {
                            $users->password = CPasswordHelper::hashPassword($data_parse_str['password']);
                            if ($data_parse_str['type'] == self::CTV) {
                                if (isset($data_parse_str['is_child_agency'])) {
                                    if ($data_parse_str['is_child_agency'] == 1) {
                                        $users->invite_code = 'AP' . self::genIntroduceKey(7);
                                    } else {
                                        $users->invite_code = self::genIntroduceKey(7);
                                    }
                                }
                            } else if ($data_parse_str['type'] == self::AGENCY) {
                                $users->invite_code = 'A' . self::genIntroduceKey(7);
                            } else {
                                $users->invite_code = self::genIntroduceKey(7);
                            }
                            $users->status = WUsers::ACTIVE;
                            if ($users->save()) {
                                $data_user        = array(
                                    'user_id'     => $users->id,
                                    'username'    => $users->username,
                                    'fullname'    => $users->fullname,
                                    'email'       => $users->email,
                                    'phone'       => $users->phone,
                                    'genre'       => $users->genre,
                                    'birthday'    => $users->birthday,
                                    'address'     => $users->address,
                                    'description' => $users->description,
                                    'status'      => $users->status,
                                    'token'       => $users->token,
                                    'avatar'      => $users->avatar,
                                    'created_at'  => $users->created_at,
                                    'updated_at'  => $users->updated_at,
                                    'otp'         => $users->otp,
                                    'is_new'      => 1,
                                    'invite_code' => isset($users->invite_code) ? $users->invite_code : 0,
                                );
                                $result['status'] = 200;
                                $result['msg']    = 'Đăng ký thành công!';
                                $result['data']   = $data_user;
                            }
                        } else {
                            $result['status'] = 500;
                            foreach ($users->getErrors() as $values) {
                                $result['msg'] = isset($values[0]) ? $values[0] : 'Unknown Errors';
                                break;
                            }
                        }
                    }
                } else {
                    $result['status'] = 401;
                    $result['msg']    = 'Truyền thiếu dữ liệu !';
                }
            }
            echo CJSON::encode($result);
        }

        /**
         * @param array $data_key
         * @param array $data_parse_str
         *
         * @return bool
         */
        public function validateDecryptData($data_key = array(), $data_parse_str = array())
        {
            foreach ($data_key as $item_data) { //Kiểm tra thiếu trường và trường null.
                if (empty($data_parse_str[$item_data])) {
                    return FALSE;
                }
            }

            return TRUE;
        }

        /**
         * Ẩn hoạt động người dùng.
         */
        public function actionChangeStatusUser()
        {
            $data = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data

            $result = FALSE;

            if ($data) {
                $data_decrypt = self::decrypt($data, $this->changestatus_key, $this->algorithm);
                parse_str($data_decrypt, $data_parse_str);
                $data_key = array('username', 'status');
//                $checkExist = self::validateDecryptData($data_key, $data_parse_str);
                $checkExist = TRUE;

                if ($checkExist) {
                    $users = Users::model()->findByAttributes(array('username' => $data_parse_str['username']));

                    if ($users) {

                        $users->status = $data_parse_str['status'];
                        if ($users->save()) {
                            $result = TRUE;
                        }
                    }
                }
            }
            echo $result;
        }

        public function actionTestLoginApi()
        {
            $data    = array(
                'username' => 'minhminh',
                'password' => '123456',
            );
            $aes_key = 'thanhnx1234567abcdef123456789090';
            $data    = http_build_query($data);
            $data    = self::encrypt($data, $aes_key, MCRYPT_RIJNDAEL_128);
            $url     = "http://118.70.177.77:8694/sso/index.php/login-api/" . $data . "";
            echo Utils::cUrlGet($url, 15, $http_status);

        }

        /**
         * Ẩn hoạt động người dùng.
         */
        public function actionLoginApi()
        {
            $aes_key = 'thanhnx1234567abcdef123456789090';
            $data    = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data

            $result = array(
                'success'     => FALSE,
                'message'     => '',
                'username'    => '',
                'password'    => '',
                'invite_code' => '',
                'sso_id'      => '',
                'fullname'    => '',
                'email'       => '',
                'phone'       => '',
                'address'     => '',
                'type'        => '',
                'status'      => '',

            );

            if ($data) {
                $data_decrypt = self::decrypt($data, $aes_key, $this->algorithm);
                parse_str($data_decrypt, $data_parse_str);
                $data_key   = array('username', 'password');
                $checkExist = self::validateDecryptData($data_key, $data_parse_str);

                if ($checkExist) {
                    $users = Users::model()->findByAttributes(array('username' => $data_parse_str['username']));
                    if ($users) {
                        if (CPasswordHelper::verifyPassword($data_parse_str['password'], $users->password)) {
                            $result['success']     = TRUE;
                            $result['message']     = 'ok';
                            $result['username']    = $data_parse_str['username'];
                            $result['password']    = $data_parse_str['password'];
                            $result['invite_code'] = isset($users->invite_code) ? $users->invite_code : '';
                            $result['sso_id']      = isset($users->id) ? $users->id : '';
                            $result['fullname']    = isset($users->fullname) ? $users->fullname : '';
                            $result['email']       = isset($users->email) ? $users->email : '';
                            $result['phone']       = isset($users->phone) ? $users->phone : '';
                            $result['address']     = isset($users->address) ? $users->address : '';
                            $result['type']        = isset($users->type) ? $users->type : '';
                            $result['status']      = isset($users->status) ? $users->status : '';
                        }
                    }
                } else {
                    $result['message'] = "Not enough data";
                }
            }
            echo CJSON::encode($result);
            exit();
        }

        /**
         * @param $otp
         * @param $msisdn
         * Gửi tin nhắn xác thực mã OTP.
         *
         * @return bool
         */
        public function sendMT($otp, $msisdn, $pid)
        {
            if ($pid != '002') {
                // Send MT.
                $mt_content = Yii::t('common/mt_content', 'otp_login', array(
                    '{otp_login}' => $otp,
                ));
                if (self::sentMtVNP($msisdn, $mt_content, 'otp_login')) {
                    return TRUE;
                }
            }

//
            return TRUE;
        }

        public function sendMtForget($otp, $msisdn)
        {

            // Send MT.
            $mt_content = Yii::t('common/mt_content', 'otp_forget_pass', array(
                '{otp_forget_pass}' => $otp,
            ));
            if (self::sentMtVNP($msisdn, $mt_content, 'otp_forget_pass')) {
                return TRUE;
            }


//
            return TRUE;
        }

        /**
         * @param $msisdn
         * @param $msgBody
         * @param $file_name
         *
         * @return bool
         */
        public static function sentMtVNP($msisdn, $msgBody, $file_name)
        {
            $logMsg   = array();
            $logMsg[] = array('Start Send MT ' . $file_name . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = Utils::sentMtVNP($msisdn, $msgBody, $mtUrl, $http_code);
            if ($flag) {
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            }

            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "Log_send_mt/" . date("Y/m/d");
            $logObj    = TraceLog::getInstance($logFolder);
            $logObj->setLogFile($file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }

        public function actionError()
        {
            if ($error = Yii::app()->errorHandler->error) {
                if (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else
                    $this->render('error', $error);
            }
        }

        public function safe_b64encode($string)
        {
            $data = base64_encode($string);
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function safe_b64decode($string)
        {
            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }

        public function encrypt($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = $this->safe_b64encode(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }

        public function decrypt($decrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt($algorithm, $key, $this->safe_b64decode($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

        private function genOtpKey($lengthChars = 32)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $alphaString  = 'abcdefghijklmnopqrstuvwxyz';
                $numberString = '1234567890';

                $shuffleString = $alphaString . $numberString;
                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);
                $user          = WUsers::model()->findByAttributes(array('otp' => $randomString));
                if ($user) {
                    return $this->genOtpKey($lengthChars = 32);
                } else {
                    return $randomString;
                }
            }
        }

        /**
         * @param int $lengthChars
         *
         * Gen mã giới thiệu.
         *
         * @return bool|string
         */
        private function genIntroduceKey($lengthChars = 7)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $numberString  = '1234567890';
                $shuffleString = $numberString;
                $randomString  = 'P' . substr(str_shuffle($shuffleString), 0, $lengthChars);
                $user          = WUsers::model()->findByAttributes(array('invite_code' => $randomString));
                if ($user) {
                    return $this->genIntroduceKey($lengthChars);
                } else {
                    return $randomString;
                }
            }
        }

        public static function genTokenKey($lengthChars = 32)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $alphaString  = 'abcdefghijklmnopqrstuvwxyz';
                $numberString = '1234567890';

                $shuffleString = $alphaString . $numberString;
                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);

                return $randomString;
            }
        }

        public static function sendEmail($from, $to, $subject, $short_desc, $content = '', $views_layout_path = 'web.views.site', &$error = '')
        {
            $mail = new YiiMailer();
            $mail->setLayoutPath($views_layout_path);
            $mail->setData(array('message' => $content, 'name' => $from, 'description' => $short_desc));

            $mail->setFrom(Yii::app()->params->sendEmail['username'], $from);
            $mail->setTo($to);
            $mail->setSubject($from . ' | ' . $subject);
            $mail->setSmtp(Yii::app()->params->sendEmail['host'], Yii::app()->params->sendEmail['port'], Yii::app()->params->sendEmail['type'], TRUE, Yii::app()->params->sendEmail['username'], Yii::app()->params->sendEmail['password']);

            if ($mail->send()) {// echo 'Email was sent';

                return TRUE;
            } else {
                //$error = $mail->getError();

                return FALSE;
            }
        }

        public function actionTest()
        {
            $from    = 'Freedoo';
            $to      = 'duong.h@centech.com.vn';
            $title   = 'Chào Dương';
            $content = 'Nội dung email...';
            $rs      = self::sendEmail($from, $to, $title, $title, $content);
            CVarDumper::dump($rs, 10, TRUE);
            die;
        }

        /**
         * @param     $url
         * @param     $post_string
         * @param int $time_out
         * @param     $http_status
         *
         * @return mixed
         */
        public static function cUrlPostJson($url, $post_string, $https = FALSE, $time_out = 15, &$http_status = '')
        {
            $ch = curl_init();
            if ($https) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            }

            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_string),
                'Authorization: key=AAAAPGdujeo:APA91bFeDV9FFbBquPKDDmtsHyxN_8j0TUwyU93M9zGkgTAGFQgqdboVVl16K1ThvkSfBfUc18FeubEzdd22bxHWDHYW7rRonqd5USj5XKjIhds7_mkNONR86QS6Pwqx0aeMXRfF9AkP'
            ));

            curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
            $data        = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $data;
        }

        // Fix lỗi CTV null.
        public function actionGenKeyCtvNull()
        {
            set_time_limit(99999);
            $users = WUsers::model()->findAll();
            foreach ($users as $key => $value) {
                if (empty($value->invite_code) || $value->invite_code == NULL) {
                    $value->invite_code = self::genIntroduceKey(7);
                    $users[$key]->update();
                }
            }

            echo 1;
        }

        public function actionTestUpdateType()
        {
            $data       = array(
                'user_id'  => 'sjnipyu4t81hg532xc7oq9wkd06mlfez',
                'username' => 'mong123',
                'type'     => 1, //1:CTV || 0:Member Thuong
            );
            $checkExist = WPartner::model()->checkExist('002');
            $data       = http_build_query($data);
            $data       = self::encrypt($data, $checkExist->aes_key, MCRYPT_RIJNDAEL_128);
            $url        = "http://118.70.177.77:8694/sso/updateType/002?data=" . $data;
            echo Utils::cUrlGet($url, 15, $http_status);

        }

        /**
         * Update rule cộng tác viên hủy hoặc nâng cấp
         */
        public function actionUpdateType()
        {
            $data = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data
            $pid  = Yii::app()->request->getParam('pid', FALSE); // Lấy dữ liệu data

            $result = FALSE;
            if (empty($pid)) {
                $error = "Đối tác không được nhận diện!";
            } else {

                $checkExist = WPartner::model()->checkExist($pid);
                if (!$checkExist) {
                    throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                } else {
                    if ($data && $pid) {
                        $key_aes      = $checkExist->aes_key . date('Ymdhi');
                        $data_decrypt = self::decrypt($data, $key_aes, $this->algorithm);
                        parse_str($data_decrypt, $data_parse_str);
                        $data_key = array('user_id', 'username');
                        $check    = self::validateDecryptData($data_key, $data_parse_str);

                        if ($check) {

                            $users = WUsers::model()->findByAttributes(array('username' => $data_parse_str['username'], 'id' => $data_parse_str['user_id']));
                            if ($users) {
                                $users->type       = $data_parse_str['type'];
                                $users->updated_at = date('Y-m-d H:i:s');
                                if ($users->update()) {
                                    echo 1;
                                } else {
                                    throw new CHttpException(403, 'Xảy ra lỗi trong quá trình update!');
                                }
                            } else {
                                throw new CHttpException(401, 'Không tồn tại user!');
                            }

                            return FALSE;
                        } else {
                            throw new CHttpException(500, 'Parser dữ liệu không thành công!');

                        }
                    } else {
                        throw new CHttpException(403, 'Bạn không có quyền truy cập!');
                    }
                }
            }

        }

        public function actionFixInviterCode()
        {
            $sql                 = "Select username,  invite_code  from tbl_users where invite_code not like 'P%' and  invite_code  not like 'AP%'";
            $criteria            = new CDbCriteria();
            $criteria->select    = "username, invite_code";
            $criteria->condition = "invite_code not like 'P%' and invite_code not like 'AP%'";
            $user_check          = CHtml::listData(WUsers::model()->findAll($criteria), 'username', 'invite_code');
            foreach ($user_check as $key => $value) {

                $user = WUsers::model()->findByAttributes(array('username' => $key));
                if ($user) {
                    $user->invite_code = self::genIntroduceKey(7);
                    $user->update();
                }
            }

            return TRUE;


        }

        public function actionCheckAttExist($attributes, $values)
        {

            if ($attributes && $values) {
                $users = WUsers::model()->findByAttributes(array($attributes => $values));
                if ($users) {
                    echo FALSE;
                    exit();
                } else {
                    echo TRUE;
                }
            }


        }

        public function actionCheckAttExistUpdate($attributes, $values, $user_id)
        {

            if ($attributes && $values) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "$attributes = '$values' and id !='$user_id'";

                $users = WUsers::model()->find($criteria);
                if ($users) {
                    echo FALSE;
                    exit();
                } else {
                    echo TRUE;
                }
            }


        }

        public function actionCreateUserSSO()
        {

            $data     = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data
            $partner  = WPartner::model()->findByAttributes(array('cp_id' => '002'));

            $logMsg   = array();
            $logMsg[] = array('Start  Log', 'Start process:' . __LINE__, 'I', time());

            $logMsg[] = array($data, 'Input: ' . __LINE__, 'T', time());
            //call api
            if ($partner) {
                $data_decrypt = self::decrypt($data, $partner->aes_key . date('Ymdhi'), $this->algorithm);

                parse_str($data_decrypt, $data_parse_str);

                $data_key = array('id', 'username', 'email', 'phone', 'password', 'token', 'status', 'invite_code', 'cp_id', 'updated_at');
                $check    = self::validateDecryptData($data_key, $data_parse_str);
                $logMsg[] = array($check, 'check: ' . __LINE__, 'T', time());
                $logMsg[] = array($data_decrypt, 'data_decrypt: ' . __LINE__, 'T', time());
                if (!empty($data_parse_str)) {
                    if (is_array($data_parse_str)) {
                        foreach ($data_parse_str as $key => $value) {
                            $logMsg[] = array($value, $key . "=>" . __LINE__, 'T', time());
                        }
                    }
                }
                if ($check) {
                    $users = WUsers::model()->findByAttributes(array('username' => $data_parse_str['username']));
                    if (!$users) {
                        $users             = new WUsers();
                        $users->attributes = $data_parse_str;
                        if ($users->save()) {
                            echo TRUE;
                            $logMsg[]  = array('True', 'Output: ' . __LINE__, 'T', time());
                            $logFolder = "Log_create_user_ctv/" . date("Y/m/d");
                            $logObj    = TraceLog::getInstance($logFolder);
                            $logObj->setLogFile('create_user_ctv.log');
                            $logMsg[] = array('create_user_ctv', 'Finish process-' . __LINE__, 'F', time());
                            $logObj->processWriteLogs($logMsg);
                            exit();
                        } else {
                            echo FALSE;
                        }
                    }
                }
                $logMsg[]  = array('False', 'Output: ' . __LINE__, 'T', time());
                $logFolder = "Log_create_user_ctv/" . date("Y/m/d");
                $logObj    = TraceLog::getInstance($logFolder);
                $logObj->setLogFile('create_user_ctv.log');
                $logMsg[] = array('create_user_ctv', 'Finish process-' . __LINE__, 'F', time());
                $logObj->processWriteLogs($logMsg);
            }
        }

        public function actionCreateUserSSOINS()
        {

            $data     = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data
            $partner  = WPartner::model()->findByAttributes(array('cp_id' => '004'));

            $logMsg   = array();
            $logMsg[] = array('Start  Log', 'Start process:' . __LINE__, 'I', time());

            $logMsg[] = array($data, 'Input: ' . __LINE__, 'T', time());
            //call api
            if ($partner) {
                $data_decrypt = self::decrypt($data, $partner->aes_key . date('Ymdhi'), $this->algorithm);

                parse_str($data_decrypt, $data_parse_str);

                $data_key = array('id', 'username', 'email', 'phone', 'password', 'token', 'status', 'invite_code', 'cp_id', 'updated_at');
                $check    = self::validateDecryptData($data_key, $data_parse_str);
                $logMsg[] = array($check, 'check: ' . __LINE__, 'T', time());
                $logMsg[] = array($data_decrypt, 'data_decrypt: ' . __LINE__, 'T', time());
                if (!empty($data_parse_str)) {
                    if (is_array($data_parse_str)) {
                        foreach ($data_parse_str as $key => $value) {
                            $logMsg[] = array($value, $key . "=>" . __LINE__, 'T', time());
                        }
                    }
                }
                if ($check) {
                    $users = WUsers::model()->findByAttributes(array('username' => $data_parse_str['username']));
                    if (!$users) {
                        $users             = new WUsers();
                        $users->attributes = $data_parse_str;
                        if ($users->save()) {
                            echo TRUE;
                            $logMsg[]  = array('True', 'Output: ' . __LINE__, 'T', time());
                            $logFolder = "Log_create_user_ctv/" . date("Y/m/d");
                            $logObj    = TraceLog::getInstance($logFolder);
                            $logObj->setLogFile('create_user_ctv.log');
                            $logMsg[] = array('create_user_ctv', 'Finish process-' . __LINE__, 'F', time());
                            $logObj->processWriteLogs($logMsg);
                            exit();
                        } else {
                            echo FALSE;
                        }
                    }
                }
                $logMsg[]  = array('False', 'Output: ' . __LINE__, 'T', time());
                $logFolder = "Log_create_user_ctv/" . date("Y/m/d");
                $logObj    = TraceLog::getInstance($logFolder);
                $logObj->setLogFile('create_user_ctv.log');
                $logMsg[] = array('create_user_ctv', 'Finish process-' . __LINE__, 'F', time());
                $logObj->processWriteLogs($logMsg);
            }
        }

    } //end class
