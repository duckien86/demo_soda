<?php
class CskhPhoneList extends PhoneList {
    public $start_date;
    public $end_date;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{phone_list}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('phone, service, tool, user', 'length', 'max'=>255),
            array('created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, phone, service, tool, user, created_on', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'phone' => 'Phone',
            'service' => 'Service',
            'tool' => 'Tool',
            'user' => 'User',
            'created_on' => 'Created On',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('phone',$this->phone,true);
        $criteria->compare('service',$this->service,true);
        $criteria->compare('tool',$this->tool,true);
        $criteria->compare('user',$this->user,true);
        $criteria->compare('created_on',$this->created_on,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PhoneList the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public function getlistphone(){
        $criteria = new CDbCriteria();
        $criteria->select = "*";
        $criteria->addCondition("created_on >= '$this->start_date' AND created_on <= '$this->end_date' ");
        $data = self::model()->findAll($criteria);
        return $data;
    }
}
?>