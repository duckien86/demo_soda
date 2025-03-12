<?php

class WorldcupController extends Controller
{
    public $layout = '/layouts/landingpage_worldcup';

    private $isMobile = FALSE;

    public $defaultAction = 'index';

    public function init()
    {
        throw new CHttpException(404, 'Chương trình này hiện tại đang tạm dừng!');
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/m_landingpage_worldcup';
        }
    }

    public function actionIndex()
    {
        $this->pageTitle = 'Trúng thưởng cùng Freedoo';
        $modelMatch = new WWCMatch();
        $modelForm  = new WWCReport();
        $show = false;
        $save = false;

        $this->performAjaxValidation($modelForm);

        if(isset($_POST['WWCReport']) && isset($_POST['WWCMatch'])){
            $match_id       = $_POST['WWCMatch']['id'];
            $modelMatch     = WWCMatch::model()->findByPk($match_id);
            if($modelMatch){
                $modelForm->attributes      = $_POST['WWCReport'];
                $modelForm->match_id        = $modelMatch->id;

                $modelForm->name    = trim($modelForm->name);
                $modelForm->email   = trim($modelForm->email);
                $modelForm->phone   = trim($modelForm->phone);

                if($modelForm->validate()){
                    if($modelForm->save()){
                        $save = true;

                        $from       = 'Freedoo';
                        $to         = $modelForm->email;
                        $subject    = 'Dự đoán WorldCup - Trúng thưởng cùng Freedoo';
                        $short_des  = '';
                        $content    = $this->renderPartial('/worldcup/_mail', array(
                            'model' => $modelForm,
                            'match' => $modelMatch
                        ),TRUE);

                        if(Utils::sendEmail($from,$to,$subject,$short_des,$content, 'web.views.layouts')){

                        }
                    }
                }
            }
            $show = true;
        }


        $this->render('index',array(
            'modelMatch'    => $modelMatch,
            'modelForm'     => $modelForm,
            'show'          => $show,
            'save'          => $save,
        ));
    }


    /**
     * ajax lấy html form dự đoán kết quả trận đấu
     */
    public function actionGetMatchContent()
    {
        $result = array(
            'error' => '',
            'msg'   => '',
            'dataHtml' => '',
        );
        if(isset($_POST['id'])){
            $model = WWCMatch::model()->findByPk($_POST['id']);
            if($model){
                $result['dataHtml'] = $this->renderPartial('/worldcup/_modal_predict_content', array(
                    'modelMatch' => $model,
                    'modelForm'  => new WWCReport(),
                ),TRUE);
                $result['msg'] = 'success';
            }else{
                $result['error'] = 'Không tìm thấy trận đấu';
            }
        }
        echo CJSON::encode($result);
        Yii::app()->end();
    }

    public function actionReward()
    {
        $this->pageTitle = 'Thể lệ & giải thưởng';
        $this->render('reward');
    }

    public function actionWinners()
    {
        $this->pageTitle = 'Danh sách trúng thưởng';
        $model=new WWCReport('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['WWCReport'])){
            $_REQUEST['WWCReport'] = $_GET['WWCReport'];

        }
        if(isset($_REQUEST['WWCReport'])){
            $model->attributes=$_REQUEST['WWCReport'];
        }

        $this->render('winners',array(
            'model'=>$model,
        ));
    }

    public function actionSearchReport()
    {
        $result = '';
        if(Yii::app()->request->isAjaxRequest && isset($_REQUEST['WWCReport'])){
            $model = new WWCReport('search');
            $model->unsetAttributes();
            $model->attributes = $_REQUEST['WWCReport'];

            $result = $this->renderPartial('_table_winners',array('model'=>$model),TRUE);
        }
        echo $result;
        Yii::app()->end();
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'worldcup-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetListMatchByType()
    {
        $data = '';
        if(isset($_POST['type'])){
            $matches = WWCMatch::getAllMatch($_POST['type'], true);
            if(!empty($matches)){
                foreach ($matches as $match_id => $match_name){
                    $data.= "<option value='$match_id'>$match_name</option>";
                }
            }
        }
        echo $data;
        Yii::app()->end();
    }

}