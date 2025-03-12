<?php

/**
 * This is the model class for table "{{location_vietinbank}}".
 *
 * The followings are the available columns in table '{{location_vietinbank}}':
 *
 * @property string $id
 * @property string $access_key
 * @property string $profile_id
 * @property string $secret_key
 * @property string $end_point
 * @property string $qr_code_merchant_id
 * @property string $vnp_TmnCode
 * @property string $vnp_hashSecret
 * @property string $vnp_end_point
 * @property string $olpay_merchantId
 * @property string $olpay_providerId
 * @property string $pServiceCode
 * @property string $pProviderId
 * @property string $pMerchantId
 * @property string $pEnd_point
 */
class ALocationVietinbank extends LocationVietinbank
{
    
    public static $ACCESS_KEY       = '4a4c017100233e719b068a23ef0c432c';
    public static $PROFILE_ID       = '7DF70835-E46F-4D61-8EDB-6EE6FCCAA637';
    public static $SECRET_KEY       = '4b766d937b334bd5810801df7ddb3b7d8e544b02a3a54628b90cf6293ff09f73201622fe2829407f88c7ba0619cc5af7d4f6fbf5548443c2a6bdc59fea4f5cb01de615088dec4256b4052c59b54154bbd57688b488244affb2ed7fbfba31d3f5f81dbf94946042be89eb584c9f9866dfd598215509b742d484d0525b5cd9df62';
    public static $END_POINT        = 'https://secureacceptance.cybersource.com/pay';

    public static $VNP_TMN_CODE         = '';
    public static $VNP_HASH_SECRET      = '';
    public static $VNP_END_POINT        = '';
    public static $OLPAY_PROVIDER_ID    = '';
    public static $OLPAY_MERCHANT_ID    = '';
    public static $P_PROVIDER_ID        = '';
    public static $P_MERCHANT_ID        = '';

    public static $P_SERVICE_CODE   = 'VINAPHONE';
    public static $P_END_POINT      = 'http://192.168.6.156:9008/directpay/init-session/v1';


    public $province;
    public $province_code;

    public $prefix;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('province_code, qr_code_merchant_id', 'required'),
            array('prefix', 'required', 'on' => array('create', 'update')),
            array('id, access_key, profile_id, end_point, qr_code_merchant_id, vnp_TmnCode, vnp_hashSecret, vnp_end_point, olpay_merchantId, olpay_providerId, pServiceCode, pProviderId, pMerchantId, pEnd_point', 'length', 'max' => 255),
            array('secret_key', 'length', 'max' => 500),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, access_key, profile_id, secret_key, end_point, qr_code_merchant_id, vnp_TmnCode, vnp_hashSecret, vnp_end_point, olpay_merchantId, olpay_providerId, pServiceCode, pProviderId, pMerchantId, pEnd_point, province', 'safe', 'on' => 'search'),
        );
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
            'id'                  => Yii::t('adm/label','id'),
            'access_key'          => Yii::t('adm/label','access_key'),
            'profile_id'          => Yii::t('adm/label','profile_id'),
            'secret_key'          => Yii::t('adm/label','secret_key'),
            'end_point'           => Yii::t('adm/label','end_point'),
            'qr_code_merchant_id' => Yii::t('adm/label','qr_code_merchant_id'),
            'vnp_TmnCode'         => Yii::t('adm/label','vnp_TmnCode'),
            'vnp_hashSecret'      => Yii::t('adm/label','vnp_hashSecret'),
            'vnp_end_point'       => Yii::t('adm/label','vnp_end_point'),
            'olpay_merchantId'    => Yii::t('adm/label','olpay_merchantId'),
            'olpay_providerId'    => Yii::t('adm/label','olpay_providerId'),
            'pServiceCode'        => Yii::t('adm/label','pServiceCode'),
            'pProviderId'         => Yii::t('adm/label','pProviderId'),
            'pMerchantId'         => Yii::t('adm/label','pMerchantId'),
            'pEnd_point'          => Yii::t('adm/label','pEnd_point'),

            'province'            => Yii::t('adm/label','province'),
            'province_code'       => Yii::t('adm/label','province'),
            'prefix'              => Yii::t('adm/label','prefix'),
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

        $criteria->compare('t.id', $this->id, TRUE);
        $criteria->compare('t.access_key', $this->access_key, TRUE);
        $criteria->compare('t.profile_id', $this->profile_id, TRUE);
        $criteria->compare('t.secret_key', $this->secret_key, TRUE);
        $criteria->compare('t.end_point', $this->end_point, TRUE);
        $criteria->compare('t.qr_code_merchant_id', $this->qr_code_merchant_id, TRUE);
        $criteria->compare('t.vnp_TmnCode', $this->vnp_TmnCode, TRUE);
        $criteria->compare('t.vnp_hashSecret', $this->vnp_hashSecret, TRUE);
        $criteria->compare('t.vnp_end_point', $this->vnp_end_point, TRUE);
        $criteria->compare('t.olpay_merchantId', $this->olpay_merchantId, TRUE);
        $criteria->compare('t.olpay_providerId', $this->olpay_providerId, TRUE);
        $criteria->compare('t.pServiceCode', $this->pServiceCode, TRUE);
        $criteria->compare('t.pProviderId', $this->pProviderId, TRUE);
        $criteria->compare('t.pMerchantId', $this->pMerchantId, TRUE);
        $criteria->compare('t.pEnd_point', $this->pEnd_point, TRUE);

        $tempCriteria = new CDbCriteria();
        $tempCriteria->join = 'LEFT JOIN tbl_province p ON t.id = p.code';
        $criteria->compare('p.name', $this->province, true);

        $criteria->mergeWith($tempCriteria, 'AND');

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
     * @return LocationVietinbank the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function beforeSave()
    {
        $this->id = $this->province_code;
        if(empty($this->access_key)){
            $this->access_key = self::$ACCESS_KEY;
        }
        if(empty($this->profile_id)){
            $this->profile_id = self::$PROFILE_ID;
        }
        if(empty($this->secret_key)){
            $this->secret_key = self::$SECRET_KEY;
        }
        if(empty($this->end_point)){
            $this->end_point = self::$END_POINT;
        }
        if(empty($this->pServiceCode)){
            $this->pServiceCode = self::$P_SERVICE_CODE;
        }
        if(empty($this->pEnd_point)){
            $this->pEnd_point = self::$P_END_POINT;
        }
        return true;
    }
}
