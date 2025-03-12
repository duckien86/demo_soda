<?php

class NewscommentsController extends Controller
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
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'fetchcomment'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
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
        $error = '';
        $username = '';
        $email = '';
        $content = '';
        if (Yii::app()->request->getParam('username')) {
            $username = Yii::app()->request->getParam('username');
        } else {

            $error .= '<p class="text-danger">Tên không được để trống</p>';
        }
        if (Yii::app()->request->getParam('email')) {
            $email = Yii::app()->request->getParam('email');
        } else {

            $error .= '<p class="text-danger">Email không được để trống</p>';
        }
        if ((!filter_var(Yii::app()->request->getParam('email'), FILTER_VALIDATE_EMAIL)) && Yii::app()->request->getParam('email') !='') {
            $error .= '<p class="text-danger">Email không đúng định dạng</p>';
        }
        if (Yii::app()->request->getParam('content')) {
            $content = Yii::app()->request->getParam('content');
        } else {
            $error .= '<p class="text-danger">Nội dung không được để trống</p>';
        }
        if ($error == '') {
            $model = new WNewsComments;
            $model->news_id = Yii::app()->request->getParam('news_id');
            $model->ip = Yii::app()->request->getParam('ip');
            $model->comment_parent = Yii::app()->request->getParam('id');
            $model->username = $username;
            $model->email = $email;
            $model->content = $content;
            $model->status = 1;
            $model->created_on = Yii::app()->request->getParam('created_on');
            $model->save();
            $error .= '';
        }

        $data = array(
            'error' => $error
        );
        echo CJSON::encode($data);
        Yii::app()->end();
    }


    public function actionFetchcomment()
    {
        $model = new WNewsComments;
        $data = $model->getFetchComment();
        $output = '';
        foreach ($data as $row) {
            $output .= '
            <div class="panel panel-default">
            <div class="panel-heading">Đăng bởi <b>' . $row["username"] . '</b> Lúc <i>' . $row["created_on"] . '</i> </div>
             <div class="panel-body">' . $row["content"] . '</div>
             <div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id="' . $row['id'] . '">Trả lời</button> </div>
            </div>
            ';
        }
        echo $output;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['NewsComments'])) {
            $model->attributes = $_POST['NewsComments'];
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
        $dataProvider = new CActiveDataProvider('NewsComments');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new NewsComments('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['NewsComments']))
            $model->attributes = $_GET['NewsComments'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return NewsComments the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = NewsComments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param NewsComments $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'news-comments-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
