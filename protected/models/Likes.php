<?php

    /**
     * This is the model class for table "sc_tbl_likes".
     *
     * The followings are the available columns in table 'sc_tbl_likes':
     *
     * @property string $id
     * @property string $sc_tbl_posts_id
     * @property string $sc_tbl_comments_id
     * @property string $sso_id
     * @property string $create_date
     */
    class Likes extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'sc_tbl_likes';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('sc_tbl_posts_id, sc_tbl_comments_id', 'length', 'max' => 20),
                array('sso_id', 'length', 'max' => 255),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sc_tbl_posts_id, sc_tbl_comments_id, sso_id, create_date', 'safe', 'on' => 'search'),
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
                'id'                 => 'ID',
                'sc_tbl_posts_id'    => 'Sc Tbl Posts',
                'sc_tbl_comments_id' => 'Sc Tbl Comments',
                'sso_id'             => 'Sso',
                'create_date'        => 'Create Date',
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
            $criteria->compare('sc_tbl_posts_id', $this->sc_tbl_posts_id, TRUE);
            $criteria->compare('sc_tbl_comments_id', $this->sc_tbl_comments_id, TRUE);
            $criteria->compare('sso_id', $this->sso_id, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);

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
         * @return Likes the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
