<?php

class APackage extends Package
{
    const PACKAGE_ACTIVE   = 1;
    const PACKAGE_INACTIVE = 0;

    const PACKAGE_PREPAID   = 1;
    const PACKAGE_POSTPAID  = 2;
    const PACKAGE_DATA      = 3;
    const PACKAGE_VAS       = 4;
    const PACKAGE_SIMKIT    = 5;
    const PACKAGE_REDEEM    = 6;
    const FLEXIBLE_CALL_INT = 7;
    const FLEXIBLE_CALL_EXT = 8;
    const FLEXIBLE_SMS_INT  = 9;
    const FLEXIBLE_SMS_EXT  = 10;
    const FLEXIBLE_DATA     = 11;
    const PACKAGE_ROAMING   = 12;
    const PACKAGE_FIBER   = 13;
    const PACKAGE_MYTV   = 14;
    const PACKAGE_API   = 15;
    const PACKAGE_MYTV_FIBER   = 16;
    const PACKAGE_HOME_BUNDLE   = 17;
    const VIP_USER = 1;

    const PERIOD_1  = 1;
    const PERIOD_3  = 3;
    const PERIOD_7  = 7;
    const PERIOD_5  = 5;
    const PERIOD_10 = 10;
    const PERIOD_15 = 15;
    const PERIOD_30 = 30;
    const PERIOD_45 = 45;
    const PERIOD_60 = 60;
    const PERIOD_90 = 90;
    const PERIOD_180 = 180;
    const PERIOD_210 = 210;
    const PERIOD_240 = 240;
    const PERIOD_360 = 360;
    const PERIOD_450 = 450;
    const PERIOD_480 = 480;

    const NATION_ALL = 0;
    const FREEDOO    = 1;
    const LOCAL      = 2;

    public $min_age;
    public $max_age;
    public $province_code;
    public $highlight;

    const ALL_PACKAGE     = -1; //Tất cả gói
    const FREEDOO_PACKAGE = 1; //Gói Freedoo
    const OTHER_PACKAGE   = 0; //Gói toàn quốc
    const LOCAL_PACKAGE   = 2; //Gói cục bộ (nằm trong gói toàn quốc)

    const DELIVERY_LOCATION_IN_CHECKOUT_NO = 0; //Không hiển thị
    const DELIVERY_LOCATION_IN_CHECKOUT_SHOP = 1; //Chỉ nhận hàng tại điểm giao dịch
    const DELIVERY_LOCATION_IN_CHECKOUT_HOME = 2; //Chỉ nhận hàng tại nhà riêng

    const DISPLAY_IN_CHECKOUT_HIDDEN = 0; //Không hiển thị
    const DISPLAY_IN_CHECKOUT_PREPAID = 1; //Chỉ hiển thị với sim trả trước
    const DISPLAY_IN_CHECKOUT_POSTPAID = 2; //Chỉ hiển thị với sim trả sau
    const SMART_TV = 1; //Loai TV thông minh
    const NORMAL_TV = 2; //Loại TV thường

