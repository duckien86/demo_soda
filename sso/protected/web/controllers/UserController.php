<?php

    class UserController extends Controller
    {
        public $layout = '/layouts/mobile/main_no_footer';

        private $isMobile = FALSE;

        public function init()
        {

            parent::init();
            $cache_flush = Yii::app()->getRequest()->getQuery('cache', "");
            if (!empty($cache_flush)) {
                Yii::app()->cache->flush();
            }
        }

        /**
         * @return string form đăng nhập bằng sđt
         */
        public function actionLogin()
        {

            if (!Yii::app()->user->isGuest) {
                $this->redirect(array('site/index'));
            }
            $this->pageTitle = "Đăng nhập";

            return $this->render('mobile/login');
        }

        /**
         * Đăng ký gói cước qua mobile
         */
        public function actionRegister()
        {
            $this->pageTitle = "Đăng ký";

            return $this->render('mobile/register');
        }

        /**
         * Liệt kê thông tin về các gói cước hiện tại
         */
        public function actionService()
        {
            $this->pageTitle = "Thông tin";

            return $this->render('mobile/service');
        }

        /**
         * Hiển thị giao diện khi user login bằng facebook thành công
         */
        public function actionLoginFacebook()
        {
            $this->pageTitle = "Tài khoản";

            return $this->render('mobile/login_facebook');
        }

        /**Thông tin account user sau khi đăng nhập thành công
         *
         * @return string
         */
        public function actionAccount()
        {

            $this->layout = '/layouts/mobile/main_no_banner';

            $this->pageTitle = "Tài khoản";
            if (Yii::app()->user->isGuest) {
                $this->redirect(array('user/login'));
            }
            $user = new WUser();
            // danh sách video yêu thích
            $user_id        = Yii::app()->user->user_id;
            $video_favorite = $user->getVideoFavorite($user_id, WVideo::PAGE_INDEX);

            //danh sach video đề xuất
            $video_hot = $user->getVideoSuggestion(WVideo::PAGE_INDEX);

            $avatar = '';
//            $avatar_data = Yii::app()->cache->get("data_avatar_account_user" . Yii::app()->user->user_id);
//            if ($avatar_data) {
//                $avatar = $avatar_data['data_list']['0']['avatar'];
//            } else
//                if ($avatar_data == FALSE) {
            $rs_avatar              = new DataAdapter();
            $rs_avatar->primary_key = Yii::app()->user->user_id;
            $avatar_data            = $rs_avatar->user_data();
            if ($avatar_data['data_list']['0']['avatar']) {
                $avatar = $avatar_data['data_list']['0']['avatar'];

            }

            return $this->render('mobile/login_mobile', [
                'video_favorite' => $video_favorite,
                'video_hot'      => $video_hot,
                'avatar'         => $avatar,
            ]);
        }

//        public function actionVideoSuggestion
        public function actionAjaxVideoSuggestion()
        {
            if (Yii::app()->request->isAjaxRequest) {
                //lấy các param truyền lên từ view ajax
                //trang hiện tại
                $page = Yii::app()->request->getPost("page");
                $user = new WUser();
                $data = Yii::app()->cache->get("video_suggestion" . $page);
                if ($data == FALSE) {
                    $data = $user->getVideoSuggestion($page);
                    Yii::app()->cache->set("video_suggestion" . $page, $data, Yii::app()->params['time_cache']);
                }

                //truyền vào view ajax
                $html_data = $this->renderPartial(
                    '/user/mobile/_ajax_video_favorite',
                    [
                        'video_favorite' => $data
                    ],
                    TRUE
                );

//                truyền ra view
                echo CJSON::encode(array(
                    'status'     => 1,
                    'message'    => 'OK',
                    'data_html'  => $html_data,
                    'page_item'  => $data['page_item'],
                    'index'      => $data['index'],
                    'total_item' => $data['total_item'],
                    'data_list'  => $data['data_list']
                ));
            }
        }

        /**
         * ajax video Yêu thích
         */
        public function actionAjaxVideoFavorite()
        {
            if (Yii::app()->request->isAjaxRequest) {
                //lấy các param truyền lên từ view ajax
                //trang hiện tại
                $page    = Yii::app()->request->getPost("page");
                $user    = new WUser();
                $user_id = Yii::app()->user->user_id;
                $data    = $user->getVideoFavorite($user_id, $page);
                //truyền vào view ajax
                $html_data = $this->renderPartial(
                    '/user/mobile/_ajax_video_favorite',
                    [
                        'video_favorite' => $data
                    ],
                    TRUE
                );

//                truyền ra view
                echo CJSON::encode(array(
                    'status'     => 1,
                    'message'    => 'OK',
                    'data_html'  => $html_data,
                    'page_item'  => $data['page_item'],
                    'index'      => $data['index'],
                    'total_item' => $data['total_item'],
                    'data_list'  => $data['data_list']
                ));
            }
        }


        /**
         * Hiển thị giao diện khi user login bằng tài khoản SMS mobile
         */
        public function actionLoginMobile()
        {
            $this->pageTitle = "Tài khoản";

            return $this->render('mobile/login_mobile');
        }

        /**
         * Hiển thị giao diện khi user login bằng tài khoản vãng lai
         */
        public function actionLoginGuest()
        {
            $this->pageTitle = "Tài khoản";

            return $this->render('mobile/login_guest');
        }

        /**
         * Action nhận request Login qua Ajax
         */
        public function actionLoginAjax()
        {
            if (Yii::app()->request->isAjaxRequest) {
                $model                  = new WLoginForm();
                $array_return['status'] = 0;
                $model->username        = Yii::app()->getRequest()->getParam('username', '');
                $model->password        = Yii::app()->getRequest()->getParam('password', '');
                $model->rememberMe      = 1;
                if ($model->username != '') {
                    $model->username = self::convert_phone($model->username);
                }
                if ($model->validate()) {
                    if ($model->login($model->username, $model->password)) {
                        $array_return['status'] = 1;
                        $msg                    = 'Đăng nhập thành công';
                    } else {
                        $msg = 'Tài khoản hoặc mật khẩu không đúng';
                    }
                } else {
                    $msg = 'Tài khoản hoặc mật khẩu không đúng';
                }
            } else {
                $msg = 'Invalid Request';
            }
            $array_return['message'] = $msg;
            echo json_encode($array_return);
            Yii::app()->end();
        }

        /**
         * Convert phone number 84.
         *
         * @param $input
         *
         * @return string
         */
        public static function convert_phone($input)
        {
            if (preg_match("/0[0-9]{9,10}$/i", $input) == TRUE) {
                $return = '84' . substr($input, 1);

                return $return;
            } else if (preg_match("/^[0-9]{9,10}$/i", $input) == TRUE) {
                $return = '84' . $input;

                return $return;
            } else {
                return $input;
            }
        }

        /**
         * LogOUT
         */
        public function actionLogOut()
        {
            Yii::app()->user->logout();
            session_destroy();
            // unset cookies
            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach ($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name  = trim($parts[0]);
                    setcookie($name, '', time() - 1000);
                    setcookie($name, '', time() - 1000, '/');
                }
            }
            $this->redirect(Yii::app()->homeUrl);
        }

        public function actionFbcallback()
        {
            return $this->render('mobile/fb-callback');
        }

        public function actionChangeAvatar()
        {

            $id              = Yii::app()->getRequest()->getParam('id', 0);
            $new_avar        = Yii::app()->getRequest()->getParam('new_avar', FALSE);
            $rs              = new DataAdapter();
            $rs->primary_key = $id;
            $rs->avatar      = $new_avar;
            $rs->updateByAttributes(array('avatar' => $new_avar), $id);


        }
    } //end class
