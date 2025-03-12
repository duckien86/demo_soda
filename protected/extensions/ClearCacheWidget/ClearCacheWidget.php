<?php

    /**
     * Clear assets and runtime folders
     *
     * @author  KienND
     * @version 1.0
     *
     */
    define('ASSETS', 'assets'); // assets folder name
    define('RUNTIME', 'runtime'); // runtime folder name

    class ClearCacheWidget extends CWidget
    {
        public $frontend = false;
        public $backend = false;
        public $agency = false;
        public $baseScriptUrl = null;
        public $frontend_assets = null; // frontend assets folder
        public $frontend_runtime = null; // frontend runtime folder
        public $agency_assets = null;  // agency assets folder
        public $agency_runtime = null; // agency runtime folder

        public function init()
        {
            Yii::import('ext.ClearCacheWidget.CFile');
            if ($this->baseScriptUrl === null) {
                $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.ClearCacheWidget.assets'));
            }

            $cs = Yii::app()->getClientScript();
            $cs->registerCssFile($this->baseScriptUrl . '/clear_cache.css');

            $this->frontend_runtime = DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'runtime';
            $this->frontend_assets = DIRECTORY_SEPARATOR . 'assets';

            $this->agency_assets = DIRECTORY_SEPARATOR . 'agency' .DIRECTORY_SEPARATOR . 'assets';
            $this->agency_runtime = DIRECTORY_SEPARATOR . 'agency' .DIRECTORY_SEPARATOR . 'runtime';
        }

        public function run()
        {
            $this->renderContent();
        }

        protected function renderContent()
        {
            $cache_id = isset($_GET['cache_id']) ? strtolower($_GET['cache_id']) : '';
            if ($cache_id) {
                switch ($cache_id) {

                    case 'web_assets':
                        if ($this->clearCacheAssets('web')) {
                            Yii::app()->user->setFlash('success', Yii::t('adm/admin', 'web_assets_cleared'));
                        } else {
                            Yii::app()->user->setFlash('error', Yii::t('adm/admin', 'Có lỗi xảy ra'));
                        }
                        break;

                    case 'web_cache':
                        if ($this->clearCache('web'))
                            Yii::app()->user->setFlash('success', Yii::t('adm/admin', 'web_cache_cleared'));
                        else
                            Yii::app()->user->setFlash('error', Yii::t('adm/admin', 'Có lỗi xảy ra'));
                        break;

                    case 'adm_assets':
                        if ($this->clearCacheAssets('adm')) {
                            Yii::app()->user->setFlash('success', Yii::t('adm/admin', 'adm_assets_cleared'));
                            Yii::app()->controller->redirect(array('aClearCache/index'));
                        } else {
                            Yii::app()->user->setFlash('error', Yii::t('adm/admin', 'Có lỗi xảy ra'));
                        }
                        break;

                    case 'adm_cache':
                        if ($this->clearCache('adm'))
                            Yii::app()->user->setFlash('success', Yii::t('adm/admin', 'adm_cache_cleared'));
                        else
                            Yii::app()->user->setFlash('error', Yii::t('adm/admin', 'Có lỗi xảy ra'));
                        break;

                    case 'agency_assets':
                        if ($this->clearCacheAssets('agency')) {
                            Yii::app()->user->setFlash('success', Yii::t('adm/admin', 'agency_assets_cleared'));
                            Yii::app()->controller->redirect(array('aClearCache/index'));
                        } else {
                            Yii::app()->user->setFlash('error', Yii::t('adm/admin', 'Có lỗi xảy ra'));
                        }
                        break;

                    case 'agency_cache':
                        if ($this->clearCache('agency'))
                            Yii::app()->user->setFlash('success', Yii::t('adm/admin', 'agency_cache_cleared'));
                        else
                            Yii::app()->user->setFlash('error', Yii::t('adm/admin', 'Có lỗi xảy ra'));
                        break;

                    default:
                        break;
                }
            }
            $this->render('ext.ClearCacheWidget.views.items',
                array(
                    'frontend' => $this->frontend,
                    'backend' => $this->backend, 
                    'agency' => $this->agency
                )
            );

        }

        public function clearCache($where)
        {

            switch ($where) {
                case 'web':
                    $web_runtime = Yii::app()->basePath . $this->frontend_runtime .DIRECTORY_SEPARATOR. 'cache';
                    return $this->refresh($web_runtime);
                    break;

                case 'adm':
                    return Yii::app()->cache->flush();
                    break;
                case 'agency':
                    $agency_runtime = Yii::app()->basePath . $this->agency_runtime .DIRECTORY_SEPARATOR. 'cache';
                    return $this->refresh($agency_runtime);
                    break;
            }
        }

        public function clearCacheAssets($where)
        {
            $assets_path = Yii::app()->assetManager->getBasePath();
            $root_path   = dirname(Yii::app()->basePath);

            switch ($where) {
                case 'adm':
                    return $this->refresh($assets_path);
                    break;
                case 'agency':
                    $assets_path = $root_path . $this->agency_assets;
                    return $this->refresh($assets_path);
                    break;

                case 'web':
                    $assets_path = $root_path . $this->frontend_assets;

                    return $this->refresh($assets_path);
                    break;

                default:
                    break;
            }

        }

        /**
         * Delete contents in folder
         *
         * @param $path folder location
         *
         * @return bool
         */
        private function refresh($path)
        {
            $path = realpath($path);
            if (is_dir($path) && (strpos($path, ASSETS) || strpos($path, RUNTIME))) {
                $destination = CFile::getInstance($path);
                $destination->set($path);
                if ($destination->delete() && $destination->createDir()) {
                    return true;
                }
            }

            return false;
        }

    }
