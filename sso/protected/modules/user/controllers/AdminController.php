<?php

    class AdminController extends Controller
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
            $dataProvider = new CActiveDataProvider('User', array(
                'pagination' => array(
                    'pageSize' => Yii::app()->controller->module->user_page_size,
                ),
            ));

            $this->render('index', array(
                'dataProvider' => $dataProvider,
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
            if (isset($_POST['User'])) {

                $model->save();

                $model->attributes = $_POST['User'];
                $model->activkey   = Yii::app()->controller->module->encrypting(microtime() . $model->password);
                $model->createtime = time();
                $model->lastvisit  = time();
                $model->parent_id  = Yii::app()->user->id;
                $p                 = new CHtmlPurifier();
                $model->username   = $p->purify($model->username);
                $model->email      = $p->purify($model->email);

                $profile->attributes = $_POST['Profile'];
                $profile->user_id    = 0;
                if ($model->validate() && $profile->validate()) {
                    $model->password = Yii::app()->controller->module->encrypting($model->password);
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
        public function actionUpdate()
        {
            $model   = $this->loadModel();
            $profile = $model->profile;
            if (isset($_POST['User'])) {

                $p                             = new CHtmlPurifier();
                $_POST['Profile']['firstname'] = $p->purify($_POST['Profile']['firstname']);
                $_POST['Profile']['lastname']  = $p->purify($_POST['Profile']['lastname']);
                $model->attributes             = $_POST['User'];
                $profile->attributes           = $_POST['Profile'];

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
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         */
        public function actionDelete()
        {
            if (Yii::app()->request->isPostRequest) {
                // we only allow deletion via POST request
                $model = $this->loadModel();
//                CVarDumper::dump($model,10,true);
//                $profile = Profile::model()->findByPk($model->id);
//                $profile->delete();
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
        public function loadModel()
        {
            if ($this->_model === NULL) {
                if (isset($_GET['id']))
                    $this->_model = User::model()->notsafe()->findbyPk($_GET['id']);
                if ($this->_model === NULL)
                    throw new CHttpException(404, 'The requested page does not exist.');
            }

            return $this->_model;
        }

    }