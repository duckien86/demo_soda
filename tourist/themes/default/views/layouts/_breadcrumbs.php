<div id="breadcrumbs" class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <?php if(isset($this->breadcrumbs)):?>
                    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links'=>$this->breadcrumbs,
                        'separator'=>'<i>&rarr;</i>',
                    )); ?><!-- breadcrumbs -->
                <?php endif?>
            </div>

            <div class="col-md-6 text-right">
                <?php echo Yii::t('tourist/label', 'hello') . ':' ?>
                <span class="user"><?php echo Yii::app()->user->name ?></span>
            </div>
        </div>
    </div>
</div>