    public $data_original;
    public $type_tv;
    public $commercial;
    public $service_type;
    public $price_stb = null;
    public $price_no_stb = null;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, name, slug, code_vnpt, type, short_description', 'required'),
            array('code', 'match', 'pattern' => '/^([A-Z0-9_\.-])+$/', 'message' => "Mã gói cước phải là chữ in hoa và không chứa ký tự đặc biệt"),
            array('type, status, sort_index, category_id, period, home_display, vip_user, sms_external, sms_internal, call_external, call_internal, hot, stock_id, freedoo, display_type, display_in_checkout, delivery_location_in_checkout,package_local', 'numerical', 'integerOnly' => true),
            array('id', 'length', 'max' => 100),
            array('name, code, short_description, thumbnail_1, thumbnail_2, thumbnail_3, extra_params, highlight, slug', 'length', 'max' => 255),
            array('price, price_discount, commission_rate_publisher, commission_rate_agency', 'length', 'max' => 10),
            array('period', 'length', 'max' => 11),
            array('code', 'unique', 'className' => 'APackage', 'attributeName' => 'code', 'message' => 'Mã gói này đã tồn tại!'),
            //                array('name', 'unique', 'className' => 'APackage', 'attributeName' => 'name', 'message' => 'Tên gói này đã tồn tại!'),
            array('slug', 'unique', 'className' => 'APackage', 'attributeName' => 'slug', 'message' => 'Slug này đã tồn tại!'),
            array('type_tv', 'checktypetv'),
            array('description,hot,display_in_checkout, delivery_location_in_checkout, data_original, parent_code, type_tv, commercial,service_type,price_stb,price_no_stb', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, code, code_vnpt, short_description, description, price, type, extra_params, status, thumbnail_1, thumbnail_2, thumbnail_3, point, sort_index, category_id, period, commission_rate_publisher, commission_rate_agency, home_display, vip_user, price_discount, sms_external, sms_internal, call_external, call_internal, data, range_age, hot, stock_id, highlight, freedoo, cp_id, display_type, slug, display_in_checkout, delivery_location_in_checkout, package_local, type_tv, commercial,service_type,price_stb,price_no_stb', 'safe', 'on' => 'search'),
        );
    }

    public function checkFloat()
    {
        if (!empty($this->data)) {
            if (!is_float($this->data)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'                => Yii::t('adm/label', 'id'),
            'name'              => Yii::t('adm/label', 'name'),
            'code'              => Yii::t('adm/label', 'package_code'),
            'short_description' => Yii::t('adm/label', 'short_description'),
            'description'       => Yii::t('adm/label', 'description'),
            'price'             => Yii::t('adm/label', 'price') . " (VNĐ)",
            'type'              => Yii::t('adm/label', 'package_type'),
            'extra_params'      => Yii::t('adm/label', 'extra_params'),
            'status'            => Yii::t('adm/label', 'status'),
            'vip_user'          => Yii::t('adm/label', 'vip_user'),
            'period'            => Yii::t('adm/label', 'period'),
            'price_discount'    => 'Số tiền khuyến mãi (VNĐ)',
            'sort_index'        => 'Sắp xếp',
            'sms_external'      => 'Sms ngoại mạng',
            'sms_internal'      => 'Sms nội mạng',
            'call_external'     => 'Thoại ngoại mạng',
            'call_internal'     => 'Thoại nội mạng',
            'data'              => 'Data',
            'range_date'        => 'Khoảng tuổi',
            'stock_id'          => 'Mã kho',
            'min_age'           => 'Tuổi bé nhất',
            'max_age'           => 'Tuổi lớn nhất',
            'province_code'     => 'Tỉnh áp dụng',
            'highlight'         => 'HighLight',
            'freedoo'           => 'Loại gói',
            'display_type'      => 'Kiểu hiển thị',
            'cp_id'             => 'Đối tác',
            'slug'              => 'Slug',
            'code_vnpt'         => 'Mã gói trả trước',
            'display_in_checkout'       => 'Hiện thị trên trang checkout',
            'delivery_location_in_checkout'  => 'Ràng buộc địa chỉ nhận hàng theo gói cước',
            'package_local' => 'Package Local',
            'parent_code'   => Yii::t('adm/label', 'parent_code'),
            'type_tv' => Yii::t('adm/label', 'type_tv'),
            'commercial' => Yii::t('adm/label', 'commercial'),
            'service_type' => 'Loại gói (Thường / Chờ kích hoạt)',
            'price_stb' => 'Giá có STB',
            'price_no_stb' => 'Giá ko STB',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, TRUE);
        $criteria->compare('name', $this->name, TRUE);
        $criteria->compare('code', $this->code, TRUE);
        $criteria->compare('short_description', $this->short_description, TRUE);
        $criteria->compare('description', $this->description, TRUE);
        $criteria->compare('price', $this->price, TRUE);
        $criteria->compare('type', $this->type);
        $criteria->compare('extra_params', $this->extra_params, TRUE);
        $criteria->compare('status', $this->status);
        $criteria->compare('vip_user', $this->vip_user);
        $criteria->compare('period', $this->period);
        $criteria->compare('package_local', $this->package_local);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return APackage the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getAllPackageType($simkit = TRUE)
    {
        if ($simkit) {
            return array(
                self::PACKAGE_PREPAID   => Yii::t('adm/label', 'package_prepaid'),
                self::PACKAGE_POSTPAID  => Yii::t('adm/label', 'package_postpaid'),
                self::PACKAGE_DATA      => Yii::t('adm/label', 'package_data'),
                self::PACKAGE_VAS       => Yii::t('adm/label', 'package_vas'),
                self::PACKAGE_SIMKIT    => Yii::t('adm/label', 'package_simkit'),
                self::PACKAGE_REDEEM    => Yii::t('adm/label', 'package_redeem'),
                self::FLEXIBLE_CALL_INT => Yii::t('adm/label', 'flexible_call_int'),
                self::FLEXIBLE_CALL_EXT => Yii::t('adm/label', 'flexible_call_ext'),
                self::FLEXIBLE_SMS_INT  => Yii::t('adm/label', 'flexible_sms_int'),
                self::FLEXIBLE_SMS_EXT  => Yii::t('adm/label', 'flexible_sms_ext'),
                self::FLEXIBLE_DATA     => Yii::t('adm/label', 'flexible_data'),
                self::PACKAGE_ROAMING   => Yii::t('adm/label', 'package_roaming'),
                self::PACKAGE_FIBER  => Yii::t('adm/label', 'package_fiber'),
                self::PACKAGE_MYTV  => Yii::t('adm/label', 'package_mytv'),
                self::PACKAGE_API  => Yii::t('adm/label', 'package_api'),
                self::PACKAGE_MYTV_FIBER  => Yii::t('adm/label', 'package_mytv_fiber'),
                self::PACKAGE_HOME_BUNDLE  => Yii::t('adm/label', 'package_home_bundle'),
            );
        }

        return array(
            self::PACKAGE_PREPAID   => Yii::t('adm/label', 'package_prepaid'),
            self::PACKAGE_POSTPAID  => Yii::t('adm/label', 'package_postpaid'),
            self::PACKAGE_DATA      => Yii::t('adm/label', 'package_data'),
            self::PACKAGE_VAS       => Yii::t('adm/label', 'package_vas'),
            self::PACKAGE_REDEEM    => Yii::t('adm/label', 'package_redeem'),
            self::FLEXIBLE_CALL_INT => Yii::t('adm/label', 'flexible_call_int'),
            self::FLEXIBLE_CALL_EXT => Yii::t('adm/label', 'flexible_call_ext'),
            self::FLEXIBLE_SMS_INT  => Yii::t('adm/label', 'flexible_sms_int'),
            self::FLEXIBLE_SMS_EXT  => Yii::t('adm/label', 'flexible_sms_ext'),
            self::FLEXIBLE_DATA     => Yii::t('adm/label', 'flexible_data'),
            self::PACKAGE_ROAMING   => Yii::t('adm/label', 'package_roaming'),
            self::PACKAGE_FIBER  => Yii::t('adm/label', 'package_fiber'),
            self::PACKAGE_MYTV  => Yii::t('adm/label', 'package_mytv'),
            self::PACKAGE_API  => Yii::t('adm/label', 'package_api'),
            self::PACKAGE_MYTV_FIBER  => Yii::t('adm/label', 'package_mytv_fiber'),
            self::PACKAGE_HOME_BUNDLE  => Yii::t('adm/label', 'package_home_bundle'),
        );
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    public function getPackageType($type)
    {
        $array_type = $this->getAllPackageType();

        return (isset($array_type[$type])) ? $array_type[$type] : $type;
    }

    public static function getListType()
    {
        return array(
            self::PACKAGE_PREPAID   => Yii::t('adm/label', 'package_prepaid'),
            self::PACKAGE_POSTPAID  => Yii::t('adm/label', 'package_postpaid'),
            self::PACKAGE_DATA      => Yii::t('adm/label', 'package_data'),
            self::PACKAGE_VAS       => Yii::t('adm/label', 'package_vas'),
            self::PACKAGE_SIMKIT    => Yii::t('adm/label', 'package_simkit'),
            self::PACKAGE_REDEEM    => Yii::t('adm/label', 'package_redeem'),
            self::FLEXIBLE_CALL_INT => Yii::t('adm/label', 'flexible_call_int'),
            self::FLEXIBLE_CALL_EXT => Yii::t('adm/label', 'flexible_call_ext'),
            self::FLEXIBLE_SMS_INT  => Yii::t('adm/label', 'flexible_sms_int'),
            self::FLEXIBLE_SMS_EXT  => Yii::t('adm/label', 'flexible_sms_ext'),
            self::FLEXIBLE_DATA     => Yii::t('adm/label', 'flexible_data'),
            self::PACKAGE_ROAMING   => Yii::t('adm/label', 'package_roaming'),
            self::PACKAGE_FIBER  => Yii::t('adm/label', 'package_fiber'),
            self::PACKAGE_MYTV  => Yii::t('adm/label', 'package_mytv'),
            self::PACKAGE_HOME_BUNDLE  => Yii::t('adm/label', 'package_home_bundle'),
        );
    }

    public static function getTypeLabel($type)
    {
        $data = self::getListType();
        return (isset($data[$type])) ? $data[$type] : $type;
    }

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        return ($this->status == self::PACKAGE_ACTIVE) ? Yii::t('adm/label', 'active') : Yii::t('adm/label', 'inactive');
    }

    public function getDisplayType()
    {
        return array(
            0 => 'Hiển thị data',
            1 => 'Hiển thị mô tả ngắn',
        );
    }
    public function getTypeTV()
    {
        return array(
            '' => 'Chọn loại TV',
            self::SMART_TV => 'Smart TV',
            self::NORMAL_TV => 'TV thường',
        );
    }
    public function getCps()
    {
        return array();
    }

    public static function getArrayPackagePeriod($day = TRUE)
    {
        if ($day) {
            return array(
                self::PERIOD_1  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_1)),
                self::PERIOD_3  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_3)),
                self::PERIOD_5  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_5)),
                self::PERIOD_7  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_7)),
                self::PERIOD_10 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_10)),
                self::PERIOD_15 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_15)),
                self::PERIOD_30 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_30)),
                self::PERIOD_45  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_45)),
                self::PERIOD_60  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_60)),
                self::PERIOD_90  => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_90)),
                self::PERIOD_180 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_180)),
                self::PERIOD_210 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_210)),
                self::PERIOD_240 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_240)),
                self::PERIOD_360 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_360)),
                self::PERIOD_450 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_450)),
                self::PERIOD_480 => Yii::t('adm/label', 'package_period', array('{period}' => self::PERIOD_480)),
            );
        } else {
            return array(
                self::PERIOD_1  => Yii::t('adm/label', 'package_day'),
                self::PERIOD_7  => Yii::t('adm/label', 'package_week'),
                self::PERIOD_30 => Yii::t('adm/label', 'package_month'),
            );
        }
    }

    public function getPackagePeriodLabel($period)
    {
        $array_period = $this->getArrayPackagePeriod();

        return (isset($array_period[$period])) ? $array_period[$period] : $period;
    }

    public function btnAddNation($id, $type)
    {
        if ($type == APackage::PACKAGE_ROAMING) {
            return CHtml::link(Yii::t('adm/label', 'add'), Yii::app()->controller->createUrl('aPackage/mapNation', array('id' => $id)), array('title' => ''));
        }

        return FALSE;
    }

    public function getTypeLocationPakage()
    {
        return array(
            self::NATION_ALL => 'Toàn quốc',
            self::FREEDOO    => 'Freedoo',
            self::LOCAL      => 'Cục bộ',
        );
    }

    public function afterFind()
    {
        if (isset($this->id)) {
            if (!empty($this->id)) {
                $pac_pro = APackagesProvince::model()->findAll('package_id= :package_id', array(':package_id' => $this->id));
                if ($pac_pro) {
                    if (!empty($pac_pro)) {
                        if (is_array($pac_pro)) {
                            foreach ($pac_pro as $value) {
                                $this->province_code[] = $value->province_code;
                            }
                        }
                    }
                }
            }
        }
        if ($this->range_age) {
            if (!empty($this->range_age)) {
                $ages = explode('-', $this->range_age);
                if (isset($ages[0])) {
                    $this->min_age = $ages[0];
                }
                if (isset($ages[1])) {
                    $this->max_age = $ages[1];
                }
            }
        }
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    /**
     * @param $arr_package_id
     *
     * @return array
     */
    public static function getListPackageById($arr_package_id)
    {
        $criteria           = new CDbCriteria();
        $criteria->select   = 't.*';
        $criteria->distinct = TRUE;
        $criteria->addInCondition('id', $arr_package_id, TRUE);
        $results = self::model()->findAll($criteria);

        return $results;
    }

    public function beforeValidate()
    {
        if (!empty($this->code_vnpt) && in_array($this->getScenario(), array('create', 'update'))) {
            $code = trim($this->code_vnpt);
            $code = preg_replace('/\s+/', ' ', $code);
            $this->code_vnpt = $code;
        }

        return TRUE;
    }

    public function beforeSave()
    {
        // Gen mã code theo code_vnpt
        if ($this->isNewRecord) {
            if (!empty($this->code_vnpt) && empty($this->code)) {

                $code = strtoupper(Utils::unsign_string($this->code_vnpt, '_')) . '_' . Utils::generateRandomString(4, TRUE);

                $criteria = new CDbCriteria();
                $criteria->condition = "t.code = '$code'";

                while (APackage::model()->find($criteria) != null) {
                    $code = strtoupper($this->code_vnpt) . '_' . Utils::generateRandomString(4, TRUE);
                    $criteria->condition = "t.code = '$code'";
                }

                $this->code = $code;
            }
        }

        if (isset($_POST['APackage']['min_age']) && isset($_POST['APackage']['max_age'])) {
            if (!empty($_POST['APackage']['min_age']) && !empty($_POST['APackage']['max_age'])) {
                $this->range_age = $_POST['APackage']['min_age'] . '-' . $_POST['APackage']['max_age'];
            }
        }
        if (isset($_POST['APackage']['data'])) {
            if (!empty($_POST['APackage']['data'])) {
                $this->data = str_replace(',', '.', $_POST['APackage']['data']);
            }
        }

        return TRUE;
    }

    /**
     * type  ex: 1:prepaid||2:postpaid||3:data||4:vas
     *
     * @param           $type
     * @param           $package_id
     * @param           $vip_user
     * @param           $period
     * @param bool|TRUE $dataProvider
     * @param int       $limit
     * @param int       $offset
     * @param int       $freedooPackage
     * @param string    $key     |
     * @param string    $orderBy | using for searching Packages
     * @param string    $order   |
     *
     * @return array|CActiveDataProvider
     */
    public static function getListPackageByType($type, $package_id = '', $dataProvider = TRUE, $vip_user = NULL, $period = NULL, $limit = 24, $offset = 0, $freedooPackage = self::ALL_PACKAGE, $key = NULL, $orderBy = NULL, $order = NULL)
    {
        $criteria = new CDbCriteria();
        if ($package_id) {
            $condition = 't.status=:status AND t.type=:type AND t.id<>:id';
            $params    = array(':status' => self::PACKAGE_ACTIVE, ':type' => $type, ':id' => $package_id);
        } else {
            $condition = 't.status=:status AND t.type=:type';
            $params    = array(':status' => self::PACKAGE_ACTIVE, ':type' => $type);
        }
        if ($vip_user) { //checkout sim
            $condition .= ' AND (t.vip_user IS NULL OR t.vip_user="" OR t.vip_user <> :vip_user)';
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
        $criteria->params    = $params;

        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        $criteria->order = 't.sort_index, t.freedoo';
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
            $results   = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array(
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
            $results   = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = self::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
            }

            return $results;
        }
    }


    public function afterSave()
    {

        //Xóa các phần tỉnh đang có.
        if (isset($_POST['APackage']['type'])) {
            if ($_POST['APackage']['type'] == APackage::PACKAGE_SIMKIT) {
                $package = APackage::model()->findByAttributes(array('code' => $this->code));
                if ($package) {
                    $pac_pro_del = APackagesProvince::model()->findAll('package_id= :package_id', array(':package_id' => $package->id));
                    if ($pac_pro_del) {
                        $id            = $package->id;
                        $connection    = Yii::app()->db;
                        $sql           = "DELETE FROM tbl_packages_province where package_id= :package_id";
                        $command       = $connection->createCommand();
                        $command->text = $sql;
                        $command->bindParam(':package_id', $id);

                        $command->execute();
                    }
                }
            }
        }
        if (isset($_POST['APackage']['type'])) {
            if ($_POST['APackage']['type'] == APackage::PACKAGE_FIBER) {
                $package = APackage::model()->findByAttributes(array('code' => $this->code));
                if ($package) {
                    $pac_pro_del = APackagesProvince::model()->findAll('package_id= :package_id', array(':package_id' => $package->id));
                    if ($pac_pro_del) {
                        $id            = $package->id;
                        $connection    = Yii::app()->db;
                        $sql           = "DELETE FROM tbl_packages_province where package_id= :package_id";
                        $command       = $connection->createCommand();
                        $command->text = $sql;
                        $command->bindParam(':package_id', $id);

                        $command->execute();
                    }
                }
            }
        }
        //Thêm các tỉnh mới.
        if (isset($_POST['APackage']['province_code'])) {
            $package = APackage::model()->findByAttributes(array('code' => $this->code));

            if (!empty($_POST['APackage']['province_code'])) {
                if (isset($_POST['APackage']['province_code'][0])) {
                    if ($_POST['APackage']['province_code'][0] != '') { //Check nếu ko phải chọn tất cả.
                        if (is_array($_POST['APackage']['province_code'])) {
                            if ($package) {
                                $array_insert = array();

                                foreach ($_POST['APackage']['province_code'] as $value) {
                                    $array_insert_key = array(
                                        'package_id'    => $package->id,
                                        'province_code' => $value,
                                    );
                                    $array_insert[]   = $array_insert_key;
                                }
                                $connection = Yii::app()->db->getSchema()->getCommandBuilder();
                                $command    = $connection->createMultipleInsertCommand('tbl_packages_province', $array_insert);
                                $command->execute();
                            }
                        }
                    } else { //Check nếu ko phải chọn tất cả.
                        if (isset($_POST['APackage']['type'])) {
                            if ($_POST['APackage']['type'] == APackage::PACKAGE_SIMKIT) {
                                $province     = AProvince::model()->findAll();
                                $array_insert = array();
                                foreach ($province as $value) {
                                    $array_insert_key = array(
                                        'package_id'    => $package->id,
                                        'province_code' => $value->code,
                                    );
                                    $array_insert[]   = $array_insert_key;
                                }
                                $connection = Yii::app()->db->getSchema()->getCommandBuilder();
                                $command    = $connection->createMultipleInsertCommand('tbl_packages_province', $array_insert);
                                $command->execute();
                            }
                        }
                    }
                }
            }
        }

        $history = new APackageHistory();
        $history->package_code = $this->code;
        $history->before_data = CJSON::encode($this->data_original);
        $history->after_data = CJSON::encode($this->attributes);
        $history->save();

        return TRUE;
    }

    /**
     * @param $code
     *
     * @return string
     */
    public static function getPackageNameByCode($code)
    {
        $result = '';
        $model = APackage::model()->findByAttributes(array('code' => $code));
        if ($model) {
            $result = $model->name;
        }
        return $result;
    }
    /**
     * @return array
     */
    public function getDisplayInCheckout()
    {
        return array(
            self::DISPLAY_IN_CHECKOUT_HIDDEN => 'Không hiển thị',
            self::DISPLAY_IN_CHECKOUT_PREPAID    => 'Chỉ hiển thị với sim trả trước',
            self::DISPLAY_IN_CHECKOUT_POSTPAID      => 'Chỉ hiển thị với sim trả sau',
        );
    }
    /**
     * @return array
     */
    public function getDeliveryLocationInCheckout()
    {
        return array(
            self::DELIVERY_LOCATION_IN_CHECKOUT_NO      => 'Không hiển thị',
            self::DELIVERY_LOCATION_IN_CHECKOUT_SHOP    => 'Chỉ nhận hàng tại điểm giao dịch',
            self::DELIVERY_LOCATION_IN_CHECKOUT_HOME    => 'Chỉ nhận hàng tại nhà riêng',
        );
    }
    public function getServiceType()
    {
        return array(
            0     => 'Gói thường',
            1     => 'Gói chờ kích hoạt',
            2     => 'Gói đặc thù cần(thanh toán Online)',
        );
    }

    public static function getAllAgencyPackage()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.type IN (
                '" . APackage::PACKAGE_PREPAID . "',
                '" . APackage::PACKAGE_POSTPAID . "',
                '" . APackage::PACKAGE_SIMKIT . "'
            )";
        $criteria->order = 't.type ASC, code ASC';

        $models = APackage::model()->findAll($criteria);

        $model_sim_prepaid = new APackage();
        $model_sim_prepaid->code = AAgencyContractDetail::ITEM_SIM_PREPAID;
        $model_sim_prepaid->name = 'SIM trả trước';
        $model_sim_prepaid->price = 0;
        $model_sim_prepaid->type = ASim::TYPE_PREPAID;

        $model_sim_postpaid = new APackage();
        $model_sim_postpaid->code = AAgencyContractDetail::ITEM_SIM_POSTPAID;
        $model_sim_postpaid->name = 'SIM trả sau';
        $model_sim_postpaid->price = 0;
        $model_sim_postpaid->type = ASim::TYPE_POSTPAID;

        array_unshift($models, $model_sim_prepaid, $model_sim_postpaid);

        return $models;
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
     * get all package code with active status
     * @return array
     */
    public static function getPackageCodes()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->condition = 'status = :status';
        $criteria->params = array(':status' => self::PACKAGE_ACTIVE);
        $results = self::model()->findAll($criteria);
        return CHtml::listData($results, 'code', 'name');
    }
    public function checktypetv()
    {
        if ($this->type == 14 && $this->type_tv == '') {
            $this->addError('type_tv', 'Là gói MyTV bắt buộc chọn loại TV');
            return FALSE;
        }
        return TRUE;
    }
}
