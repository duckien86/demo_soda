<?php

    class SiteController extends AController
    {
        public function init()
        {
            parent::init();
            $this->pageTitle = Yii::app()->params->project_name;

        }


        public function actionIndex()
        {
            // B1: Check user login.
            if (Yii::app()->user->isGuest) { // Nếu chưa login.
                //B1.2: Nếu đăng nhập từ hệ thống cskh_vnpt.
                if (isset($_GET['username']) && !empty($_GET['username'])) {
                    $chekExist = self::checkUserMapExist($_GET['username']); // Kiểm tra tồn tại.
                    if ($chekExist) {
                        self::autoLogin($_GET['username']); // Tự động đăng nhập.
                    } else {   //Chuyển hướng về login nữa user chưa map.
                        if (isset($_GET['password'])) { // Password có thể truyền hoặc không.
                            $this->redirect(array('/user/loginCskh&map[username]=' . $_GET['username'] . "&map[password] =" . $_GET['password']));
                        } else {
                            $this->redirect(array('/user/loginCskh&map[username]=' . $_GET['username']));
                        }
                    }
                }
                $this->redirect(array('/user/loginCskh'));
            } else {
                $this->render('index');
            }
        }

        /**
         * Tự dộng login cho tài khoản đã từng map.
         */
        public function autoLogin($username_ext)
        {

            $user_map = UserMap::model()->findByAttributes(array('username_ext' => $username_ext));
            if ($user_map) {
                $user = User::model()->findByAttributes(array('id' => $user_map->user_id));
                if ($user) {
                    $identity = new UserIdentity($user->username, $user->password);
                    if ($identity->authenticateAutologin()) {
                        $user_map->login = UserMap::ONLINE;
                        $user_map->update();
                        $duration = 3600 * 24 * 30; // 30 days
                        Yii::app()->user->login($identity, $duration);
                    }
                }
            }
            $this->redirect(array('/user/admin/profileTable'));
        }

        /**
         * Kiểm tra user map đã tồn tại map hay chưa.
         */
        public function checkUserMapExist($username_ext)
        {
            $user = UserMap::model()->findByAttributes(array('username_ext' => $username_ext));
            if ($user) {
                return TRUE;
            }

            return FALSE;
        }

        // Uncomment the following methods and override them if needed
        /*
        public function filters()
        {
            // return the filter configuration for this controller, e.g.:
            return array(
                'inlineFilterName',
                array(
                    'class'=>'path.to.FilterClass',
                    'propertyName'=>'propertyValue',
                ),
            );
        }
    */
        public function actions()
        {
            // return external action classes, e.g.:
            return array(
                // captcha action renders the CAPTCHA image displayed on the contact page
                'captcha' => array(
                    'class'   => 'CaptchaExtendedAction',
                    'density' => 10,
                    'lines'   => 15
                    //'backColor'=>0xFFFFFF,
                ),
            );
        }

        /**
         * This is the action to handle external exceptions.
         */
        public function actionError()
        {

            if ($error = Yii::app()->errorHandler->error) {
                if (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else
                    $this->render('error', $error);
            }
        }
    }