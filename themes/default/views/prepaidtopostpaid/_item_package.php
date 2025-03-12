<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPackage
 * @var $ptp WPrepaidToPostpaid
 */
$selected = ($ptp->package_code == $model->code) ? 'selected' : '';
?>
<div class="col-sm-4">
    <div class="item_package item_package_other <?php echo $selected?>" data-code="<?php echo $model->code?>">
        <div class="title">
            <h4>
                <?php echo CHtml::encode($model->name)?>
            </h4>
            <p>
                <?php
                $title_des = WPackage::model()->getPackageTypeLabel($model->type);
                $title_des.= ($model->freedoo == WPackage::FREEDOO_PACKAGE) ? ' - Freedoo' : '';
                echo CHtml::encode($title_des);
                ?>
            </p>

            <?php if($model->price_discount){?>
                <span class="discount">
                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_label_discount.png">
                    <label><?php echo CHtml::encode('KM')?></label>
                </span>
            <?php } ?>
        </div>

        <div class="item_package_separator"></div>

        <div class="package_description">

            <table class="table">
                <?php if($model->vip_user == WPackage::VIP_USER){?>
                    <tr class="package_ctv">
                        <td colspan="2" class="text-center"><?php echo CHtml::encode(Yii::t('web/portal','package_ctv_only'))?></td>
                    </tr>
                <?php }?>

                <?php if($model->display_type == WPackage::DISPLAY_TYPE_RESOURCE || empty($model->display_type)){?>
                    <?php if($model->call_internal || $model->call_external){?>
                        <tr class="call_des">
                            <td class="col-xs-2">
                                <span class="package_icon">
                                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_icon_phone.png"/>
                                </span>
                            </td>
                            <td class="col-xs-10">
                                <div class="content">
                                    <?php if($model->call_internal){
                                        if($model->call_internal != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->call_internal, 0, ',', '.') . ' ' . Yii::t('web/portal','minute') . ' ' . Yii::t('web/portal','call_internal_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','call_internal_text')).'</p>';
                                        }
                                    }?>

                                    <?php if($model->call_external){
                                        if($model->call_external != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->call_external, 0, ',', '.') . ' ' . Yii::t('web/portal','minute') . ' ' . Yii::t('web/portal','call_external_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','call_external_text')).'</p>';
                                        }
                                    }?>
                                </div>
                            </td>
                        </tr>
                    <?php }?>

                    <?php if($model->sms_internal || $model->sms_external){?>
                        <tr class="sms_des">
                            <td class="col-xs-2">
                                <span class="package_icon">
                                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_icon_sms.png"/>
                                </span>
                            </td>
                            <td class="col-xs-10">
                                <div class="content">
                                    <?php if($model->sms_internal){
                                        if($model->sms_internal != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->sms_internal, 0, ',', '.') . ' ' . Yii::t('web/portal','sms_internal_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','sms_internal_text')).'</p>';
                                        }
                                    }?>

                                    <?php if($model->sms_external){
                                        if($model->sms_external != WPackage::FREE){
                                            echo '<p>'.CHtml::encode(number_format($model->sms_external, 0, ',', '.') . ' ' . Yii::t('web/portal','sms_external_text')).'</p>';
                                        }else{
                                            echo '<p>'.CHtml::encode(Yii::t('web/portal','free') . ' ' . Yii::t('web/portal','sms_external_text')).'</p>';
                                        }
                                    }?>
                                </div>
                            </td>
                        </tr>
                    <?php }?>

                    <?php if($model->data){?>
                        <tr class="data_des">
                            <td class="col-xs-2">
                                <span class="package_icon">
                                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_icon_phonewave.png"/>
                                </span>
                            </td>
                            <td class="col-xs-10">
                                <div class="content">
                                    <?php if($model->data != WPackage::FREE){
                                        $data = ($model->data == intval($model->data)) ? number_format($model->data, 0,',','.') : str_replace('.',',',$model->data);
                                        echo '<p>'.CHtml::encode($data . ' GB ' . Yii::t('web/portal','data_text')).'</p>';
                                    }else{
                                        echo '<p>'.CHtml::encode(Yii::t('web/portal','free_data')).'</p>';
                                    }?>
                                </div>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>

                <?php if ($model->display_type == WPackage::DISPLAY_TYPE_SHORT_DES){?>

                        <?php
                        $list_short_des = explode("\n",$model->short_description);
                        foreach ($list_short_des as $short_des){
                            $short_des = trim($short_des);
                            if(!empty($short_des)){
                                ?>
                                <tr class="short_des">
                                    <td class="col-xs-2">
                                        <span class="package_icon">
                                            <img src="<?php echo Yii::app()->theme->baseUrl?>/images/check.png" style="width:19px; margin-left:1px"/>
                                        </span>
                                    </td>
                                    <td class="col-xs-10">
                                        <div class="content">
                                            <?php echo CHtml::encode($short_des)?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                <?php }?>

            </table>

        </div>

        <div class="price">
            <?php $price = (!empty($model->price_discount)) ? $model->price_discount : $model->price; ?>
            <?php echo CHtml::encode(number_format($price, 0, ',', '.'))?><sup>đ</sup><span><?php echo '/' . WPackage::model()->getPackagePeriodLabel($model->period)?></span>
        </div>

    </div>
</div>