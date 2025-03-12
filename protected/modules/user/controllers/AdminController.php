<?php

    class AdminController extends AController
    {
        public $defaultAction = 'admin';

        private $_model;

        /**
         * @return array action filters
         */
        public function filters()
        {
            return CMap::mergeArray(parent::filters(), array(
//			'accessControl', // perform access control for CRUD operations
                'rights', // perform access control for CRUD operations
            ));
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
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('admin', 'delete', 'create', 'update', 'view'),
                    'users'   => UserModule::getAdmins(),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new User();
            if(isset($_REQUEST['User'])){
                $model->attributes = $_REQUEST['User'];
            }

            return $this->render('index', array(
                'model'        => $model,
            ));
        }


        /**
         * Displays a particular model.
         */
        public function actionView()
        {
            $model = $this->loadModel();
            $this->render('view', array(
                'model' => $model,
            ));
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model   = new User;
            $profile = new Profile;

            $this->performAjaxValidation($model);
            if (isset($_POST['User'])) {

                $model->attributes = $_POST['User'];
                $model->activkey   = Yii::app()->controller->module->encrypting(microtime() . $model->password);
                $model->createtime = time();
                $model->lastvisit  = time();
                if ($model->parent_id == '') {
                    $model->parent_id = Yii::app()->user->id;
                }

                if (isset($_POST['User']['groupRole'])) {
                    if (!empty($_POST['User']['groupRole'])) {
                        if ($_POST['User']['groupRole'] == 'LeaderShift') {
                            $model->unit_id = isset($_POST['User']['unit_id']) ? $_POST['User']['unit_id'] : '';
                        } else if ($_POST['User']['groupRole'] == 'KTV') {
                            $model->unit_id = isset($_POST['User']['unit_id']) ? $_POST['User']['unit_id'] : '';
                        }
                    }
                }


                $p                   = new CHtmlPurifier();
                $model->username     = $p->purify($model->username);
                $model->email        = $p->purify($model->email);
                $profile->attributes = $_POST['Profile'];
                $model->regency      = isset($_POST['User']['regency']) ? $_POST['User']['regency'] : '';
                $profile->user_id    = 0;

                if ($model->validate() && $profile->validate()) {
                    $profile->birthday = date('Y-m-d', strtotime($profile->birthday));
                    $model->password   = Yii::app()->controller->module->encrypting($model->password);
                    if ($model->save()) {
                        $profile->user_id = $model->id;
                        $profile->save();
                    }
                    $this->redirect(array('/user/admin'));
                } else $profile->validate();
            }


            $this->render('create', array(
                'model'   => $model,
                'profile' => $profile,
            ));
        }

        /**
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         */
        public
        function actionUpdate()
        {
            $model   = $this->loadModel();
            $profile = $model->profile;
            if (!$profile) {
                $profile          = new Profile();
                $profile->user_id = $model->id;
            }
            if (isset($_POST['User'])) {

                $p                             = new CHtmlPurifier();
                $_POST['Profile']['firstname'] = $p->purify($_POST['Profile']['firstname']);
                $_POST['Profile']['lastname']  = $p->purify($_POST['Profile']['lastname']);
                $model->attributes             = $_POST['User'];
                $profile->attributes           = $_POST['Profile'];
                $model->regency                = isset($_POST['User']['regency']) ? $_POST['User']['regency'] : '';

                if (isset($_POST['User']['groupRole'])) {
                    if (!empty($_POST['User']['groupRole'])) {
                        if ($_POST['User']['groupRole'] == 'LeaderShift') {
                            $model->unit_id = isset($_POST['User']['unit_id']) ? $_POST['User']['unit_id'] : '';
                        } else if ($_POST['User']['groupRole'] == 'KTV') {
                            $model->unit_id = isset($_POST['User']['unit_id']) ? $_POST['User']['unit_id'] : '';
                        }
                    }
                }

                if ($model->validate() && $profile->validate()) {
                    $old_password = User::model()->notsafe()->findByPk($model->id);

                    if ($old_password->password != $model->password) {
                        $model->password = Yii::app()->controller->module->encrypting($model->password);
                        $model->activkey = Yii::app()->controller->module->encrypting(microtime() . $model->password);
                    }
                    $model->save();
                    $profile->save();

                    $this->redirect(array('view', 'id' => $model->id));
                } else $profile->validate();
            }

            $this->render('update', array(
                'model'   => $model,
                'profile' => $profile,
            ));
        }

        /**
         * Bảng làm việc cá nhân tổng quan.
         */
        public
        function actionProfileTable()
        {
            $model   = new Complain('search'); //Lịch hẹn gọi lại
            $model_c = new Categories('search'); // Chương trình OB
            $model_u = new UserMap('search'); // Danh sách khai thác viên đang online.
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['Complain'])) {
                $model->attributes = $_GET['Complain'];
            }
            if (isset($_GET['Categories'])) {
                $model_c->attributes = $_GET['Categories'];
            }
            if (isset($_GET['UserMap'])) {
                $model_u->attributes = $_GET['UserMap'];
            }
            $this->render('table_profile', array(
                'model'   => $model,
                'model_c' => $model_c,
                'model_u' => $model_u,
            ));
        }

        /*
         * Bảng làm việc cá nhân theo đơn vị.
         */
        public
        function actionProfileTableAjax()
        {
            $unit_id = Yii::app()->getRequest()->getParam('unit_id', 0);
            $model   = new Complain(); //Lịch hẹn gọi lại
            $model_c = new Categories(); // Chương trình OB
            $model_u = new UserMap(); // Danh sách khai thác viên đang online.
            if ($unit_id) {
                $data = $this->renderPartial('_table_profile_child',
                    array('model' => $model, 'model_c' => $model_c, 'model_u' => $model_u, 'unit_id' => $unit_id));
                echo $data;
            }
        }


        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         */
        public
        function actionDelete()
        {
            if (Yii::app()->request->isPostRequest) {
                // we only allow deletion via POST request
                $model = $this->loadModel();
//                CVarDumper::dump($model,10,true);
                $profile = Profile::model()->findByPk($model->id);
                if ($profile) {
                    $profile->delete();
                }
                $model->delete();
                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//                if (!isset($_POST['ajax']))
//                    $this->redirect(array('/user/admin'));
//            } else
//                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            }
        }


        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         */
        public
        function loadModel()
        {
            if ($this->_model === NULL) {
                if (isset($_GET['id']))
                    $this->_model = User::model()->findbyPk($_GET['id']);
                if ($this->_model === NULL)
                    throw new CHttpException(404, 'The requested page does not exist.');
            }

            return $this->_model;
        }


        /**
         * Performs the AJAX validation.
         *
         * @param User $model the model to be validated
         */
        protected
        function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        public
        function actionGetRegency()
        {
            $regency         = array();
            $brand_office_id = Yii::app()->getRequest()->getParam("brand_offices_id", "");

            if (!empty($brand_office_id)) {
                $regency = array(
                    'ADMIN' => 'Admin',
                    'STAFF' => 'Quản lý',
                );
            } else {
                $regency = array(
                    'ADMIN'      => 'Admin',
                    'STAFF'      => 'Quản lý',
                    'ACCOUNTANT' => 'Kế toán',
                );

            }
            echo "<option value=''>Chọn chức vụ</option>";
            foreach ($regency as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

    }