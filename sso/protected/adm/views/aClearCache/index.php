<?php
    $this->breadcrumbs = array(
        Yii::t('adm/admin', 'manage_cache'),
    );

    $this->pageTitle = Yii::t('adm/admin', 'manage_cache');
    $this->pageHint  = Yii::t('adm/admin', 'clear_cache');

    $this->widget('ext.ClearCacheWidget.ClearCacheWidget', array(
            'backend'  => TRUE, // true is enable function(Clear Cache). False is diable
            'frontend' => TRUE,
        )
    );
?>