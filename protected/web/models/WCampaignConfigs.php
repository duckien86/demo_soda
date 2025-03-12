<?php
    /**
     * Created by PhpStorm.
     * User: kienn
     * Date: 11/29/2017
     * Time: 4:07 PM
     */

    class WCampaignConfigs extends CampaignConfigs
    {
        public static function getByCampaign($utm_source, $utm_campaign)
        {
            if (empty($utm_source) || empty($utm_campaign)) return FALSE;

            $criteria = new CDbCriteria();
            $criteria->compare('utm_source', $utm_source, TRUE);
            $criteria->compare('utm_campaign', $utm_campaign, TRUE);

            return self::model()->find($criteria);
        }
    }