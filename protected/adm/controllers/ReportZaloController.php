<?php

class ReportZaloController extends AController{

    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
    }

    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex()
    {
        $model = new ReportZalo(FALSE);

        $data = array();
        $data_sim = array();
        $data_package = array();
        $data_consume = array();
        $data_detail_sim = array();
        $data_detail_package = array();
        $data_detail_consume = array();

        if(isset($_REQUEST['ReportZalo'])){
            $model->attributes = $_REQUEST['ReportZalo'];

            if($model->validate()){
                $data           = $model->searchRemuneration(TRUE);
//                $data_sim       = $model->searchRemunerationSim(TRUE);
//                $data_package   = $model->searchRemunerationPackage(TRUE);
                $data_consume   = $model->searchRemunerationConsume(TRUE);
                if($model->on_detail == 'on') {
//                    $data_detail_sim = $model->searchRemunerationSimDetail(TRUE);
//                    $data_detail_package    = $model->searchRemunerationPackageDetail(TRUE);
                    $data_detail_consume    = $model->searchRemunerationConsumeDetail(TRUE);
                }

            }
        }

        $this->render('index', array(
            'model' => $model,
            'data' => $data,
            'data_sim' => $data_sim,
            'data_package' => $data_package,
            'data_consume' => $data_consume,
            'data_detail_sim' => $data_detail_sim,
            'data_detail_package' => $data_detail_package,
            'data_detail_consume' => $data_detail_consume,
        ));
    }
}