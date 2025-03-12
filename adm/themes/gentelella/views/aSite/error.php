<?php
    $this->pageTitle   = Yii::app()->name . ' - Error';
    $this->breadcrumbs = array(
        'Error',
    );
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Error <?php echo $code; ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="error">
                    <?php echo CHtml::encode($message); ?>
                </div>
            </div>
        </div>
    </div>
</div>
