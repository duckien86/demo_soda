<?php
/**
 * @var $this SimKitController
 * @var $model WPackage
 */
?>
<div id="block_package">
    <div class="block_package container">
        <div class="row">
            <div class="col-md-5">
                <div class="package_thumbnail">
                    <a href="javascript:void(0);">
                        <img src="<?php echo Yii::app()->baseUrl . '/uploads/' . $model->thumbnail_2 ?>">
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div class="package_content">
                    <div class="title">
                        <?php echo CHtml::encode($model->name)?>
                    </div>

                    <div class="price">
                        <div class="price_num">
                            <?php $price = (!empty($model->price_discount)) ? $model->price_discount : $model->price;
                            $price += 50000;
                            ?>
                            <?php echo number_format($price, 0,',', '.') . 'đ' ?>
                        </div>
                        <div class="price_note">
                            <?php echo CHtml::encode(Yii::t('web/portal','simkit_price_note'))?>
                        </div>
                    </div>

                    <div class="preferential_content">
                        <div class="title">
                            <?php echo Yii::t('web/portal','preferential_content');?>
                        </div>
                        <div class="content">
                            <?php echo nl2br($model->short_description)?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <a href="#" class="btn btn-lg btn-detail" data-toggle="modal" data-target="#modal_package">
                    <?php echo CHtml::encode(Yii::t('web/portal','view'))?>
                </a>
            </div>
        </div>
    </div>
    <?php $this->renderPartial('/simkit/_modal_package', array('model' => $model))?>
</div>

