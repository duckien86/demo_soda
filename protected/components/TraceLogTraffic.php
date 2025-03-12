<?php

    /**
     * Class SystemLog
     */
    class TraceLogTraffic extends CFileLogRoute
    {
        private static $_instance;
        protected      $_logFolder = "";
        public         $type       = '';

        public function __construct($logFolderPath = NULL, $type)
        {
            $this->type = $type;
            $root       = dirname(Yii::app()->getBasePath());
            $dir_log    = $root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'traffic_log';
            if (!is_dir($dir_log)) {
                mkdir($dir_log, 0777, TRUE);
            }
            if ($this->getLogPath() === NULL) $this->setLogPath($dir_log);  // use default path
            if (!is_null($logFolderPath)) $this->_createLogFolder($logFolderPath);  // set customize path
            $this->setMaxFileSize(1);
            $this->setMaxLogFiles(1000);
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
                $this->processLogs($logs);
            } catch (Exception $_ex) {
                error_log(__METHOD__ . ': Exception processing application logs: ' . $_ex->getMessage());
            }
        }

        /**
         * @return string
         */
        protected function formatLogMessage2($arrayParams)
        {
            $strReturn = '';
            $strReturn .= strtoupper($this->type) . "\t";
            if (isset($arrayParams['msisdn']))
                $strReturn .= $arrayParams['msisdn'];
            $strReturn .= "\t";
            if (isset($arrayParams['channel']))
                $strReturn .= $arrayParams['channel'];
            $strReturn .= "\t";
            if (isset($arrayParams['device_name']))
                $strReturn .= $arrayParams['device_name'];
            $strReturn .= "\t";
            if (isset($arrayParams['brand']))
                $strReturn .= $arrayParams['brand'];
            $strReturn .= "\t";
            if (isset($arrayParams['ua']))
                $strReturn .= $arrayParams['ua'];
            $strReturn .= "\t";
            if (isset($arrayParams['ip']))
                $strReturn .= $arrayParams['ip'];
            $strReturn .= "\t";
            $strReturn .= date("Y-m-d H:i:s", time());
            $strReturn .= "\t";
            if (isset($arrayParams['controller']))
                $strReturn .= $arrayParams['controller'];
            $strReturn .= "\t";
            if (isset($arrayParams['action']))
                $strReturn .= $arrayParams['action'];
            $strReturn .= "\t";
            if (isset($arrayParams['campaign']))
                $strReturn .= $arrayParams['campaign'];
            $strReturn .= "\t";
            if (isset($arrayParams['device_os']))
                $strReturn .= $arrayParams['device_os'];
            $strReturn .= "\t";
            if (isset($arrayParams['device_type']))
                $strReturn .= $arrayParams['device_type'];
            $strReturn .= "\t";
//            $strReturn .= "\n";
            if (isset($arrayParams['is_member']))
                $strReturn .= $arrayParams['is_member'];
            $strReturn .= "\t";
            $strReturn .= "\n";

            return $strReturn;
        }

        /**
         * Saves log messages in files.
         *
         * @param array $logs list of log messages
         */
        protected function processLogs($log_ary)
        {
            $text    = $this->formatLogMessage2($log_ary);
            $logFile = $this->getLogPath() . DIRECTORY_SEPARATOR . $this->getLogFile();
            $fp      = @fopen($logFile, 'a');
            @flock($fp, LOCK_EX);
            if (@filesize($logFile) > $this->getMaxFileSize() * 1024) {
                $this->rotateFiles();
                @flock($fp, LOCK_UN);
                @fclose($fp);
                @file_put_contents($logFile, $text, FILE_APPEND | LOCK_EX);
            } else {
                @fwrite($fp, $text);
                @flock($fp, LOCK_UN);
                @fclose($fp);
            }
        }

        /**
         * Rotates log files.
         */
        protected function rotateFiles()
        {
            $file = $this->getLogPath() . DIRECTORY_SEPARATOR . $this->getLogFile();
            $max  = $this->getMaxLogFiles();
            for ($i = $max; $i > 0; --$i) {
                $rotateFile = $file . '-' . $i.'.txt';

                if (is_file($rotateFile)) {
                    // suppress errors because it's possible multiple processes enter into this section
                    if ($i === $max) {
                        @unlink($rotateFile);
                    } else {
                        @rename($rotateFile, $file . '-' . ($i + 1) . '.txt');
                    }

                }
            }
            if (is_file($file)) {
                // suppress errors because it's possible multiple processes enter into this section
                if ($this->rotateByCopy) {
                    @copy($file, $file . '-1.txt');
                    if ($fp = @fopen($file, 'a')) {
                        @ftruncate($fp, 0);
                        @fclose($fp);
                    }
                } else {
                    @rename($file, $file . '-1.txt');
                }
            }
            // clear stat cache after moving files so later file size check is not cached
            clearstatcache();
        }

    }
