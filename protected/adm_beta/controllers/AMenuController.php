<?php

    class AMenuController extends AController
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
//                'accessControl', // perform access control for CRUD operations
//                'postOnly + delete', // we only allow deletion via POST request
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
            $model = new AMenu;
            $time  = date("Ymdhis");

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AMenu'])) {
                $model->attributes = $_POST['AMenu'];
                if (!is_dir(Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu)) {
                    mkdir(Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu, 0777, TRUE);
                }

                $uploadedFile = CUploadedFile::getInstance($model, 'icon');
                if (isset($uploadedFile) && $uploadedFile != NULL) {
                    $uploads_dir = str_replace('../', '', Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu);
                    $file_name   = $time . Utils::unsign_string($uploadedFile->name) . '.' . $uploadedFile->extensionName;
                    $model->icon = $uploads_dir . $file_name;
                    $uploadedFile->saveAs(realpath(Yii::app()->getBasePath() . '/' . Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu . '/') . '/' . $file_name);
                }

                if ($model->save())
                    $this->redirect(array('admin'));
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
            $model           = $this->loadModel($id);
            $time            = date("Ymdhis");
            $model->old_file = $model->icon;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AMenu'])) {
                $_POST['AMenu']['icon'] = $model->icon;
                $model->attributes      = $_POST['AMenu'];

                if (!is_dir(Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu)) {
                    mkdir(Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu, 0777, TRUE);
                }

                $uploadedFile = CUploadedFile::getInstance($model, 'icon');
                if (isset($uploadedFile) && $uploadedFile != NULL) {
                    $uploads_dir = str_replace('../', '', Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu);
                    $file_name   = $time . Utils::unsign_string($uploadedFile->name) . '.' . $uploadedFile->extensionName;
                    $model->icon = $uploads_dir . $file_name;
                    $uploadedFile->saveAs(realpath(Yii::app()->getBasePath() . '/' . Yii::app()->params->upload_dir_path . Yii::app()->params->upload_menu . '/') . '/' . $file_name);
                }

                if ($model->save()) {
                    $dir_old_file = '/../' . $model->old_file;
                    if (!empty($model->old_file) && ($model->old_file != $model->icon) && file_exists(realpath(Yii::app()->getBasePath() . $dir_old_file))) {
                        $model->cleanup($dir_old_file);
                    }
                    $this->redirect(array('admin'));
                }
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
            $model           = $this->loadModel($id);
            $model->old_file = $model->icon;
            $dir_old_file    = '/../' . $model->old_file;
            $this->loadModel($id)->delete();
            if (!empty($model->old_file) && file_exists(realpath(Yii::app()->getBasePath() . $dir_old_file))) {
                $model->cleanup($dir_old_file);
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('AMenu');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model     = new AMenu();
            $list_menu = $model->getListMenu();
            $data      = array();
            foreach ($list_menu as $item) {
                if (empty($item->parent_id)) {//parent
                    $data[$item->id] = array(
                        'label'       => CHtml::encode($item->name) . ' --> ' . CHtml::encode($item->target_link),
                        'url'         => '#',
                        'items'       => self::getChildArray($list_menu, $item->id),
                        'linkOptions' => array('onclick' => 'getFormMenu("' . $item->id . '", "' . $item->parent_id . '");')
                    );
                }
            }
            $this->render('admin', array(
                'data' => $data,
            ));
        }

        /**
         * @param      $array
         * @param null $parent_id
         *
         * @return array
         */
        private function getChildArray($array, $parent_id = NULL)
        {
            $data = array();
            foreach ($array as $item) {
                if ($item->parent_id == $parent_id) {
                    $data[$item->id] = array(
                        'label'       => CHtml::encode($item->name) . ' --> ' . CHtml::encode($item->target_link),
                        'url'         => '#',
                        'items'       => self::getChildArray($array, $item->id),
                        'linkOptions' => array('onclick' => 'getFormMenu("' . $item->id . '", "' . $item->parent_id . '");')
                    );
                }
            }

            return $data;
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AMenu the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AMenu::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AMenu $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'amenu-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * @throws CException
         */
        public function actionGetFormMenu()
        {
            $parent_id = Yii::app()->request->getParam('parent_id', '');
            $id        = Yii::app()->request->getParam('id', '');
            $model     = AMenu::model()->find('id=:id', array(':id' => $id));

            if (!$model) {
                $model            = new AMenu();
                $model->parent_id = $parent_id;
            }
            $content = $this->renderPartial('_form',
                array(
                    'model' => $model,
                ), TRUE
            );
            echo CJSON::encode(array('content' => $content));
            exit();
        }
    }
