<?php

    /**
     * Class SystemLog
     */
    class TraceLog extends CFileLogRoute
    {
        private static $_instance;
        protected      $_logFolder = "";

        public function __construct($logFolderPath = NULL)
        {
            if ($this->getLogPath() === NULL) $this->setLogPath(Yii::app()->getRuntimePath());
            if (!is_null($logFolderPath)) $this->_createLogFolder($logFolderPath);
            $this->setMaxFileSize(10000);
            $this->setMaxLogFiles(100);

        }


        public function setLogFolder($value)
        {
            $this->_logFolder = $value;
        }

        public function getLogFolder()
        {
            return $this->_logFolder;
        }

        public static function getInstance($logFolderPath = NULL)
        {
            if (!is_object(self::$_instance)) {
                self::$_instance = new self($logFolderPath);
            }

            return self::$_instance;
        }

        public function init()
        {
            parent::init();
        }

        /**
         * @param string $message
         * @param null   $category
         * @param string $level Value: I(init),T(trace),E(error),W(warning),F(finish)
         * @param null   $time
         *
         * @return string
         */
        protected function formatLogMessage($message, $category = NULL, $level = 'I', $time = NULL)
        {

            $message_start  = '[------------------' . $message . '------------------]';
            $message_finish = '[' . str_repeat('-', strlen($message_start)) . ']';

            if ($time == NULL) $time = time();

            if ($level == 'I') { // start log
                return @date('Y/m/d H:i:s', $time) . '<' . $level . '> ' . $message_start . PHP_EOL;
            } elseif ($level == 'F') { // finish log
                return @date('Y/m/d H:i:s', $time) . '<' . $level . '> ' . $message_finish . "\n" . PHP_EOL;
            } else { // trace log
                return '-->[' . sprintf('%-20s', $category) . '] ' . ': <' . $level . '> ' . $message . PHP_EOL;
            }
        }

        protected function _createLogFolder($logFolderPath)
        {
            if ($logFolderPath != "") {
                $paths = explode("/", $logFolderPath);
                try {
                    foreach ($paths as $_path) {
                        ini_set('display_errors', 1);
                        $_folderLogPath = $this->getLogPath() . DIRECTORY_SEPARATOR . $_path;

                        if (!is_dir($_folderLogPath)) mkdir($_folderLogPath, 0777);
                        $this->setLogPath($_folderLogPath);
                    }
                } catch (Exception $_ex) {
                    error_log(__METHOD__ . ': Exception processing create log folder path : ' . $_ex->getMessage());
                }
            }

            return $this->getLogPath();
        }

        public function processWriteLogs($logs = array())
        {
            try {
                parent::processLogs($logs);
            } catch (Exception $_ex) {
                error_log(__METHOD__ . ': Exception processing application logs: ' . $_ex->getMessage());
            }
        }
    }
