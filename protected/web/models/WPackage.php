<?php

class WPackage extends Package
{
    const  PACKAGE_ACTIVE = 1;
    const  PACKAGE_INACTIVE = 0;

    const  PACKAGE_DAY = 'DAY';
    const  PACKAGE_MONTH = 'MONTH';
    const  PACKAGE_FLEXIBLE = 'FLEXIBLE';

    const PACKAGE_PREPAID = 1;
    const PACKAGE_POSTPAID = 2;
    const PACKAGE_DATA = 3;
    const PACKAGE_VAS = 4;
    const PACKAGE_SIMKIT = 5;
    const PACKAGE_REDEEM = 6;
    const PACKAGE_CALL_INT = 7;
    const PACKAGE_CALL_EXT = 8;
    const PACKAGE_SMS_INT = 9;
    const PACKAGE_SMS_EXT = 10;
    const PACKAGE_DATA_FLEX = 11;
    const PACKAGE_ROAMING = 12;
    const PACKAGE_FIBER = 13;
    const PACKAGE_API   = 15;

    const PERIOD_1 = 1;
    const PERIOD_7 = 7;
    const PERIOD_30 = 30;
    const PERIOD_180 = 180;
    const PERIOD_210 = 210;
    const PERIOD_240 = 240;
    const PERIOD_360 = 360;
    const PERIOD_450 = 450;
    const PERIOD_480 = 480;
    const VIP_USER = 1;

    const HOT = 1;
    const PACKAGE_HOT = 13;

    const ALL_PACKAGE = -1; //Tất cả gói
    const FREEDOO_PACKAGE = 1; //Gói Freedoo
    const OTHER_PACKAGE = 0; //Gói toàn quốc
    const LOCAL_PACKAGE = 2; //Gói cục bộ (nằm trong gói toàn quốc)

    const FREE = -1;

    const DISPLAY_TYPE_RESOURCE = 0;
    const DISPLAY_TYPE_SHORT_DES = 1;

