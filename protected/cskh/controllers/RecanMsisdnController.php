<?php

    class RecanMsisdnController extends AController
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
            $form               = new RecanForm();// clear any default values
            $package_registered = $package_not_register = array();
            $history            = new CskhRegisterPackage();
            $msisdn             = '';
            $post               = 0;
            if (isset($_POST['RecanForm']) || isset($_GET['RecanForm'])) {

                if (empty($_POST['RecanForm'])) {
                    $_POST['RecanForm'] = $_GET['RecanForm'];
                }
                $form->attributes = $_POST['RecanForm'];

                if ($form->validate()) {
                    $post        = 1;
                    $data_input  = array(
                        'so_tb' => $form->msisdn,
                    );
                    $msisdn      = $form->msisdn;
                    $orders_data = new CskhOrders();
                    //call api lấy danh sách gói cước đã đăng ký.
                    $data_output = $orders_data->getListPackage($data_input);

                    if ($data_output) {
                        foreach ($data_output as $key => $value) {
                            if (!in_array($value['package_code'], $package_registered)) {
                                array_push($package_registered, $value['package_code']);
                            }
                        }
                        foreach ($data_output as $key => $value) {
                            if (!isset($value['status'])) {
                                $data_output[$key]['status'] = 'Hủy';
                            }
                        }
                    }
                    //Lấy dữ liệu gói cước chưa đăng ký.
                    $criteria = new CDbCriteria();

                    $criteria->condition = "type IN ('" . CskhPackage::PACKAGE_DATA . "',
                    '" . CskhPackage::PACKAGE_POSTPAID . "','" . CskhPackage::PACKAGE_PREPAID . "',
                    '" . CskhPackage::PACKAGE_VAS . "') and status=1";

                    $packages = CskhPackage::model()->findAll($criteria);
                    foreach ($packages as $key => $value) {
                        $package_not_register_key = array(
                            'package_name'      => '',
                            'package_code'      => '',
                            'short_description' => '',
                        );
                        if (!in_array($value->code, $package_registered)) {
                            $package_not_register_key['package_name']      = $value->name;
                            $package_not_register_key['package_code']      = $value->code;
                            $package_not_register_key['short_description'] = $value->short_description;
                            $package_not_register_key['status']            = 'Đăng ký';
                            $package_not_register[]                        = $package_not_register_key;
                        }
                    }
                    $package_not_register = new CArrayDataProvider($package_not_register, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'pageSize' => 30,
                            'params'   => array(
                                "RecanForm[msisdn]" => $form->msisdn,
                            ),
                        ),
                    ));

                    if ($data_output) {
                        $package_registered = new CArrayDataProvider($data_output, array(
                            'keyField'   => FALSE,
                            'pagination' => array(
                                'pageSize' => 30,
                                'params'   => array(
                                    "RecanForm[msisdn]" => $form->msisdn,
                                ),
                            ),
                        ));
                    }
                }
            }
            if (isset($_GET['CskhRegisterPackage'])) {
                $post                  = 1;
                $history->package_code = $_GET['CskhRegisterPackage']['package_code'];
            }
            $this->render('admin', array(
                'form'                 => $form,
                'package_registered'   => $package_registered,
                'package_not_register' => $package_not_register,
                'history'              => $history,
                'msisdn'               => $msisdn,
                'post'                 => $post,
            ));
        }

        /**
         * call api cancel package
         */
        public function actionCancelPackage()
        {
            $package_code = Yii::app()->request->getParam('package_code', FALSE);
            $msisdn       = Yii::app()->request->getParam('msisdn', FALSE);
            $result       = array(
                'status' => FALSE,
                'msg'    => Yii::t('web/portal', 'error_exception'),
            );

            if ($msisdn && $package_code) {
                $orders_data = new CskhOrders();
                $data_input  = array(
                    'msisdn'       => $msisdn,
                    'package_code' => $package_code,
                );

                //call api web_remove_package
                $data_output = $orders_data->cancelPackage($data_input);
                if (isset($data_output['code']) && $data_output['code'] == 1) {
                    $result['status'] = TRUE;
                    $result['msg']    = Yii::t('web/portal', 'cancel_package_success', array(
                        '{package_name}' => $data_input['package_code'],
                    ));
                } else {
                    if (isset($data_output['msg']) && !empty($data_output['msg'])) {
                        $result['msg'] = $data_output['msg'];
                    }
                }
            }
            if ($result['status']) {
                Yii::app()->user->setFlash('success', $result['msg']);
            } else {
                Yii::app()->user->setFlash('danger', $result['msg']);
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }

        public function actionRegisterPackage()
        {
            $package_code = Yii::app()->request->getParam('package_code', FALSE);
            $msisdn       = Yii::app()->request->getParam('msisdn', FALSE);
            $result       = array(
                'status' => FALSE,
                'msg'    => Yii::t('web/portal', 'error_exception'),
            );

            if ($msisdn && $package_code) {
                $orders_data = new CskhOrders();
                $package     = CskhPackage::model()->findByAttributes(array('code' => $package_code));
                $customers   = CskhCustomers::model()->findByAttributes(array('phone' => $msisdn));
                if ($package->type == CskhPackage::PACKAGE_REDEEM) {
                    if ($customers) {
                        if ($customers->bonus_point < $package->price) {
                            $result['status'] = FALSE;
                            $result['msg']    = 'Bạn không đủ điểm đổi quà!';
                            echo CJSON::encode($result);
                            Yii::app()->end();
                        }
                    }
                }
                $data_input = array(
                    'msisdn'       => $msisdn,
                    'package_code' => $package_code,
                    'type_package' => $package->type,
                    'price'        => $package->price,
                );
                //call api web_remove_packagep
                $data_output = $orders_data->registerPackage($data_input);

                if (isset($data_output['code']) && $data_output['code'] == 1) {
                    $result['status'] = TRUE;
                    $result['msg']    = Yii::t('web/portal', 'register_package_success', array(
                        '{package}' => $data_input['package_code'],
                        '{msisdn}'  => $data_input['msisdn'],
                    ));
                } else {
                    if (isset($data_output['msg']) && !empty($data_output['msg'])) {
                        $result['msg'] = $data_output['msg'];
                    }
                }
            }
            if ($result['status']) {
                Yii::app()->user->setFlash('success', $result['msg']);
            } else {
                Yii::app()->user->setFlash('danger', $result['msg']);
            }
            echo CJSON::encode($result);
            Yii::app()->end();
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

    }
