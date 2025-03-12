<?php

    /**
     * This is the model class for table "{{question_answer}}".
     *
     * The followings are the available columns in table '{{question_answer}}':
     *
     * @property string  $id
     * @property string  $question
     * @property string  $answer
     * @property integer $cate_qa_id
     * @property integer $status
     */
    class AQuestionAnswer extends QuestionAnswer
    {
        const ACTIVE   = 1;
        const INACTIVE = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('cate_qa_id, status', 'numerical', 'integerOnly' => TRUE),
                array('question, answer', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, question, answer, cate_qa_id, status', 'safe', 'on' => 'search'),
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
                'id'         => 'ID',
                'question'   => 'Câu hỏi',
                'answer'     => 'Câu trả lời',
                'cate_qa_id' => 'Danh mục',
                'status'     => 'Trạng thái',
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
            $criteria->compare('question', $this->question, TRUE);
            $criteria->compare('answer', $this->answer, TRUE);
            $criteria->compare('cate_qa_id', $this->cate_qa_id);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         * @param string $className active record class name.
         * @return QuestionAnswer the static model class
         */
        public static function model($className=__CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Viet hoa trang thai
         */
        public static function getStatus($id)
        {
            $data = array(
                self::INACTIVE => 'ẨN',
                self::ACTIVE   => 'KÍCH HOẠT',
            );

            return isset($data[$id]) ? $data[$id] : '';
        }
    }
