<?php

    /**
     * This is the model class for table "sc_tbl_comments".
     *
     * The followings are the available columns in table 'sc_tbl_comments':
     *
     * @property string  $id
     * @property string  $content
     * @property string  $image
     * @property string  $media_url
     * @property string  $sso_id
     * @property string  $sc_tbl_post_id
     * @property string  $note
     * @property string  $total_like
     * @property string  $status
     * @property string  $create_date
     * @property string  $last_update
     * @property integer $get_award
     */
    class Comments extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'sc_tbl_comments';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('image, media_url, note', 'length', 'max' => 500),
                array('sso_id', 'length', 'max' => 255),
                array('sc_tbl_post_id', 'length', 'max' => 11),
                array('total_like', 'length', 'max' => 20),
                array('status', 'length', 'max' => 8),
                array('content, create_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, content, image, media_url, sso_id, sc_tbl_post_id, note, total_like, status, create_date, last_update', 'safe', 'on' => 'search'),
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
                    'id'             => 'ID',
                    'content'        => 'Content',
                    'image'          => 'Image',
                    'media_url'      => 'Media Url',
                    'sso_id'         => 'Sso',
                    'sc_tbl_post_id' => 'Sc Tbl Post',
                    'note'           => 'Note',
                    'total_like'     => 'Total Like',
                    'status'         => 'Status',
                    'create_date'    => 'Create Date',
                    'last_update'    => 'Last Update',
                    'get_award'      => 'get_award',
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
            $criteria->compare('content', $this->content, TRUE);
            $criteria->compare('image', $this->image, TRUE);
            $criteria->compare('media_url', $this->media_url, TRUE);
            $criteria->compare('sso_id', $this->sso_id, TRUE);
            $criteria->compare('sc_tbl_post_id', $this->sc_tbl_post_id, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('total_like', $this->total_like, TRUE);
            $criteria->compare('status', $this->status, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);

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
         * @return Comments the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
