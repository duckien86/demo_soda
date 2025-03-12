<?php

    /**
     * Class SystemLog
     */
    class ATraceLog extends TraceLog
    {
        private static $_instance;
        protected      $_logFolder = "";

        public function __construct($logFolderPath = NULL)
        {

            $root    = dirname(Yii::app()->getBasePath());
            $dir_log = $root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'logs' . '/agency' . DIRECTORY_SEPARATOR;
            if (!is_dir($dir_log)) {
                mkdir($dir_log, 0777, TRUE);
            }
//            if ($this->getLogPath() === NULL) $this->setLogPath(Yii::app()->getRuntimePath());
            if ($this->getLogPath() === NULL) $this->setLogPath($dir_log);
            if (!is_null($logFolderPath)) $this->_createLogFolder($logFolderPath);
            $this->setMaxFileSize(10000);
            $this->setMaxLogFiles(100);

        }

        public static function getInstance($logFolderPath = NULL)
        {
            if (!is_object(self::$_instance)) {
                self::$_instance = new self($logFolderPath);
            }

            return self::$_instance;
        }

        /**
         * overwrite
         * @param string $value
         */
        public function setLogFile($value)
        {
            parent::setLogFile($value);
        }



    }
