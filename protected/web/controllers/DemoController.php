<?php

    class DemoController extends Controller
    {
        public $layout = 'demo';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
        }

        public function actionDemo7()
        {
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($this->isMobile) {
                $src = Yii::app()->theme->baseUrl . '/images/demo7_m.jpg';
            } else {
                $src = Yii::app()->theme->baseUrl . '/images/demo7_pc.jpg';
            }
            $this->render('demo', array('src' => $src));
        }

        public function actionDemo8()
        {
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($this->isMobile) {
                $src = Yii::app()->theme->baseUrl . '/images/demo8_m.jpg';
            } else {
                $src = Yii::app()->theme->baseUrl . '/images/demo8_pc.jpg';
            }
            $this->render('demo', array('src' => $src));
        }

        public function actionDemo9()
        {
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($this->isMobile) {
                $src = Yii::app()->theme->baseUrl . '/images/demo9_m.jpg';
            } else {
                $src = Yii::app()->theme->baseUrl . '/images/demo9_pc.jpg';
            }
            $this->render('demo', array('src' => $src));
        }
    }