<?php

/**
 * This is the model class for table "{{location_napas}}".
 *
 * The followings are the available columns in table '{{location_napas}}':
 *
 * @property string $id
 * @property string $vpc_AccessCode
 * @property string $vpc_Merchant
 * @property string $secure_secret
 * @property string $end_point
 * @property string $bank_account
 * @property string $bank_name
 */
class ALocationNapas extends LocationNapas
{

    private static $VPC_ACCESS_CODE = 'ECAFAB';
    private static $VPC_MERCHANT = 'VINAPHONE';
    private static $SECURE_SECRET = '198BE3F2E8C75A53F38C1C4A5B6DBA27';

    public $province;
    public $province_code;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('province_code, bank_account, bank_name', 'required'),
            array('id, vpc_AccessCode, vpc_Merchant, secure_secret, end_point, bank_account, bank_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, vpc_AccessCode, vpc_Merchant, secure_secret, end_point, bank_account, bank_name, province', 'safe', 'on' => 'search'),
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
            'id'             => Yii::t('adm/label','id'),
            'vpc_AccessCode' => Yii::t('adm/label','vpc_AccessCode'),
            'vpc_Merchant'   => Yii::t('adm/label','vpc_Merchant'),
            'secure_secret'  => Yii::t('adm/label','secure_secret'),
            'end_point'      => Yii::t('adm/label','end_point'),
            'bank_account'   => Yii::t('adm/label','bank_account'),
            'bank_name'      => Yii::t('adm/label','bank_name'),

            'province'       => Yii::t('adm/label','province'),
            'province_code'  => Yii::t('adm/label','province'),
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
        $criteria->compare('t.vpc_AccessCode', $this->vpc_AccessCode, TRUE);
        $criteria->compare('t.vpc_Merchant', $this->vpc_Merchant, TRUE);
        $criteria->compare('t.secure_secret', $this->secure_secret, TRUE);
        $criteria->compare('t.end_point', $this->end_point, TRUE);
        $criteria->compare('t.bank_account', $this->bank_account, TRUE);
        $criteria->compare('t.bank_name', $this->bank_name, TRUE);

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
     * @return LocationNapas the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function beforeSave()
    {
        $this->id = $this->province_code;
        $this->vpc_AccessCode = self::$VPC_ACCESS_CODE;
        $this->vpc_Merchant = self::$VPC_MERCHANT;
        $this->secure_secret = self::$SECURE_SECRET;
        return true;
    }
}
