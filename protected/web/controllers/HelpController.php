<?php

    class HelpController extends Controller
    {
        public $layout = '/layouts/main';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($detect->isMobile()) {
                $this->layout = '/layouts/mobile_main';
            }
            $this->pageImage       = 'http://' . SERVER_HTTP_HOST . Yii::app()->theme->baseUrl . '/images/slider1.jpg';
            $this->pageDescription = Yii::t('web/portal', 'page_description');
        }

        public function actionIndex($t = '')
        {
            $this->pageTitle = 'VNPT SHOP - Hỗ trợ';
            $cate            = WCategoryQa::getAllCateQa();
            $question        = new WQuestionAnswer();

            return $this->render('index',
                array(
                    'tab'      => $t,
                    'cate'     => $cate,
                    'question' => $question
                )
            );
        }

        public function actionSupportSell()
        {
            $pages           = new CPagination(3);
            $criteria        = new CDbCriteria();
            $pages->pageSize = 10;
            $pages->applyLimit($criteria);

            return $this->render('_support_sell', array('pages' => $pages));
        }

        public function actionSupportProduct()
        {
            $pages           = new CPagination(3);
            $criteria        = new CDbCriteria();
            $pages->pageSize = 10;
            $pages->applyLimit($criteria);

            return $this->render('_support_package', array('pages' => $pages));
        }

        /*
         * Show popup answer.
         */
        public function actionShowAnswer()
        {
            $result = FALSE;
            $id     = Yii::app()->request->getParam('id', FALSE);

            $question = WQuestionAnswer::model()->findByPk($id);
            $data     = $this->renderPartial('_popup_answer_item', array('question' => $question));
            echo $data;

            echo $result;
            exit();
        }
    } //end class