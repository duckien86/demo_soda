<?php

class APrepaidtopostpaidController extends AController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
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
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('admin'),
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
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new APrepaidToPostpaid('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if (isset($_REQUEST['APrepaidToPostpaid'])){
            $model->attributes = $_REQUEST['APrepaidToPostpaid'];
            $model->start_date = $_REQUEST['APrepaidToPostpaid']['start_date'];
            $model->end_date   = $_REQUEST['APrepaidToPostpaid']['end_date'];

            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }
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
     * @return APrepaidToPostpaid the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = APrepaidToPostpaid::model()->findByPk($id);
        if ($model === NULL) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param ACardStore $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'acardstore-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionReport(){
        $model = new APrepaidToPostpaid('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if (isset($_REQUEST['APrepaidToPostpaid'])){
            $model->attributes = $_REQUEST['APrepaidToPostpaid'];
            $model->start_date = $_REQUEST['APrepaidToPostpaid']['start_date'];
            $model->end_date   = $_REQUEST['APrepaidToPostpaid']['end_date'];
            
            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }
        }

        $this->render('report', array(
            'model' => $model,
        ));
    }

    public function actionReportSynthetic(){
        $model = new APrepaidToPostpaid('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if (isset($_REQUEST['APrepaidToPostpaid'])){
            if(isset(Yii::app()->session['ptpReportSynthetic_start_date']) && isset(Yii::app()->session['ptpReportSynthetic_end_date'])){
                unset(Yii::app()->session['ptpReportSynthetic_start_date']);
                unset(Yii::app()->session['ptpReportSynthetic_end_date']);
            }

            $model->attributes = $_REQUEST['APrepaidToPostpaid'];
            $model->start_date = $_REQUEST['APrepaidToPostpaid']['start_date'];
            $model->end_date   = $_REQUEST['APrepaidToPostpaid']['end_date'];

            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }
            Yii::app()->session['ptpReportSynthetic_start_date'] = $start_date;
            Yii::app()->session['ptpReportSynthetic_end_date'] = $end_date;
        }else{
            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));
            Yii::app()->session['ptpReportSynthetic_start_date'] = $start_date;
            Yii::app()->session['ptpReportSynthetic_end_date'] = $end_date;
        }


        $this->render('report_synthetic', array(
            'model' => $model,
        ));
    }

    public function actionGetPtpContent()
    {
        $result = '';
        if(Yii::app()->request->isAjaxRequest){
            $id = $_POST['APrepaidToPostpaid']['id'];
            $model = APrepaidToPostpaid::model()->findByPk($id);
            if($model){
                $result = $this->renderPartial('/aPrepaidtopostpaid/_confirm', array(
                    'model' => $model,
                ), TRUE);
            }
        }
        echo $result;
        Yii::app()->end();
    }

    public function actionApprovePtp()
    {
        $result = '';
        if(Yii::app()->request->isAjaxRequest){
            $id = $_POST['APrepaidToPostpaid']['id'];
            $model = APrepaidToPostpaid::model()->findByPk($id);
            if($model){

                $response_code = 1;
                $response_msg = 'Duyệt đơn thành công! Hệ thống đang tiến hành xử lí';

                if($model->status == APrepaidToPostpaid::PTP_APPROVE){
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $model->status = APrepaidToPostpaid::PTP_PROCESSING;
                    $model->receive_date = date('Y-m-d H:i:s');
                    $model->user_id = Yii::app()->user->name;
                    $model->save(false);
                }
                $result = $this->renderPartial('/aPrepaidtopostpaid/_confirm', array(
                    'model' => $model,
                    'response_code' => $response_code,
                    'response_msg' => $response_msg,
                ), TRUE);
            }
        }
        echo $result;
        Yii::app()->end();
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
//    public function actionDelete($id)
//    {
//        $this->loadModel($id)->delete();
//
//        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//        if(!isset($_GET['ajax']))
//            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//    }
}
