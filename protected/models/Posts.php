<?php

    /**
     * This is the model class for table "sc_tbl_posts".
     *
     * The followings are the available columns in table 'sc_tbl_posts':
     *
     * @property string  $id
     * @property string  $title
     * @property string  $content
     * @property string  $image
     * @property string  $media_url
     * @property string  $total_comment
     * @property string  $total_like
     * @property string  $sso_id
     * @property string  $status
     * @property string  $note
     * @property string  $create_date
     * @property string  $last_update
     * @property string  $post_category_id
     * @property integer $get_award
     * @property integer $sort_order
     * @property string  $tags
     */
    class Posts extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'sc_tbl_posts';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('get_award, sort_order', 'numerical', 'integerOnly' => TRUE),
                array('title, image, media_url, sso_id', 'length', 'max' => 255),
                array('total_comment, total_like, status', 'length', 'max' => 10),
                array('note, tags', 'length', 'max' => 500),
                array('post_category_id', 'length', 'max' => 11),
                array('content, create_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, title, content, image, media_url, total_comment, total_like, sso_id, status, note, create_date, last_update, post_category_id, get_award, sort_order, tags', 'safe', 'on' => 'search'),
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
                'id'               => 'ID',
                'title'            => 'Title',
                'content'          => 'Content',
                'image'            => 'Image',
                'media_url'        => 'Media Url',
                'total_comment'    => 'Total Comment',
                'total_like'       => 'Total Like',
                'sso_id'           => 'Sso',
                'status'           => 'Status',
                'note'             => 'Note',
                'create_date'      => 'Create Date',
                'last_update'      => 'Last Update',
                'post_category_id' => 'Post Category',
                'get_award'        => 'Get Award',
                'sort_order'       => 'Sort Order',
                'tags'             => 'Tags',
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
            $criteria->compare('title', $this->title, TRUE);
            $criteria->compare('content', $this->content, TRUE);
            $criteria->compare('image', $this->image, TRUE);
            $criteria->compare('media_url', $this->media_url, TRUE);
            $criteria->compare('total_comment', $this->total_comment, TRUE);
            $criteria->compare('total_like', $this->total_like, TRUE);
            $criteria->compare('sso_id', $this->sso_id, TRUE);
            $criteria->compare('status', $this->status, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('post_category_id', $this->post_category_id, TRUE);
            $criteria->compare('get_award', $this->get_award);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare('tags', $this->tags);

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
         * @return Posts the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
