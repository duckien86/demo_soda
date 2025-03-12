<?php

    class AController extends RController
    {

        public $layout          = '//layouts/column1';
        public $menu            = array();
        public $breadcrumbs     = array();
        public $group_id;
        public $username;
        public $pageHint        = '';
        public $pageImage       = '';
        public $pageTitle       = '';
        public $pageKeyword     = '';
        public $pageDescription = '';

        public function init()
        {
            if(!isset(Yii::app()->user->agency) || empty(Yii::app()->user->agency)){
                Yii::app()->user->logout();
            }

            if (Yii::app()->user->checkAccess("Admin")) {
                define("SUPER_ADMIN", TRUE);
            } else {
                define("SUPER_ADMIN", FALSE);
            }
            if (Yii::app()->user->checkAccess("Admin_online")) {

                define("ADMIN", TRUE);
            } else {
                define("ADMIN", FALSE);
            }
            if (isset(Yii::app()->user->id)) {
                $auth = Authassignment::model()->findByAttributes(array('userid' => Yii::app()->user->id));
                if (isset($auth)) {
                    if ($auth->itemname == 'Admin_cskh') {
                        define("ADMIN_CSKH", TRUE);
                    } else {
                        define("ADMIN_CSKH", FALSE);
                    }
                } else {
                    define("ADMIN_CSKH", FALSE);
                }
            } else {
                define("ADMIN_CSKH", FALSE);
            }

            if (!isset(Yii::app()->user->province_code) || empty(Yii::app()->user->province_code)) {
                define("USER_NOT_LOCATE", TRUE);
            } else {
                define("USER_NOT_LOCATE", FALSE);
            }

            if (isset(Yii::app()->user->province_code)) {
                if (!empty(Yii::app()->user->province_code)) {
                    if (Yii::app()->user->id) {
                        $auth = Authassignment::model()->findByAttributes(array('userid' => Yii::app()->user->id));
                        if ($auth) {
                            if ($auth->itemname == 'PBH_DN') {
                                define("PBH_DN", TRUE);
                            } else {
                                define("PBH_DN", FALSE);
                            }
                        }
                    }
                }
            }

            if (isset(Yii::app()->user->province_code) && isset(Yii::app()->user->sale_offices_id) && isset(Yii::app()->user->brand_offices_id)) {
                if (!empty(Yii::app()->user->province_code) && !empty(Yii::app()->user->sale_offices_id) && empty(Yii::app()->user->brand_offices_id)) {
                    if (Yii::app()->user->id) {
                        $auth = Authassignment::model()->findByAttributes(array('userid' => Yii::app()->user->id));
                        if ($auth) {
                            if ($auth->itemname == 'PBH') {
                                define("PBH", TRUE);
                            } else {
                                define("PBH", FALSE);
                            }
                        }
                    }
                }else{
                    define("PBH", FALSE);
                }
            }

            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min.js');
            $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/global.js');

            Yii::$classMap = array_merge(Yii::$classMap, array(
                'CaptchaExtendedAction'    => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
                'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
            ));

            /*if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
                $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                $this->redirect($redirect);
            }*/
        }

        /**
         * @param CAction $action
         * Check action controller diffrent change-password and update-info.
         *
         * @return bool
         */
        public function beforeAction()
        {
            $data_action = array(
                'view', 'index', 'admin'
            );
            if (!in_array($this->getAction()->getId(), $data_action)) {
                $log             = new ABackendLogs();
                $log->username   = Yii::app()->user->name;
                $log->ipaddress  = $_SERVER['REMOTE_ADDR'];
                $log->logtime    = date("Y-m-d H:i:s");
                $log->controller = $this->getId();
                $log->action     = $this->getAction()->getId();
                $log->detail     = CJSON::encode($_REQUEST);
                $log->save();
            }

            return TRUE;
        }

        public function afterAction($action)
        {
            unset(Yii::app()->session['order_confirm_password']);
            if (Yii::app()->controller->id != 'aCheckout' && Yii::app()->controller->id != 'aCompleteOrders') {

                //check finish order
                if (AOrders::checkOrdersSessionExists(AOrdersData::BUY_SIM_AGENCY)) { //
                    $orders_data = Yii::app()->session['orders_data'];
                    $modelSim = $orders_data->sim;
                    if ($modelSim && isset($modelSim->msisdn) && !empty($modelSim->msisdn) && isset($modelSim->store_id) && !empty($modelSim->store_id)) {
                        echo '<script>displayWarning();</script>';
                    }
                }
            } else {
                $orders_data = Yii::app()->session['orders_data']->orders;
                if (Yii::app()->cache->get('createSim_' . $orders_data->id)) {
                    if (Yii::app()->controller->action->id != 'createSerialSim') {
                        if (AOrders::checkOrdersSessionExists()) {
                            echo '<script>displayWarning();</script>';
                        }
                    }
                }else if (Yii::app()->cache->get('registerSimCell_' . $orders_data->id)){
                    if (Yii::app()->controller->action->id != 'registerInfo') {
                        if (AOrders::checkOrdersSessionExists()) {
                            echo '<script>displayWarning();</script>';
                        }
                    }
                }
            }
            parent::afterAction($action);
        }


        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {
            $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            echo "<option value=''>".Yii::t('adm/label', 'district_code')."</option>";
            if ($provice_code) {
                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN || USER_NOT_LOCATE) {
                    $criteria->condition = "province_code='" . $provice_code . "'";
                } else {
                    if (Yii::app()->user->province_code != "") {
                        $criteria->condition = "province_code ='" . Yii::app()->user->province_code . "'";
                    }
                }
                $data   = District::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');

                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        /**
         * Lấy danh sách phường xã theo quận huyện.
         */
        public function actionGetWardByDistrict()
        {
            $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
            echo "<option value=''>".Yii::t('adm/label', 'ward_code')."</option>";
            if ($district_code) {
                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN || USER_NOT_LOCATE) {
                    $criteria->condition = "t.district_code='" . $district_code . "'";;
                } else {
                    if (Yii::app()->user->district_code != "") {
                        $criteria->condition = "district_code ='" . Yii::app()->user->district_code . "'";
                    }
                }
                $data   = Ward::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');

                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        public function actionGetBrandOfficeBySale()
        {
            $sale_offices_id = Yii::app()->getRequest()->getParam("sale_offices_id", FALSE);
            echo "<option value=''>".Yii::t('adm/label', 'sale_office')."</option>";

            if ($sale_offices_id) {
                $sale_offices = SaleOffices::model()->findByAttributes(array('id' => $sale_offices_id));

                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN || USER_NOT_LOCATE) {
                    if ($sale_offices) {
                        $criteria->condition = "head_office='" . $sale_offices->code . "'";
                    }
                } else {
                    if (Yii::app()->user->sale_offices_id != "") {
                        if ($sale_offices) {
                            $criteria->condition = "head_office ='" . $sale_offices->code . "'";
                        }
                    }
                }

                $criteria->compare('agency_id', Yii::app()->user->agency, FALSE);

                $data = BrandOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'id', 'name');
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }


        public function actionGetBrandOfficeBySaleCode()
        {
            $sale_offices_code = Yii::app()->getRequest()->getParam("sale_offices_code", FALSE);
            echo "<option value=''>".Yii::t('adm/label', 'brand_offices')."</option>";

            if ($sale_offices_code) {

                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN || USER_NOT_LOCATE
                    || !isset(Yii::app()->user->sale_offices_id) || empty(Yii::app()->user->sale_offices_id)
                ) {
                    $criteria->condition = "head_office='" . $sale_offices_code . "'";
                } else {
                    if (isset(Yii::app()->user->sale_offices_id) || !empty(Yii::app()->user->sale_offices_id)) {
                        $criteria->condition = "head_office ='" . $sale_offices_code . "'";
                    }
                }

                $criteria->compare('agency_id', Yii::app()->user->agency, FALSE);

                $data = BrandOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'id', 'name');
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }


        public function actionGetSaleOfficeByWard()
        {
            $ward_code = Yii::app()->getRequest()->getParam("ward_code", FALSE);
            echo "<option value=''>".Yii::t('adm/label', 'sale_office')."</option>";

            if ($ward_code) {
                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN || USER_NOT_LOCATE) {
                    $criteria->condition = "t.ward_code='" . $ward_code . "'";;
                } else {
                    if (Yii::app()->user->ward_code != "") {
                        $criteria->condition = "ward_code ='" . Yii::app()->user->ward_code . "'";
                    }
                }

                $criteria->compare('agency_id', Yii::app()->user->agency, FALSE);

                $data = SaleOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'id', 'name');
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        public function actionGetSaleOfficeByProvince()
        {
            $province_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            echo "<option value=''>".Yii::t('adm/label', 'sale_office')."</option>";

            if ($province_code) {
                $criteria         = new CDbCriteria();
                $criteria->select = "DISTINCT code, name";

                if (SUPER_ADMIN || ADMIN || USER_NOT_LOCATE) {
                    $criteria->condition = "province_code='" . $province_code . "'";;
                } else {
                    if (isset(Yii::app()->user->sale_offices_id) && Yii::app()->user->sale_offices_id != "") {
                        $criteria->condition = "code ='" . Yii::app()->user->sale_offices_id . "'";
                    } else {
                        $criteria->condition = "province_code='" . $province_code . "'";;
                    }
                }
                $criteria->compare('agency_id', Yii::app()->user->agency, FALSE);

                $data = SaleOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'code', 'name');

                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

    }

?>