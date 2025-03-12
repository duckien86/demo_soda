<?php

class NewsController extends Controller
{

    public $layout = '/layouts/news';

    public $isMobile = FALSE;

    /**
     * @var int $item_per_page - số bài tin tức hiển thị mỗi trang
     */
    private $item_per_page = 10;

    public function init()
    {
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/mobile_news';
        }
        $this->pageImage       = 'http://' . SERVER_HTTP_HOST . Yii::app()->theme->baseUrl . '/images/slider1.jpg';
        $this->pageDescription = Yii::t('web/portal', 'page_description');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    public function actionIndex()
    {
        $this->pageTitle = 'VNPT SHOP - ' . Yii::t('adm/label', 'news');
        $page = 0;
        $limit = $this->item_per_page;
        $offset = $this->item_per_page * $page;
        //lấy danh sách tin tức đặc sắc - hiển thị trên slide
        $list_top = WNews::getListNewsByType(WNews::POSITION_HOT_NEWS, false, $limit, $offset);
        //lấy danh sách tin tức thường - hiển thị theo list
        $list_default = WNews::getListNewsByType(WNews::POSITION_NEWS, false, $limit, $offset);

        $total_page = WNews::getNewsTotalPage(WNews::POSITION_NEWS,$this->item_per_page);

        $this->render('index', array(
            'list_top' => $list_top,
            'list_default' => $list_default,
            'page' => $page,
            'total_page' => $total_page,
        ));
    }

    /**
     * @param int $page
     *
     * Ajax tải thêm tin tức thường
     */
    public function actionLoadmore($page = 1)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect(Yii::app()->baseUrl . '/news/index');
        }
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = intval($_GET['page']);
        }
        $return = '';
        $limit = $this->item_per_page;
        $offset = $this->item_per_page * $page;
        $list_default = WNews::getListNewsByType(WNews::POSITION_NEWS, false, $limit, $offset);
        if (!empty($list_default)) {
            $return = $this->renderPartial('_list_item_default', array(
                'list' => $list_default,
                'page' => $page
            ));
        }
        echo $return;
    }

    public function actionView($id)
    {
//        CVarDumper::dump($_REQUEST,10,true);
//        die("1");
        $model = WNews::model()->find('id=:id AND status= 1', array(':id' => $id));
        $list_related = WNews::getListRelatedNews($model, false, $this->item_per_page);

        $this->pageTitle = $model->title;
        $modelc = new WNewsComments;
        $data_comment = $modelc->getFetchComment($id);
        $data_comment_reply = $modelc->getReplyFetchComment($id);

        $this->render('view', array(
            'model' => $model,
            'list_related' => $list_related,
            'data_comment' => $data_comment,
            'data_comment_reply' => $data_comment_reply,
        ));
    }

}