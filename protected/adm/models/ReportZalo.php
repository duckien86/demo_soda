<?php

class ReportZalo extends CFormModel
{
    CONST ZALO_CHANNEL_CODE = 12;
    CONST STATUS_ACTIVE = 1;


    public $start_date;
    public $end_date;
    public $province_code;
    public $msisdn;
    public $order_id;

    public $on_detail;

    private $_ora;

    public function rules()
    {
        return array(
            array('start_date, end_date', 'required'),
            array('on_detail, province_code, msisdn, order_id', 'safe'),
            array('end_date', 'checkDate'),
        );
    }

    protected function beforeValidate()
    {
        $this->start_date = trim($this->start_date);
        $this->end_date = trim($this->end_date);
        return TRUE;
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
            'start_date'        => Yii::t('adm/label', 'start_date'),
            'end_date'          => Yii::t('adm/label', 'finish_date'),
            'province_code'     => Yii::t('adm/label', 'province_code'),
            'msisdn'            => Yii::t('adm/label', 'msisdn'),
            'order_id'          => Yii::t('adm/label', 'order_id'),

            'on_detail'         => Yii::t('adm/label', 'on_detail'),
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

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AFTOrders[]
     */
    public function searchRemuneration($dataProvider = TRUE)
    {
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

//        if(!empty($data_details_sim)){
//            $model_sim->total = count($data_details_sim);
//            foreach ($data_details_sim as $item){
//                $model_sim->revenue += $item->item_price;
//                $model_sim->rose += $item->amount;
//            }
//        }
//
//        if(!empty($data_details_package)){
//            $model_package->total = count($data_details_package);
//            foreach ($data_details_package as $item){
//                $model_package->revenue += $item->item_price;
//                $model_package->rose += $item->amount;
//            }
//        }

        if(!empty($data_details_consume)){
            $model_consume->total = count($data_details_consume);
            foreach ($data_details_consume as $item){
                $model_consume->revenue += $item->item_price;
                $model_consume->rose += $item->amount;
            }
        }

//        $data[] = $model_sim;
//        $data[] = $model_package;
        $data[] = $model_consume;

        $result = $data;

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                       => 1,
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,
                    )
                ),
            ));
        }else{
            return $result;
        }

    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | ACommisionsSimDetails[]
     */
    public function searchRemunerationSim($dataProvider = TRUE)
    {
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchRemunerationSimDetail(FALSE);

        if(!empty($data_detail)){

            foreach ($data_detail as $item){
                if(!isset($data_raw[$item->sub_type])){
                    $data_raw[$item->sub_type]['total'] = 0;
                    $data_raw[$item->sub_type]['revenue'] = 0;
                    $data_raw[$item->sub_type]['rose'] = 0;
                }
                $data_raw[$item->sub_type]['total']++;
                $data_raw[$item->sub_type]['revenue']+= $item->item_price;
                $data_raw[$item->sub_type]['rose']+= $item->amount;
            }

            foreach ($data_raw AS $key => $item){
                $model = new ACommisionsSimDetails();
                $model->affiliate_channel = self::ZALO_CHANNEL_CODE;
                $model->sub_type = $key;
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
                        'get'                       => 1,
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | ACommisionsSimDetails[]
     */
    public function searchRemunerationSimDetail($dataProvider = TRUE)
    {
        $result = array();

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date)));
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date)));

            $cache_key = "ReportZalo_searchRemunerationSimDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_order_id_'.$this->order_id
                .'_msisdn_'.$this->msisdn;
            $result  = Yii::app()->cache->get($cache_key);

            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*,
                    (SELECT o.create_date FROM tbl_orders o WHERE o.id = t.order_id) AS 'create_date',
                    (SELECT os.create_date FROM tbl_order_state os WHERE os.order_id = t.order_id AND os.delivered = 10 LIMIT 1) AS 'active_time'
                ";
                $criteria->condition = "t.affiliate_channel = :affiliate_channel
                    AND t.order_status = :order_status
                    AND t.order_create_date >= :start_date
                    AND t.order_create_date <= :end_date
                ";
                $criteria->params = array(
                    ':affiliate_channel' => self::ZALO_CHANNEL_CODE,
                    ':order_status' => self::STATUS_ACTIVE,
                    ':start_date' => $start_date,
                    ':end_date' => $end_date,
                );

                if(!empty($this->province_code)){
                    $criteria->compare('t.order_province_code', $this->province_code, FALSE);
                }

                if(!empty($this->order_id)){
                    $criteria->compare('t.order_id', $this->order_id, FALSE);
                }

                if(!empty($this->msisdn)){
                    $criteria->compare('t.item_name', $this->msisdn, TRUE);
                }

                $result = ACommisionsSimDetails::model()->findAll($criteria);
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
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,

                        'ReportZalo[on_detail]'     => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | ACommisionsPackageDetails[]
     */
    public function searchRemunerationPackage($dataProvider = TRUE)
    {
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchRemunerationPackageDetail(FALSE);

        if(!empty($data_detail)){

            foreach ($data_detail as $item){
                if(!isset($data_raw[$item->item_id])){
                    $data_raw[$item->item_id]['item_name'] = $item->item_name;
                    $data_raw[$item->item_id]['package_type'] = $item->package_type;
                    $data_raw[$item->item_id]['total'] = 0;
                    $data_raw[$item->item_id]['revenue'] = 0;
                    $data_raw[$item->item_id]['rose'] = 0;
                }
                $data_raw[$item->item_id]['total']++;
                $data_raw[$item->item_id]['revenue']+= $item->item_price;
                $data_raw[$item->item_id]['rose']+= $item->amount;
            }

            foreach ($data_raw AS $key => $item){
                $model = new ACommisionsPackageDetails();
                $model->affiliate_channel = self::ZALO_CHANNEL_CODE;
                $model->item_id = $key;
                $model->item_name = $item['item_name'];
                $model->package_type = $item['package_type'];
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
                        'get'                       => 1,
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | ACommisionsPackageDetails[]
     */
    public function searchRemunerationPackageDetail($dataProvider = TRUE)
    {
        $result = array();

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date)));
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date)));

            $cache_key = "ReportZalo_searchRemunerationPackageDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_order_id_'.$this->order_id
                .'_msisdn_'.$this->msisdn;
            $result  = Yii::app()->cache->get($cache_key);

            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*";
                $criteria->condition = "t.affiliate_channel = :affiliate_channel
                    AND t.order_status = :order_status
                    AND t.order_create_date >= :start_date
                    AND t.order_create_date <= :end_date
                ";
                $criteria->params = array(
                    ':affiliate_channel' => self::ZALO_CHANNEL_CODE,
                    ':order_status' => self::STATUS_ACTIVE,
                    ':start_date' => $start_date,
                    ':end_date' => $end_date,
                );

                if(!empty($this->province_code)){
                    $criteria->compare('t.order_province_code', $this->province_code, FALSE);
                }

                if(!empty($this->order_id)){
                    $criteria->compare('t.order_id', $this->order_id, FALSE);
                }

                if(!empty($this->msisdn)){
                    $criteria->compare('t.phone_customer', $this->msisdn, TRUE);
                }

                $result = ACommisionsPackageDetails::model()->findAll($criteria);
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
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,

                        'ReportZalo[on_detail]'     => $this->on_detail,

                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AIncentivesAgency[]
     */
    public function searchRemunerationConsume($dataProvider = true)
    {
        $data = array();
        $data_detail = $this->searchRemunerationConsumeDetail(FALSE);

        if(!empty($data_detail)){

            $model = new AIncentivesAgency();
            $model->affiliate_channel = self::ZALO_CHANNEL_CODE;
            $model->total = 0;
            $model->revenue = 0;
            $model->rose = 0;

            foreach ($data_detail as $item){
                $model->total++;
                $model->revenue+= $item->item_price;
                $model->rose+= $item->amount;
            }

            $data[] = $model;
        }

        $result = $data;

        if($dataProvider){
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'pageSize' => 10,
                    'params' => array(
                        'get'                       => 1,
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

    /**
     * @param bool $dataProvider
     * @return CArrayDataProvider | AIncentivesAgency[]
     */
    public function searchRemunerationConsumeDetail($dataProvider = true)
    {
        $result = array();

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date)));
            $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date)));

            $cache_key = "ReportZalo_searchRemunerationConsumeDetail"
                .'_start_date_'.$start_date
                .'_end_date_'.$end_date
                .'_province_code_'.$this->province_code
                .'_order_id_'.$this->order_id
                .'_msisdn_'.$this->msisdn;
            $result  = Yii::app()->cache->get($cache_key);

            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->select = "t.*";
                $criteria->condition = "t.affiliate_channel = :affiliate_channel
                    AND t.campaign_category_id = :campaign_category_id
                    AND t.order_status = :order_status
                    AND t.create_date >= :start_date
                    AND t.create_date <= :end_date
                ";
                $criteria->params = array(
                    ':affiliate_channel' => self::ZALO_CHANNEL_CODE,
                    ':campaign_category_id' => AIncentivesAgency::CAMPAIGN_TD_TKC,
                    ':order_status' => self::STATUS_ACTIVE,
                    ':start_date' => $start_date,
                    ':end_date' => $end_date,
                );

                if(!empty($this->province_code)){
                    $criteria->compare('t.order_province_code', $this->province_code, FALSE);
                }

                if(!empty($this->order_id)){
                    $criteria->compare('t.order_id', $this->order_id, FALSE);
                }

                if(!empty($this->msisdn)){
                    $criteria->compare('t.item_name', $this->msisdn, TRUE);
                }

                $result = AIncentivesAgency::model()->findAll($criteria);
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
                        'ReportZalo[start_date]'    => $this->start_date,
                        'ReportZalo[end_date]'      => $this->end_date,
                        'ReportZalo[province_code]' => $this->province_code,
                        'ReportZalo[order_id]'      => $this->order_id,
                        'ReportZalo[msisdn]'        => $this->msisdn,

                        'ReportZalo[on_detail]'     => $this->on_detail,
                    )
                ),
            ));
        }else{
            return $result;
        }
    }

}