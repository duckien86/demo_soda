<?php

    class TrafficController extends AController
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout        = '//layouts/column2';
        public $defaultAction = 'admin';

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
                'rights',
            );
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         *
         * @return array access control rules
         */
        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('admin'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('admin', 'delete'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        /**
         * Displays a particular model.
         *
         * @param integer $id the ID of the model to be displayed
         */
        public function actionView($id)
        {
            $order_state   = CskhOrderState::getListOrderState($id, TRUE, 30);//order history
            $order_detail  = CskhOrderState::getDetailOrder($id);
            $order_shipper = CskhOrders::getShipperDetail($id);

            $this->render('view', array(
                'model'         => $this->loadModel($id),
                'order_state'   => $order_state,
                'order_detail'  => $order_detail,
                'order_shipper' => $order_shipper,
            ));
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new CskhTraffic();

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['CskhTraffic'])) {
                $model->attributes = $_POST['CskhTraffic'];
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }

            $this->render('create', array(
                'model' => $model,
            ));
        }

        /**
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id the ID of the model to be updated
         */
        public function actionUpdate($id)
        {
            $model = $this->loadModel($id);

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['CskhTraffic'])) {
                $model->attributes = $_POST['CskhTraffic'];
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }

            $this->render('update', array(
                'model' => $model,
            ));
        }

        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'admin' page.
         *
         * @param integer $id the ID of the model to be deleted
         */
        public function actionDelete($id)
        {
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('CskhTraffic');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new CskhTraffic('search');
            $model->unsetAttributes();  // clear any default values
            $model->scenario = "admin";
            $show            = TRUE;
            self::performAjaxValidation($model);
            if (isset($_POST['CskhTraffic'])) {
                $model->attributes = $_POST['CskhTraffic'];
                if ($_POST['CskhTraffic']['start_date'] != '' && $_POST['CskhTraffic']['end_date'] != '') {
                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhTraffic']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhTraffic']['end_date'])));
                }
                if (!$model->validate()) {
                    $model->getErrors();
                    $show = FALSE;
                }
            }
            if (isset($_GET['CskhTraffic'])) {

                $model->attributes = $_GET['CskhTraffic'];
                if ($_POST['CskhTraffic']['start_date'] != '' && $_POST['CskhTraffic']['end_date'] != '') {
                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['CskhTraffic']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['CskhTraffic']['end_date'])));
                }
            }


            $this->render('admin', array(
                'model' => $model,
                'show'  => $show,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AOrders the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = CskhTraffic::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AOrders $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'aorders-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        public function actionGetShipperByAddress()
        {
            $ward_code     = Yii::app()->getRequest()->getParam("ward_code", FALSE);
            $id            = Yii::app()->getRequest()->getParam("id", FALSE);
            $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
            $province_code = Yii::app()->getRequest()->getParam("province_code", FALSE);

            $criteria = new CDbCriteria();
            // Mức 1 theo phường xã
            if ($ward_code && $id) {
                $criteria->condition = "ward_code='" . $ward_code . "'";
                $data                = new CActiveDataProvider('Shipper', array(
                    'criteria' => $criteria,
                ));
                $result              = $this->renderPartial('_popup_assign_info', array('data' => $data, 'id' => $id));

                return $result;
            } // Mức 2 theo quận huyện
            else if ($district_code && $id) {

                $criteria->condition = "district_code='" . $district_code . "'";
                $data                = new CActiveDataProvider('Shipper', array(
                    'criteria' => $criteria,
                ));
                $result              = $this->renderPartial('_popup_assign_info', array('data' => $data, 'id' => $id));

                return $result;
            } // Mức 3 theo tỉnh
            else if ($province_code && $id) {

                $criteria->condition = "province_code='" . $province_code . "'";
                $data                = new CActiveDataProvider('Shipper', array(
                    'criteria' => $criteria,
                ));
                $result              = $this->renderPartial('_popup_assign_info', array('data' => $data, 'id' => $id));

                return $result;
            }


        }

        public function actionAssignmentShipper()
        {
            $shipper_id = Yii::app()->getRequest()->getParam("shipper_id", FALSE);
            $order_id   = Yii::app()->getRequest()->getParam("order_id", FALSE);
            $email      = Yii::app()->getRequest()->getParam("email", FALSE);
            $result     = 0;

            if ($shipper_id && $order_id && $email) {
                $model     = CskhShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
                $model_new = Orders::model()->findByAttributes(array('id' => $order_id));
                if (!$model) {
                    $model              = new CskhShipperOrder();
                    $model->type_assign = $model::ASSIGN;
                } else {
                    $model->type_assign = $model::CANCEL;
                }
                $model_new->shipper_id = $shipper_id;
                $model->shipper_id     = $shipper_id;
                $model->order_id       = $order_id;
                $model->assign_date    = date('Y-m-d h:i:s');
                $model->assign_by      = Yii::app()->user->id;
                $model->delivery_date  = date("Y-m-d h:i:s", strtotime(date("Y-m-d h:i:s") . '+2days'));
                $model->ship_cost      = 20000;
                $model->email          = $email;
                if ($model->validate()) {
                    if ($model->save() && $model_new->update()) {
                        $result = 1;
                    }
                }
            }

            echo $result;
        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {
            if (Yii::app()->user->checkAccess("PBH")) {
                if (Yii::app()->user->id) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->district_code != "") {
                            $district = District::model()->findByAttributes(array('code' => $user->district_code));
                            if ($district) {
                                echo CHtml::tag('option', array('value' => $user->district_code), CHtml::encode($district->name), TRUE);
                            }
                        }
                    }
                }
            } else {
                $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
                if ($provice_code) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "province_code='" . $provice_code . "'";

                    $data   = District::model()->findAll($criteria);
                    $return = CHtml::listData($data, 'code', 'name');
                    echo "<option>Chọn tất cả</option>";
                    foreach ($return as $k => $v) {
                        echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                    }
                }
            }
        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetWardByDistrict()
        {
            if (Yii::app()->user->checkAccess("DGD")) {
                if (Yii::app()->user->id) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->ward_code != "") {
                            $ward = Ward::model()->findByAttributes(array('id' => $user->ward_code));
                            if ($ward) {
                                echo CHtml::tag('option', array('value' => $user->ward_code), CHtml::encode($ward->name), TRUE);
                            }
                        }
                    }
                }
            } else {
                $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
                if ($district_code) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "district_code='" . $district_code . "'";

                    $data   = Ward::model()->findAll($criteria);
                    $return = CHtml::listData($data, 'id', 'name');
                    echo "<option>Chọn tất cả</option>";
                    foreach ($return as $k => $v) {
                        echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                    }
                }
            }
        }

        public function actionGetBrandOfficeByWard()
        {
            $ward_code = Yii::app()->getRequest()->getParam("ward_code", FALSE);
            if ($ward_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "ward_code='" . $ward_code . "'";

                $data   = BrandOffices::model()->findAll($criteria);
                $return = CHtml::listData($data, 'id', 'name');
                echo "<option>Chọn tất cả</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }
    }
