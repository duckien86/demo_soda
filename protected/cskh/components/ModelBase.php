<?php

    /**
     * Created by PhpStorm.
     * User: hoangduong
     * Date: 06/07/2017
     * Time: 1:13 CH
     */
    class ModelBase extends CActiveRecord
    {

        //Lưu log sau khi save.
        public function afterSave()
        {
            $model          = new Log();
            $controllerName = Yii::app()->controller->id;
            if ($model) {
                $model->user_id     = Yii::app()->user->id;
                $model->create_date = date('Y-m-d h:i:s');
                $model->action      = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
                $model->object_id   = $this->id;
                $model->ip_adress   = $_SERVER['REMOTE_ADDR'];
                $model->save();
            }
        }

        //Lưu log sau khi update.
        public function afterUpdate()
        {
            $model          = new Log();
            $controllerName = Yii::app()->controller->id;
            if ($model) {
                $model->user_id     = Yii::app()->user->id;
                $model->create_date = date('Y-m-d h:i:s');
                $model->action      = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
                $model->object_id   = $this->id;
                $model->ip_adress   = $_SERVER['REMOTE_ADDR'];
                $model->save();
            }
        }
    }