<?php

    class ACustomersController extends Controller
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout           = '//layouts/column2';
        public $changestatus_key = "qanahanahannnbvgtyijhuij12345432";

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
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
            $data     = array();
            $customer = ACustomers::model()->findByAttributes(array('id' => $id));
            if ($customer) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "sso_id='" . $customer->sso_id . "'";

                $data = new CActiveDataProvider(APointHistory::model(), array(
                    'criteria' => $criteria,
                ));
            }
            $this->render('view', array(
                'model'              => $this->loadModel($id),
                'data_history_point' => $data,
            ));
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new ACustomers;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['ACustomers'])) {
                $model->attributes = $_POST['ACustomers'];
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

            if (isset($_POST['ACustomers'])) {
                $model->attributes = $_POST['ACustomers'];
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
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('ACustomers');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new ACustomers('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['ACustomers']))
                $model->attributes = $_GET['ACustomers'];

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
         * @return ACustomers the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ACustomers::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ACustomers $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'acustomers-form') {
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
            $id     = Yii::app()->request->getParam('id', FALSE);
            $status = Yii::app()->request->getParam('status', FALSE);

            $model = ACustomers::model()->findByPk($id);
            if ($model) {
                $model->status = $status;
                $data          = array(
                    'username' => $model->username,
                    'status'   => $model->status,
                );
                $data          = http_build_query($data);
                $data          = self::encrypt($data, $this->changestatus_key, MCRYPT_RIJNDAEL_128);
                if (Yii::app()->getBaseUrl(TRUE) == 'http://10.2.0.107:8694/') {
                    $url = "http://10.2.0.107:8694/sso/changestatus?data=" . $data;
                } else {
                    $url = Yii::app()->getBaseUrl(TRUE) . "/sso/changestatus?data=" . $data;
                }
                $data = Utils::cUrlGet($url, 15, $http_status);
                if ($data == '1') {
                    if ($model->update()) {
                        $result = TRUE;
                    }
                }
            }
            echo $result;
            exit();
        }

        public function safe_b64encode($string)
        {
            $data = base64_encode($string);
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function encrypt($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = $this->safe_b64encode(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }

    }
