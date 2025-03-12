<?php

/**
 * @Package:
 * @Author: nguyenpv
 * @Date: 9/8/15
 * @Time: 5:07 PM
 * index-import data
 */
class ToolController extends CController
{
    private $_authenUsername = 'CiTV_20!&';
    private $_authenPassword = 'CiTV_pAsS_@#20!&';

    private $_username = 'Tool';
    private $_password = 'Tool_20!7';

    public function init()
    {

        parent::init();
        CFunction::authentication($this->_authenUsername, $this->_authenPassword);
        $username = Yii::app()->request->getParam('username', '');
        $password = Yii::app()->request->getParam('password', '');
        if ($username != $this->_username || $password != $this->_password) {
            echo 'Incorrect username/password..';
            exit;
        }
    }

    public function actionIndex()
    {
        echo 'Tool..';
    }



    //index cho bảng media
    public function actionIndexMedia()
    {
        //exit;
        set_time_limit(0);
        $start_time = microtime(true);

        $esClient = new ElasticSearch();

        $total = Media::model()->count("status=:status", array(
            ":status"   =>  Media::ACTIVE
        ));
        $page = Yii::app()->request->getQuery('page', 1);
        $limit = Yii::app()->request->getQuery('limit', 1000);
        if (isset($_GET['limit']) && $_GET['limit'] <= 0) {
            $limit = 1000;
        }
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;
        $totalPage = ceil($total / $limit);
        echo 'Total row: ' . $total . '<br>';
        echo 'Limit: ' . $limit . '<br>';
        echo 'Page: ' . $page . '/' . $totalPage . '<br><hr><hr>';

        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->offset = $offset;
        $criteria->condition = "status=:status";
        $criteria->params = array(
            ":status"   =>  Media::ACTIVE
        );
        $data = Media::model()->findAll($criteria);

        if (is_array($data) && sizeof($data) > 0) {
            foreach ($data as $item) {
                Elastic::indexMedia($esClient, $item);
            }
        }

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    //index cho bảng nghẹ sỹ
    public function actionIndexArtist()
    {
        //exit;
        set_time_limit(0);
        $start_time = microtime(true);

        $esClient = new ElasticSearch();

        $total = Artist::model()->count("status=:status", array(
            ":status"   =>  Artist::STATUS_ACTIVE
        ));
        $page = Yii::app()->request->getQuery('page', 1);
        $limit = Yii::app()->request->getQuery('limit', 1000);
        if (isset($_GET['limit']) && $_GET['limit'] <= 0) {
            $limit = 1000;
        }
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;
        $totalPage = ceil($total / $limit);
        echo 'Total row: ' . $total . '<br>';
        echo 'Limit: ' . $limit . '<br>';
        echo 'Page: ' . $page . '/' . $totalPage . '<br><hr><hr>';

        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->offset = $offset;
        $criteria->condition = "status=:status";
        $criteria->params = array(
            ":status"   =>  Artist::STATUS_ACTIVE
        );
        $data = Artist::model()->findAll($criteria);

        if (is_array($data) && sizeof($data) > 0) {
            foreach ($data as $item) {
                Elastic::indexArtist($esClient, $item);
            }
        }

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }



    //index cho bảng tin tức
    public function actionIndexNews()
    {
        //exit;
        set_time_limit(0);
        $start_time = microtime(true);

        $esClient = new ElasticSearch();

        $total = News::model()->count("status=:status", array(
            ":status"   =>  News::STATUS_ACTIVE
        ));
        $page = Yii::app()->request->getQuery('page', 1);
        $limit = Yii::app()->request->getQuery('limit', 1000);
        if (isset($_GET['limit']) && $_GET['limit'] <= 0) {
            $limit = 1000;
        }
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;
        $totalPage = ceil($total / $limit);
        echo 'Total row: ' . $total . '<br>';
        echo 'Limit: ' . $limit . '<br>';
        echo 'Page: ' . $page . '/' . $totalPage . '<br><hr><hr>';

        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->offset = $offset;
        $criteria->condition = "status=:status";
        $criteria->params = array(
            ":status"   =>  News::STATUS_ACTIVE
        );
        $data = News::model()->findAll($criteria);

        if (is_array($data) && sizeof($data) > 0) {
            foreach ($data as $item) {
                Elastic::indexNews($esClient, $item);
            }
        }

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }
    /*
     * index(import) data to videos type
     * */

    public function actionGetInfo()
    {
        //get all indices
        echo 'All indeces:<br>';
        $start_time = microtime(true);
        $baseUrl = Yii::app()->params['elastic_config']['hosts'][0];
        $url = $baseUrl . '/_cat/indices?v';
        $resutl = CFunction::callCURL($url);
        CVarDumper::dump($resutl['data'], 10, true);

        echo '<hr>Start:<br>';
        $url2 = $baseUrl . '/_stats';
        $resutl2 = CFunction::callCURL($url2);
        CVarDumper::dump(CJSON::decode($resutl2['data']), 10, true);

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    public function actionGetIndexInfo()
    {
        //get all indices
        echo 'Indeces info:<br>';
        $start_time = microtime(true);
        $esClient = new ElasticSearch();
        $resutl2 = $esClient->info();
        CVarDumper::dump($resutl2, 10, true);

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    public function actionTypeInfo()
    {
        $start_time = microtime(true);
        //get all indices
        echo 'Type info:<br>';
        $type = Yii::app()->request->getQuery('type', '');
        if ($type != '') {
            $esClient = new ElasticSearch();
            $paramsSearch = array();
            $paramsSearch['type'] = $type;
            $resutl2 = $esClient->count($paramsSearch);
            CVarDumper::dump($resutl2, 10, true);
        } else {
            echo 'Type is null<br>';
        }

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    public function actionGetAllMapping()
    {
        //get all indices
        $start_time = microtime(true);
        $index = Yii::app()->request->getQuery('index', '');
        if ($index != '') {
            $esClient = new ElasticSearch();
            $searchParams = array();
            $searchParams['index'] = $index;
            $result = $esClient->indices()->getMapping($searchParams);
            echo 'All indeces: <br>';
            CVarDumper::dump($result, 10, true);
        } else {
            echo 'index is null';
        }
        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    public function actionDeleteIndex()
    {
        $start_time = microtime(true);
        $index = Yii::app()->request->getQuery('index', '');

        if ($index != '') {
            $esClient = new ElasticSearch();
            $deleteParams = array();
            $deleteParams['index'] = $index;
            if ($esClient->indices()->exists($deleteParams)) {
                $result = $esClient->indices()->delete($deleteParams);
                CVarDumper::dump($result, 10, true);
            } else {
                echo $index . ' not found';
            }
        } else {
            echo 'index is null';
        }

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    public function actionEmptyType()
    {
        $start_time = microtime(true);
        $type = Yii::app()->request->getQuery('type', '');

        if (in_array($type, array(Elastic::TYPE_NEWS, Elastic::TYPE_VIDEOS))) {
            $esClient = new ElasticSearch();
            $deleteParams = array();
            $deleteParams['index'] = $esClient->getIndex();
            $deleteParams['type'] = $type;
            $result = $esClient->indices()->deleteMapping($deleteParams);
            CVarDumper::dump($result, 10, true);
            exit;
        } else {
            echo 'Type not in news, videos';
        }
        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }


    public function actionQueueInfo()
    {
        //info of table elastic_queue
        echo 'elastic queue info:<br> ';
        $start_time = microtime(true);

        $total = ElasticQueue::model()->count();
        $page = Yii::app()->request->getQuery('page', 1);
        $limit = Yii::app()->request->getQuery('limit', 2000);
        if (isset($_GET['limit']) && $_GET['limit'] <= 0) {
            $limit = 2000;
            //$limit = 10;
        }
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;
        $totalPage = ceil($total / $limit);
        echo 'Total row: ' . $total . '<br>';
        echo 'Limit: ' . $limit . '<br>';
        echo 'Page: ' . $page . '/' . $totalPage . '<br><hr>';

        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->offset = $offset;
        $data = ElasticQueue::model()->findAll($criteria);

        if (is_array($data) && sizeof($data) > 0) {
            foreach ($data as $item) {
                CVarDumper::dump($item, 10, true);
                echo '<br>';
            }
        }

        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }

    public function actionEmptyQueue()
    {
        //empty table elastic_queue
        $start_time = microtime(true);
        $result = ElasticQueue::model()->deleteAll();
        echo 'Total row deleted: ';
        CVarDumper::dump($result, 10, true);
        echo '<hr>Total time: ' . (microtime(true) - $start_time) . 's';
    }
}