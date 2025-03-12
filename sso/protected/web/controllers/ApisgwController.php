<?php

    /**
     * Class ApisgwController
     *
     * @author : KienND + Duong.H
     */
    class ApisgwController extends Controller
    {

        /**
         * Tạo tài khoản cộng tác viên với hệ thống SV Ecom
         */
        public function init()
        {
            parent::init();

        }

        public function actionCreateUser()
        {
            $return_arr = [
                'ok'    => FALSE,
                'error' => 'invalid params',
                'code'  => "5",
            ];

            $request = new CHttpRequest();
            if ($request->isPostRequest) {

                $logMsg[] = array('Start SV-Ecom Log', 'Start proccess:', 'I', time());

                $logMsg[] = array(http_build_query($_POST), 'Params:' . __LINE__, 'T', time());
                // Giá trị khởi tại là TRUE nghĩa là ko được để trống
                $ary_post = [
                    'username'                => TRUE,
                    'password'                => TRUE,
                    'fullname'                => TRUE,
                    'email'                   => FALSE,
                    'personal_id'             => TRUE,
                    'phone'                   => TRUE,
                    'address'                 => TRUE,
                    'student_code'            => TRUE,
                    'bank_account'            => FALSE,
                    'bank_name'               => FALSE,
                    'bank_brand_office'       => FALSE,
                    'bank_owner_fullname'     => FALSE,
                    'personal_id_image_front' => TRUE,
                    'personal_id_image_back'  => TRUE,
                    'portrait_image'          => TRUE,
                    'province_code'           => TRUE,
                    'district_code'           => TRUE,
                    'ward_code'               => TRUE,
//                    'personal_id_issue_date'    => TRUE,
//                    'personal_id_issue_address' => TRUE,
                ];
                $flag     = TRUE;
                foreach ($ary_post as $key => $value) {
                    // kiểm tra biến hợp lệ
                    if ($ary_post[$key] == TRUE && (!isset($_POST[$key]) || empty($_POST[$key]))) {
                        echo $key . '/';
                        header('HTTP/1.1 404 ');
                        $return_arr['error'] = "$key is_empty";
                        $return_arr['code']  = "1";
                        $flag                = FALSE;
                        break;
                    }
                }
                if ($flag) {
                    // tạo user sso
                    $user              = new WUsers();
                    $user->scenario    = 'api';
                    $user->attributes  = $_POST;
                    $user->password    = CPasswordHelper::hashPassword($_POST['password']);
                    $user->token       = self::genTokenKey(32);
                    $user->invite_code = 'P' . self::genIntroduceKey(7);
                    $user->birthday    = date('Y-m-d');
                    $user->created_at  = date('Y-m-d h:i:s');
                    $user->updated_at  = date('Y-m-d h:i:s');
                    $user->cp_id       = '002';
                    $user->status      = WUsers::ACTIVE;
                    $user->type        = 1;
                    if ($user->validate()) {

                        $return_arr['ok']    = TRUE;
                        $return_arr['error'] = '';
                        $return_arr['code']  = "0";

                        // tạo cộng tác viên
                        $data_request = array(
                            'username'                  => $user->username,
                            'email'                     => $user->email,
                            'mobile'                    => $user->phone,
                            'sso_user_id'               => $user->id,
                            'personal_id'               => $_POST['personal_id'],
//                            'personal_id_issue_date'    => $_POST['personal_id_issue_date'],
//                            'personal_id_issue_address' => $_POST['personal_id_issue_address'],
                            'fullname'                  => $_POST['fullname'],
                            'date_of_birth'             => $user->birthday,
                            'personal_photo_font_url'   => $_POST['personal_id_image_front'],
                            'personal_photo_behind_url' => $_POST['personal_id_image_back'],
                            'resident_address'          => $_POST['address'],
                            'province_code'             => $_POST['province_code'],
                            'district_code'             => $_POST['district_code'],
                            'ward_code'                 => $_POST['ward_code'],
                            'address'                   => $_POST['address'],
                            'inviter_code'              => $user->invite_code,
                        );

                        $logMsg[] = array(CJSON::encode($data_request), 'Request_CTV:' . __LINE__, 'T', time());

                        $response = Utils::cUrlPostJson(Yii::app()->params['url_svecom'], CJSON::encode($data_request));

                        if ($response) {
                            $data_reponse = CJSON::decode($response);
                            $logMsg[]     = array($data_reponse['status'], 'Response_CTV:' . __LINE__, 'T', time());
                            $logMsg[]     = array($data_reponse['msg'], 'Response_CTV:' . __LINE__, 'T', time());
                            if ($data_reponse['status'] == 1) {
                                $user->save();
                            } else {
                                $return_arr['ok']    = FALSE;
                                $return_arr['error'] = $data_reponse['msg'];
                            }
                        } else {
                            $logMsg[] = array(CJSON::encode($response), 'Response_CTV:' . __LINE__, 'T', time());
                        }

                    } else {
                        $stt = 0;
                        foreach ($user->getErrors() as $key => $value) {
                            if ($stt == 0) {
                                $return_arr['error'] = "Ban gap phai cac loi: " . $value[0];
                            } else if ($key == 'phone') {
                                $return_arr['code'] = "4";
                                $return_arr['error'] .= ", " . $value[0];
                            } else if ($key == 'email') {
                                $return_arr['code'] = "3";
                                $return_arr['error'] .= ", " . $value[0];
                                $return_arr['error'] .= "email exists";
                            } else if ($key == 'username') {
                                $return_arr['code'] = "5";
                                $return_arr['error'] .= ", " . $value[0];
                            }
                            $stt++;
                        }
                    }
                }
                header('HTTP/1.1 200 OK');
                //

                $logMsg[] = array(http_build_query($return_arr), 'Response:' . __LINE__, 'T', time());

                $logMsg[] = array('create_user', 'Finish proccess', 'F', time());

                $logFolder = "sv_ecom/" . date("Y/m/d");
                $logObj    = SystemLog::getInstance($logFolder);
                $logObj->setLogFile('create_user.log');
                $logObj->processWriteLogs($logMsg);

            }
            header('Content-type: application/json');

            echo CJSON::encode($return_arr);
            exit();
        }

        /**
         * Basic http authentication
         *
         * @return bool
         */
        private function _checkAuth()
        {
            $username = 'centech_manhtv';
            $password = 'centech_@manhtv';
            if (!isset($_SERVER['PHP_AUTH_USER']) || ($_SERVER['PHP_AUTH_USER'] != $username) || ($_SERVER['PHP_AUTH_PW'] != $password)) {
                return FALSE;
            }

            return TRUE;

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
                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);
                $user          = WUsers::model()->findByAttributes(array('invite_code' => $randomString));
                if ($user) {
                    return self::genIntroduceKey($lengthChars);
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


    }