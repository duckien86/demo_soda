<?php

    class ReportOci
    {
        public $start_date;
        public $end_date;

        public $msisdn;
        public $type_msisdn;

        public $date; //  Ngày xem báo cáo.

        public $price_card; // Mênh giá nạp thẻ
        public $vnp_province_id; // Mã tỉnh vnp
        public $total_card; // Sản lượng thẻ
        public $renueve_card; // Doanh thu thẻ

        public $channel_code;
        public $utm_campaign;

        const MO_TYPE = 1;
        const MT_TYPE = 2;

        // Báo cáo thuê bao
        const NEW_ROAMING         = 1; // Hòa mạng mới
        const SPS_PRODUCT_POST    = 'SPS_PRODUCT_POST'; //Trả sau
        const SPS_PRODUCT_VINA690 = 'SPS_PRODUCT_VINA690'; //Trả trước
        const ACTIVE              = 1; //Đang hoạt động
        const CANCEL              = 2; //TB hủy
        const LOCK_IC             = 3; //Khóa IC
        const LOCK_OC             = 4; //Khóa OC
        const LOCK_2C             = 0; //Khóa 2 chiều

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
         * Lấy doanh thu nạp thẻ thuê bao freedoo tổng quan.
         */
        public function getCardFreedooOverView()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }


            $result = array();

            $sql = "SELECT
                        NAPTIEN,
                        COUNT(MSISDN) AS total_card, 
                        SUM(NAPTIEN) AS renueve_card
                        FROM 
                        VSB_CARD
                        ";
            $sql .= " WHERE 1=1";
            if ($this->start_date !== '' && $this->end_date !== '') {
                $sql .= " AND
                                    CREATED_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    CREATED_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                ";
            }
            if ($this->vnp_province_id != '') {
                $sql .= " AND MATINH =:MATINH";
            }
            if ($this->price_card != '') {
                $sql .= " AND NAPTIEN =:NAPTIEN";
            }
            $sql .= " GROUP BY NAPTIEN";

            $stmt = oci_parse($this->_ora->oraConn, $sql);
            if ($this->vnp_province_id != '') {
                oci_bind_by_name($stmt, ':MATINH', $this->vnp_province_id);
            }
            if ($this->price_card != '') {
                oci_bind_by_name($stmt, ':NAPTIEN', $this->price_card);
            }
            oci_execute($stmt);
            $result = array();
            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }


            return $result;
        }

        /**
         * Lấy doanh thu nạp thẻ thuê bao freedoo chi tiết.
         */
        public function getCardFreedooDetail()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $result = array();
            $sql    = "SELECT
                        MSISDN, CREATED_DATE, NAPTIEN, MATINH
                        FROM 
                        VSB_CARD
                        ";
            $sql .= " WHERE 1=1";
            if ($this->start_date !== '' && $this->end_date !== '') {
                $sql .= " AND
                                    CREATED_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    CREATED_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                ";
            }
            if ($this->vnp_province_id != '') {
                $sql .= " AND MATINH =:MATINH";
            }
            if ($this->price_card != '') {
                $sql .= " AND NAPTIEN =:NAPTIEN";
            }
            $sql .= " ORDER BY CREATED_DATE DESC ";

            $stmt = oci_parse($this->_ora->oraConn, $sql);

            if ($this->vnp_province_id != '') {
                oci_bind_by_name($stmt, ':MATINH', $this->vnp_province_id);
            }
            if ($this->price_card != '') {
                oci_bind_by_name($stmt, ':NAPTIEN', $this->price_card);
            }
            oci_execute($stmt);
            $result = array();
            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }


            return $result;
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

        public function getSubscribers($status = '', $type = '')
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $sql = "SELECT LOAI_TB, MATINH, STATUS, COUNT(MSISDN) AS TOTAL 
                    FROM VSB_ACTIVE";
            $sql .= " WHERE 1=1";
            if ($this->start_date !== '' && $this->end_date !== '') {
                $sql .= " AND
                                    NGAY_HM >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    NGAY_HM <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                ";
            }

            if ($status != '' || $status == 0) {
                $sql .= " AND STATUS =:STATUS";

            }
            if ($type != '') {
                $sql .= " AND LOAI_TB =:LOAI_TB";
            }
            if ($status != '' || $status == 0 || $type != '') {
                $sql .= " GROUP BY MATINH, STATUS, LOAI_TB";
            }

            $stmt = oci_parse($this->_ora->oraConn, $sql);


            if ($status != '' || $status == 0) {
                oci_bind_by_name($stmt, ':STATUS', $status);
            }
            if ($type != '') {
                oci_bind_by_name($stmt, ':LOAI_TB', $type);
            }
            oci_execute($stmt);
            $result = array();

            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }


            return $result;
        }

        public function getSubscribersByMsisdn($msisdn)
        {
            $sql = "SELECT LOAI_TB, MATINH, STATUS, MSISDN, NGAY_HM 
                    FROM VSB_ACTIVE";
            $sql .= " WHERE 1=1";

            if ($msisdn != '') {
                $sql .= " AND MSISDN =:MSISDN";
            }

            $stmt = oci_parse($this->_ora->oraConn, $sql);

            if ($msisdn != '') {
                oci_bind_by_name($stmt, ':MSISDN', $msisdn);
            }
            oci_execute($stmt);
            $result = array();

            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }

            return $result;
        }

        public function getUserTraffixByHour()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date)));
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date)));
            }

            $sql = "SELECT RXTIME_DATE , CAMPAIGN, SUM(COUNT) AS TOTAL, CHANNEL_CODE 
                    FROM USER_TRAFFIXBYHOUR";
            $sql .= " WHERE SERVICE_CODE ='FREEDOO' AND TYPEOFACCESS ='pagehit'";
            if ($this->start_date !== '' && $this->end_date !== '') {
                $sql .= " AND
                                    RXTIME_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    RXTIME_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND 
                                   RXTIME_HOUR >='0' 
                                 AND 
                                   RXTIME_HOUR <='24'
                                ";
            }


            if ($this->channel_code != '') {
                $sql .= " AND CHANNEL_CODE =:CHANNEL_CODE";
            }
            if ($this->utm_campaign != '') {
                $sql .= " AND CAMPAIGN =:CAMPAIGN";
            }
            $sql .= " GROUP BY RXTIME_DATE, CHANNEL_CODE, CAMPAIGN";
            $sql .= " ORDER BY RXTIME_DATE, CAMPAIGN";


            $stmt = oci_parse($this->_ora->oraConn, $sql);
            if ($this->channel_code != '') {
                oci_bind_by_name($stmt, ':CHANNEL_CODE', $this->channel_code);
            }
            if ($this->utm_campaign != '') {
                oci_bind_by_name($stmt, ':CAMPAIGN', $this->utm_campaign);
            }
            oci_execute($stmt);
            $result = array();

            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }


            return $result;
        }

        public function getTotalUserTraffixByHour()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date)));
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date)));
            }

            $sql = "SELECT CAMPAIGN, SUM(COUNT) AS TOTAL, CHANNEL_CODE 
                    FROM USER_TRAFFIXBYHOUR";
            $sql .= " WHERE SERVICE_CODE ='FREEDOO' AND TYPEOFACCESS ='pagehit'";
            if ($this->start_date !== '' && $this->end_date !== '') {
                $sql .= " AND
                                    RXTIME_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    RXTIME_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND 
                                   RXTIME_HOUR >='0' 
                                 AND 
                                   RXTIME_HOUR <='24'
                                ";
            }
            if ($this->channel_code != '') {
                $sql .= " AND CHANNEL_CODE =:CHANNEL_CODE";
            }
            if ($this->utm_campaign != '') {
                $sql .= " AND CAMPAIGN =:CAMPAIGN";
            }
            $sql .= " GROUP BY CAMPAIGN, CHANNEL_CODE";

            $stmt = oci_parse($this->_ora->oraConn, $sql);
            if ($this->channel_code != '') {
                oci_bind_by_name($stmt, ':CHANNEL_CODE', $this->channel_code);
            }
            if ($this->utm_campaign != '') {
                oci_bind_by_name($stmt, ':CAMPAIGN', $this->utm_campaign);
            }
            oci_execute($stmt);
            $result = array();

            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }


            return $result;
        }

        /**
         * @param $status = 1: Tỷ lệ chuyển đổi thành công
         *                Lấy tổng đơn hàng theo campaign.
         */
        public function getOrderCampaign($status = '')
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }

            $criteria            = new CDbCriteria();
            $criteria->select    = "COUNT(DISTINCT t.id) AS total, t.campaign_id";
            $criteria->condition = "t.create_date >='" . $this->start_date . "' and t.create_date <='" . $this->end_date . "' and campaign_id !=''";
            if ($status != '') {
               
            } else {
                $criteria->addCondition("os.delivered = 10");
            }
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            $criteria->join  = "INNER JOIN {{order_state}} os ON os.order_id = t.id";
            $criteria->group = "t.campaign_id";
            $data            = AOrders::model()->findAll($criteria);

            return $data;
        }

        /**
         * @param       $records
         * @param array $columns
         * Lấy cột footer Tổng của tbgridView.
         *
         * @return array
         */
        public function getTotal($records, $columns = array())
        {
            $total = array();


            foreach ($records as $record) {

                foreach ($columns as $column) {
                    if (!isset($total[$column])) $total[$column] = 0;
                    $total[$column] += $record[$column];
                }
            }

            return $total;
        }
    }

?>