<?php

    class WPointHistory extends PointHistory
    {

        public $total;

        const TYPE_ADD = 1;
        const TYPE_SUB = 0;

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'            => 'ID',
                'event'         => 'Lý do',
                'description'   => 'Mô tả',
                'amount'        => 'Điểm',
                'amount_before' => 'Điểm trước đó',
                'create_date'   => 'Ngày cập nhật ',
                'note'          => 'Note',
                'sso_id'        => 'User ID',
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
            $criteria->compare('event', $this->event, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('amount', $this->amount);
            $criteria->compare('amount_before', $this->amount_before);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('sso_id', $this->sso_id);

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
         * @return WPointHistory the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Đổi sự kiện.
         */
        public static function convertEvent($event, $type)
        {
            $arr_events = WCustomers::getListEvent($type);
            if (isset($arr_events[$event]) && !empty($arr_events[$event])) {
                $result = $arr_events[$event];
            } else {
                $result = $event;
            }

            return $result;
        }
    }
