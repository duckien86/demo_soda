<?php

    class AImportExcelController extends AController
    {
        public  $layout = '//layouts/column2';
        private $root_path;

        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
            $this->pageTitle     = 'Import dữ liệu từ Excel';
            $this->root_path     = Yii::app()->basePath;
        }

        public function filters()
        {
            return array(
//                'accessControl', // perform access control for CRUD operations
                'rights', // perform access control for CRUD operations
            );
        }

        public function accessRules()
        {
            return array(
                array('allow', // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index'),
                    'users'   => array('*'),
                ),
                array('deny', // deny all users
                    'users' => array('*'),
                ),
            );
        }

        public function actionIndex()
        {

            $modelName = CHttpRequest::getParam('m');
            $redirect  = CHttpRequest::getParam('u');
            $cate_id   = CHttpRequest::getParam('cate_id', NULL);
            if (!$modelName) {
                Yii::app()->user->setFlash('success', 'Bạn phải chọn 1 Model');//success,error,notice
            } else {

                $model = new $modelName();

                $path = '../uploads/excel/';

                $uploadFile = CUploadedFile::getInstance($model, 'filename');
                if (isset($uploadFile)) {
                    Yii::import('ext.phpexcel.XPHPExcel');
                    XPHPExcel::createPHPExcel();
                    if (isset($cate_id)) {
                        $result = XPHPExcel::import($modelName, $path, $uploadFile, $cate_id);
                    } else {
                        $result = XPHPExcel::import($modelName, $path, $uploadFile);
                    }

                    if (isset($result['error'])) {
                        Yii::app()->user->setFlash('error', $result['error']);//success,error,notice
                        $this->redirect(array('/' . Yii::app()->controller->id, 'm' => $modelName));
                    } else {

                        Yii::app()->user->setFlash('success', $result['success']);//success,error,notice
                        $this->refresh();

                        $this->redirect(Yii::app()->session['userView' . Yii::app()->user->id . 'returnURL']);
                    }
                }
            }
            $this->render('_import_excel', array(
                'model'        => $model,
                'modelName'    => $modelName,
                'url_redirect' => $redirect,
                'cate_id'      => isset($cate_id) ? $cate_id : NULL,
            ));


        }

        public function actionExcelTemplate()
        {
            $modelName = CHttpRequest::getParam('m');
            $cate_id   = CHttpRequest::getParam('cate_id');

            if (!$modelName) {
                die;
            }

            $model        = new $modelName();
            $array_fields = Yii::app()->db->schema->getTable($model->tableSchema->name)->getColumnNames();


            Yii::import('ext.phpexcel.XPHPExcel');

            // Create new PHPExcel object
            $objPHPExcel = XPHPExcel::createPHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Centech")
                ->setLastModifiedBy("Centech")
                ->setTitle("Centech")
                ->setSubject("Centech")
                ->setDescription("Centech")
                ->setKeywords("Centech")
                ->setCategory("Centech");

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle($modelName);


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            if (isset($cate_id)) {
                $field_not_show = array(
                    'id',
                    'thumbnail',
                    'create_time',
                    'createtime',
                    'last_update',
                    'code',
                    'created_by',
                    'cp_id',
                    'approved_by',
                    'languages_id',
                    'categories_id',
                );
            } else {
                $field_not_show = array(
                    'id',
                    'thumbnail',
                    'create_time',
                    'createtime',
                    'last_update',
                    'code',
                    'created_by',
                    'cp_id',
                    'approved_by',
                    'languages_id',
                );
            }

            $row = 1; // 1-based index
            $col = 0;
            foreach ($array_fields as $field) {
                if (in_array($field, $field_not_show))
                    continue;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
                $col++;
            }

            foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(TRUE);
            }

//
//            $configs = "DUS800, DUG900+3xRRUS, DUW2100, 2xMU, SIU, DUS800+3xRRUS, DUG900+3xRRUS, DUW2100";
//
//            $objValidation = $objPHPExcel->getActiveSheet()->getCell('B5')->getDataValidation();
//            $objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
//            $objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
//            $objValidation->setAllowBlank(false);
//            $objValidation->setShowInputMessage(true);
//            $objValidation->setShowErrorMessage(true);
//            $objValidation->setShowDropDown(true);
//            $objValidation->setErrorTitle('Input error');
//            $objValidation->setError('Value is not in list.');
//            $objValidation->setPromptTitle('Pick from list');
//            $objValidation->setPrompt('Please pick a value from the drop-down list.');
//            $objValidation->setFormula1('"'.$configs.'"');
//            $objPHPExcel->setActiveSheetIndex(0);


// Save Excel 95 file
//            $callStartTime = microtime(TRUE);
//
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('populate.xls');

            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $modelName . '.xls"');
            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed
//            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            if ($objWriter->save('php://output')) {
                echo "Import dữ liệu thành công!";
            }
            exit;
        }
        /**
         * This is the action to handle external exceptions.
         */
    }

?>