<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo CHtml::encode(Yii::app()->name); ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p>Congratulations! You have successfully created your Yii application.</p>

                <p>You may change the content of this page by modifying the following two files:</p>
                <ul>
                    <li>View file: <code><?php echo __FILE__; ?></code></li>
                    <li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
                </ul>

                <p>For more details on how to further develop this application, please read
                    the <a href="http://www.yiiframework.com/doc/">documentation</a>.
                    Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
                    should you have any questions.</p>
            </div>
        </div>
    </div>
</div>