<?php

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    class AIOTreeFunction
    {

        public static function getTreeArray($preCheckValue = NULL)
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = 'id, name';
            $criteria->condition = 'status=:status AND (parent_id=0 OR parent_id IS NULL)';
            $criteria->params    = array(':status' => AMenu::MENU_ACTIVE);

            $criteria->order = '`name` asc';
            $operatorObj     = AMenu::model()->findAll($criteria);

            $operator     = CHtml::listData($operatorObj, 'id', 'name');
            $operator_ary = array();

            foreach ($operator as $key => $value) {
                $operator_ary[$key] = array(
                    'parentid' => '',
                    'value'    => $key,
                    'text'     => $value,
                    'checked'  => self::checkedValue($key, $preCheckValue),
                );

                self::getArrayChild($preCheckValue, $key, $operator_ary);
            }

            return $operator_ary;
        }

        /**
         * @param $preCheckValue
         * @param $parent_id
         * @param $operator_ary
         */
        public static function getArrayChild($preCheckValue, $parent_id, &$operator_ary)
        {
            $criteria_sub            = new CDbCriteria();
            $criteria_sub->select    = 'id, name';
            $criteria_sub->condition = 'status=:status AND parent_id=:parent_id';
            $criteria_sub->params    = array(':status' => AMenu::MENU_ACTIVE, ':parent_id' => $parent_id);
            $sub                     = AMenu::model()->findAll($criteria_sub);
            if (count($sub) > 0) {
                $sub = CHtml::listData($sub, 'id', 'name');
                foreach ($sub as $key => $value) {
                    $key_sub            = $parent_id . '_' . $key;
                    $operator_ary[$key] = array(
                        'parentid' => $parent_id,
                        'value'    => $key_sub,
                        'text'     => $value,
                        'checked'  => self::checkedValue($key_sub, $preCheckValue),
                    );
                    self::getArrayChild($preCheckValue, $key, $operator_ary);
                }
            }
        }

        /**
         * @param $key
         * @param $preCheckValue
         *
         * @return bool
         */
        private static function checkedValue($key, $preCheckValue)
        {
            if (isset($preCheckValue)) {
                if (is_array($preCheckValue) && in_array($key, $preCheckValue)) {
                    return TRUE;
                }
            }

            return FALSE;
        }

        /**
         * @param $ary_input
         *
         * @return array|bool
         */
        public static function getValidAry($ary_input)
        {
            if (is_array($ary_input)) {
                $parent = array();
                foreach ($ary_input as $str) {

                    $temp = explode('_', $str);

                    if (count($temp) > 1) {
                        $parent[$temp[0]][] = $temp[1];
                    } else {
                        $parent[$temp[0]] = array();
                    }
                }
                if (count($parent) > 0)
                    return $parent;
                else
                    return FALSE;
            }

            return FALSE;
        }

        /**
         * @param $ary_input
         *
         * @return array|bool
         */
        public static function getValidAryConvert($ary_input)
        {
            if (is_array($ary_input)) {
                $return = array();
                foreach ($ary_input as $k => $v) {
                    $return[] = $k;
                    if (is_array($v)) {
                        foreach ($v as $sk => $sv) {
                            $return[] = $k . '_' . $sv;
                        }
                    }
                }

                if (count($return) > 0)
                    return $return;
                else
                    return FALSE;
            }

            return FALSE;
        }
    }

?>
