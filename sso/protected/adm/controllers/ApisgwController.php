<?php

    /**
     * Class ApisgwController
     *
     * @author : KienND + Duong.H
     */
    class ApisgwController extends AController
    {

        /**
         * Tạo tài khoản cộng tác viên với hệ thống SV Ecom
         */
        public function actionCreateUser()
        {
            $return_arr = [
                'ok'    => FALSE,
                'error' => 'invalid params'
            ];

            $request = new CHttpRequest();
            if ($request->isPostRequest) {
                $logMsg[] = array('Start SV-Ecom Log', 'Start proccess:', 'I', time());

                $logMsg[] = array(http_build_query($_POST), 'Params:' . __LINE__, 'T');
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
                ];
                $flag     = TRUE;
                foreach ($ary_post as $key => $value) {
                    // kiểm tra biến hợp lệ
                    if ($ary_post[$key] == TRUE && (!isset($_POST[$key]) || empty($_POST[$key]))) {
                        echo $key . '/';
                        header('HTTP/1.1 404 ');
                        $return_arr['error'] = "$key is_empty";
                        $flag                = FALSE;
                        break;
                    }
                }
                if ($flag) {
                    // tạo user sso
                    $user              = new AUsers();
                    $user->attributes  = $_POST;
                    $user->password    = CPasswordHelper::hashPassword($_POST['password']);
                    $user->token       = Utils::genTokenKey(32);
                    $user->invite_code = 'P' . Utils::genIntroduceKey(7);
                    $user->birthday    = date('Y-m-d');
                    $user->created_at  = date('Y-m-d');
                    $user->updated_at  = date('Y-m-d');
                    $user->cp_id       = '002';
                    if ($user->save()) {
                        $return_arr['ok']    = TRUE;
                        $return_arr['error'] = '';
                    } else {
                        $return_arr['error'] = $user->getErrors();
                    }

                    // tạo cộng tác viên
                }
                header('HTTP/1.1 200 OK');
                //
                $logMsg[] = array(http_build_query($return_arr), 'Response:' . __LINE__, 'T');

                $logMsg[]  = array('', 'Finish proccess', 'F', time());
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


    }