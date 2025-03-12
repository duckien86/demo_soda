<?php
    if (!defined("ORA_USERNAME")) define("ORA_USERNAME", "creport");
    if (!defined("ORA_PASSWORD")) define("ORA_PASSWORD", "creport");
    if (!defined("ORA_DB")) define("ORA_DB", "10.2.0.108:1521/CENTECH");
    /*if (!defined("ORA_DB")) define("ORA_DB", "222.252.19.249:1521/CENTECH");*/
    /*if (!defined("ORA_DB")) define("ORA_DB", "192.168.100.3:1521/CENTECH");*/
    if (!defined("ORA_CHARSET")) define("ORA_CHARSET", null);
    if (!defined("ORA_SESSION_MODE")) define("ORA_SESSION_MODE", null);

    class Oracle
    {
        private static $_instance;

        protected $_username;

        protected $_password;

        protected $_oradb;

        protected $_charset = NULL;

        protected $_session_mode = NULL;

        public $oraConn;

        public static function getInstance()
        {
            if (is_null(self::$_instance))
                self::$_instance = new Oracle();

            return self::$_instance;
        }

        public function __construct()
        {
            $this->_username     = ORA_USERNAME;
            $this->_password     = ORA_PASSWORD;
            $this->_oradb        = ORA_DB;
            $this->_charset      = ORA_CHARSET;
            $this->_session_mode = ORA_SESSION_MODE;
        }

        public function connect()
        {
            return $this->oraConn = oci_connect($this->_username, $this->_password, $this->_oradb, $this->_charset, $this->_session_mode);
        }

        public function __destruct()
        {
            if (isset($this->oraConn)) unset($this->oraConn);
        }
    }

?>