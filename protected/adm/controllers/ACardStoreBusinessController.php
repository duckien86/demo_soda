<?php

class ACardStoreBusinessController extends AController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'admin';
    public $dir_upload = 'cdata/cstorebuisness';

    public $dir_upload_2 = 'tourist/torders';

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
        $model = new ACardStoreBusiness('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');
        $model->type_search_date = ACardStoreBusiness::SEARCH_CREATE_DATE;

        if (isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];
            $model->start_date = $_REQUEST['ACardStoreBusiness']['start_date'];
            $model->end_date   = $_REQUEST['ACardStoreBusiness']['end_date'];
            $model->type_search_date = $_REQUEST['ACardStoreBusiness']['type_search_date'];

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
     * @return ACardStoreBusiness the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ACardStoreBusiness::model()->findByPk($id);
        if ($model === NULL) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param ACardStoreBusiness $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'acardstorebusiness-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetFileImportContent()
    {
        $result = array(
            'error' => '',
            'msg'   => '',
            'data_html'   => '',
        );
        $accept_file_ext = array('txt');
        $max_upload_size = 999 * 1024 * 1024;
        if ($uploadedFile = CUploadedFile::getInstanceByName('ACardStoreBusiness[upload]')) {
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
                $model = new ACardStoreBusiness();
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

                $result['data_html'] = $this->renderPartial('/aCardStoreBusiness/_table_card',array(
                    'data' => $data,
                ),true);
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }


    /**
     * ajax
     * action upload file import cards | lưu file nhập kho
     */
    public function actionUploadFileImport()
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
        $folder = 'import/';
        $object = AFTFiles::OBJECT_FILE_CARD_IMPORT;

        if ($uploadedFile = CUploadedFile::getInstanceByName('ACardStoreBusiness[upload]')) {
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
                $model = new ACardStoreBusiness();
                $content = $model->getUploadFileContent($uploadedFile);
                $model->validateUploadFileContent($content);
                $result['msg'] = $model->upload_msg;
                $result['error'] = $model->upload_error;

                if(empty($result['error'])){
                    // save DB
                    $import_code = ACardStoreBusiness::generateImportCode();
                    foreach ($content as $card){
                        $card->import_code = $import_code;
                    }
                    $insert = ACardStoreBusiness::insertBatch($content);
                    if($insert > 0){
                        $result['msg'] = Yii::t('adm/label','upload_cstorebusiness_file_success') . ". Mã lệnh nhập kho: $import_code";

                        if(YII_DEBUG != TRUE){
                            $msisdn = Yii::app()->user->phone;
                            $file  = 'card_store_business_import';
                            $msg = Yii::t('adm/mt_content','card_store_business_import_success', array('{import_code}' => $import_code));
                            $sendmt = OtpForm::sentMtVNP($msisdn,$msg,$file);
                        }

                    }else{
                        $result['msg'] = Yii::t('adm/label','upload_cstorebusiness_file_failed');
                        $result['error'] = Yii::t('adm/label','error_save_data');
                    }

                    // save File
                    // init folder contain file
                    $destinationFolder = $dir_upload . $DS . $folder . $DS. $time . $DS;

                    // check and create folder;
                    if (!file_exists($destinationFolder)) {
                        mkdir($destinationFolder, 0777, TRUE);
                    }
                    $filename = $uploadedFile->name;
                    if(copy($fileTemporary, $destinationFolder . $filename)){
                        unlink($fileTemporary);

                        //save model
                        $file = AFTFiles::model()->findByAttributes(array(
                            'object' => $object,
                            'object_id' => $import_code
                        ));
                        if (!$file) {
                            $file = new AFTFiles();
                            $file->object = $object;
                            $file->object_id = $import_code;
                        }
                        $file->file_name = explode('.', $uploadedFile->name)[0];
                        $file->file_ext = explode('.', $uploadedFile->name)[1];
                        $file->file_size = filesize($destinationFolder . $uploadedFile->name);
                        $file->folder_path = 'uploads/' . $this->dir_upload . $DS . $folder . $DS. $time . $DS;
                        $file->status = 0;
                        $file->save(false);

                    }else{
                        $result['msg'] = Yii::t('adm/label','upload_cstorebusiness_file_failed');
                        $result['error'] = Yii::t('adm/label','error_save_file');
                    }
                }
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }


    public function actionExport(){
        $model = new AFTOrders('search');
        $model->unsetAttributes();  // clear any default values
        $model->type = AFTOrders::TYPE_CARD;

        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if (isset($_REQUEST['AFTOrders'])){
            $model->attributes = $_REQUEST['AFTOrders'];
            $model->start_date = $_REQUEST['AFTOrders']['start_date'];
            $model->end_date   = $_REQUEST['AFTOrders']['end_date'];

            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }
        }

        $this->render('export', array(
            'model' => $model,
        ));
    }

    /**
     * Xuất thẻ
     */
    public function actionCreate()
    {
        $modelOrder             = new AFTOrders();
        $modelOrder->scenario   = 'order_card';
        $modelOrder->user_id    = Yii::app()->user->id;
        $modelOrder->type       = AFTOrders::TYPE_CARD;

        $modelDetail    = new AFTOrderDetails();
        $modelUser      = new UserLogin();
        $modelCard      = new ACardStoreBusiness();

        $contracts = array();
        $contract_details = array();

        if(isset($_POST['AFTOrders']) && isset($_POST['UserLogin'])){
            $modelOrder->attributes = $_POST['AFTOrders'];
            $modelOrder->customer = $_POST['AFTOrders']['customer'];
            $modelOrder->card = $_POST['AFTOrders']['card'];

            $modelUser->attributes = $_POST['UserLogin'];
            $modelUser->username = Yii::app()->user->name;
            $modelUser->phone    = Yii::app()->user->phone;

            if($modelOrder->customer){
                $contracts = AFTContracts::getListContractsByUser($modelOrder->customer);

                $customer = AFTUsers::model()->findByPk($modelOrder->customer);
                if($customer){
                    $modelOrder->orderer_name = $customer->company;
                    $modelOrder->receiver_name = $customer->fullname;
                    $modelOrder->orderer_phone = $customer->phone;
                }
            }
            if($modelOrder->contract_id){
                $contract_details = AFTContractsDetails::getListDetailsByContractId($modelOrder->contract_id);
            }
            $this->validateActiveUploadFile($modelOrder,'accepted_payment_files',null,array('jpg', 'jpeg', 'png', 'pdf'));
            $modelOrder->accepted_payment_files = -1;

            if($modelOrder->validate(null, false) && $modelUser->validate()){
                if($modelOrder->save()){
                    $modelOrder->code = AFTContracts::getContractCode($modelOrder->contract_id) . 'OD' . $modelOrder->id;
                    $modelOrder->status = AFTOrders::ORDER_CARD_CREATE;
                    $modelOrder->save(false);
                    $file_accept = $this->uploadFile($modelOrder, 'accepted_payment_files');

                    foreach ($contract_details as $detail){
                        $card = AFTPackage::model()->findByPk($detail->item_id);
                        $price = $card->price;
                        if($detail->price_discount_percent){
                            $price -= ($price * $detail->price_discount_percent /100);
                        }else if($detail->price_discount_amount){
                            $price -= $detail->price_discount_amount;
                        }

                        $order_detail = new AFTOrderDetails();
                        $order_detail->order_id = $modelOrder->id;
                        $order_detail->item_id  = $detail->item_id;
                        $order_detail->quantity = $detail->quantity;
                        $order_detail->price    = $price;
                        $order_detail->save();

                        ACardStoreBusiness::exportCard($card->price, $detail->quantity, $modelOrder->id, $modelOrder->customer);
                    }
                    $modelContact = AFTContracts::model()->findByPk($modelOrder->contract_id);
                    $modelContact->status = AFTContracts::CONTRACT_COMPLETE;
                    $modelContact->save();
                    $this->redirect(Yii::app()->createUrl('aCardStoreBusiness/export'));
                }
            }
        }

        $this->render('create',array(
            'modelOrder'    => $modelOrder,
            'modelDetail'   => $modelDetail,
            'modelUser'     => $modelUser,
            'modelCard'     => $modelCard,
            'contracts'     => $contracts,
            'contract_details' => $contract_details,
        ));

    }

    public function actionGetContractsByUser()
    {
        $return = "<option value=''>" . Yii::t('adm/label', 'select') . "</option>";
        if(isset($_POST['user_id'])){
            $user_id = $_POST['user_id'];
            $contracts = AFTContracts::getListContractsByUser($user_id);

            if(!empty($contracts)){
                $data = CHtml::listData($contracts,'id', 'code');
                foreach ($data as $key => $value) {
                    $return.= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
                }
            }
        }
        echo $return;
        Yii::app()->end();
    }

    public function actionGetOrdersByContract()
    {
        $return = "<option value=''>" . Yii::t('adm/label', 'select') . "</option>";
        if(isset($_POST['contract_id'])){
            $contract_id    = $_POST['contract_id'];
            $status         = AFTOrders::ORDER_CARD_CONFIRM;
            $type           = AFTOrders::TYPE_CARD;
            $order = AFTOrders::getListOrderByContract($contract_id, $type, $status);
            if(!empty($order)){
                $data = CHtml::listData($order,'id', 'code');
                foreach ($data as $key => $value) {
                    $return.= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
                }
            }
        }
        echo $return;
        Yii::app()->end();
    }

    public function actionGetContractsCards(){
        $result = array(
            'data_html' => ''
        );

        if(isset($_POST['contract_id'])){
            $contract_id = $_POST['contract_id'];
            $contract_details = AFTContractsDetails::getListDetailsByContractId(intval($contract_id));

            $result['data_html'] = $this->renderPartial('/aCardStoreBusiness/_table_card_export', array(
                'contract_details' => $contract_details,
            ), TRUE);
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }

    /**
     * @param $model AFTOrders
     * @param $attribute string
     * @param $size int
     * @param $ext  string | array
     * @return boolean
     */
    protected function validateActiveUploadFile($model, $attribute, $size, $ext)
    {
        $file = CUploadedFile::getInstance($model, $attribute);
        if($file){
            $model->$attribute = $file->name;
            if(!$size){
                $size = 999 * 1024 * 1024;
            }
            if($file->size > $size){
                $model->addError($attribute, Yii::t('tourist/label','upload_file_invalid_size'));
                return false;
            }
            $file_ext = pathinfo($file->name, PATHINFO_EXTENSION);
            if(is_array($ext)){
                if(!in_array($file_ext, $ext)){
                    $model->addError($attribute, Yii::t('tourist/label','upload_file_invalid_ext',array(
                        '{ext}' => implode(', ',$ext)
                    )));
                    return false;
                }
            }else{
                if($file_ext != $ext){
                    $model->addError($attribute, Yii::t('tourist/label','upload_file_invalid_ext',array(
                        '{ext}' => $ext
                    )));
                    return false;
                }
            }
        }else{
            $model->addError($attribute, 'File Ủy nhiệm chi chưa được chọn');
            return false;
        }
        return true;
    }

    /**
     * upload accepted_payment_files | lưu file ủy nhiệm chi
     *
     * @param $model AFTOrders
     * @param $attribute string
     * @return AFTFiles
     */
    protected function uploadFile($model, $attribute)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dir_upload = Yii::app()->params->upload_dir_path . $this->dir_upload_2;
        $time = date("Ymdhis");
        $DS = DIRECTORY_SEPARATOR;
        $folder = 'accepted_payment_files/';
        $object = AFTOrders::OBJECT_FILE_ACCEPT_PAYMENT;

        if ($uploadedFile = CUploadedFile::getInstance($model, $attribute)) {
            $fileTemporary = $uploadedFile->tempName;

            // init folder contain file
            $destinationFolder = $dir_upload . $DS . $folder . $DS. $time . $DS;

            // check and create folder;
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder, 0777, TRUE);
            }

            // copy temporary file to image file folder and delete in temporary folder
            $uploadedFile->saveAs($destinationFolder . $uploadedFile->name);

            //save model
            $file = AFTFiles::model()->findByAttributes(array(
                'object' => $object,
                'object_id' => $model->id
            ));
            if (!$file) {
                $file = new AFTFiles();
                $file->object = $object;
                $file->object_id = $model->id;
            }
            $file->file_name = explode('.', $uploadedFile->name)[0];
            $file->file_ext = explode('.', $uploadedFile->name)[1];
            $file->file_size = filesize($destinationFolder . $uploadedFile->name);
            $file->folder_path = 'uploads/' . $this->dir_upload_2 . $DS . $folder . $DS. $time . $DS;
            $file->status = 0;
            $file->save(false);

            return $file;
        }
        return null;
    }

    public function actionViewExport($id)
    {
        $model = AFTOrders::model()->findByPk($id);
        if($model == NULL){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $model_logs    = new AFTLogs();
        $model_details = new AFTOrderDetails();
        $this->render('view_export', array(
            'model'         => $model,
            'model_logs'    => $model_logs,
            'model_details' => $model_details,
        ));
    }


    public function actionReportImport()
    {
        $model = new ACardStoreBusiness('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if(isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];
            $model->start_date = $_REQUEST['ACardStoreBusiness']['start_date'];
            $model->end_date   = $_REQUEST['ACardStoreBusiness']['end_date'];

            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }

        }

        $this->render('report_import', array(
            'model' => $model,
        ));
    }

    public function actionReportExport(){
        $model = new ACardStoreBusiness('search_export');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');
        if(isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];
            $model->start_date = $_REQUEST['ACardStoreBusiness']['start_date'];
            $model->end_date   = $_REQUEST['ACardStoreBusiness']['end_date'];
            $model->order_code = $_REQUEST['ACardStoreBusiness']['order_code'];

            $model->validate();
//            if(empty($model->start_date)){
//                $model->addError('start_date', Yii::t('adm/label','empty_start_date'));
//            }
//            if(empty($model->end_date)){
//                $model->addError('end_date', Yii::t('adm/label','empty_start_date'));
//            }
//
//            if(!empty($model->start_date) && !empty($model->end_date)){
//                $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
//                $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));
//
//                if($start_date > $end_date){
//                    $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
//                }
//            }
        }

        $this->render('report_export', array(
            'model' => $model,
        ));
    }

    public function actionReportRemain()
    {
        $model = new ACardStoreBusiness('search');
        $model->unsetAttributes();  // clear any default values
        $model->create_date = date('d/m/Y');

        if(isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];
            if(empty($model->create_date)){
                $model->addError('create_date', 'Ngày tra cứu không được rỗng');
            }
        }

        $this->render('report_remain', array(
            'model' => $model,
        ));
    }

    public function actionReportSynthetic()
    {
        $model = new ACardStoreBusiness('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if(isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];
            $model->start_date = $_REQUEST['ACardStoreBusiness']['start_date'];
            $model->end_date   = $_REQUEST['ACardStoreBusiness']['end_date'];

            if(empty($model->start_date)){
                $model->addError('start_date', 'Ngày bắt đầu không được rỗng');
            }
            if(empty($model->end_date)){
                $model->addError('end_date', 'Ngày kết thúc không được rỗng');
            }

            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }
        }

        $data = $model->searchReportSynthetic(false);

        $this->render('report_synthetic', array(
            'model' => $model,
            'data' => $data,
        ));
    }

    public function actionReportCard()
    {
        $model = new ACardStoreBusiness('search');
        $model->unsetAttributes();  // clear any default values
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date   = date('d/m/Y');

        if(isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];
            $model->start_date = $_REQUEST['ACardStoreBusiness']['start_date'];
            $model->end_date   = $_REQUEST['ACardStoreBusiness']['end_date'];
            $model->order_code = $_REQUEST['ACardStoreBusiness']['order_code'];

            $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
            $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

            if($start_date > $end_date){
                $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
            }

        }

        $this->render('report_card', array(
            'model' => $model,
        ));
    }

    public function actionReportCardDetail($id)
    {
        $order = AFTOrders::model()->findByPk($id);
        if(!$order){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $user = AFTUsers::getUserByContract($order->contract_id);

        if($user && $user->user_type != AFTUsers::USER_TYPE_AGENCY){
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $model = new ACardStoreBusiness('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_REQUEST['ACardStoreBusiness'])){
            $model->attributes = $_REQUEST['ACardStoreBusiness'];

        }

        $model->order_id = $order->id;

        $this->render('report_card_detail', array(
            'model' => $model,
            'order' => $order,
        ));

    }

    public function actionConfirm()
    {
        $result = array(
            'msg' => '',
            'error' => false,
        );
        if(isset($_POST['order_id'])){
            $order_id = $_POST['order_id'];
            $order = AFTOrders::model()->findByPk($order_id);
            if($order && $order->status == AFTOrders::ORDER_CARD_CREATE){
                $order->status = AFTOrders::ORDER_CARD_PROCESSING;
                if($order->save()){
                    $log = new AFTLogs();
                    $log->object_name = AFTLogs::OBJECT_ORDER;
                    $log->object_id = $order_id;
                    $log->data_json_before = CJSON::encode(array('status' => AFTOrders::ORDER_CARD_PROCESSING));
                    $log->data_json_after  = CJSON::encode(array('status' => AFTOrders::ORDER_CARD_CONFIRM));
                    $log->create_time      = date('Y-m-d H:i:s');
                    $log->active_by        = Yii::app()->user->id;
                    $log->save();

                    $result['msg'] = 'Xác nhận đơn hàng thành công!';
                    $result['error'] = false;
                }else{
                    $result['msg'] = 'Xác nhận đơn hàng thất bại!';
                    $result['error'] = true;
                }
            }else{
                $result['msg'] = 'Không tìm thấy đơn hàng !';
                $result['error'] = true;
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }


    public function actionGetModalExportContent()
    {
        $result = array(
            'data_title' => '',
            'data_html' => ''
        );
        if(isset($_POST['order_id'])){
            $order_id = $_POST['order_id'];
            $order = AFTOrders::model()->findByPk($order_id);
            if($order){
                $details = AFTOrderDetails::getAllDetailByOrder($order_id);

                $result['data_title'] = $order->code;
                $result['data_html'] = $this->renderPartial('/aCardStoreBusiness/_modal_export_body', array(
                    'model' => $order,
                    'details' => $details
                ), TRUE);
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }

    /**
     * Cấp thêm thẻ cho đơn hàng gặp lỗi khi active thẻ
     */
    public function actionUpdate($order_id)
    {
        $modelOrder = AFTOrders::model()->findByPk($order_id);
        if(!$modelOrder || $modelOrder->status != AFTOrders::ORDER_CARD_FAIL){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $modelOrder->scenario   = 'order_card';

        $modelUser  = new UserLogin();
        $contract   = AFTContracts::model()->findByPk($modelOrder->contract_id);
        $details    = AFTOrderDetails::getAllDetailByOrder($order_id);
        $customer   = AFTUsers::getUserByContract($modelOrder->contract_id);

        if(isset($_POST['AFTOrders']) && isset($_POST['UserLogin'])){
            $modelOrder->attributes = $_POST['AFTOrders'];
            $modelOrder->card = $_POST['AFTOrders']['card'];
            $modelOrder->customer = $customer->id;

            $modelUser->attributes = $_POST['UserLogin'];
            $modelUser->username = Yii::app()->user->name;
            $modelUser->phone    = Yii::app()->user->phone;

            if($modelOrder->validate(null, false) && $modelUser->validate()){

                $modelOrder->status = AFTOrders::ORDER_CARD_PROCESSING;
                $modelOrder->save(false);

                AFTLogs::saveLogOrderStatus($modelOrder,AFTOrders::ORDER_CARD_FAIL, AFTOrders::ORDER_CARD_PROCESSING);

                foreach ($modelOrder->card as $value => $quantity){
                    ACardStoreBusiness::exportCard($value, $quantity, $modelOrder->id, $modelOrder->customer);
                }
                $this->redirect(Yii::app()->createUrl('aCardStoreBusiness/export'));
            }
        }

        $this->render('update',array(
            'modelOrder'    => $modelOrder,
            'modelUser'     => $modelUser,
            'contract'      => $contract,
            'customer'      => $customer,
            'details'       => $details,
        ));
    }
}
