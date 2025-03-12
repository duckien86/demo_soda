<?php

class DownloadController extends AController
{
    public function init()
    {
        parent::init();
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', 'users' => array('@')),
            array('deny', 'users' => array('*')),
        );
    }


    public function actionIndex($folder, $file, $type)
    {
        $url = Yii::app()->baseUrl. "/../uploads/$folder/$file.$type";
        $file = $_SERVER['DOCUMENT_ROOT'] . $url;

        if(file_exists($file)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

}