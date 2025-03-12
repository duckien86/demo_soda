<?php

class AFTReportController extends AController
{

    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
//            $this->pageTitle     = 'Query Builder';
    }

    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }


    /**
     * Báo cáo tổng quan.
     */
    public function actionIndex()
    {
        $model = new AFTReport();
        $model->scenario = 'search';
        $data  = array();
        $data_detail = array();

        if(isset($_REQUEST['AFTReport'])){
            $model->attributes = $_REQUEST['AFTReport'];

            if($model->validate()){
                $data = $model->searchStatistic(TRUE);
                $data_detail = $model->searchStatisticDetail(TRUE);
            }
        }

        return $this->render('index', array(
            'model'         => $model,
            'data'          => $data,
            'data_detail'   => $data_detail
        ));
    }


    /**
     * Báo cáo doanh thu sim du lịch
     */
    public function actionRenueve()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'search';
        $data = array();
        $data_details = array();

        if (isset($_REQUEST['AFTReport'])) {
            $model->attributes = $_REQUEST['AFTReport'];

            if ($model->validate()) {
                $data = $model->searchRevenue();

                if($model->on_detail == 'on'){
                    $data_details = $model->searchRevenueDetail();
                }
            }

        }

        return $this->render('renueve', array(
            'model'         => $model,
            'data'          => $data,
            'data_details'  => $data_details,
        ));
    }

    /**
     * Lấy danh sách hợp đồng theo user.
     */
    public function actionGetContractByUsers()
    {

        $user_tourist = Yii::app()->getRequest()->getParam("user_tourist", FALSE);
        if ($user_tourist) {
            $contract = AFTContracts::model()->findAll('user_id =:user_id and status =:status',
                array(
                    ':user_id' => $user_tourist,
                    ':status'  => AFTContracts::CONTRACT_ACTIVE
                )
            );

            $data = CHtml::listData($contract, 'id', 'code');
            echo "<option value=''>Mã hợp đồng</option>";
            foreach ($data as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

    }

    /**
     * Lấy danh sách đơn hàng theo hợp đồng.
     */
    public function actionGetOrdersByContract()
    {

        $contract_id = Yii::app()->getRequest()->getParam("contract_id", FALSE);
        if ($contract_id) {
            $orders = AFTOrders::model()->findAll('contract_id =:contract_id',
                array(
                    ':contract_id' => $contract_id
                )
            );

            $data = CHtml::listData($orders, 'id', 'code');
            echo "<option value=''>Mã đơn hàng</option>";
            foreach ($data as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

    }

    public function actionGetOrdersCtvByUsers()
    {
        $user_tourist_ctv = Yii::app()->getRequest()->getParam("user_tourist_ctv", FALSE);
        if ($user_tourist_ctv) {
            $contract = AFTContracts::model()->find('user_id =:user_id',
                array(
                    ':user_id' => $user_tourist_ctv,
                )
            );
            $orders = AFTOrders::model()->findAll('contract_id =:contract_id',
                array(
                    ':contract_id' => $contract->id
                )
            );

            $data = CHtml::listData($orders, 'id', 'code');
            echo "<option value=''>Mã đơn hàng</option>";
            foreach ($data as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }
    }


    public function actionRemuneration()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'search';
        $data                   = array();
        $data_sim               = array();
        $data_detail_sim        = array();
        $data_package           = array();
        $data_detail_package    = array();
        $data_consume           = array();
        $data_detail_consume    = array();

        if(isset($_REQUEST['AFTReport'])){
            $model->attributes = $_REQUEST['AFTReport'];

            if($model->validate()){
                $data           = $model->searchRemuneration(TRUE);
                $data_sim       = $model->searchRemunerationSim(TRUE);
                $data_package   = $model->searchRemunerationPackage(TRUE);
                $data_consume   = $model->searchRemunerationConsume(TRUE);

                if($model->on_detail == 'on') {
                    $data_detail_sim = $model->searchRemunerationSimDetail(TRUE);
                    $data_detail_package    = $model->searchRemunerationPackageDetail(TRUE);
                    $data_detail_consume    = $model->searchRemunerationConsumeDetail(TRUE);
                }
            }
        }

        $this->render('remuneration', array(
            'model'                 => $model,
            'data'                  => $data,
            'data_sim'              => $data_sim,
            'data_detail_sim'       => $data_detail_sim,
            'data_package'          => $data_package,
            'data_detail_package'   => $data_detail_package,
            'data_consume'          => $data_consume,
            'data_detail_consume'   => $data_detail_consume,
        ));
    }


    public function actionGetListCustomer()
    {
        $result = '';
        if(isset($_POST['promo_code_prefix'])){
            $prefix = $_POST['promo_code_prefix'];
            $data_user = AFTUsers::getAllUserName($prefix, TRUE, 'invite_code');

            echo "<option value=''>Tất cả</option>";
            foreach ($data_user as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }
        Yii::app()->end();
    }


    public function actionSim(){
        $model = new AFTReport();
        $model->scenario = 'search_sim';
        $data  = array();

        if(isset($_REQUEST['AFTReport'])){
            $model->attributes = $_REQUEST['AFTReport'];

            if($model->validate()){
                $data = $model->searchSim();
            }
        }

        return $this->render('sim', array(
            'model'         => $model,
            'data'          => $data,
        ));
    }

}

?>