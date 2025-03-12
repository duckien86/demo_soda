<?php

    class ALogMtController extends AController
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout        = '//layouts/column1';
        public $defaultAction = 'admin';

        public $msisdn; // Số điên thoại tra cứu
        public $type_msisdn; // Loại tin MO/MT
        public $start_date;
        public $end_date;

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
                'rights', // perform access control for CRUD operations
            );
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $form                    = new ReportForm();
            $model                   = new ReportOci();
            $form_validate           = new ReportForm();
            $form->scenario          = 'logMT';
            $form_validate->scenario = 'logMT';

            $data = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                $model->type_msisdn = 2;
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->msisdn = $model->msisdn = $form_validate->msisdn = isset($_POST['ReportForm']['msisdn']) ? CFunction_MPS::makePhoneNumberStandard($_POST['ReportForm']['msisdn']) : '';
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }

                if ($form_validate->validate()) {
                    $data = $model->getMtLog();
                    $data = new CArrayDataProvider($data, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                "ReportForm[start_date]" => $model->start_date,
                                "ReportForm[end_date]"   => $model->end_date,
                                "ReportForm[msisdn]"     => $model->msisdn,
                            ),
                            'pageSize' => 30,
                        ),
                    ));

                } else {
                    $form_validate->getErrors();
                }

            }

            return $this->render('momt_log', array(
                'model'         => $model,
                'form'          => $form,
                'form_validate' => $form_validate,
                'data'          => $data,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return ABanners the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ABanners::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ABanners $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'abanners-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
    }
