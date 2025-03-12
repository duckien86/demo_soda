<?php
/**
 * @var $this PackageController
 * @var $model WPackage
 */
?>
<div class="col-sm-4">
    <div class="item_package item_package_other">
        <div class="title">
            <a href="<?php echo Yii::app()->controller->createUrl('package/detail', array('slug' => $model->slug));?>">
                <h4>
                    <?php echo CHtml::encode($model->name)?>
                </h4>
                <p>&nbsp;</p>
                <?php if($model->price_discount){?>
                    <span class="discount">
                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_label_discount.png">
                    <label><?php echo CHtml::encode(number_format($model->price_discount, 0,',','.')) . '%';?></label>
                </span>
                <?php } ?>
            </a>
        </div>

        <div class="item_package_separator"></div>

        <div class="package_description">

            <?php if($model->vip_user == WPackage::VIP_USER){?>
                <div class="package_ctv">
                    <?php echo CHtml::encode(Yii::t('web/portal','package_ctv_only'))?>
                </div>
            <?php }?>

            <?php if($model->display_type == WPackage::DISPLAY_TYPE_RESOURCE || empty($model->display_type)){?>
                <?php if($model->call_internal || $model->call_external){?>
                    <div class="call_des">
                        <div class="row">
                            <div class="col-xs-3">
                        <span class="package_icon">
                            <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_icon_phone.png"/>
                        </span>
                            </div>
                            <div class="col-xs-9">
                                <div class="content">
                                    <?php if($model->call_internal){
                                        if($model->call_internal != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->call_internal, 0,',','.') . ' ' . Yii::t('web/portal','minute') . ' ' . Yii::t('web/portal','call_internal_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','call_internal_text')).'</p>';
                                        }
                                    }?>

                                    <?php if($model->call_external){
                                        if($model->call_external != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->call_external, 0,',','.') . ' ' . Yii::t('web/portal','minute') . ' ' . Yii::t('web/portal','call_external_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','call_external_text')).'</p>';
                                        }
                                    }?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <?php if($model->sms_internal || $model->sms_external){?>
                    <div class="sms_des">
                        <div class="row">
                            <div class="col-xs-3">
                        <span class="package_icon">
                            <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_icon_sms.png"/>
                        </span>
                            </div>
                            <div class="col-xs-9">
                                <div class="content">
                                    <?php if($model->sms_internal){
                                        if($model->sms_internal != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->sms_internal, 0,',','.') . ' ' . Yii::t('web/portal','sms_internal_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','sms_internal_text')).'</p>';
                                        }
                                    }?>

                                    <?php if($model->sms_external){
                                        if($model->sms_external != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->sms_external, 0,',','.') . ' ' . Yii::t('web/portal','sms_external_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','sms_external_text')).'</p>';
                                        }
                                    }?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <?php if($model->data){?>
                    <div class="data_des">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="package_icon">
                                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_icon_phonewave.png"/>
                                </span>
                            </div>
                            <div class="col-xs-9">
                                <div class="content">
                                    <?php if($model->data != WPackage::FREE){
                                        $data = ($model->data == intval($model->data)) ? number_format($model->data, 0,',','.') : str_replace('.',',',$model->data);
                                        echo '<p>'.CHtml::encode($data . ' GB ' . Yii::t('web/portal','data_text')).'</p>';
                                    }else{
                                        echo '<p>'.CHtml::encode(Yii::t('web/portal','free_data')).'</p>';
                                    }?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            <?php }?>

            <?php if ($model->display_type == WPackage::DISPLAY_TYPE_SHORT_DES){?>
                <div class="short_des">
                    <?php
                    $list_short_des = explode("\n",$model->short_description);
                    foreach ($list_short_des as $short_des){
                        $short_des = trim($short_des);
                        if(!empty($short_des)){
                            ?>
                            <div class="row">
                                <div class="col-xs-3">
                            <span class="package_icon">
                                <img src="<?php echo Yii::app()->theme->baseUrl?>/images/check.png" style="width:19px; margin-left:1px"/>
                            </span>
                                </div>
                                <div class="col-xs-9">
                                    <div class="content">
                                        <?php echo CHtml::encode($short_des)?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php }?>

        </div>

        <div class="price">
            <?php $price = (!empty($model->price_discount)) ? $model->price_discount : $model->price; ?>
            <?php echo CHtml::encode(number_format($price, 0, ',', '.'))?><sup>đ</sup><span><?php echo '/' . WPackage::model()->getPackagePeriodLabel($model->period)?></span>
        </div>

        <div class="item_package_separator"></div>

        <div class="action text-center">
            <?php if ($model->name == 'HEY' || $model->name == 'HEYTIIN' || $model->name == 'FTIN' || $model->name == 'FLY') { ?>
                <a href="" class="btn btn-register" data-toggle="modal"
                   data-target="#showmodal_<?php echo $model->id ?>">
                    <?php echo CHtml::encode(Yii::t('web/portal', 'register')); ?>
                </a>
            <?php } else { ?>
                <a href="<?php echo Yii::app()->controller->createUrl('package/register', array('package' => $model->id)); ?>"
                   class="btn btn-register">
                    <?php echo CHtml::encode(Yii::t('web/portal', 'register')); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="showmodal_<?php echo $model->id ?>" role="dialog">
    <div class="modal-dialog modal-sm modal-custom">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thông báo</h4>
            </div>
            <div class="modal-body">
                <p class="ct-tb">Gói cước chỉ dành cho thuê bao Freedoo hòa mạng mới. Vui lòng chọn sim mới để đăng ký gói cước hấp dẫn này.</p>
            </div>
            <div class="modal-footer">
                <a href="<?php echo Yii::app()->controller->createUrl('sim/index'); ?>" class="btn btn-danger simso">Chọn sim số</a>
            </div>
        </div>

    </div>
</div>
<style>
    .ct-tb{
        font-size: 17px;
    }
    .simso{
        background: #ed0677 !important;
    }
    .modal-footer{
        text-align: center !important;
    }
    .modal-custom{
        width: 447px !important;
    }
</style>