<?php

    class ProductsController extends Controller
    {
        public $layout = '/layouts/main';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
        }

        /**
         * This is the action to handle external exceptions.
         */
        public function actionError()
        {
            if ($error = Yii::app()->errorHandler->error) {
                if (Yii::app()->request->isAjaxRequest) {
                    echo $error['message'];
                } else {
                    $this->render('error', $error);
                }
            }
        }

        public function actionIndex()
        {
            $this->render('index', array());
        }

        /**
         * actionSearch
         */
        public function actionSearch()
        {
            if (isset($_REQUEST['WProducts']) || isset($_REQUEST['ajax'])) {
                $p          = new CHtmlPurifier();
                $keyword    = $p->purify($_REQUEST['WProducts']['keyword']);
                $rs         = new DataAdapter();
                $rs->msisdn = $keyword;
                $data       = $rs->search_msisdn();
                $this->render('search', array(
                    'data'    => $data,
                    'keyword' => $keyword
                ));
            }
        }
    } //end class