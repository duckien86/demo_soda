<?php

    class TestController extends CController
    {
        public function init()
        {
            if (isset($_REQUEST) && count($_REQUEST) > 0) {
                $p = new CHtmlPurifier();
                foreach ($_REQUEST as $k => $v) {
                    $_REQUEST[$k] = $p->purify($v);
                }
            }
        }

        /**
         * Basic http authentication
         */
        private function _checkAuth()
        {
            if (!isset($_SERVER['PHP_AUTH_USER']) || ($_SERVER['PHP_AUTH_USER'] != 'centech_test')
                || !isset($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_PW'] != '@centech1503')) {
                header('WWW-Authenticate: Basic realm="https://freedoo.vnpt.vn/ Authentication System"');
                header('HTTP/1.0 401 Unauthorized');
                echo "You must enter a valid login ID and password to access this page\n";
                exit();
            }
        }

        public function actionIndex()
        {
            $this->_checkAuth();
            CVarDumper::dump($_SERVER, 10, TRUE);
            die;
        }
    } //end class