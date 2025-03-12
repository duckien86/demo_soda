<?php

class AFTReport extends CFormModel
{
    public $start_date;
    public $end_date;
    public $user_tourist;
    public $status_order;
    public $contract_id;
    public $order_id;
    public $total;
    public $item_id;
    public $province_code;

    public $order_type;
    public $user_tourist_ctv;
    public $order_id_ctv;

    public $order_code;
    public $msisdn;
    public $package_name;
    public $package_id;

    public $contract_code;
    public $customer;
    public $customer_id;

    public $promo_code;
    public $promo_code_prefix;

    public $bundle;

    CONST PREFIX_CTV = 'P';
    CONST PREFIX_AGENCY = 'AP';

    public $on_detail;

    private $_ora;

    CONST ORDER_NORMAL = 1;
    CONST ORDER_CTV    = 4;

    public function rules()
    {
        return array(
            array('user_tourist, status_order, order_id, contract_id, total,
                item_id, province_code, order_type, user_tourist_ctv, order_id_ctv,
                on_detail, order_code, msisdn, package_name, package_id,
                contract_code, customer, customer_id, promo_code, promo_code_prefix,
                bundle', 'safe'),
            array('msisdn', 'required', 'on' => 'search_sim'),
            array('start_date, end_date', 'required', 'on' => 'search'),
            array('end_date', 'checkDate', 'on' => 'search'),
            array('start_date, end_date', 'safe', 'on' => 'export'),
        );
    }

    public function checkDate()
    {
        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date)));
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date)));
            if($end_date < $start_date){
                $this->addError('end_date', 'Ngày kết thúc phải lớn hơn ngày bắt đầu');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function attributeLabels()
    {
        return array(
            'start_date'        => 'Ngày bắt đầu',
            'end_date'          => 'Ngày kết thúc',
            'status_order'      => 'Trạng thái',
            'user_tourist'      => 'Khách hàng',
            'order_id'          => 'Mã đơn hàng',
            'contract_id'       => 'Mã hợp đồng',
            'item_id'           => 'Sản phẩm',
            'package_id'        => 'Sản phẩm',
            'province_code'     => 'TTKD',
            'order_type'        => 'Loại đơn hàng',
            'user_tourist_ctv'  => 'Khách hàng',
            'order_id_ctv'      => 'Mã đơn hàng',
            'customer_id'       => 'Khách hàng',
            'promo_code'        => 'Mã CTV',
            'promo_code_prefix' => 'Loại hình CTV',
            'order_code'        => 'Mã đơn hàng',
            'msisdn'            => 'Số thuê bao',
            'bundle'            => 'Loại gói',
        );
    }


    public function __construct($oracle = TRUE)
    {
        parent::__construct();
        if($oracle){
            $this->_ora = Oracle::getInstance();
            $this->_ora->connect();
        }
    }

    public function getOrderData($excel = FALSE)
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria            = new CDbCriteria();
        $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                           INNER JOIN tbl_contracts c ON c.id = t.contract_id 
                           INNER JOIN tbl_users u ON u.id = c.user_id";
        $criteria->select    = "t.id, t.contract_id, t.total_success, t.total_fails,
                                (
                                    SELECT
                                        SUM(od1.quantity)
                                    FROM
                                        tbl_order_details od1
                                    WHERE
                                        od1.order_id = od.order_id
                                ) AS total,
                                t.status";
        $criteria->condition = "1=1";
        if ($this->start_date && $this->end_date) {
            $criteria->addCondition("t.create_time >= '$this->start_date' and t.create_time <='$this->end_date' and t.status !=-1");
        }

        if ($this->user_tourist != '') {
            $criteria->addCondition("c.user_id ='$this->user_tourist'");
        }
        if ($this->status_order != '') {
            $criteria->addCondition("t.status ='$this->status_order'");
        }
        if ($this->order_id != '') {
            $criteria->addCondition("t.id ='$this->order_id'");
        }
        if ($this->contract_id != '') {
            $criteria->addCondition("t.contract_id ='$this->contract_id'");
        }
        if($this->order_type == AFTReportForm::ORDER_NORMAL){
            $criteria->addCondition("u.user_type NOT IN (".AFTUsers::USER_TYPE_AGENCY.",".AFTUsers::USER_TYPE_CTV.")");
        }else if($this->order_type == AFTReportForm::ORDER_CTV){
            $criteria->addCondition("u.user_type = ".AFTUsers::USER_TYPE_CTV);
        }

        if (!ADMIN && !SUPER_ADMIN) {
            if (isset(Yii::app()->user->vnp_province_id)) {
                if (!empty(Yii::app()->user->vnp_province_id)) {
                    $criteria->addCondition("t.province_code ='" . Yii::app()->user->vnp_province_id . "'");
                }
            }
        }

        $criteria->group = "t.id";
        if ($excel) {
            $data = AFTOrders::model()->findAll($criteria);
        } else {
            $data = new CActiveDataProvider('AFTOrders', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_time asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                         => 1,
                        'AFTReportForm[start_date]'   => $this->start_date,
                        'AFTReportForm[end_date]'     => $this->end_date,
                        'AFTReportForm[user_tourist]' => $this->user_tourist,
                        'AFTReportForm[status_order]' => $this->status_order,
                    ),
                    'pageSize' => 30,
                ),
            ));
        }

        return $data;
    }

    public function getDetailOrders($order_id)
    {
        $result = array();

        $sql = "SELECT
                    MSISDN,
                    to_char(SERIA_NUMBER) AS SERIA_NUMBER,
                    ORDER_ID,
                    CONTRACT_ID, 
                    SUB_TYPE,
                    ASSIGN_KIT_STATUS,
                    to_char(ASSIGN_KIT_TIME,'yyyy-mm-dd hh24:mi:ss') AS ASSIGN_KIT_TIME,   
                    to_char(CREATED_DATE,'yyyy-mm-dd hh24:mi:ss') AS CREATED_DATE,
                    NOTE
                    FROM 
                    SDL_ACTIONS
                    ";

        $sql .= " WHERE 1=1";
//            if ($this->start_date !== '' && $this->end_date !== '') {
//                $sql .= " AND
//                                    CREATED_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
//                                 AND
//                                    CREATED_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
//                                ";
//            }
        if ($order_id != '') {
            $sql .= " AND ORDER_ID =:ORDER_ID";
        }
        $stmt = oci_parse($this->_ora->oraConn, $sql);
        if ($order_id != '') {
            oci_bind_by_name($stmt, ':ORDER_ID', $order_id);
        }

        oci_execute($stmt);
        $result    = array();
        $result[0] = array(
            'MSISDN'            => 'Số thuê bao',
            'SERIA_NUMBER'      => 'Số serial',
            'ORDER_ID'          => 'Mã đơn hàng',
            'CONTRACT_ID'       => 'Ma hợp đồng',
            'SUB_TYPE'          => 'Tên bộ kít',
            'SUB_PRICE'         => 'Giá bộ kít',
            'ASSIGN_KIT_STATUS' => 'Trạng thái ghép',
            'ASSIGN_KIT_TIME'   => 'Thời gian ghép',
            'NOTE'              => 'Ghi chú',
        );
        $stt       = 1;

        $order = AFTOrders::model()->findByPk($order_id);
        $contract = AFTContracts::model()->findByPk($order->contract_id);
        while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {

            foreach ($entry as $key => $value) {


                if ($key == 'ORDER_ID') {
                    $entry[$key] = $order->code;
                }
                if ($key == 'CONTRACT_ID') {
                    $entry[$key] = $contract->code;
                }

                if ($key == 'SUB_TYPE') {
                    $entry['SUB_PRICE'] = AFTPackage::getPriceByCode($entry['SUB_TYPE']);
                    $entry[$key]        = AFTPackage::getNameByCode($entry['SUB_TYPE']);
                }


                if ($key == 'SERIA_NUMBER') {
                    $entry[$key] = "'" . $entry['SERIA_NUMBER'];
                }
                if ($key == 'ASSIGN_KIT_STATUS') {
                    $entry[$key] = self::getStatusJoinKit($entry[$key]);
                }

                $result[$stt]['MSISDN']            = $entry['MSISDN'];
                $result[$stt]['SERIA_NUMBER']      = $entry['SERIA_NUMBER'];
                $result[$stt]['ORDER_ID']          = $entry['ORDER_ID'];
                $result[$stt]['CONTRACT_ID']       = $entry['CONTRACT_ID'];
                $result[$stt]['SUB_TYPE']          = $entry['SUB_TYPE'];
                $result[$stt]['SUB_PRICE']         = $entry['SUB_PRICE'];
                $result[$stt]['ASSIGN_KIT_STATUS'] = $entry['ASSIGN_KIT_STATUS'];
                $result[$stt]['ASSIGN_KIT_TIME']   = $entry['ASSIGN_KIT_TIME'];
                $result[$stt]['NOTE']              = $entry['NOTE'];
            }

            $stt++;
        }
        return $result;
    }

    public function getDetailOrdersCtv($order_id)
    {
        $result = array();

        $sql = "SELECT
                    MSISDN,
                    to_char(SERIA_NUMBER) AS SERIA_NUMBER,
                    ORDER_ID,
                    CONTRACT_ID, 
                    SUB_TYPE,
                    ASSIGN_KIT_STATUS,
                    to_char(ASSIGN_KIT_TIME,'yyyy-mm-dd hh24:mi:ss') AS ASSIGN_KIT_TIME,   
                    to_char(CREATED_DATE,'yyyy-mm-dd hh24:mi:ss') AS CREATED_DATE,
                    NOTE
                    FROM 
                    SDL_ACTIONS
                    ";

        $sql .= " WHERE 1=1";
//            if ($this->start_date !== '' && $this->end_date !== '') {
//                $sql .= " AND
//                                    CREATED_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
//                                 AND
//                                    CREATED_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
//                                ";
//            }
        if ($order_id != '') {
            $sql .= " AND ORDER_ID =:ORDER_ID";
        }
        $stmt = oci_parse($this->_ora->oraConn, $sql);
        if ($order_id != '') {
            oci_bind_by_name($stmt, ':ORDER_ID', $order_id);
        }

        oci_execute($stmt);
        $result    = array();
        $result[0] = array(
            'MSISDN'            => 'Số thuê bao',
            'SERIA_NUMBER'      => 'Số serial',
            'ORDER_ID'          => 'Mã đơn hàng',
            'SUB_TYPE'          => 'Tên bộ kít',
            'SUB_PRICE'         => 'Giá bộ kít',
            'ASSIGN_KIT_STATUS' => 'Trạng thái ghép',
            'ASSIGN_KIT_TIME'   => 'Thời gian ghép',
            'NOTE'              => 'Ghi chú',
        );
        $stt       = 1;
        $order = AFTOrders::model()->findByPk($order_id);
        while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {

            foreach ($entry as $key => $value) {


                if ($key == 'ORDER_ID') {
                    $entry[$key] = $order->code;
                }
                if ($key == 'SUB_TYPE') {
                    $entry['SUB_PRICE'] = AFTPackage::getPriceByCode($entry['SUB_TYPE']);
                    $entry[$key]        = AFTPackage::getNameByCode($entry['SUB_TYPE']);
                }
                if ($key == 'SERIA_NUMBER') {
                    $entry[$key] = "'" . $entry['SERIA_NUMBER'];
                }
                if ($key == 'ASSIGN_KIT_STATUS') {
                    $entry[$key] = self::getStatusJoinKit($entry[$key]);
                }

                $result[$stt]['MSISDN']            = $entry['MSISDN'];
                $result[$stt]['SERIA_NUMBER']      = $entry['SERIA_NUMBER'];
                $result[$stt]['ORDER_ID']          = $entry['ORDER_ID'];
                $result[$stt]['SUB_TYPE']          = $entry['SUB_TYPE'];
                $result[$stt]['SUB_PRICE']         = $entry['SUB_PRICE'];
                $result[$stt]['ASSIGN_KIT_STATUS'] = $entry['ASSIGN_KIT_STATUS'];
                $result[$stt]['ASSIGN_KIT_TIME']   = $entry['ASSIGN_KIT_TIME'];
                $result[$stt]['NOTE']              = $entry['NOTE'];
            }

            $stt++;
        }
        return $result;
    }

    public static function unsign_string($str, $separator = ' ', $keep_special_chars = FALSE)
    {
        $str = str_replace(array("à", "á", "ạ", "ả", "ã", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ"), "a", $str);
        $str = str_replace(array("À", "Á", "Ạ", "Ả", "Ã", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ"), "A", $str);
        $str = str_replace(array("è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ"), "e", $str);
        $str = str_replace(array("È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ"), "E", $str);
        $str = str_replace("đ", "d", $str);
        $str = str_replace("Đ", "D", $str);
        $str = str_replace(array("ỳ", "ý", "ỵ", "ỷ", "ỹ", "ỹ"), "y", $str);
        $str = str_replace(array("Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ"), "Y", $str);
        $str = str_replace(array("ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ"), "u", $str);
        $str = str_replace(array("Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ"), "U", $str);
        $str = str_replace(array("ì", "í", "ị", "ỉ", "ĩ"), "i", $str);
        $str = str_replace(array("Ì", "Í", "Ị", "Ỉ", "Ĩ"), "I", $str);
        $str = str_replace(array("ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ"), "o", $str);
        $str = str_replace(array("Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ"), "O", $str);
        if ($keep_special_chars == FALSE) {
            $str = str_replace(array(' ', '…', '“', '”', "~", "!", "@", "#", "$", "%", "^", "&", "*", "/", "\\", "?", "<", ">", "'", "\"", ":", ";", "{", "}", "[", "]", "|", "(", ")", ",", ".", "`", "+", "=", "-"), $separator, $str);
            $str = preg_replace("/[^_A-Za-z0-9- ]/i", '', $str);
        }

        $str = str_replace(' ', $separator, $str);

        return trim(strtolower($str), "");
    }


    public static function getTotal($type = 1, $order_id)
    {
        $result = array();

        $sql = "SELECT
                    COUNT(ORDER_ID) AS TOTAL
                    FROM 
                    SDL_ACTIONS
                    ";
        $sql .= " WHERE 1=1";

        $success = 10;
        if ($order_id != '') {
            $sql .= " AND ORDER_ID =:ORDER_ID";
        }
        if ($type == '1') {
            $sql .= " AND ASSIGN_KIT_STATUS =:ASSIGN_KIT_STATUS";
        } else {
            $sql .= " AND ASSIGN_KIT_STATUS !=:ASSIGN_KIT_STATUS";
        }
        $stmt = oci_parse(Oracle::getInstance()->oraConn, $sql);
        if ($order_id != '') {
            oci_bind_by_name($stmt, ':ORDER_ID', $order_id);
        }
        oci_bind_by_name($stmt, ':ASSIGN_KIT_STATUS', $success);
        oci_execute($stmt);
        $result = array();
        while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
            $result[] = $entry;
        }

        return $result;
    }

    public function getRenueveOverview()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria = new CDbCriteria();

        $criteria->select = "p.name as package_name, SUM(od.total_success) as total, SUM(od.total_success * od.price) as total_renueve";
        if ($this->start_date && $this->end_date) {
            $criteria->addCondition("t.create_time >='$this->start_date' and t.create_time <= '$this->end_date'");
        }
        if ($this->status_order != '') {
            $criteria->addCondition("t.status ='$this->status_order'");
        }
        if ($this->user_tourist != '') {
            $criteria->addCondition("c.user_id ='$this->user_tourist'");
        }
        if ($this->order_id != '') {
            $criteria->addCondition("t.id ='$this->order_id'");
        }
        if ($this->item_id != '') {
            $criteria->addCondition("p.id ='$this->item_id'");
        }
        if ($this->province_code != '') {
            $criteria->addCondition("t.province_code ='$this->province_code'");
        }
        $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                          INNER JOIN tbl_package p ON p.id = od.item_id
                          INNER JOIN tbl_contracts c ON c.id = t.contract_id ";
        $criteria->group = "p.name";

        $data = self::controlRenueveOverview(AFTOrders::model()->findAll($criteria));

        return $data;
    }

    public function getRenueveDetails($excel = FALSE)
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria = new CDbCriteria();

        $criteria->select = "t.code as code,
                            c.code as contract_code,
                            c.user_id as user_tourist,
                            SUM(od.total_success) as total,
                            SUM(od.total_success * od.price) as total_renueve";
        if ($this->start_date && $this->end_date) {
            $criteria->addCondition("t.create_time >='$this->start_date' and t.create_time <= '$this->end_date'");
        }
        if ($this->status_order != '') {
            $criteria->addCondition("t.status ='$this->status_order'");
        }
        if ($this->user_tourist != '') {
            $criteria->addCondition("c.user_id ='$this->user_tourist'");
        }
        if ($this->order_id != '') {
            $criteria->addCondition("t.id ='$this->order_id'");
        }
        if ($this->item_id != '') {
            $criteria->addCondition("p.id ='$this->item_id'");
        }
        if ($this->province_code != '') {
            $criteria->addCondition("t.province_code ='$this->province_code'");
        }
        $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                          INNER JOIN tbl_package p ON p.id = od.item_id
                          INNER JOIN tbl_contracts c ON c.id = t.contract_id ";
        $criteria->group = "t.code";
        IF ($excel) {

            $criteria->order = "t.create_time asc";

            $data = AFTOrders::model()->findAll($criteria);

        } ELSE {
            $data = new CActiveDataProvider('AFTOrders', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_time asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                          => 1,
                        'AFTReportForm[start_date]'    => $this->start_date,
                        'AFTReportForm[end_date]'      => $this->end_date,
                        'AFTReportForm[order_id]'      => $this->order_id,
                        'AFTReportForm[province_code]' => $this->province_code,
                        'AFTReportForm[user_tourist]'  => $this->user_tourist,
                        'AFTReportForm[item_id]'       => $this->item_id,
                        'AFTReportForm[status_order]'  => $this->status_order,
                        'AFTReportForm[on_detail]'     => $this->on_detail,
                    ),
                    'pageSize'  => 30,
                ),
            ));
        }

        return $data;
    }

    public static function controlRenueveOverview($data)
    {
        $result = array();
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $result_key                  = array(
                    'package_name'  => '',
                    'total'         => '',
                    'total_renueve' => '',
                );
                $result_key['package_name']  = isset($value->package_name) ? $value->package_name : '';
                $result_key['total']         = isset($value->total) ? $value->total : '';
                $result_key['total_renueve'] = isset($value->total_renueve) ? $value->total_renueve : '';
                $result[]                    = $result_key;
            }
        }

        return $result;
    }

    public static function getStatusJoinKit($status)
    {
        $data = array(
            0  => 'Chưa xử lý',
            1  => 'Thất bại',
            10 => 'Hoàn thành'
        );

        return isset($data[$status]) ? $data[$status] : '';
    }


    public function searchStatistic($dataProvider = TRUE)
    {
        $result = array();
        $data_raw = $this->searchStatisticDetail(FALSE);

        if(!empty($data_raw)){

            $data = array();

            $model = new AFTOrders();
            $model->quantity = 0;
            $model->total_success = 0;
            $model->total_fails = 0;

            foreach ($data_raw as $item){
                $model->total           += $item->total;
                $model->total_success   += $item->total_success;
                $model->total_fails     += $item->total_fails;
            }
            $data[] = $model;

            $result = $data;
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                       => 1,
                        'AFTReport[start_date]'     => $this->start_date,
                        'AFTReport[end_date]'       => $this->end_date,
                        'AFTReport[province_code]'  => $this->province_code,
                        'AFTReport[order_type]'     => $this->order_type,
                        'AFTReport[status_order]'   => $this->status_order,
                        'AFTReport[order_code]'     => $this->order_code,
                        'AFTReport[customer_id]'    => $this->customer_id,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchStatisticDetail($dataProvider = TRUE)
    {
        $result = array();
        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

            $cache_key = "AFTReport_searchStatistic"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_order_type_'.$this->order_type
                .'_status_order_'.$this->status_order
                .'_customer_id_'.$this->customer_id
                .'_order_code_'.$this->order_code;

            $result  = Yii::app()->cache->get($cache_key);
            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*, 
                    (SELECT SUM(quantity) FROM tbl_order_details WHERE order_id = t.id) AS 'total',
                    (SELECT user_type FROM tbl_users WHERE id = (SELECT user_id FROM tbl_contracts WHERE id = t.contract_id)) AS 'user_type'
                ";

                $criteria->condition = "t.status >= :status
                    AND t.type = :type
                    AND t.create_time >= :start_date 
                    AND t.create_time <= :end_date
                ";
                $criteria->params = array(
                    ':status'       => AFTOrders::ORDER_JOIN_KIT,
                    ':type'         => AFTOrders::TYPE_SIM,
                    ':start_date'   => $start_date,
                    ':end_date'     => $end_date
                );

                if(!empty($this->province_code)){
                    $criteria->addCondition("t.province_code = '$this->province_code'");
                }

                if(!empty($this->status_order)){
                    $criteria->addCondition("t.status = '$this->status_order'");
                }

                if(!empty($this->order_type)){
                    if($this->order_type == AFTReport::ORDER_NORMAL){
                        $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id IN (SELECT id FROM tbl_users WHERE user_type IN (".AFTUsers::USER_TYPE_KHDN.",".AFTUsers::USER_TYPE_SDL.")))");
                    }else if($this->order_type == AFTReport::ORDER_CTV){
                        $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id IN (SELECT id FROM tbl_users WHERE user_type IN ('".AFTUsers::USER_TYPE_CTV."')))");
                    }
                }

                if(!empty($this->customer_id)){
                    $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id = '$this->customer_id')");
                }

                if(!empty($this->order_code)){
                    $criteria->compare('t.code', $this->order_code, TRUE);
                }

                $data = array();
                $data_raw = AFTOrders::model()->findAll($criteria);

                if(!empty($data_raw)){
                    $arr_order_id = '';
                    $first = true;
                    foreach ($data_raw as $order){
                        if($first){
                            $arr_order_id.= $order->id;
                            $first = false;
                        }else{
                            $arr_order_id.= ",$order->id";
                        }
                    }

                    $sql = "
                        SELECT 
                            ORDER_ID, ASSIGN_KIT_STATUS, COUNT(*) AS TOTAL 
                        FROM 
                            SDL_ACTIONS
                        WHERE 
                            ORDER_ID IN ($arr_order_id)
                        GROUP BY ORDER_ID, ASSIGN_KIT_STATUS
                        ORDER BY ORDER_ID, ASSIGN_KIT_STATUS
                        ";

                    $stmt = oci_parse($this->_ora->oraConn, $sql);
                    oci_execute($stmt);

                    $data_total = array();
                    $stt = 0;
                    $total_success = 0;
                    $total_fails = 0;
                    while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                        if(!isset($data_total[$entry['ORDER_ID']])){
                            $data_total[$entry['ORDER_ID']]['total_success'] = 0;
                            $data_total[$entry['ORDER_ID']]['total_fails'] = 0;
                        }

                        if($entry['ASSIGN_KIT_STATUS'] == 10){
                            $data_total[$entry['ORDER_ID']]['total_success'] = $entry['TOTAL'];
                            $total_success+= $entry['TOTAL'];
                        }else{
                            $data_total[$entry['ORDER_ID']]['total_fails'] = $entry['TOTAL'];
                            $total_fails+= $entry['TOTAL'];
                        }
                    }

                    foreach ($data_raw as $order){
                        $order->total_success = 0;
                        $order->total_fails = 0;
                        foreach ($data_total as $key => $value){
                            if($order->id == $key){
                                $order->total_success += $value['total_success'];
                                $order->total_fails += $value['total_fails'];
                                break;
                            }
                        }
                        $data[] = $order;
                    }
                }
                $result = $data;
                Yii::app()->cache->set($cache_key, $result, 60*1);
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 30,
                    'params' => array(
                        'get'                       => 1,
                        'AFTReport[start_date]'     => $this->start_date,
                        'AFTReport[end_date]'       => $this->end_date,
                        'AFTReport[province_code]'  => $this->province_code,
                        'AFTReport[order_type]'     => $this->order_type,
                        'AFTReport[status_order]'   => $this->status_order,
                        'AFTReport[order_code]'     => $this->order_code,
                        'AFTReport[customer_id]'    => $this->customer_id,
                    )
                ),
            ));
        }else{
            return $result;
        }

    }

    public function searchRevenue($dataProvider = TRUE)
    {
        $result = array();
        $data_raw = $this->searchRevenueDetail(FALSE);

        if(!empty($data_raw)){
            $data = array();
            $data_detail = array();

            foreach ($data_raw as $item){
                if(isset($data_detail[$item->package_id])){
                    $data_detail[$item->package_id]['total_success']    += $item->total_success;
                    $data_detail[$item->package_id]['revenue']          += $item->revenue;
                }else{
                    $data_detail[$item->package_id]['total_success']    = $item->total_success;
                    $data_detail[$item->package_id]['package_name']     = $item->package_name;
                    $data_detail[$item->package_id]['revenue']          = $item->revenue;
                }
            }

            foreach ($data_detail as $key => $item) {
                $model = new AFTOrders();
                $model->package_id      = $key;
                $model->package_name    = $item['package_name'];
                $model->total_success   = $item['total_success'];
                $model->revenue         = $item['revenue'];

                $data[] = $model;
            }

            $result = $data;
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                       => 1,
                        'AFTReport[start_date]'     => $this->start_date,
                        'AFTReport[end_date]'       => $this->end_date,
                        'AFTReport[province_code]'  => $this->province_code,
                        'AFTReport[order_type]'     => $this->order_type,
                        'AFTReport[status_order]'   => $this->status_order,
                        'AFTReport[package_id]'     => $this->package_id,
                        'AFTReport[customer]'       => $this->customer,
                        'AFTReport[customer_id]'    => $this->customer_id,
                        'AFTReport[contract_code]'  => $this->contract_code,
                        'AFTReport[order_code]'     => $this->order_code,
                        'AFTReport[package_name]'   => $this->package_name,
                        'AFTReport[on_detail]'      => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchRevenueDetail($dataProvider = TRUE)
    {
        $result = array();
        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

            $cache_key = "AFTReport_searchRevenueDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_order_type_'.$this->order_type
                .'_status_order_'.$this->status_order
                .'_package_id_'.$this->package_id
                .'_customer_id_'.$this->customer_id
                .'_customer_'.$this->customer
                .'_contract_code_'.$this->contract_code
                .'_order_code_'.$this->order_code
                .'_package_name_'.$this->package_name;


            $result  = Yii::app()->cache->get($cache_key);
            if(!$result){

                $data = array();
                $data_order = array();
                $data_detail = array();

                // Lấy dữ liệu đơn hàng
                $criteria = new CDbCriteria();
                $criteria->select = "t.*, 
                    (SELECT username FROM tbl_users WHERE id = (SELECT user_id FROM tbl_contracts WHERE id = t.contract_id)) AS 'customer',
                    (SELECT user_type FROM tbl_users WHERE id = (SELECT user_id FROM tbl_contracts WHERE id = t.contract_id)) AS 'user_type',
                    (SELECT code FROM tbl_contracts WHERE id = t.contract_id) AS 'contract_code',
                    od.item_id
                ";
                $criteria->join = "INNER JOIN tbl_order_details od ON t.id = od.order_id";

                $criteria->condition = "t.status >= :status
                    AND t.type = :type
                    AND t.create_time >= :start_date
                    AND t.create_time <= :end_date
                ";
                $criteria->params = array(
                    ':status'       => AFTOrders::ORDER_JOIN_KIT,
                    ':type'         => AFTOrders::TYPE_SIM,
                    ':start_date'   => $start_date,
                    ':end_date'     => $end_date
                );

                if(!empty($this->province_code)){
                    $criteria->addCondition("t.province_code = '$this->province_code'");
                }

                if(!empty($this->status_order)){
                    $criteria->addCondition("t.status = '$this->status_order'");
                }

                if(!empty($this->order_type)){
                    if($this->order_type == AFTReport::ORDER_NORMAL){
                        $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id IN (SELECT id FROM tbl_users WHERE user_type IN (".AFTUsers::USER_TYPE_KHDN.",".AFTUsers::USER_TYPE_SDL.")))");
                    }else if($this->order_type == AFTReport::ORDER_CTV){
                        $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id IN (SELECT id FROM tbl_users WHERE user_type IN ('".AFTUsers::USER_TYPE_CTV."')))");
                    }
                }

                if(!empty($this->order_code)){
                    $criteria->compare('t.code', $this->order_code, TRUE);
                }

                if(!empty($this->contract_code)){
                    $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE code LIKE '%$this->contract_code%')");
                }

                if(!empty($this->customer)){
                    $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id IN (SELECT id FROM tbl_users WHERE username LIKE '%$this->customer%'))");
                }

                if(!empty($this->package_id)){
                    $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE item_id = '$this->package_id')");
                }

                if(!empty($this->customer_id)){
                    $criteria->addCondition("t.contract_id IN (SELECT id FROM tbl_contracts WHERE user_id = '$this->customer_id')");
                }

                if(!empty($this->package_name)){
                    $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE item_id IN (SELECT id FROM tbl_package WHERE name LIKE '%$this->package_name%'))");
                }

                $criteria->order = 't.id DESC';

                $data_order = AFTOrders::model()->findAll($criteria);

                //Lấy dữ liệu chi tiết
                if(!empty($data_order)){
                    //Ghép các mã đơn hàng trên thành mảng
                    $arr_order_id = '';
                    $first = true;
                    foreach ($data_order as $order){
                        if($first){
                            $arr_order_id .= "'$order->id'";
                            $first = false;
                        }else{
                            $arr_order_id .= ",'$order->id'";
                        }
                    }

                    $criteria = new CDbCriteria();
                    $criteria->select = "t.*,
                        (SELECT name FROM tbl_package WHERE id = t.item_id) AS 'package_name'
                    ";
                    $criteria->condition = "t.order_id IN ($arr_order_id)";

                    if(!empty($this->package_id)){
                        $criteria->addCondition("t.item_id = '$this->package_id'");
                    }

                    if(!empty($this->package_name)){
                        $criteria->addCondition("t.item_id IN (SELECT id FROM tbl_package WHERE name LIKE '%$this->package_name%')");
                    }

                    $data_detail = AFTOrderDetails::model()->findAll($criteria);

                }

                //Ghép dữ liệu
                if(!empty($data_order) && !empty($data_detail)){
                    foreach ($data_order as $order){
                        foreach ($data_detail as $detail){
                            if($detail->order_id == $order->id){
                                $model                  = $order;
                                $model->package_id      = $detail->item_id;
                                $model->package_name    = $detail->package_name;
                                $model->price           = $detail->price;
                                $model->total           = $detail->quantity;
                                $model->total_success   = $detail->total_success;
                                $model->total_fails     = $detail->total_fails;
                                $model->revenue         = $detail->price * $detail->total_success;

                                $data[] = $model;
                            }
                        }
                    }
                }

                $result = $data;

                Yii::app()->cache->set($cache_key, $result, 60*10);
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 30,
                    'params' => array(
                        'get'                       => 1,
                        'AFTReport[start_date]'     => $this->start_date,
                        'AFTReport[end_date]'       => $this->end_date,
                        'AFTReport[province_code]'  => $this->province_code,
                        'AFTReport[order_type]'     => $this->order_type,
                        'AFTReport[status_order]'   => $this->status_order,
                        'AFTReport[package_id]'     => $this->package_id,
                        'AFTReport[customer]'       => $this->customer,
                        'AFTReport[customer_id]'    => $this->customer_id,
                        'AFTReport[contract_code]'  => $this->contract_code,
                        'AFTReport[order_code]'     => $this->order_code,
                        'AFTReport[package_name]'   => $this->package_name,
                        'AFTReport[on_detail]'      => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchRemuneration($dataProvider = TRUE)
    {
        ini_set('memory_limit', '-1');
        $data_details_sim       = $this->searchRemunerationSimDetail(FALSE);
        $data_details_package   = $this->searchRemunerationPackageDetail(FALSE);
        $data_details_consume   = $this->searchRemunerationConsumeDetail(FALSE);
        $data = array();

        $model_sim = new AFTOrders();
        $model_sim->total   = 0;
        $model_sim->revenue = 0;
        $model_sim->rose    = 0;
        $model_sim->campaign_category_id = AFTActions::CAMPAIGN_CATEGORY_ID_SIM;

        $model_package = new AFTOrders();
        $model_package->total     = 0;
        $model_package->revenue   = 0;
        $model_package->rose      = 0;
        $model_package->campaign_category_id = AFTActions::CAMPAIGN_CATEGORY_ID_PACKAGE;

        $model_consume = new AFTOrders();
        $model_consume->total     = 0;
        $model_consume->revenue   = 0;
        $model_consume->rose      = 0;
        $model_consume->campaign_category_id = AFTActions::CAMPAIGN_CATEGORY_ID_CONSUME;

        if(!empty($data_details_sim)){
            $model_sim->total = count($data_details_sim);
            foreach ($data_details_sim as $item){
                $model_sim->revenue += $item->price;
                $model_sim->rose += $item->amount;
            }
        }

        if(!empty($data_details_package)){
            $model_package->total = count($data_details_package);
            foreach ($data_details_package as $item){
                $model_package->revenue += $item->price;
                $model_package->rose += $item->amount;
            }
        }

        if(!empty($data_details_consume)){
            $model_consume->total = count($data_details_consume);
            foreach ($data_details_consume as $item){
                $model_consume->revenue += $item->total_money;
                $model_consume->rose += $item->amount;
            }
        }

        $data[] = $model_sim;
        $data[] = $model_package;
        $data[] = $model_consume;

        $result = $data;

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                   => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }

    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchRemunerationSim($dataProvider = TRUE)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchRemunerationSimDetail(FALSE);

        if(!empty($data_detail)){

            foreach ($data_detail as $item){
                if(isset($data_raw[$item->inviter_code])){
                    $data_raw[$item->inviter_code]['total']++;
                    $data_raw[$item->inviter_code]['revenue'] += $item->price;
                    $data_raw[$item->inviter_code]['rose'] += $item->amount;
                }else{
                    $data_raw[$item->inviter_code]['total'] = 1;
                    $data_raw[$item->inviter_code]['revenue'] = $item->price;
                    $data_raw[$item->inviter_code]['rose'] = $item->amount;
                }
            }

            foreach ($data_raw AS $key => $item){
                $model = new AFTOrders();
                $model->promo_code = $key;
                $model->total = $item['total'];
                $model->revenue = $item['revenue'];
                $model->rose = $item['rose'];

                $data[] = $model;
            }
        }

        $result = $data;

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                   => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTActions[]
     */
    public function searchRemunerationSimDetail($dataProvider = TRUE)
    {
        $result = array();

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';;

            $cache_key = "AFTReport_searchRemunerationSimDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_promo_code_prefix'.$this->promo_code_prefix
                .'_promo_code_'.$this->promo_code
                .'_order_code_'.$this->order_code
                .'_msisdn_'.$this->msisdn
                .'_package_id_'.$this->package_id;
            $result  = Yii::app()->cache->get($cache_key);

            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*
                ";
                $criteria->condition = "(t.campaign_category_id = :campaign_category_id)
                    AND (t.created_on >= :start_date)
                    AND (t.created_on <= :end_date)
                ";
                $criteria->params = array(
                    ':campaign_category_id' => AFTActions::CAMPAIGN_CATEGORY_ID_SIM,
                    ':start_date'           => $start_date,
                    ':end_date'             => $end_date,
                );

                if(!empty($this->province_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE province_code = '$this->province_code')");
                }

                if(!empty($this->promo_code_prefix)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE promo_code LIKE '$this->promo_code_prefix%')");
                }

                if(!empty($this->promo_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE promo_code LIKE '%$this->promo_code%')");
                }

                if(!empty($this->order_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE code LIKE '%$this->order_code%')");
                }

                if(!empty($this->msisdn)){
                    $criteria->compare('t.msisdn', $this->msisdn, TRUE);
                }

                if(!empty($this->package_id)){
                    $criteria->addCondition("t.order_code IN (SELECT order_id FROM tbl_order_details WHERE item_id = '$this->package_id')");
                }

                $result = AFTActions::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $result, 60*10);
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 30,
                    'params' => array(
                        'get'                           => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchRemunerationPackage($dataProvider = TRUE)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchRemunerationPackageDetail(FALSE);


        if(!empty($data_detail)){

            foreach ($data_detail as $item){
                if(isset($data_raw[$item->inviter_code][$item->product_name])){
                    $data_raw[$item->inviter_code][$item->product_name]['total']++;
                    $data_raw[$item->inviter_code][$item->product_name]['revenue'] += $item->price;
                    $data_raw[$item->inviter_code][$item->product_name]['rose'] += $item->amount;
                }else{
                    $data_raw[$item->inviter_code][$item->product_name]['name'] = $item->product_name;
                    $data_raw[$item->inviter_code][$item->product_name]['bundle'] = $item->bundle;
                    $data_raw[$item->inviter_code][$item->product_name]['total'] = 1;
                    $data_raw[$item->inviter_code][$item->product_name]['revenue'] = $item->price;
                    $data_raw[$item->inviter_code][$item->product_name]['rose'] = $item->amount;
                }
            }

            foreach ($data_raw AS $key => $item){
                foreach ($item AS $sub_key => $sub_item){
                    $model = new AFTOrders();
                    $model->promo_code      = $key;
                    $model->package_code    = $sub_key;
                    $model->package_name    = $sub_item['name'];
                    $model->bundle          = $sub_item['bundle'];
                    $model->total           = $sub_item['total'];
                    $model->revenue         = $sub_item['revenue'];
                    $model->rose            = $sub_item['rose'];
                    $data[] = $model;
                }
            }
        }

        $result = $data;

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                   => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTActions[]
     */
    public function searchRemunerationPackageDetail($dataProvider = TRUE)
    {

        $result = array();

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';;

            $cache_key = "AFTReport_searchRemunerationPackageDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_promo_code_prefix'.$this->promo_code_prefix
                .'_promo_code_'.$this->promo_code
                .'_order_code_'.$this->order_code
                .'_msisdn_'.$this->msisdn
                .'_package_id_'.$this->package_id;
            $result  = Yii::app()->cache->get($cache_key);

            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*,
       
                    (SELECT p.is_bundle FROM tbl_package p INNER JOIN tbl_order_details od ON p.id = od.item_id WHERE od.order_id = t.order_code) AS 'bundle'
                ";
                $criteria->condition = "(t.campaign_category_id = :campaign_category_id)
                    AND (t.created_on >= :start_date)
                    AND (t.created_on <= :end_date)
                ";
                $criteria->params = array(
                    ':campaign_category_id' => AFTActions::CAMPAIGN_CATEGORY_ID_PACKAGE,
                    ':start_date'           => $start_date,
                    ':end_date'             => $end_date,
                );

                if(!empty($this->province_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE province_code = '$this->province_code')");
                }

                if(!empty($this->promo_code_prefix)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE promo_code LIKE '$this->promo_code_prefix%')");
                }

                if(!empty($this->promo_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE promo_code LIKE '%$this->promo_code%')");
                }

                if(!empty($this->order_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE code LIKE '%$this->order_code%')");
                }

                if(!empty($this->msisdn)){
                    $criteria->compare('t.msisdn', $this->msisdn, TRUE);
                }

                if(!empty($this->package_id)){
                    $criteria->addCondition("t.order_code IN (SELECT order_id FROM tbl_order_details WHERE item_id = '$this->package_id')");
                }

                $result = AFTActions::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $result, 60*10);
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 30,
                    'params' => array(
                        'get'                   => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchRemunerationConsume($dataProvider = true)
    {
        ini_set('memory_limit', '-1');
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchRemunerationConsumeDetail(FALSE);

        if(!empty($data_detail)){

            foreach ($data_detail as $item){
                if(isset($data_raw[$item->inviter_code])){
                    $data_raw[$item->inviter_code]['total']++;
                    $data_raw[$item->inviter_code]['revenue'] += $item->total_money;
                    $data_raw[$item->inviter_code]['rose'] += $item->amount;
                }else{
                    $data_raw[$item->inviter_code]['total'] = 1;
                    $data_raw[$item->inviter_code]['revenue'] = $item->total_money;
                    $data_raw[$item->inviter_code]['rose'] = $item->amount;
                }
            }

            foreach ($data_raw AS $key => $item){
                $model = new AFTOrders();
                $model->promo_code = $key;
                $model->total = $item['total'];
                $model->revenue = $item['revenue'];
                $model->rose = $item['rose'];

                $data[] = $model;
            }
        }

        $result = $data;

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                   => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTActions[]
     */
    public function searchRemunerationConsumeDetail($dataProvider = true)
    {
        $result = array();

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';;

            $cache_key = "AFTReport_searchRemunerationConsumeDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_promo_code_prefix'.$this->promo_code_prefix
                .'_promo_code_'.$this->promo_code
                .'_order_code_'.$this->order_code
                .'_msisdn_'.$this->msisdn
                .'_package_id_'.$this->package_id;
            $result  = Yii::app()->cache->get($cache_key);

            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*
                ";
                $criteria->condition = "(t.campaign_category_id = :campaign_category_id)
                    AND (t.created_on >= :start_date)
                    AND (t.created_on <= :end_date)
                    AND (t.amount IS NOT NULL AND t.amount > 0)
                ";
                $criteria->params = array(
                    ':campaign_category_id' => AFTActions::CAMPAIGN_CATEGORY_ID_CONSUME,
                    ':start_date'           => $start_date,
                    ':end_date'             => $end_date,
                );

                if(!empty($this->province_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE province_code = '$this->province_code')");
                }

                if(!empty($this->promo_code_prefix)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE promo_code LIKE '$this->promo_code_prefix%')");
                }

                if(!empty($this->promo_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE promo_code LIKE '%$this->promo_code%')");
                }

                if(!empty($this->order_code)){
                    $criteria->addCondition("t.order_code IN (SELECT id FROM tbl_orders WHERE code LIKE '%$this->order_code%')");
                }

                if(!empty($this->msisdn)){
                    $criteria->compare('t.msisdn', $this->msisdn, TRUE);
                }

                if(!empty($this->package_id)){
                    $criteria->addCondition("t.order_code IN (SELECT order_id FROM tbl_order_details WHERE item_id = '$this->package_id')");
                }

                $result = AFTActions::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $result, 60*10);
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 30,
                    'params' => array(
                        'get'                   => 1,
                        'AFTReport[start_date]'         => $this->start_date,
                        'AFTReport[end_date]'           => $this->end_date,
                        'AFTReport[province_code]'      => $this->province_code,
                        'AFTReport[promo_code_prefix]'  => $this->promo_code_prefix,
                        'AFTReport[promo_code]'         => $this->promo_code,
                        'AFTReport[order_code]'         => $this->order_code,
                        'AFTReport[msisdn]'             => $this->msisdn,
                        'AFTReport[package_id]'         => $this->package_id,
                        'AFTReport[on_detail]'          => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    public function searchSim()
    {
        $result = array();

        if(!empty($this->msisdn)){
            $sql = "SELECT
                  MSISDN,
                  SERIA_NUMBER,
                  ORDER_ID,
                  CONTRACT_ID,
                  ASSIGN_KIT_STATUS,
                  to_char(ASSIGN_KIT_TIME,'yyyy-mm-dd hh24:mi:ss') AS ASSIGN_KIT_TIME,
                  PACKAGE_ID
                FROM 
                SDL_ACTIONS
                WHERE 
                  (MSISDN = :MSISDN OR MSISDN = :MSISDN_STANDARD)
                ";
            $stmt = oci_parse(Oracle::getInstance()->oraConn, $sql);
            $msisdn = CFunction::makePhoneNumberBasic($this->msisdn);
            $msisdn_standard = CFunction::makePhoneNumberStandard($this->msisdn);
            oci_bind_by_name($stmt, ':MSISDN', $msisdn);
            oci_bind_by_name($stmt, ':MSISDN_STANDARD', $msisdn_standard);

            oci_execute($stmt);

            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $entry['ORDER'] = AFTOrders::model()->findByPk($entry['ORDER_ID']);
                $entry['PACKAGE'] = AFTPackage::model()->findByPk($entry['PACKAGE_ID']);
                $entry['USER'] = AFTUsers::getUserByContract($entry['CONTRACT_ID']);
                $result[] = $entry;
            }
        }

        return $result;
    }

}
?>