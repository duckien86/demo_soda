<?php
/**
 * @var $this SimKitController
 * @var $model WPackage
 */
?>
<div class="col-sm-6">
    <div class="item_simkit">
        <div class="simkit_thumbnail">
            <a href="<?php echo Yii::app()->controller->createUrl('simkit/detail',array('id' => $model->id))?>">
                <img src="<?php echo Yii::app()->baseUrl .'/uploads/'. $model->thumbnail_1 ?>">
            </a>
        </div>
        <div class="simkit_content">
            <div class="row">
                <div class="col-md-8">
                    <div class="title">
                        <?php echo CHtml::encode($model->name)?>
                    </div>
                    <div class="price">
                        <?php $price = (!empty($model->price_discount)) ? $model->price_discount : $model->price;
                        $price += 50000;
                        ?>
                        <?php echo CHtml::encode(Yii::t('web/portal','price') .': '. number_format($price, 0, ',', '.')) . 'đ/' . WPackage::model()->getPackagePeriodLabel($model->period)?>
                    </div>

                    <div class="highlight">
                        <?php if(!empty($model->highlight)){?>
                        <?php echo CHtml::encode($model->highlight);?>
                        <?php }else{ echo "&nbsp;"; }?>
                    </div>
                </div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-12">
                    <div class="action">
                        <a class="btn btn-lg btn-buynow" href="<?php echo Yii::app()->controller->createUrl('simkit/detail',array('id'=>$model->id))?>">
                            <?php echo Yii::t('web/portal','buy_now')?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

