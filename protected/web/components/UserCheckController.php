<?php

    class UserCheckController extends Controller
    {



        public function init()
        {

            parent::init();
            if (isset($_GET['cache'])) {
                Yii::app()->cache->flush();
            }
        }





    }
