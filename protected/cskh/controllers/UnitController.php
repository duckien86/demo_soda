<?php

    class UnitController extends Controller
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout = '//layouts/column2';

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
//			'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
                'rights', // perform access control for CRUD operations
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
                    'users'   => array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('@'),
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
            $this->render('view', array(
                'model' => $this->loadModel($id),
            ));
        }


        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new Unit;
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['Unit'])) {
                $model->attributes  = $_POST['Unit'];
                $model->create_date = date('Y-m-d');

                if ($model->save()) {
                    $this->redirect(array('view', 'id' => $model->id));
                }
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

            if (isset($_POST['Unit'])) {
                $model->attributes = $_POST['Unit'];
                if ($model->update())
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
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('Unit');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new Unit('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['Unit'])) {
                $model->attributes = $_GET['Unit'];
            }
            $this->render('admin', array(
                'model' => $model,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return Unit the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = Unit::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param Unit $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'unit-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }


        /**
         * Action change status
         */
        public function actionChangeStatus()
        {
            $result = FALSE;
            $id     = Yii::app()->getRequest()->getParam('id');
            $status = Yii::app()->getRequest()->getParam('status');
            $model  = $this->loadModel($id);
            if ($model) {
                $model->status = $status;
                if ($model->update()) {

                    $result = TRUE;
                    Yii::app()->user->setFlash('success', Yii::t('adm/label', 'alert_success'));
                } else {
                    $result = FALSE;
                    Yii::app()->user->setFlash('error', Yii::t('adm/label', 'alert_fail'));
                }
            }
            echo CJSON::encode($result);
            exit();
        }


        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {

            if (!SUPER_ADMIN && !ADMIN) {
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
        public function actionGetBrandOfficesByDistrict()
        {
            if (!SUPER_ADMIN && !ADMIN) {
                if (Yii::app()->user->id) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->brand_offices_id != "") {
                            $brand = BrandOffices::model()->findByAttributes(array('id' => $user->ward_code));
                            if ($brand) {
                                echo CHtml::tag('option', array('value' => $user->brand_offices_id), CHtml::encode($brand->name), TRUE);
                            }
                        }
                    }
                }
            } else {
                $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
                if ($district_code) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "district_code='" . $district_code . "'";

                    $data   = BrandOffices::model()->findAll($criteria);
                    $return = CHtml::listData($data, 'id', 'name');
                    echo "<option>Chọn tất cả</option>";
                    foreach ($return as $k => $v) {
                        echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                    }
                }
            }
        }

    }
