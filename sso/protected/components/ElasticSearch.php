<?php
/**
 * @Package:
 * @Author: nguyenpv
 * @Date: 9/8/15
 * @Time: 5:10 PM
 */

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../vendor/elasticsearch/elasticsearch/src/Elasticsearch/Client.php';

class ElasticSearch
{
    private $_hosts = array();
    private $_index;
    private $_es = null;

    public function __construct($params = null)
    {
        $newParams = array();
        if (is_null($params)) {
            $elastic_config = Yii::app()->params['elastic_config'];
            $this->_hosts = $elastic_config['hosts'];
            $this->_index = $elastic_config['index'];
            //
            $newParams['hosts'] = $this->_hosts;
        } else {
            if (isset($params['hosts'])) {
                $this->_hosts = $params['hosts'];
                $newParams['hosts'] = $params['hosts'];
            }
            if (isset($params['index'])) {
                $this->_index = $params['index'];
            }
        }

        $esClient = new ElasticSearch\Client($newParams);

        $this->_es = $esClient;
    }

    public function setIndex($index)
    {
        $this->_index = $index;
    }

    public function getIndex()
    {
        return $this->_index;
    }

    public function info()
    {
        return $this->_es->info();
    }

    public function ping()
    {
        return $this->_es->ping();
    }

    public function create($params)
    {
        $params['index'] = $this->_index;
        return $this->_es->create($params);
    }

    public function index($params)
    {
        $params['index'] = $this->_index;
        return $this->_es->index($params);
    }

    public function delete($params)
    {
        $params['index'] = $this->_index;

        return $this->_es->delete($params);
    }

    public function indices()
    {
        return $this->_es->indices();
    }

    public function update($params)
    {
        $params['index'] = $this->_index;
        return $this->_es->update($params);
    }

    public function search($params = array())
    {
        $params['index'] = $this->_index;
        return $this->_es->search($params);
    }

    public function searchExists($params = array())
    {
        $params['index'] = $this->_index;
        return $this->_es->searchExists($params);
    }

    public function suggest($params = array())
    {
        $params['index'] = $this->_index;
        return $this->_es->suggest($params);
    }

    public function bulk($params = array())
    {
        $params['index'] = $this->_index;
        return $this->_es->bulk($params);
    }

    public function deleteByQuery($params = array())
    {
        $params['index'] = $this->_index;
        return $this->_es->deleteByQuery($params);
    }

    public function get($params)
    {
        $params['index'] = $this->_index;
        return $this->_es->get($params);
    }

    public function count($params = array())
    {
        $params['index'] = $this->_index;
        return $this->_es->count($params);
    }

    public function exists($params)
    {
        $params['index'] = $this->_index;

        return $this->_es->exists($params);

    }

    public function __destruct()
    {
        $this->_es = null;
    }
} 