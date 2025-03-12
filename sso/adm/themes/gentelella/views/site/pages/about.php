<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('app', 'About');
$this->breadcrumbs = array(
    Yii::t('app', 'About'),
);
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo Yii::t('app', 'About') ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p>This is a "static" page. You may change the content of this page
                    by updating the file <code><?php echo __FILE__; ?></code>.</p>
            </div>
        </div>
    </div>
</div>