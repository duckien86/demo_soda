<?php

    class LogoutCskhController extends Controller
    {

        public $defaultAction = 'logout';

        /**
         * Logout the current user and redirect to returnLogoutUrl.
         */
        public function actionLogout()
        {
//            $user_map = UserMap::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
//            if ($user_map) { // Chuyển trạng thái offline cho ktv.
//                $user_map->login = 0;
//                if ($user_map->update()) {
                    Yii::app()->user->logout();
                    $this->redirect(Yii::app()->controller->module->returnLogoutCskhUrl);
//                }
//            }
        }

    }