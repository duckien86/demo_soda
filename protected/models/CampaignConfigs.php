<?php

    /**
     * This is the model class for table "{{campaign_configs}}".
     *
     * The followings are the available columns in table '{{campaign_configs}}':
     *
     * @property string  $id
     * @property string  $utm_source
     * @property string  $utm_medium
     * @property string  $utm_campaign
     * @property string  $target_link
     * @property integer $type
     * @property string  $create_date
     * @property integer $status
     */
    class CampaignConfigs extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{campaign_configs}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('type, status', 'numerical', 'integerOnly' => TRUE),
                array('utm_source, utm_medium, utm_campaign, target_link', 'length', 'max' => 255),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, utm_source, utm_medium, utm_campaign, target_link, type, create_date, status', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'utm_source'   => 'Utm Source',
                'utm_medium'   => 'Utm Medium',
                'utm_campaign' => 'Utm Campaign',
                'target_link'  => 'Target Link',
                'type'         => 'Type',
                'create_date'  => 'Create Date',
                'status'       => 'Status',
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
            $criteria->compare('utm_source', $this->utm_source, TRUE);
            $criteria->compare('utm_medium', $this->utm_medium, TRUE);
            $criteria->compare('utm_campaign', $this->utm_campaign, TRUE);
            $criteria->compare('target_link', $this->target_link, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('create_date', $this->create_date, TRUE);
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
         * @return CampaignConfigs the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
