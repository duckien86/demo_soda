<?php

    class AUsersController extends Controller
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
                'accessControl', // perform access control for CRUD operations
                'postOnly + delete', // we only allow deletion via POST request
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
//            @todo : Thêm yii right
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view', 'createMutilUser'),
                    'users'   => array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update', 'createMutilUser'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('admin', 'delete', 'changeStatus', 'isAdmin', 'createMutilUser'),
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
            $model = new AUsers;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AUsers'])) {
                $model->attributes = $_POST['AUsers'];
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

            if (isset($_POST['AUsers'])) {
                $model->attributes = $_POST['AUsers'];
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
            $dataProvider = new CActiveDataProvider('AUsers');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new AUsers('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['AUsers']))
                $model->attributes = $_GET['AUsers'];

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
         * @return AUsers the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AUsers::model()->findByAttributes(array('id' => $id));
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AUsers $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'ausers-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        public function actionChangeStatus()
        {
            $result          = FALSE;
            $username        = Yii::app()->getRequest()->getParam('username', FALSE);
            $status          = Yii::app()->getRequest()->getParam('status', FALSE);
            $model           = AUsers::model()->findByAttributes(array('username' => $username));
            $model->scenario = 'changeStatus';
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

        public function actionIsAdmin()
        {
            $result          = FALSE;
            $username        = Yii::app()->getRequest()->getParam('username', FALSE);
            $is_admin        = Yii::app()->getRequest()->getParam('is_admin', FALSE);
            $model           = AUsers::model()->findByAttributes(array('username' => $username));
            $model->scenario = 'isAdmin';
            if ($model) {
                $model->is_admin = $is_admin;
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

        public function actionCreateMutilUser($number_start = 1, $number_end = 1000, $password = '')
        {
            set_time_limit(9999999);
            $data = array();
            $i    = (10 % 1000);
            for ($i = $number_start; $i <= $number_end; $i++) {
                if ($i < 10) {
                    $us_id = "000" . $i;
                } else if ($i >= 10 && $i < 100) {
                    $us_id = "00" . $i;
                } else if ($i >= 100 && $i < 1000) {
                    $us_id = "0" . $i;
                } else {
                    $us_id = 1000;
                }
                $data[$i][0] = array(
                    'id'          => AUsers::genId(32),
                    'username'    => 'ctv' . str_replace('.', '', ($us_id)),
                    'fullname'    => 'ctv' . str_replace('.', '', ($us_id)),
                    'email'       => 'ctv' . str_replace('.', '', ($us_id)) . '@vnpt.vn',
                    'phone'       => '091213' . str_replace('.', '', ($us_id)),
                    'password'    => CPasswordHelper::hashPassword('abc123456'),
                    'genre'       => 1,
                    'birthday'    => NULL,
                    'address'     => '',
                    'description' => '',
                    'status'      => '',
                    'token'       => '',
                    'created_at'  => date('Y-m-d'),
                    'updated_at'  => date('Y-m-d'),
                    'otp'         => self::genOtpKey(6),
                    'is_new'      => '',
                    'invite_code' => 'P' . self::genIntroduceKey(7),

                );
                $build[$i] = new CDbCommandBuilder(Yii::app()->db->schema);
                $command_create_user[$i] = $build[$i]->createMultipleInsertCommand('tbl_users', $data[$i]);
                $command_create_user[$i]->execute();
            }
            return TRUE;
        }

        /**
         * Cập nhật thông tin người dùng.
         */
        public function actionUpdateUser()
        {
            $data = Yii::app()->request->getParam('data', FALSE); // Lấy dữ liệu data

            $result = FALSE;

            if ($data) {
                $data_decrypt = self::decrypt($data, $this->changestatus_key, $this->algorithm);
                parse_str($data_decrypt, $data_parse_str);
                $data_key = array('username', 'status');
//                $checkExist = self::validateDecryptData($data_key, $data_parse_str);
                $checkExist = TRUE;

                if ($checkExist) {
                    $users = Users::model()->findByAttributes(array('username' => $data_parse_str['username']));

                    if ($users) {

                        $users->status = $data_parse_str['status'];
                        if ($users->save()) {
                            $result = TRUE;
                        }
                    }
                }
            }
            echo $result;
        }

        private function genOtpKey($lengthChars = 32)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $alphaString  = 'abcdefghijklmnopqrstuvwxyz';
                $numberString = '1234567890';

                $shuffleString = $alphaString . $numberString;
                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);
                $user          = AUsers::model()->findByAttributes(array('otp' => $randomString));
                if ($user) {
                    return $this->genOtpKey($lengthChars = 32);
                } else {
                    return $randomString;
                }
            }
        }
        /**
         * @param int $lengthChars
         *
         * Gen mã giới thiệu.
         *
         * @return bool|string
         */
        private function genIntroduceKey($lengthChars = 7)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $numberString  = '1234567890';
                $shuffleString = $numberString;
                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);
                $user          = AUsers::model()->findByAttributes(array('invite_code' => $randomString));
                if ($user) {
                    return $this->genOtpKey($lengthChars);
                } else {
                    return $randomString;
                }
            }
        }

//        public function actionDecrypt()
//        {
//            $data = CPasswordHelper::verifyPassword('123456Aa', '$2y$13$o1D5S6gDSstJxq8DGh6kIuaOag6yHEReEitiBYuddIkqSpW3D1JzG');
//            CVarDumper::dump($data, 10, TRUE);
//            die();
//        }

    }
