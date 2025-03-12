<?php

    class QueryToolController extends Controller
    {
        private $msisdn_pre = '84';
        private $_ora;
        public function init()
        {

            parent::init();
            $this->_ora = Oracle::getInstance();
            $this->_ora->connect();
            $this->defaultAction = 'index';
            $this->pageTitle     = 'Query Builder';
        }

        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view', 'exportData'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update', 'admin'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('delete'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        public function filters()
        {
            return array(
                'accessControl', // perform access control for CRUD operations
            );
        }

        public function actionIndex()
        {
            $dataProvider = array();
            $option = Yii::app()->session['option'] = Yii::app()->request->getParam('option', 1);
            $db_type                                = Yii::app()->request->getParam('db_type', 'oracle');
            $op_limit                               = Yii::app()->request->getParam('oplimit', '40');
            if ($option)

                if (isset($_REQUEST['post']) || Yii::app()->request->isAjaxRequest) {
                    if (!Yii::app()->request->isAjaxRequest) {
                        Yii::app()->session['query_string'] = Yii::app()->request->getParam('query_string', '');

                    }
                    $result       = array();
                    if($db_type=='oracle'){
                        $stmt = oci_parse($this->_ora->oraConn, Yii::app()->session['query_string']);
                        oci_execute($stmt);
                        if($stmt && $option==1){
                            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                                $result[] = $entry;
                            }
                        }
                    }else {
                        if(empty($db_type)){
                            $db_type = 'db';
                        }
                        $result = Yii::app()->$db_type->createCommand(Yii::app()->session['query_string'])->queryAll();
                    }

                    Yii::app()->session['query_tool_result'] = $result;

                    $dataProvider = new CArrayDataProvider($result, array(
                        'id'         => 'user',
                        'sort'       => array(
                            'attributes' => array(
                                'id', 'username', 'email',
                            ),
                        ),
                        'keyField'   => FALSE,
                        'pagination' => ($op_limit == 'unlimit') ? FALSE : array(
                            'pageSize' => 40,
                        ),
                    ));
                }
            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'option'       => $option,
                'db_type'      => $db_type,
                'op_limit'     => $op_limit,
            ));

        }

        public function actionExportData()
        {
            if(isset(Yii::app()->session['query_tool_result'])){
                $result = Yii::app()->session['query_tool_result'];
                if(is_array($result)){
                    $arr_key = array();
                    $item_key = $result[0];

                    foreach ($item_key as $key => $value){
                        $arr_key[$key] = $key;
                    }
                    array_unshift($result, $arr_key);
                    $filename = 'query_tool_result' . date('YmdHis');

                    Utils::exportCSV($filename, $result);
                }else{
                    echo "Invalid Format";
                }
            }else{
                echo 'Timeout';
            }
        }

    }

?>