    const INSTANT_PAYMENT = 2;
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return WPackage the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param           $category_id
     * @param string $package_id
     * @param bool|TRUE $dataProvider
     * @param int $limit
     * @param int $offset
     *
     * @return CActiveDataProvider|static[]
     */
    public static function getListPackageByCategoryId($category_id, $package_id = '', $dataProvider = TRUE, $limit = 6, $offset = 0)
    {
        $criteria = new CDbCriteria();
        if ($package_id) {
            $criteria->condition = 't.status=:status AND t.category_id=:category_id AND t.id<>:id';
            $criteria->params = array(':status' => self::PACKAGE_ACTIVE, ':category_id' => $category_id, ':id' => $package_id);
        } else {
            $criteria->condition = 't.status=:status AND t.category_id=:category_id';
            $criteria->params = array(':status' => self::PACKAGE_ACTIVE, ':category_id' => $category_id);
        }
        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        $criteria->order = 't.sort_index';
        if ($dataProvider) {
            return new CActiveDataProvider(self::model(), array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 't.sort_index',
                ),
                'pagination' => array(
                    'pageSize' => $limit,
                )
            ));
        } else {
            return self::model()->findAll($criteria);
        }
    }

    public static function getHomeList()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('home_display', 1);
        $criteria->condition = "thumbnail_1 <> '' ";

        $package = WPackage::model()->findAll($criteria);

        return $package;
    }

    public function getListPackage($clause)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "*";
        $criteria->condition = "code IN ('$clause')";
        $package = WPackage::model()->findAll($criteria);
        return $package;
    }

    /*
     * Lấy ra gói cước fiber freedoo Final
     */
    public function getListFiberFreedooLocalFinal($province_code)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.id IN (SELECT package_id FROM tbl_packages_province WHERE province_code = $province_code) AND status = 1 AND type = 13 AND freedoo IN (1,2) AND package_local = 1 order by name ASC, price ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }

    /*
    * Lấy ra gói cước combo  Final
    */
    public function getListComboLocalFinal($province_code)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.id IN (SELECT package_id FROM tbl_packages_province WHERE province_code = $province_code) AND status = 1 AND type = 16 AND freedoo IN (1,2) order by name ASC, price ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }

    /*
     * Lấy ra gói cước fiber freedoo Final
     */
    public function getListMyTV()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 14 order by name ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    public function getListMyTV_SmartTV_Home()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 14 AND type_tv = 1 order by name ASC limit 9";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    public function getListMyTV_NomalTV()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 14 AND type_tv = 2 order by name ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    /*
     * Lấy ra gói cước fiber Final
     */
    public function getListFiberLocalFinal($province_code)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.id IN (SELECT package_id FROM tbl_packages_province WHERE province_code = $province_code) AND status = 1 AND type = 13 AND freedoo = 2 AND package_local = 1 order by name DESC, price ASC, sort_index ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    /*
     * Lấy ra gói cước mytv Final
     */
    public function getListMyTVLocalFinal($province_code)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.id IN (SELECT package_id FROM tbl_packages_province WHERE province_code = $province_code) AND status = 1 AND type = 14 AND freedoo = 2  order by price ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    /*
     * Lấy ra danh sách gói cước fiber freedoo
     */
    public function getListPackageFiberFreedoo()
    {
        $type = self::PACKAGE_FIBER;
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.type = $type AND freedoo = 1 AND status = 1 order by price ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }

    /*
   * Lấy ra danh sách gói cước fiber toàn quốc
   */
    public function getListPackageFiberNational()
    {
        $type = self::PACKAGE_FIBER;
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.type = $type AND freedoo = 0 AND status = 1";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    /*
     * Lấy ra gói cước fiber
     */
    public function getListFiber($clause)
    {
        $active = self::PACKAGE_ACTIVE;
        $fiber = self::PACKAGE_FIBER;
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "name IN ('$clause') AND status = $active AND type = $fiber";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }

    public function getDetailFiber($id)
    {
        $data = WPackage::model()->findByPk($id);
        return $data;
    }

    public function getThumbnailpackage($name)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "thumbnail_1";
        $criteria->condition = "name = '$name'";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }

    /**
     * @param null $flexible
     *
     * @return array
     */
    public static function getListPackageType($flexible = NULL)
    {
        if ($flexible) {
            return array(
                self::PACKAGE_CALL_INT => Yii::t('web/portal', 'flexible_call_int'),
                self::PACKAGE_CALL_EXT => Yii::t('web/portal', 'flexible_call_ext'),
                self::PACKAGE_SMS_INT => Yii::t('web/portal', 'flexible_sms_int'),
                self::PACKAGE_SMS_EXT => Yii::t('web/portal', 'flexible_sms_ext'),
                self::PACKAGE_DATA_FLEX => Yii::t('web/portal', 'flexible_data'),
            );
        } else {
            return array(
                self::PACKAGE_PREPAID => Yii::t('web/portal', 'package_prepaid'),
                self::PACKAGE_POSTPAID => Yii::t('web/portal', 'package_postpaid'),
                self::PACKAGE_DATA => Yii::t('web/portal', 'package_data'),
                self::PACKAGE_VAS => Yii::t('web/portal', 'package_vas'),
                self::PACKAGE_SIMKIT => Yii::t('web/portal', 'package_simkit'),
                self::PACKAGE_FIBER => Yii::t('web/portal', 'package_fiber'),
            );
        }
    }

    public function getPackageTypeLabel($type, $flexible = NULL)
    {
        $array_type = $this->getListPackageType($flexible);

        return (isset($array_type[$type])) ? $array_type[$type] : $type;
    }

    public static function getAllPackages()
    {
        $cache_key = 'data_getAllPackages';
        $results = Yii::app()->cache->get($cache_key);

        if (!$results) {
            $results = array();
            $array_type = self::getListPackageType();
            foreach ($array_type as $key => $item) {
                if ($key != self::PACKAGE_SIMKIT) {
                    $temp['category']['id'] = $key;
                    $temp['category']['name'] = $item;
                    $temp['package'] = self::getListPackageByType($key, '', FALSE);
                    if ($temp) {
                        array_push($results, $temp);
                    }
                }
            }

            Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
        }
        if ($results) {
            return $results;
        } else {
            return FALSE;
        }
    }

    /**
     * type  ex: 1:prepaid||2:postpaid||3:data||4:vas
     *
     * @param           $type
     * @param           $package_id
     * @param           $vip_user
     * @param           $period
     * @param bool|TRUE $dataProvider
     * @param int $limit
     * @param int $offset
     * @param int $freedooPackage
     * @param string $key |
     * @param string $orderBy | using for searching Packages
     * @param string $order |
     *
     * @return array|CActiveDataProvider
     */
    public static function getListPackageByType($type, $package_id = '', $dataProvider = TRUE, $vip_user = NULL, $period = NULL, $limit = 24, $offset = 0, $freedooPackage = self::ALL_PACKAGE, $key = NULL, $orderBy = NULL, $order = NULL)
    {
        //            $package_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE
        $criteria = new CDbCriteria();
        if ($package_id) {
            $condition = 't.status=:status AND t.type=:type AND t.id<>:id';
            $params = array(':status' => self::PACKAGE_ACTIVE, ':type' => $type, ':id' => $package_id);
        } else {
            $condition = 't.status=:status AND t.type=:type';
            $params = array(':status' => self::PACKAGE_ACTIVE, ':type' => $type);
        }

        if ($vip_user !== NULL) { //checkout sim
            if ($vip_user === 0) {
                $condition .= ' AND (t.vip_user IS NULL OR t.vip_user = :vip_user)';
            } else {
                $condition .= ' AND (t.vip_user = :vip_user)';
            }
            $params[':vip_user'] = $vip_user;
        }

        if ($period) { //flexible
            $condition .= ' AND (t.period=:period)';
            $params[':period'] = $period;
        }
        if ($freedooPackage != self::ALL_PACKAGE) {
            if ($freedooPackage == self::OTHER_PACKAGE) {
                $condition .= ' AND (t.freedoo=:freedoo1 OR t.freedoo=:freedoo2)';
                $params[':freedoo1'] = self::OTHER_PACKAGE;
                $params[':freedoo2'] = self::LOCAL_PACKAGE;
            } else {
                $condition .= ' AND (t.freedoo=:freedoo)';
                $params[':freedoo'] = $freedooPackage;
            }
        }

        $criteria->condition = $condition;
        $criteria->params = $params;

        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        $criteria->order = 't.sort_index DESC ,t.price ASC, t.freedoo';
        if ($orderBy) {
            $criteria->order .= ', t.' . $orderBy;
            if ($order) {
                $criteria->order .= ' ' . $order;
            }
        }
        if ($key) {
            $criteria->addSearchCondition('name', $key);
        }
        if ($dataProvider) {
            $cache_key = 'getListPackageByType_DataProvider_' . $type . $package_id . $vip_user . $period . $freedooPackage . $key . $orderBy . $order;
            $results = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = new CActiveDataProvider(self::model(), array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 't.sort_index',
                    ),
                    'pagination' => array(
                        'pageSize' => $limit,
                    )
                ));
                Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            }

            return $results;
        } else {
            $cache_key = 'getListPackageByType_' . $type . $package_id . $vip_user . $period . $freedooPackage . $key . $orderBy . $order;
            $results = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = self::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            }

            return $results;
        }
    }
    public static function getListFiberFreedoo()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 13 AND freedoo = 1 AND package_local = 0 order by price ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }

    public static function getListFiberToanQuoc()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 13 AND freedoo = 0 AND package_local = 0 order by name ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    public static function getListComboToanQuoc()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 16 AND freedoo = 0 order by name ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    public static function getListFiberFreedooToanQuoc()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 13 AND freedoo = 1 AND package_local = 0 order by price ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    public static function getListHomeBundleToanQuoc()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND type = 17 AND freedoo = 0 order by name ASC";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    /**
     *
     * @param           $package_id
     * @param           $vip_user
     * @param           $period
     * @param bool|TRUE $dataProvider
     * @param int $limit
     * @param int $offset
     * @param string $key |
     * @param string $orderBy | using for searching Packages
     * @param string $order |
     *
     * @return array|CActiveDataProvider
     */
    public static function getListPackageHot($package_id = '', $dataProvider = TRUE, $vip_user = NULL, $period = NULL, $limit = 24, $offset = 0, $freedooPackage = self::ALL_PACKAGE, $key = NULL, $orderBy = NULL, $order = NULL)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "*";
        if ($package_id) {
            $condition = 't.status=:status AND t.hot=:hot AND t.id<>:id';
            $params = array(':status' => self::PACKAGE_ACTIVE, ':hot' => self::HOT, ':id' => $package_id);
        } else {
            $condition = 't.status=:status AND t.hot=:hot';
            $params = array(':status' => self::PACKAGE_ACTIVE, ':hot' => self::HOT);
        }
        if ($vip_user !== NULL) { //checkout sim
            $condition .= ' AND (t.vip_user = :vip_user)';
            $params[':vip_user'] = $vip_user;
        }
        if ($period) { //flexible
            $condition .= ' AND (t.period=:period)';
            $params[':period'] = $period;
        }
        if ($freedooPackage != self::ALL_PACKAGE) {
            if ($freedooPackage == self::OTHER_PACKAGE) {
                $condition .= ' AND (t.freedoo=:freedoo1 OR t.freedoo=:freedoo2)';
                $params[':freedoo1'] = self::OTHER_PACKAGE;
                $params[':freedoo2'] = self::LOCAL_PACKAGE;
            } else {
                $condition .= ' AND (t.freedoo=:freedoo)';
                $params[':freedoo'] = $freedooPackage;
            }
        }

        $criteria->condition = $condition;
        $criteria->params = $params;

        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        $criteria->order = 't.sort_index DESC ,t.price ASC, t.freedoo';
        if ($orderBy) {
            $criteria->order .= ', t.' . $orderBy;
            if ($order) {
                $criteria->order .= ' ' . $order;
            }
        }
        if ($key) {
            $criteria->addSearchCondition('name', $key);
        }
        if ($dataProvider) {
            $cache_key = 'getListPackageHot_DataProvider_' . $package_id . $vip_user . $period . $freedooPackage . $key . $orderBy . $order;
            $results = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = new CActiveDataProvider(self::model(), array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 't.sort_index,',
                    ),
                    'pagination' => array(
                        'pageSize' => $limit,
                    )
                ));
                Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            }

            return $results;
        } else {
            $cache_key = 'getListPackageHot_' . $package_id . $vip_user . $period . $freedooPackage . $key . $orderBy . $order;
            $results = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = self::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            }

            return $results;
        }
    }

    public function getPackagePeriodLabel($period)
    {
        $array_period = array(
            self::PERIOD_1 => Yii::t('web/portal', 'package_day'),
            self::PERIOD_7 => Yii::t('web/portal', 'package_week'),
            self::PERIOD_30 => Yii::t('web/portal', 'package_month'),
            self::PERIOD_180 => Yii::t('web/portal', 'package_month_6'),
            self::PERIOD_210 => Yii::t('web/portal', 'package_month_7'),
            self::PERIOD_240 => Yii::t('web/portal', 'package_month_8'),
            self::PERIOD_360 => Yii::t('web/portal', 'package_month_12'),
            self::PERIOD_450 => Yii::t('web/portal', 'package_month_15'),
            self::PERIOD_480 => Yii::t('web/portal', 'package_month_16'),
        );
        if ($period > 0) {
            if (isset($array_period[$period])) {
                $period_label = $array_period[$period];
            } else {
                $period_label = $period . ' ' . Yii::t('web/portal', 'package_day');
            }
        } else {
            $period_label = 'Lượt';
        }

        return $period_label;
    }

    /**
     * Đăng ký gói cước đổi quà.
     *
     * @param $customer
     * @param $package
     * @param $transaction_id
     *
     * @return bool
     */
    public static function registerFreePackage($customer, $package, $transaction_id)
    {
        //Call api đăng ký gói cước.
        $type = 'web_register_packages_public';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $package_code = isset($package->code) ? $package->code : '';
        if (isset($package->extra_params) && !empty($package->extra_params)) {
            $package_code .= ';' . $package->extra_params;
        }
        $data = array(
            'msisdn' => isset($customer->phone) ? $customer->phone : '',
            'package_code' => $package_code,
        );

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($GLOBALS['config_common']['api']['hostname'], 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        //call api
        $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    /**
     * @param $package_code
     *
     * @return int
     */
    public function getAmountPackage($package_code)
    {
        $amount = 0;
        foreach ($package_code as $code) {
            $price = 0;
            $modelPackage = WPackage::model()->find('code=:code', array(':code' => $code));
            if ($modelPackage) {
                //check price_discount
                if ($modelPackage->price_discount > 0) {
                    $modelPackage->price = $modelPackage->price_discount;
                } elseif ($modelPackage->price_discount == -1) {
                    $modelPackage->price = 0;
                }
                $price = $modelPackage->price;
            }
            $amount += $price;
        }

        return $amount;
    }

    public static function checkVipUser()
    {
        $flag = FALSE;
        if (
            !Yii::app()->user->isGuest && Yii::app()->user->customer_type == WPackage::VIP_USER
            && Yii::app()->user->sim_freedoo == WCustomers::SIM_FREEDOO
        ) {
            $flag = TRUE;
        }

        return $flag;
    }

    /**
     * call api check price_discount
     *
     * @param OrdersData $orders_data
     * @param WOrders $modelOrder
     * @param WPackage $modelPackage
     * @param            $flag
     */
    public function checkDiscountPricePackage(OrdersData $orders_data, WOrders $modelOrder, WPackage &$modelPackage, &$flag = FALSE)
    {
        $data_input = array(
            'so_tb' => $modelOrder->phone_contact,
            'package_code' => $modelPackage->code,
            'promo_code' => $modelOrder->promo_code,
        );

        //call api web_check_ctkm
        $data_output = $orders_data->checkDiscountPricePackage($data_input);
        if ($data_output > 0) { //price_discount
            //check price_discount
            if ($modelPackage->price_discount > 0) {
                $modelPackage->price = $modelPackage->price_discount;
            } elseif ($modelPackage->price_discount == -1) {
                $modelPackage->price = 0;
            }
            $flag = TRUE;
        }
    }

    /**
     * list package flexible
     *
     * @param $package_code
     *
     * @return array
     */
    public function getListPackageFlexible($package_code)
    {
        $packages = array();
        $arr_flexible = $this->getListPackageType(TRUE);
        foreach ($package_code as $code) {
            $modelPackage = WPackage::model()->find('code=:code', array(':code' => $code));
            if ($modelPackage) {
                if (isset($arr_flexible[$modelPackage->type])) {
                    $packages[] = $modelPackage;
                }
            }
        }

        return $packages;
    }

    /**
     * get link change package
     *
     * @param $code
     *
     * @return string
     */
    public static function getLinkChangePackage($code)
    {
        $link = '';
        $package = WPackage::model()->find('code=:code AND type=:type', array(':code' => $code, ':type' => self::PACKAGE_POSTPAID));
        if ($package) {
            $link = CHtml::link(
                'Chuyển đổi gói cước',
                Yii::app()->controller->createUrl('package/listChangePackage', array('package' => $code)),
                array('title' => '', 'style' => 'margin-left: 10px;')
            );
        }

        return $link;
    }

    /**
     * get all packages active(api vne)
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array|mixed|null
     */
    public static function getListPackageApi($limit = 0, $offset = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status=:status';
        $criteria->params = array(':status' => self::PACKAGE_ACTIVE);
        $arr_type = array(
            self::PACKAGE_PREPAID,
            self::PACKAGE_POSTPAID,
            self::PACKAGE_DATA,
            self::PACKAGE_VAS,
            self::PACKAGE_CALL_INT,
            self::PACKAGE_CALL_EXT,
            self::PACKAGE_SMS_INT,
            self::PACKAGE_SMS_EXT,
            self::PACKAGE_DATA_FLEX,
        );
        $criteria->addInCondition('t.type', $arr_type);

        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        $criteria->order = 't.sort_index';

        $packages = self::model()->findAll($criteria);
        $results = array();
        if ($packages) {
            foreach ($packages as $key => $item) {
                $results[$key]['id'] = $item->id;
                $results[$key]['name'] = $item->name;
                $results[$key]['type'] = $item->type;
                $results[$key]['short_description'] = $item->short_description;
                $results[$key]['description'] = $item->description;
                $results[$key]['price'] = $item->price;
                $results[$key]['price_discount'] = $item->price_discount;
                $results[$key]['sort_index'] = $item->sort_index;
                $results[$key]['period'] = $item->period;
                $results[$key]['vip_user'] = $item->vip_user;
                $results[$key]['hot'] = $item->hot;
                $results[$key]['sms_external'] = $item->sms_external;
                $results[$key]['sms_internal'] = $item->sms_internal;
                $results[$key]['call_external'] = $item->call_external;
                $results[$key]['call_internal'] = $item->call_internal;
                $results[$key]['data'] = $item->data;
                $results[$key]['range_age'] = $item->range_age;
                $results[$key]['stock_id'] = $item->stock_id;
                $results[$key]['freedoo'] = $item->freedoo;
                $results[$key]['display_type'] = $item->display_type;
                $results[$key]['highlight'] = $item->highlight;
            }
        }

        return $results;
    }

    /**
     * list package roaming
     *
     * @param      $type
     * @param bool $dataProvider
     * @param int $limit
     * @param int $offset
     *
     * @return CActiveDataProvider|static[]
     */
    public static function getListPackageRoaming($type, $dataProvider = TRUE, $limit = 6, $offset = 0)
    {
        $criteria = new CDbCriteria();
        $condition = 't.status=:status AND t.type=:type';
        $params = array(':status' => self::PACKAGE_ACTIVE, ':type' => $type);

        $criteria->select = 't.*';
        $criteria->distinct = TRUE;

        $criteria->condition = $condition;
        $criteria->params = $params;

        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        $criteria->order = 't.sort_index';
        if ($dataProvider) {
            //                $cache_key = 'getListPackageRoaming_DataProvider_' . $type . $limit . $offset;
            //                $results   = Yii::app()->cache->get($cache_key);
            //                if (!$results) {
            $results = new CActiveDataProvider(self::model(), array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 't.sort_index',
                ),
                'pagination' => array(
                    'pageSize' => $limit,
                )
            ));
            //                    Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            //                }
        } else {
            //                $cache_key = 'getListPackageRoaming_' . $type . $limit . $offset;
            //                $results   = Yii::app()->cache->get($cache_key);
            //                if (!$results) {
            $results = self::model()->findAll($criteria);
            //                    Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            //                }
        }

        return $results;
    }

    /**
     * @param $type int
     *
     * @return string - url
     */
    public static function getPackageIconByType($type)
    {
        $url = Yii::app()->theme->baseUrl;

        switch ($type) {
            case WPackage::PACKAGE_HOT:
                $url .= '/images/package_hot.png';
                break;
            case WPackage::PACKAGE_PREPAID:
                $url .= '/images/package_prepaid.png';
                break;
            case WPackage::PACKAGE_POSTPAID:
                $url .= '/images/package_postpaid.png';
                break;
            case WPackage::PACKAGE_DATA:
                $url .= '/images/package_data.png';
                break;
            case WPackage::PACKAGE_VAS:
                $url .= '/images/package_vas.png';
                break;
            case WPackage::PACKAGE_DATA_FLEX:
                $url .= '/images/package_flexible.png';
                break;
            default:
                $url .= '/images/package_postpaid.png';
                break;
        }

        return $url;
    }

    public static function checkSimFreedoo($msisdn)
    {
        $flag = FALSE;
        $msisdn = CFunction::makePhoneNumberBasic($msisdn);
        $msisdn_standard = CFunction::makePhoneNumberStandard($msisdn);


        $sim = WSim::model()->find('(msisdn=:msisdn OR msisdn=:msisdn_standard) AND status=:status', array(
            ':msisdn' => $msisdn,
            ':msisdn_standard' => $msisdn_standard,
            ':status' => WCustomers::SIM_FREEDOO,
        ));

        $customer = WCustomers::model()->find('(phone=:msisdn OR phone=:msisdn_standard) AND customer_type=:customer_type', array(
            ':msisdn' => $msisdn,
            ':msisdn_standard' => $msisdn_standard,
            ':customer_type' => WPackage::VIP_USER,
        ));
        if ($sim || $customer) { //SIM_FREEDOO || VIP_USER
            $flag = TRUE;
        } else {
            $_ora = Oracle::getInstance();
            $_ora->connect();

            $sql = "SELECT
                    COUNT(*) AS EXIST
                    FROM SDL_ACTIONS
                    WHERE (MSISDN = :MSISDN OR MSISDN = :MSISDN_STANDARD) AND ASSIGN_KIT_STATUS = 10
                    ";

            $stmt = oci_parse($_ora->oraConn, $sql);
            oci_bind_by_name($stmt, ':MSISDN', $msisdn);
            oci_bind_by_name($stmt, ':MSISDN_STANDARD', $msisdn_standard);
            oci_execute($stmt);
            $result = oci_fetch_array($stmt, OCI_ASSOC);
            if ($result['EXIST'] > 0) {
                $flag = true;
            }
        }

        return $flag;
    }

    /**
     * @param $arr_package_id
     *
     * @return array
     */
    public static function getListPackageById($arr_package_id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->distinct = TRUE;
        $criteria->addInCondition('id', $arr_package_id, TRUE);
        $results = self::model()->findAll($criteria);

        return $results;
    }

    /**
     * Lấy các gói hiện thị trên trang checkout
     * @param $package_type
     * @return object
     */
    public static function getListPackageByDisplayCheckout($package_type)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->condition = 'display_in_checkout = :display_in_checkout AND status = :status';
        $criteria->params = array(':display_in_checkout' => $package_type, ':status' => 1);
        $results = self::model()->findAll($criteria);
        return $results;
    }

    /**
     * Get list package converting prepaid to postpaid
     *
     * @var $freedoo int
     * @return array
     */
    public static function getListPackagePtp($freedoo = null)
    {
        $data = null;
        if ($freedoo !== null) {
            if ($freedoo == WPackage::OTHER_PACKAGE) {
                $data = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, null, false, null, null, 0, 0, WPackage::OTHER_PACKAGE);
            } else if ($freedoo == WPackage::FREEDOO_PACKAGE) {
                $data = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, null, false, null, null, 0, 0, WPackage::FREEDOO_PACKAGE);
            }
        } else {
            $package_other = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, null, false, null, null, 0, 0, WPackage::OTHER_PACKAGE);
            $package_freedoo = WPackage::getListPackageByType(WPackage::PACKAGE_POSTPAID, null, false, null, null, 0, 0, WPackage::FREEDOO_PACKAGE);
            $data = array_merge($package_freedoo, $package_other);
        }
        return $data;
    }


    public function getInfoPhone($data)
    {
        $type = 'web_get_msisdn_info';
        $id = Yii::app()->request->csrfToken;
        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);
        //call api
        $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }
    public function checkKHDN($data)
    {
        $type = 'web_check_khdn';
        $id = Yii::app()->request->csrfToken;
        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);
        //call api
        $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    public static function getSubPackageByParentCode($parent_code)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "t.code IN (SELECT child_code FROM tbl_parent_child_package_codes WHERE parent_code =:parent_code) AND status =:status";
        $criteria->params = array(
            ':parent_code' => $parent_code,
            ':status' => WPackage::PACKAGE_ACTIVE
        );
        $data = self::model()->findAll($criteria);
        return $data;
    }

    public static function getPackageAPI()
    {
        $criteria = new CDbCriteria();
        $criteria->select = ['name', 'code_vnpt'];
        $criteria->condition = 'type =:type AND status=:status';
        $criteria->params = array(
            ':type' => WPackage::PACKAGE_API,
            ':status' => WPackage::PACKAGE_ACTIVE
        );
        return self::model()->findAll($criteria);
    }
    public static function getPackageByCodeVnpt($code_vnpt)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'code_vnpt=:code AND status=:status';
        $criteria->params = array(
            ':code' => $code_vnpt,
            ':status' => WPackage::PACKAGE_ACTIVE
        );
        return self::model()->find($criteria);
    }

    public static function checkRU($package_code)
    {
        $ru_package = ['RU10_3815', 'RU3_1191', 'RU7_1645']; //RU10, RU3, RU7
        if (in_array($package_code, $ru_package)) {
            return true;
        }
        return false;
    }

    public static function getAnchorLink($package_name)
    {
        $anchor =  'tit_policy';
        $policy_R = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7', 'R8', 'R8', 'R10', 'R11', 'R12', 'R13', 'R14', 'R5'];
        $policy_RU = ['RU1', 'RU2', 'RU3'];
        if ($package_name  == 'R500') {
            $anchor = 'r500_policy';
        } else if (in_array($package_name, $policy_R)) {
            $anchor = 'r1_policy';
        } else if (in_array($package_name, $policy_RU)) {
            $anchor = 'ru1_policy';
        }
        return $anchor;
    }
    public static function getListTrasua()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND code_vnpt IN ('SPS_PRODUCT_HEY79_6T','SPS_PRODUCT_HEYZB')";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
    public static function getListBanhMy()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->condition = "status = 1 AND code_vnpt IN ('SPS_PRODUCT_HEYTIIN')";
        $data = WPackage::model()->findAll($criteria);
        return $data;
    }
}
