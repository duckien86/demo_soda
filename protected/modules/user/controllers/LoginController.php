<?php

    class LoginController extends Controller
    {
        public $defaultAction = 'login';
        public $algorithm     = MCRYPT_RIJNDAEL_128;
        public $aes_key       = '0123456789abcdef0123456789abcdef';

        /**
         * Displays the login page
         */
        public function actionLogin()
        {

            if (Yii::app()->user->isGuest) {

                $model = new UserLogin;

                // collect user input data
                if (isset($_POST['UserLogin'])) {
                    $model->attributes = $_POST['UserLogin'];
                    if (Yii::app()->user->id) {
                        $this->redirect(Yii::app()->controller->module->returnUrl);
                    }

                    // validate user input and redirect to previous page if valid
                    if ($model->validate()) {

                        $user = User::model()->findByAttributes(array('username' => $model->username));
                        if($user){
                            if(empty($user->agency_id)) {
                                Yii::app()->user->setState('username', $user->username);
                                Yii::app()->user->setState('phone', $user->phone);

                                $token = Utils::getTokenPass($model);
                                Yii::app()->user->setState('token', $token);

                                if(!empty($user->province_code)){
                                    $province = Province::model()->findByAttributes(array('code' => $user->province_code));
                                    if ($province) {
                                        Yii::app()->user->setState('vnp_province_id', $province->vnp_province_id);
                                    }
                                }

                                Yii::app()->user->setState('province_code', $user->province_code);
                                Yii::app()->user->setState('district_code', $user->district_code);
                                Yii::app()->user->setState('ward_code', $user->ward_code);
                                Yii::app()->user->setState('brand_offices_id', $user->brand_offices_id);
                                Yii::app()->user->setState('sale_offices_id', $user->sale_offices_id);

                                if (isset($user->unit_id) && !empty($user->unit_id)) {
                                    Yii::app()->user->setState('unit_id', $user->unit_id);
                                }

                                if (!empty($user->phone)) {
                                    $phone = substr(CFunction::makePhoneNumberBasic($user->phone), 1);
                                }

                                Yii::app()->user->setState('msisdn_otp', isset($phone) ? $phone : '');
                                Yii::app()->user->setState('msisdn_eload', isset($model->phone) ? $model->phone : '');

                                // Kiểm tra ngày hiện tại đã gửi OTP chưa
                                $found = false;
                                $user_otp = OtpUser::model()->findByAttributes(array('username' => $model->username));
                                if($user_otp){
                                    if( !empty($user_otp->create_date) && !empty($user_otp->otp)
                                        && date('Y-m-d', strtotime($user_otp->create_date)) == date('Y-m-d') )
                                    {
                                        $found = true;
                                    }
                                }else{
                                    $user_otp = new OtpUser();
                                    $user_otp->username     = $user->username;
                                    $user_otp->phone        = $user->phone;
                                }

                                if(!$found){
                                    $user_otp->create_date  = date('Y-m-d H:i:s');
                                    $user_otp->otp = $model->genTokenKey(4, TRUE);
                                    $user_otp->save();

                                    $model->sendMT($user_otp->otp, $user->phone);
                                }

                                $data_response = array(
                                    'username'   => $model->username,
                                    'password'   => $model->password,
                                    'phone'      => $model->phone,
                                    'rememberMe' => $model->rememberMe,
                                    'otp'        => $user_otp->otp,
                                );
                                $data_build = http_build_query($data_response);
                                $data = Utils::encrypt($data_build, $this->aes_key, $this->algorithm);

                                return $this->redirect('index.php?r=user/login/otp&data=' . $data);
                            }else{
                                $model->addError('username', UserModule::t("Username is incorrect."));
                            }
                        }
                    }
                }


                $this->layout = '//layouts/login';

                // display the login form
                $this->render('/user/login', array('model' => $model));
            } else
                $this->redirect(Yii::app()->controller->module->returnUrl);
        }

        public function actionOtp()
        {
            if (Yii::app()->user->isGuest) {
                $this->layout = '//layouts/login';
                $data_decrypt = Utils::decrypt($_GET['data'], $this->aes_key, $this->algorithm);

                parse_str($data_decrypt, $data_parse_str);

                $model           = new UserLogin();
                $model->username = $data_parse_str['username'];
                $model->password = $data_parse_str['password'];
                $model->phone      = $data_parse_str['phone'];
                $model->rememberMe = isset($data_parse_str['rememberMe']) ? $data_parse_str['rememberMe'] : 0;

                if ($model) {
                    if (isset($_POST['UserLogin']['otp'])) {

                        $model->attributes = $_POST['UserLogin'];
                        $check_token_key   = OtpUser::model()->findByAttributes(array('username' => $model->username, 'otp' => $_POST['UserLogin']['otp']));
                        if ($check_token_key) {
                            $identity = new UserIdentity($model->username, $model->password);
                            $identity->authenticate();
                            $duration = 0;
                            if (isset($_POST['UserLogin']['rememberMe'])) {
                                $duration = $_POST['UserLogin']['rememberMe'] ? 3600 * 24 * 30 : 0; // 30 days
                            }
                            if (Yii::app()->user->login($identity, $duration)) {
                                $this->lastVisit();
                                if (strpos(Yii::app()->user->returnUrl, '/index.php') !== FALSE) {
                                    $this->redirect(Yii::app()->controller->createUrl('/aSite/index'));
                                } else {
                                    $this->redirect(Yii::app()->user->returnUrl);
                                }
                            }
                        } else {
                            $model->addError('token_key', 'Mã xác thực không chính xác!');
                        }
                    }
                    $this->render('/user/login_otp', array('model' => $model, 'otp' => $data_parse_str['otp']));
                }
            } else {
                $this->redirect(Yii::app()->controller->module->returnUrl);
            }
        }

        public function actionResendOtp()
        {
            if(isset($_GET['data'])){
                $data_decrypt = Utils::decrypt($_GET['data'], $this->aes_key, $this->algorithm);

                parse_str($data_decrypt, $data_parse_str);

                $model              = new UserLogin();
                $model->username    = $data_parse_str['username'];
                $model->password    = $data_parse_str['password'];
                $model->phone       = $data_parse_str['phone'];
                $model->rememberMe  = isset($data_parse_str['rememberMe']) ? $data_parse_str['rememberMe'] : 0;

                if(!$model->validate()){
                    return $this->redirect('index.php?r=user/login');
                }

                $otp = $model->genTokenKey(4, TRUE);

                if ($model->sendMT($otp, $model->phone)) {
                    $user_otp = OtpUser::model()->findByAttributes(array('username' => Yii::app()->user->username));
                    if (!$user_otp) {
                        $user_otp = new OtpUser();
                        $user_otp->username = $model->username;
                        $user_otp->phone = $model->phone;
                    }
                    $user_otp->create_date = date('Y-m-d H:i:s');
                    $user_otp->otp = $otp;
                    $user_otp->save();
                }

                $data_response = array(
                    'username'      => $model->username,
                    'password'      => $model->password,
                    'phone'         => $model->phone,
                    'rememberMe'    => $model->rememberMe,
                    'otp'           => $otp,
                );

                $data_build = http_build_query($data_response);

                $data = Utils::encrypt($data_build, $this->aes_key, $this->algorithm);

                return $this->redirect('index.php?r=user/login/otp&data=' . $data);
            }
            return $this->redirect('index.php?r=user/login');
        }

        private function lastVisit()
        {

            $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);

            $lastVisit->lastvisit = time();

            $lastVisit->save();
        }

        public static function getTokenPass(UserLogin $model)
        {

            $password_md5 = md5($model->password);
            $password_sub = substr($password_md5, 0, 16);
            Yii::app()->user->setState('password_sub', $password_sub);
            $token = $model->apiLogin($model->username, $password_sub);

            return $token;

        }

        public function safe_b64encode_noreplace($string)
        {
            $data = base64_encode($string);

//            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function safe_b64decode_noreplace($data)
        {
//            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }

        public function encrypt_noreplace($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = $this->safe_b64encode_noreplace(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }


        public function decrypt_noreplace($decrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt($algorithm, $key, $this->safe_b64decode_noreplace($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

    }