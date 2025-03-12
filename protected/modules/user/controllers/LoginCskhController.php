<?php

    class LoginCskhController extends Controller
    {
        public $defaultAction = 'login';
        public $algorithm     = MCRYPT_RIJNDAEL_128;
        public $aes_key       = '0123456789abcdef';

        /**
         * Displays the login page
         */
        public function actionLogin()
        {
            if (Yii::app()->user->isGuest) {
                $type  = 1;
                $model = new UserLoginCskh();
                // collect user input data
                if (isset($_GET['map'])) {
                    $user_map               = new UserMap();
                    $user_map->username_ext = $_GET['map']['username'];
                    if (isset($_GET['map']['password']) && !empty($_GET['map']['password'])) {
                        $user_map->password_ext = $_GET['map']['password'];
                    }
                }
                if (isset($_POST['UserLoginCskh'])) {

                    $model->attributes = $_POST['UserLoginCskh'];
                    $model->rememberMe = $_POST['UserLoginCskh']['rememberMe'];
                    // validate user input and redirect to previous page if valid
                    if (Yii::app()->user->id) {
                        $this->redirect(Yii::app()->controller->module->returnUrl);
                    }
                    if ($model->validate()) {

                        $user = User::model()->findByAttributes(array('username' => $model->username));
                        if($user){
                            $agency = AgencyUser::model()->findByAttributes(array('user_id' => $user->id));
                            if(!$agency) {

                                Yii::app()->user->setState('username', isset($user->username) ? $user->username : '');
                                Yii::app()->user->setState('province_code', isset($user->province_code) ? $user->province_code : '');
                                Yii::app()->user->setState('district_code', isset($user->district_code) ? $user->district_code : '');
                                Yii::app()->user->setState('ward_code', isset($user->ward_code) ? $user->ward_code : '');
                                Yii::app()->user->setState('brand_offices_id', isset($user->brand_offices_id) ? $user->brand_offices_id : '');
                                Yii::app()->user->setState('sale_offices_id', isset($user->sale_offices_id) ? $user->sale_offices_id : '');
                                Yii::app()->user->setState('unit_id', isset($user->unit_id) ? $user->unit_id : '');
                                
                                if (!empty($user->phone)) {
                                    $phone = substr($user->phone, 1);
                                }
                                Yii::app()->user->setState('msisdn_otp', isset($phone) ? $phone : '');

                                // Kiểm tra ngày hiện tại đã gửi OTP chưa
                                $found = false;
                                $user_otp = OtpUser::model()->findByAttributes(array('username' => $model->username));
                                if ($user_otp) {
                                    if (!empty($user_otp->create_date) && !empty($user_otp->otp)
                                        && date('Y-m-d', strtotime($user_otp->create_date)) == date('Y-m-d')
                                    ) {
                                        $found = true;
                                    }
                                } else {
                                    $user_otp = new OtpUser();
                                    $user_otp->username = $user->username;
                                    $user_otp->phone = $user->phone;
                                }

                                if (!$found) {
                                    $user_otp->create_date = date('Y-m-d H:i:s');
                                    $user_otp->otp = $model->genTokenKey(4, TRUE);
                                    $user_otp->save();

                                    $model->sendMT($user_otp->otp, $user->phone);
                                }

                                $data = array(
                                    'username' => $model->username,
                                    'password' => $model->password,
                                    'phone' => $model->phone,
                                    'username_ext' => isset($user_map->username_ext) ? $user_map->username_ext : NULL,
                                    'password_ext' => isset($user_map->password_ext) ? $user_map->password_ext : NULL,
                                    'rememberMe' => $model->rememberMe,
                                    'otp' => $user_otp->otp,
                                );
                                $data = http_build_query($data);

                                $data = self::encrypt($data, $this->aes_key, $this->algorithm);

                                return $this->redirect('index.php?r=user/loginCskh/otp&data=' . $data);
                            }else{
                                $model->addError('username', UserModule::t("Username is incorrect."));
                            }
                        }
                    }
                }
                $this->layout = '//layouts/login';
                // display the login form
                $this->render('/user/login', array('model' => $model, 'type' => $type));
            } else {
                $this->redirect(Yii::app()->controller->module->returnUrl);
            }
        }

        private function lastVisit()
        {
            $lastVisit            = User::model()->notsafe()->findByPk(Yii::app()->user->id);
            $lastVisit->lastvisit = time();
            $lastVisit->save();
        }

        public function actionOtp()
        {
            if (Yii::app()->user->isGuest) {
                $this->layout = '//layouts/login';
                $data_decrypt = $this->decrypt($_GET['data'], $this->aes_key, $this->algorithm);
                parse_str($data_decrypt, $data_parse_str);

                $model             = new UserLoginCskh();
                $model->username   = $data_parse_str['username'];
                $model->password   = $data_parse_str['password'];
                $model->phone      = $data_parse_str['phone'];
                $model->rememberMe = $data_parse_str['rememberMe'];
                if ($model) {
                    if (isset($_POST['UserLoginCskh']['otp'])) {
                        $model->attributes = $_POST['UserLoginCskh'];
                        $check_token_key   = OtpUser::model()->findByAttributes(array('username' => $model->username, 'otp' => $_POST['UserLoginCskh']['otp']));

                        if ($check_token_key) {
                            $identity = new UserIdentity($model->username, $model->password);
                            $identity->authenticate();
                            $duration = $_POST['UserLoginCskh']['rememberMe'] ? 3600 * 24 * 30 : 0; // 30 days
                            if (Yii::app()->user->login($identity, $duration)) {
                                if (isset($data_parse_str['username_ext']) && !empty($data_parse_str['username_ext'])) {
                                    $user                   = User::model()->findByAttributes(array('username' => $model->username));
                                    $user_map               = new UserMap();
                                    $user_map->user_id      = $user->id;
                                    $user_map->username_ext = $data_parse_str['username_ext'];
                                    $user_map->password_ext = isset($data_parse_str['password_ext']) ? $data_parse_str['password_ext'] : NULL;
                                    $user_map->login        = 1;
                                    $user_map->status       = $user->status;
                                    $user_map->last_login   = date('Y-m-d h:i:s');
                                    $user_map->save();
                                }
                                $this->lastVisit();
                                if (strpos(Yii::app()->user->returnUrl, '/index.php') !== FALSE) {
                                    $this->redirect(Yii::app()->controller->createUrl('/Site/index'));
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
                    return $this->redirect('index.php?r=user/loginCskh');
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

                return $this->redirect('index.php?r=user/loginCskh/otp&data=' . $data);
            }
            return $this->redirect('index.php?r=user/loginCskh');
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


    }