<?php

    class AController extends RController
    {

        public $layout      = '//layouts/column1';
        public $menu        = array();
        public $breadcrumbs = array();
        public $group_id;
        public $username;
        public $pageHint    = '';


        //public $channel_code = array();

        public function init()
        {
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
            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min.js');
            $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/global.js');

            Yii::$classMap = array_merge(Yii::$classMap, array(
                'CaptchaExtendedAction'    => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
                'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
            ));

//            if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
//                $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//
//                $this->redirect($redirect);
//            }
        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {
            $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            if ($provice_code) {
                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "province_code='" . $provice_code . "'";
                } else {
                    if (Yii::app()->user->province_code != "") {
                        $criteria->condition = "province_code ='" . Yii::app()->user->province_code . "'";
                    }
                }

                $data   = District::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option value=''>Chọn quận huyện</option>";
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
            if ($district_code) {
                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "t.district_code='" . $district_code . "'";;
                } else {
                    if (Yii::app()->user->district_code != "") {
                        $criteria->condition = "district_code ='" . Yii::app()->user->district_code . "'";
                    }
                }
                $data   = Ward::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option value=''>Chọn phường xã</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        public function actionGetBrandOfficeBySale()
        {
            $sale_offices_id = Yii::app()->getRequest()->getParam("sale_offices_id", FALSE);

            if ($sale_offices_id) {
                $sale_offices = SaleOffices::model()->findByAttributes(array('id' => $sale_offices_id));

                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN) {
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

                $data = BrandOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'id', 'name');
                echo "<option value=''>Chọn điểm giao dịch</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }


        public function actionGetBrandOfficeBySaleCode()
        {
            $sale_offices_code = Yii::app()->getRequest()->getParam("sale_offices_code", FALSE);

            if ($sale_offices_code) {

                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "head_office='" . $sale_offices_code . "'";
                } else {
                    if (Yii::app()->user->sale_offices_id != "") {
                        $criteria->condition = "head_office ='" . $sale_offices_code . "'";
                    }
                }

                $data = BrandOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'id', 'name');
                echo "<option value=''>Chọn điểm giao dịch</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }


        public function actionGetSaleOfficeByWard()
        {
            $ward_code = Yii::app()->getRequest()->getParam("ward_code", FALSE);
            if ($ward_code) {
                $criteria = new CDbCriteria();
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "t.ward_code='" . $ward_code . "'";;
                } else {
                    if (Yii::app()->user->ward_code != "") {
                        $criteria->condition = "ward_code ='" . Yii::app()->user->ward_code . "'";
                    }
                }

                $data = SaleOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'id', 'name');
                echo "<option value=''>Chọn phòng bán hàng</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        public function actionGetSaleOfficeByProvince()
        {
            $province_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            if ($province_code) {
                $criteria         = new CDbCriteria();
                $criteria->select = "DISTINCT code, name";

                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "province_code='" . $province_code . "'";;
                } else {
                    if (Yii::app()->user->sale_offices_id != "") {
                        $criteria->condition = "code ='" . Yii::app()->user->sale_offices_id . "'";
                    } else {
                        $criteria->condition = "province_code='" . $province_code . "'";;
                    }
                }

                $data = SaleOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'code', 'name');
                echo "<option value=''> Chọn phòng bán hàng</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        public function actionGetSaleOfficeIdByProvince()
        {
            $province_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            if ($province_code) {
                $criteria         = new CDbCriteria();
                $criteria->select = "DISTINCT code, name";
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "province_code='" . $province_code . "'";;
                } else {
                    if (Yii::app()->user->province_code != "") {
                        $criteria->condition = "province_code ='" . Yii::app()->user->province_code . "'";
                    }
                }

                $data = SaleOffices::model()->findAll($criteria);

                $return = CHtml::listData($data, 'í', 'name');
                echo "<option value=''>Chọn phòng bán hàng</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }
    }

?>