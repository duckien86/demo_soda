<?php

class AAffiliateManager extends AffiliateManager{

    CONST  AFF_ACTIVE   = 1;
    CONST  AFF_INACTIVE = 0;

    CONST TYPE_LINK             = 1; //Mua hàng qua link: accesstrade
    CONST TYPE_PARTNER_SITE     = 2; //Mua hàng tại nhà: mua hàng tại nhà
    CONST TYPE_PARTNER_SITE_VNP = 3; //Mua hàng tại nhà: chọn số VNP
    CONST TYPE_AGENCY           = 5; //Đại lý tổ chức đăng nhập backend: cellphoneS
    CONST TYPE_FULL_API         = 6; //Mua hàng qua hình thức gọi full api: zalo

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, code', 'required'),
            array('code', 'unique'),
            array('name, code, url_redirect', 'length', 'max' => 255),
            array('type, status', 'numerical', 'integerOnly' => TRUE),
            array('create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, code, type, url_redirect, status', 'safe', 'on' => 'search'),
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
            'id'            => 'ID',
            'name'          => 'Tên',
            'code'          => 'Mã',
            'status'        => 'Trạng thái',
            'type'          => 'Loại',
            'url_redirect'  => 'Url Redirect',
            'create_date'   => 'Ngày tạo',
        );
    }

    public function beforeSave()
    {
        if(!empty($this->create_date)){
            $this->create_date = date('Y-m-d H:i:s');
        }
        RETURN TRUE;
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, TRUE);
        $criteria->compare('code', $this->code, TRUE);
        $criteria->compare('status', $this->status);

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
     * @return AAffiliateManager the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public static function getListStatus(){
        return array(
            self::AFF_ACTIVE => Yii::t('adm/label','active'),
            self::AFF_INACTIVE => Yii::t('adm/label','inactive'),
        );
    }

    public static function getStatusLabel($status){
        $data = AAffiliateManager::getListStatus();
        return isset($data[$status]) ? $data[$status] : $status;
    }

    public static function getAffiliateName($id)
    {
        $name = '';
        $model = AAffiliateManager::model()->findByPk($id);
        if($model){
            $name = $model->name;
        }
        return $name;
    }

    public static function getAffiliateNameByCode($code)
    {
        $name = '';
        $model = AAffiliateManager::model()->findByAttributes(array('code' => $code));
        if($model){
            $name = $model->name;
        }
        return $name;
    }

    public static function getListChannel()
    {
        $data = array();
        $criteria = new CDbCriteria();
        $criteria->condition = "t.code != 'freedoo'";
        $models = AffiliateManager::model()->findAll($criteria);
        if($models){
            $data = CHtml::listData($models, 'code', 'name');
        }
        return $data;
    }
}
