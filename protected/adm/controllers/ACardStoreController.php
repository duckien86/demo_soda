<?php

class ACardStoreController extends AController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'admin';
    public $dir_upload = 'cdata/cstore';

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
        $model = new ACardStore('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');
        $model->type_search_date = ACardStore::SEARCH_CREATE_DATE;

        if (isset($_REQUEST['ACardStore'])){
            $model->attributes = $_REQUEST['ACardStore'];
            $model->start_date = $_REQUEST['ACardStore']['start_date'];
            $model->end_date   = $_REQUEST['ACardStore']['end_date'];
            $model->type_search_date = $_REQUEST['ACardStore']['type_search_date'];

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
     * @return ACardStore the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ACardStore::model()->findByPk($id);
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

    public function actionGetUploadFileContent()
    {
        $result = array(
            'error' => '',
            'msg'   => '',
            'data_html'   => '',
        );
        $accept_file_ext = array('txt');
        $max_upload_size = 999 * 1024 * 1024;
        if ($uploadedFile = CUploadedFile::getInstanceByName('ACardStore[upload]')) {
            // get upload file info
            $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
            if (!in_array($ext, $accept_file_ext)) {
                $result['msg'] = Yii::t('adm/label','file_ext_invalid');
                $result['error'] = Yii::t('adm/label','accept_file_only', array('{ext}' => '.txt'));
            }
            if($uploadedFile->size > $max_upload_size){
                $result['msg'] = Yii::t('adm/label','upload_size_invalid');
                $result['error'] = Yii::t('adm/label','max_upload_size_error');
            }

            if(empty($result['error'])){
                $model = new ACardStore();
                $content = $model->getUploadFileContent($uploadedFile);
                $model->validateUploadFileContent($content);
                $result['msg'] = $model->upload_msg;
                $result['error'] = $model->upload_error;

                if(empty($result['error'])){
                    $result['msg'] = Yii::t('adm/label','card_file_valid');
                    $data = $content;
                }else{
                    $data=array();
                }

                $result['data_html'] = $this->renderPartial('/aCardStore/_table_card',array(
                    'data' => $data,
                ),true);
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }


    /**
     * action upload accepted_payment_files
     * ajax
     */
    public function actionUpload()
    {
        $result = array(
            'error' => '',
            'msg'   => '',
        );
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dir_upload = Yii::app()->params->upload_dir_path . $this->dir_upload;
        $DS = DIRECTORY_SEPARATOR;
        $time   = date('YmdHis');
        $accept_file_ext = array('txt');
        $max_upload_size = 999 * 1024 * 1024;

        if ($uploadedFile = CUploadedFile::getInstanceByName('ACardStore[upload]')) {
            $fileTemporary = $uploadedFile->tempName;
            // get upload file info
            $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
            if (!in_array($ext, $accept_file_ext)) {
                $result['msg'] = Yii::t('adm/label','file_ext_invalid');
                $result['error'] = Yii::t('adm/label','accept_file_only', array('{ext}' => '.txt'));
            }
            if($uploadedFile->size > $max_upload_size){
                $result['msg'] = Yii::t('adm/label','upload_size_invalid');
                $result['error'] = Yii::t('adm/label','max_upload_size_error');
            }

            if(empty($result['error'])){
                // validate
                $model = new ACardStore();
                $content = $model->getUploadFileContent($uploadedFile);
                $model->validateUploadFileContent($content);
                $result['msg'] = $model->upload_msg;
                $result['error'] = $model->upload_error;

                if(empty($result['error'])){
                    // save DB
                    $import_code = ACardStore::generateImportCode();
                    foreach ($content as $card){
                        $card->import_code = $import_code;
                    }
                    $insert = ACardStore::insertBatch($content);
                    if($insert > 0){
                        $result['msg'] = Yii::t('adm/label','upload_cstore_file_success');
                    }else{
                        $result['msg'] = Yii::t('adm/label','upload_cstore_file_failed');
                        $result['error'] = Yii::t('adm/label','error_save_data');
                    }

                    // save File
                    // init folder contain file
                    $destinationFolder = $dir_upload . $DS . $time . $DS;

                    // check and create folder;
                    if (!file_exists($destinationFolder)) {
                        mkdir($destinationFolder, 0777, TRUE);
                    }
                    $filename = $uploadedFile->name;
                    if(copy($fileTemporary, $destinationFolder . $filename)){
                        unlink($fileTemporary);
                    }else{
                        $result['msg'] = Yii::t('adm/label','upload_cstore_file_failed');
                        $result['error'] = Yii::t('adm/label','error_save_file');
                    }
                }
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }


    public function actionReport()
    {
        $model = new ACardStore('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');
        $model->type_search_date = ACardStore::SEARCH_CREATE_DATE;

        if (isset($_REQUEST['ACardStore'])){
            $model->attributes = $_REQUEST['ACardStore'];
            $model->start_date = $_REQUEST['ACardStore']['start_date'];
            $model->end_date   = $_REQUEST['ACardStore']['end_date'];
            $model->type_search_date = $_REQUEST['ACardStore']['type_search_date'];

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

}
