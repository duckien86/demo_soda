<?php

class SurveyController extends Controller
{
    public $layout = '/layouts/landingpage_survey';

    private $isMobile = FALSE;

    public $defaultAction = 'index';

    public function init()
    {
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/landingpage_survey';
        }
    }

    public function actionIndex($od)
    {
        // Lấy thông tin đơn hàng + Survey mới nhất
        $order = WOrders::model()->findByPk($od);
        $model = WSurvey::getNewestSurvey();
        if (!$order || !$model) {
            throw new CHttpException(404, Yii::t('web/portal', 'page_not_found'));
        }
        // Lấy danh sách câu hỏi
        $list_question = WSurveyQuestion::getListQuestionBySurveyId($model->id);
        $msg = '';
        $require_login = false;
        $return_home = false;
        $modelForm = new WSurveyReport();
        $modelForm->survey_id = $model->id;

        /**
         * Kiểm tra xem User hiện tại đã làm Survey chưa?
         * Làm rồi-> xóa session lưu dữ liệu
         * Chưa làm -> chấp nhận dữ liệu gửi đến
         */
        if (!WSurveyReport::isSurveyAvailableForCurrentUser($modelForm->survey_id, $order->id)) {
            $msg = Yii::t('web/portal', 'survey_done');
            $return_home = true;
            if (isset(Yii::app()->session['WSurveyReport'])) {
                unset(Yii::app()->session['WSurveyReport']);
            }
        } else {
            if (isset($_POST['WSurveyReport'])) {
                $modelForm->attributes = $_POST['WSurveyReport'];
                $modelForm->question = $_POST['WSurveyReport']['question'];
                Yii::app()->session['WSurveyReport'] = $modelForm;
            }
        }

        //Lấy dữ liệu Survey từ session
        if (isset(Yii::app()->session['WSurveyReport']) && !empty(Yii::app()->session['WSurveyReport'])) {
            $modelForm = Yii::app()->session['WSurveyReport'];
            $modelForm->clearErrors();
            //Kiểm tra người dùng đã trả lời hết các câu hỏi chưa?
            if ($this->validateSurveyReport($modelForm)) {
                //Kiểm tra đã đăng nhập chưa?
                if (isset(Yii::app()->user->sso_id) && !empty(Yii::app()->user->sso_id)) {
                    //Lưu Survey
                    if ($this->saveSurvey($model, $modelForm, $order)) {
                        $msg = '';
                        $return_home = true;
                        //Cộng điểm cho người dùng
                        if($model->limit > 0 && $model->point > 0){
                            $countSurveyDone = WSurveyReport::getSurveyDoneQuantity($model->id);
                            if ($countSurveyDone < $model->limit) {
                                $customer = WCustomers::model()->findByAttributes(array('sso_id' => Yii::app()->user->sso_id));
                                $customer->bonus_point += $model->point;
                                $customer->save(false);
                                $msg = Yii::t('web/portal', 'save_survey_success_msg_1', array('{point}' => number_format($model->point)));
                            }
                        }
                        if(empty($msg)){
                            $msg = Yii::t('web/portal', 'save_survey_success_msg_2');
                        }
                        unset(Yii::app()->session['WSurveyReport']);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'survey_require_login');
                    $require_login = true;
                }
            }
        }

        if ($model) {
            $this->render('index', array(
                'model'         => $model,
                'list_question' => $list_question,
                'modelForm'     => $modelForm,
                'msg'           => $msg,
                'require_login' => $require_login,
                'return_home'   => $return_home,
            ));
        } else {
            $this->redirect(Yii::app()->createUrl('site/index'));
        }
    }

    /**
     * @param $model WSurvey
     * @param $modelForm WSurveyReport
     * @param $order WOrders
     * @return bool
     */
    protected function saveSurvey($model, $modelForm, $order)
    {
        $list_survey_report = array();
        $modelForm->create_date = date("Y-m-d H:i:s");
        foreach ($modelForm->question as $question_id => $question_data) {
            if (!empty($question_data['check'])) {
                foreach ($question_data['check'] as $answer_id) {
                    $surveyReport = new WSurveyReport();
                    $surveyReport->user_id = Yii::app()->user->sso_id;
                    $surveyReport->order_id = $order->id;
                    $surveyReport->survey_id = $modelForm->survey_id;
                    $surveyReport->question_id = $question_id;
                    $surveyReport->answer_id = $answer_id;
                    if (isset($question_data['content'][$answer_id]) && !empty($question_data['content'][$answer_id])) {
                        $surveyReport->content = $question_data['content'][$answer_id];
                    }
                    $surveyReport->is_right = $question_data['is_right'][$answer_id];
                    $surveyReport->create_date = $modelForm->create_date;

                    if ($surveyReport->validate()) {
                        $list_survey_report[] = $surveyReport;
                    }
                }
            }
        }
        if (WSurveyReport::batchInsert($list_survey_report)) {
            return true;
        }
        return false;
    }

    /**
     * @param $modelForm WSurveyReport
     * @return bool
     */
    protected function validateSurveyReport($modelForm)
    {
        $question_count = 0;
        $answer_count = 0;
        foreach ($modelForm->question as $question_id => $question_data) {
            $question_count++;
            if (!empty($question_data['check'])) {
                $answer_count++;
            }
        }
        if ($question_count != $answer_count) {
            $modelForm->addError('question', 'Bạn vui lòng điền đầy đủ nội khảo sát!');
            return false;
        }
        return true;
    }

}