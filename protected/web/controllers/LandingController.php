<?php

    class LandingController extends Controller
    {
        private $isMobile      = FALSE;
        public  $defaultAction = 'index';
        public  $layout        = 'landingpage_main';

        /**
         * verify captcha
         */
        public function actionIndex()
        {
            $this->pageTitle = 'VNPT SHOP - Xác thực người dùng';
            $msg             = Yii::t('web/portal', 'captcha_error');

            if (isset($_POST['btn_verify']) && $_POST['btn_verify']) {
                if (Utils::googleVerify(Yii::app()->params->secret_key)) {
                    Yii::app()->session['landing_verify'] = TRUE;
                    $this->redirect($this->createUrl('site/index'));
                }
            }

            $this->render('index', array(
                'msg' => $msg
            ));
        }
    } //end class