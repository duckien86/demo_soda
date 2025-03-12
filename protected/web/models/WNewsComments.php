<?php
 class WNewsComments extends NewsComments{
     CONST ACTIVE = 1;
     CONST INACTIVE = 0;
     /**
      * @return string the associated database table name
      */
     public function tableName()
     {
         return '{{comments}}';
     }

     /**
      * @return array validation rules for model attributes.
      */
     public function rules()
     {
         // NOTE: you should only define rules for those attributes that
         // will receive user inputs.
         return array(
             array('news_id, comment_parent, status', 'numerical', 'integerOnly'=>true),
             array('ip, username, email', 'length', 'max'=>255),
             array('content, created_on', 'safe'),
             // The following rule is used by search().
             // @todo Please remove those attributes that should not be searched.
             array('id, news_id, ip, comment_parent, username, email, content, status, created_on', 'safe', 'on'=>'search'),
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
             'news_id' => 'News',
             'ip' => 'Ip',
             'comment_parent' => 'Comment Parent',
             'username' => 'Username',
             'email' => 'Email',
             'content' => 'Content',
             'status' => '1: hiện
2: ẩn',
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
         $criteria->compare('news_id',$this->news_id);
         $criteria->compare('ip',$this->ip,true);
         $criteria->compare('comment_parent',$this->comment_parent);
         $criteria->compare('username',$this->username,true);
         $criteria->compare('email',$this->email,true);
         $criteria->compare('content',$this->content,true);
         $criteria->compare('status',$this->status);
         $criteria->compare('created_on',$this->created_on,true);

         return new CActiveDataProvider($this, array(
             'criteria'=>$criteria,
         ));
     }

     /**
      * Returns the static model of the specified AR class.
      * Please note that you should have this exact method in all your CActiveRecord descendants!
      * @param string $className active record class name.
      * @return NewsComments the static model class
      */
     public static function model($className=__CLASS__)
     {
         return parent::model($className);
     }

     public function getFetchComment($news_id){
         $active = self::ACTIVE;
         $criteria = new CDbCriteria();
         $criteria->select = "*";
         $criteria->condition = "comment_parent = 0 AND username IS NOT NULL AND news_id = '$news_id' AND status = $active  ORDER BY id DESC ";
         $data = self::model()->findAll($criteria);
         return $data;
     }
     public function getReplyFetchComment($news_id = 0){
         $active = self::ACTIVE;
         $criteria = new CDbCriteria();
         $criteria->select = "*";
         $criteria->condition = "username IS NOT NULL AND news_id = '$news_id' AND status = $active ORDER BY id DESC";
         $data = self::model()->findAll($criteria);
         return $data;
     }
}