<?php

    class AUserPermission
    {
        public static function checkUserPermission($controller, $action)
        {
            $controller = strtoupper($controller . 'Controller');
            if (Yii::app()->user->name === 'admin')
                return TRUE;
            if (!isset(Yii::app()->user->role))
                return FALSE;
            $userId   = Yii::app()->user->getId();
            $group_id = Yii::app()->user->role;
            /*get group permission*/
            $permission           = AGroupPermission::model()->findAll(
                'group_id = :group_id',
                array(
                    ':group_id' => $group_id,
                )
            );
            $arrayGroupPermission = array();
            foreach ($permission as $row) {
                $arrayGroupPermission[strtoupper($row['controller'])] = unserialize($row['permission']);
            }
            /*get user permission*/
            $uerPermission       = ASystemUserPermission::model()->findAll(
                'user_id = :user_id',
                array(
                    ':user_id' => $userId,
                )
            );
            $arrayUserPermission = array();
            if (is_array($uerPermission)) {
                foreach ($uerPermission as $row) {
                    $arrayUserPermission[strtoupper($row['controller'])] = unserialize($row['permission']);
                }
            }
            $resutUserPermission = array_merge($arrayGroupPermission, $arrayUserPermission);

            if (is_array($resutUserPermission)) {
                if (isset($resutUserPermission[$controller]) && in_array($action, $resutUserPermission[$controller]))
                    return TRUE;
            }

            return FALSE;
        }

    }

?>