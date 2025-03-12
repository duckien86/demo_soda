<?php

    class ASurveyQuestionController extends AController
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
            $model = new ASurveyQuestion();
            $model->answer = array();

            // Uncomment the following line if AJAX validation is needed
//             $this->performAjaxValidation($model);

            $answer_error = false;
            if (isset($_POST['ASurveyQuestion'])) {
                $model->attributes = $_POST['ASurveyQuestion'];
                $model->answer = isset($_POST['ASurveyQuestion']['answer']) ? $_POST['ASurveyQuestion']['answer'] : array();

                $model->validate();
                if(!empty($model->answer)){
                    $index = 1;
                    $list_answer = array();
                    foreach($model->answer as $key => $answer_data){
                        $answer                 = new ASurveyAnswer();
                        $answer->question_id    = -1;
                        $answer->content        = $answer_data['content'];
                        $answer->type           = $answer_data['type'];
                        $answer->is_right       = isset($answer_data['is_right']) ? $answer_data['is_right'] : ASurveyAnswer::WRONG_ANSWER;
                        $answer->sort_order     = isset($answer_data['sort_order']) ? $answer_data['sort_order'] : 0;
                        $answer->status         = isset($answer_data['status']) ? $answer_data['status'] : ASurveyAnswer::ANSWER_ACTIVE;

                        if(!$answer->validate()){
                            $answer_error = true;
                        }
                        $list_answer[$index] = $answer;
                        $index++;
                    }
                    $model->answer = $list_answer;
                    if($answer_error){
                        $model->addError('answer', Yii::t('adm/label','empty_answer_content'));
                    }
                }else{
                    $model->addError('answer', Yii::t('adm/label','empty_answer'));
                }

                if(!$answer_error && !$model->hasErrors()){
                    if ($model->save(false)) {
                        foreach($model->answer as $key => $answer_data) {
                            $answer                 = new ASurveyAnswer();
                            $answer->question_id    = $model->id;
                            $answer->content        = $answer_data['content'];
                            $answer->type           = $answer_data['type'];
                            $answer->is_right       = isset($answer_data['is_right']) ? $answer_data['is_right'] : ASurveyAnswer::WRONG_ANSWER;
                            $answer->sort_order     = isset($answer_data['sort_order']) ? $answer_data['sort_order'] : 0;
                            $answer->status         = isset($answer_data['status']) ? $answer_data['status'] : ASurveyAnswer::ANSWER_ACTIVE;
                            $answer->save(false);
                        }
                        $this->redirect(array('admin'));
                    }
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
            $list_old_answer = ASurveyAnswer::getListAnswersByQuestionId($model->id);
            if(!empty($list_old_answer)){
                $index = 1;
                foreach ($list_old_answer as $answer){
                    $model->answer[$index] = $answer;
                    $index++;
                }
            }
//             Uncomment the following line if AJAX validation is needed
//             $this->performAjaxValidation($model);

            $answer_error = false;
            if (isset($_POST['ASurveyQuestion'])) {
                $model->attributes = $_POST['ASurveyQuestion'];
                $model->answer = isset($_POST['ASurveyQuestion']['answer']) ? $_POST['ASurveyQuestion']['answer'] : array();

//                CVarDumper::dump($model->answer,10,true);
//                die();


                $model->validate();
                if(!empty($model->answer)){
                    $index = 1;
                    $list_answer = array();
                    foreach($model->answer as $key => $answer_data){
                        $answer                 = new ASurveyAnswer();
                        $answer->id             = isset($answer_data['id']) ? $answer_data['id'] : null;
                        $answer->question_id    = $model->id;
                        $answer->content        = $answer_data['content'];
                        $answer->type           = $answer_data['type'];
                        $answer->is_right       = isset($answer_data['is_right']) ? $answer_data['is_right'] : ASurveyAnswer::WRONG_ANSWER;
                        $answer->sort_order     = isset($answer_data['sort_order']) ? $answer_data['sort_order'] : 0;
                        $answer->status         = isset($answer_data['status']) ? $answer_data['status'] : ASurveyAnswer::ANSWER_ACTIVE;

                        if(!$answer->validate()){
                            $answer_error = true;
                        }
                        $list_answer[$index] = $answer;
                        $index++;
                    }
                    $model->answer = $list_answer;
                    if($answer_error){
                        $model->addError('answer', Yii::t('adm/label','empty_answer_content'));
                    }
                }else{
                    $model->addError('answer', Yii::t('adm/label','empty_answer'));
                }

                if(!$answer_error && !$model->hasErrors()){
                    if ($model->save(false)) {
                        $list_old_answer_id = array();
                        foreach ($list_old_answer as $answer){
                            $list_old_answer_id[] = $answer->id;
                        }
                        foreach($model->answer as $key => $answer_data) {
                            $answer                 = new ASurveyAnswer();
                            $answer->id             = isset($answer_data['id']) ? $answer_data['id'] : null;
                            $answer->question_id    = $model->id;
                            $answer->content        = $answer_data['content'];
                            $answer->type           = $answer_data['type'];
                            $answer->is_right       = isset($answer_data['is_right']) ? $answer_data['is_right'] : ASurveyAnswer::WRONG_ANSWER;
                            $answer->sort_order     = isset($answer_data['sort_order']) ? $answer_data['sort_order'] : 0;
                            $answer->status         = isset($answer_data['status']) ? $answer_data['status'] : ASurveyAnswer::ANSWER_ACTIVE;
                            if($answer->id != null){
                                $answer->isNewRecord = false;
                                $answer->save(false);
                                $list_old_answer_id = array_diff( $list_old_answer_id, array($answer->id) );
                            }else{
                                $answer->save(false);
                            }
                        }

                        foreach ($list_old_answer as $old_answer){
                            if(in_array($old_answer->id,$list_old_answer_id)){
                                $old_answer->delete();
                            }
                        }
                        $this->redirect(array('admin'));
                    }
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
            $dataProvider = new CActiveDataProvider('ASurveyQuestion');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new ASurveyQuestion('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['ASurveyQuestion']))
                $model->attributes = $_GET['ASurveyQuestion'];

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
         * @return ASurveyQuestion the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ASurveyQuestion::model()->findByPk($id);
            if ($model === NULL){
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ASurveyQuestion $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'asurveyquestion-form') {
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
            $id     = Yii::app()->request->getParam('id');
            $status = Yii::app()->request->getParam('status');
            $model  = ASurveyQuestion::model()->findByPk($id);
            if ($model) {
                $model->status = $status;
                if ($model->update()) {
                    $result = TRUE;
                }
            }

            echo CJSON::encode($result);
            exit();
        }

        /**
         * Ajax get answer form
         */
        public function actionAddAnswer()
        {
            $result = '';
            if (Yii::app()->request->isAjaxRequest) {
                $index = (isset($_POST['index'])) ? $_POST['index'] : 1;
                $model = new ASurveyAnswer();
                $model->status = ASurveyAnswer::ANSWER_ACTIVE;
                $model->sort_order = $index;
                $result = $this->renderPartial('/aSurveyQuestion/_item_answer', array(
                    'model' => $model,
                    'index' => $index,
                ),TRUE);
            }
            echo $result;
            Yii::app()->end();
        }
    }
