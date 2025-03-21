<?php $this->widget('booster.widgets.TbAlert'); ?>
<div class="well">
    <?php
        if (true) {
            $this->widget('booster.widgets.TbButton', array(
                'label' => Yii::t('adm/admin', 'Clear WEB Assets'),
                'context'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'large', // null, 'large', 'small' or 'mini'
                'htmlOptions'=>array('onClick'=>"location.href='".Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'web_assets'))."'")
            ));

            $this->widget('booster.widgets.TbButton', array(
                'label' => Yii::t('adm/admin', 'Clear WEB Cache'),
                'context'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'large', // null, 'large', 'small' or 'mini'
                'htmlOptions'=>array('onClick'=>"location.href='".Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'web_cache'))."'")
            ));
        }

        if ($backend) {
            $this->widget('booster.widgets.TbButton', array(
                'label' => Yii::t('adm/admin', 'Clear ADMIN Assets'),
                'context'  => 'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'large', // null, 'large', 'small' or 'mini'
                //'url'   => Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'adm_assets')), // null, 'large', 'small' or 'mini'
                'htmlOptions'=>array('onClick'=>"location.href='".Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'adm_assets'))."'")
            ));

            $this->widget('booster.widgets.TbButton', array(
                'label' => Yii::t('adm/admin', 'Clear ADMIN Runtime'),
                'context'  => 'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'large', // null, 'large', 'small' or 'mini'
                'htmlOptions'=>array('onClick'=>"location.href='".Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'adm_cache'))."'")
            ));
        }
        if ($agency) {
            echo '<hr>';
            $this->widget('booster.widgets.TbButton', array(
                'label' => Yii::t('adm/admin', 'Clear AGENCY Assets'),
                'context'  => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'large', // null, 'large', 'small' or 'mini'
                //'url'   => Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'adm_assets')), // null, 'large', 'small' or 'mini'
                'htmlOptions'=>array('onClick'=>"location.href='".Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'agency_assets'))."'")
            ));

            $this->widget('booster.widgets.TbButton', array(
                'label' => Yii::t('adm/admin', 'Clear AGENCY Runtime'),
                'context'  => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'large', // null, 'large', 'small' or 'mini'
                'htmlOptions'=>array('onClick'=>"location.href='".Yii::app()->createUrl('aClearCache/index', array('cache_id' => 'agency_cache'))."'")
            ));
        }
    ?>
</div>