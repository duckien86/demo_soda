<?php

    class ReportOci
    {
        public $start_date;
        public $end_date;

        public $msisdn;
        public $type_msisdn;

        const MO_TYPE = 1;
        const MT_TYPE = 2;

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('package_group, package_id, period', 'safe'),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
            );
        }

        public function __construct()
        {
            $this->_ora = Oracle::getInstance();
            $this->_ora->connect();
        }

        /**
         * Lấy doanh thu thẻ cào.
         */
        public function getMtLog()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }


            $result = array();

            if ($this->msisdn != '' && $this->type_msisdn != '') {
                if ($this->type_msisdn == self::MO_TYPE) {
                    $sql = "SELECT
                               'MO' as M_TYPE,
                                MO_MSISDN,
                                to_char(MO_RXTIME, 'DD/MM/YYYY - HH24:MI:SS') AS MO_RXTIME,
                                MO_MSG,
                                MO_SHORTCODE,
                                MO_BILLINGSTATUS,
                                MO_ID_ORIGIN
                            FROM
                                MO
                            WHERE MO_MSISDN = :MSISDN ";
                    if ($this->start_date !== '' && $this->end_date !== '') {
                        $sql .= "AND
                                    MO_RXTIME >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    MO_RXTIME <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                ";
                    }
                    $sql .= " ORDER BY MO_TXTIME DESC ";
                } else {
                    $sql = "SELECT
                                'MT' as M_TYPE,
                                MT_MSISDN AS MT_MSISDN,
                                to_char(MT_RXTIME, 'DD/MM/YYYY - HH24:MI:SS') AS MT_RXTIME,
                                MT_MSG AS MT_MSG,
                                MT_SHORTCODE AS MT_SHORTCODE ,
                                MT_BILLINGSTATUS AS MT_BILLINGSTATUS
                            FROM
                                MT
                            WHERE  MT_MSISDN = :MSISDN ";
                    if ($this->start_date !== '' && $this->end_date !== '') {
                        $sql .= "AND
                                    MT_RXTIME >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    MT_RXTIME <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                ";
                    }
                    $sql .= " ORDER BY MT_TXTIME DESC ";

                }

                $stmt = oci_parse($this->_ora->oraConn, $sql);
                oci_bind_by_name($stmt, ':MSISDN', $this->msisdn);
                oci_execute($stmt);
                $result = array();
                while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                    $result[] = $entry;
                }
            }

            return $result;
        }


        /**
         * Lấy thể loại sim
         */
        public static function getTypeName($type)
        {
            $type_sim = array(
                1 => 'Trả trước',
                2 => 'Trả sau',
                3 => 'Data',
                4 => 'Vas',
                5 => 'Sim Kit',
                6 => 'Đổi quà'
            );

            return $type_sim[isset($type) ? $type : 1];
        }

    }

?>