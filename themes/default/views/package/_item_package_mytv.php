<?php
/**
 * @var $this PackageController
 * @var $model WPackage
 */
?>
<div class="col-sm-4">
    <div class="item_package item_package_fiber">
        <div class="title title_fiber">
            <a data-toggle="modal" data-target="#popup_<?php echo $model->id ?>">
                <h4>
                    <?php echo CHtml::encode($model->name) ?>
                </h4>

                <?php if ($model->commercial) { ?>
                    <span class="discount">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/package_label_discount1.png">
                        <label>
                             <span style="color: #ffff00"><?php echo $model->commercial ?></span>
                        </label>
                    </span>
                <?php } ?>
            </a>
        </div>

        <div class="item_package_separator"></div>

        <div class="package_descriptions" data-toggle="modal" data-target="#popup_<?php echo $model->id ?>">
            <div class="img-fiber">
                <img src="<?php echo Yii::app()->baseUrl. "/uploads/" . $model->thumbnail_1 ?>">
            </div>
        </div>


        <div class="price">
            <span>
                <?php /*if (!$model->price_discount) { */?><!--
                    <p>
                    <div class="old-price"><?php /*echo number_format($model->price) */?><sup>đ</sup><span><?php /*echo '/' . WPackage::model()->getPackagePeriodLabel($model->period) */?></span></div></p>
                <?php /*} elseif ($model->price_discount) { */?>
                    <div class="">
                        <div class="new-price" style="float: left; margin-right: 5px; width: 100%; font-size: 20px">
                            <?php /*echo number_format($model->price_discount) */?><sup>đ</sup><span><?php /*echo '/' . WPackage::model()->getPackagePeriodLabel($model->period) */?></span>
                             <span style="text-decoration: none !important; color: #fff; padding: 2px 10px; background: red; border-radius: 5px; margin-left: 13%">
                          <?php /*if($model->price_discount){ */?>
                              - <?php /*echo  round((100 - ($model->price_discount * 100) / $model->price)) */?> %
                          <?php /*}*/?>
                        </span>
                        </div>
                        <p>
                        <div class="old-price"
                             style="float:left; font-size: 17px; color: #999 ; text-decoration: line-through; width: 100%">
                            <?php /*echo number_format($model->price) */?> đ <span><?php /*echo '/' . WPackage::model()->getPackagePeriodLabel($model->period) */?></span>

                        </div>

                    </div>

                    </p>
                --><?php /*} */?>
                <?php if($model->price_no_stb){ ?>
                    <div class="old-price"><?php echo number_format($model->price_no_stb) ?><sup>đ</sup><span><?php echo '/' . WPackage::model()->getPackagePeriodLabel($model->period) ?></span></div></p>
                <?php }?>
            </span>
        </div>

        <div class="item_package_separator"></div>

        <div class="action text-center cus-btn">
            <a class="btn btn-detail" data-toggle="modal" data-target="#popup_<?php echo $model->id ?>">Chi
                tiết</a>
            <a href="<?php echo Yii::app()->controller->createUrl('package/registermytv', array('package' => $model->id)); ?>" class="btn btn-register" >
            Đăng ký
            </a>
        </div>
    </div>
</div>
<style>
    .title_fiber {
        height: 100px;
    }

    .package_descriptions {
        padding: 0px !important;
    }

    .package_descriptions .img-fiber img {
        width: 100%;
        height: 200px;
        margin-top: 15px;
    }

    .description-item-fiber {
        font-size: 16px;
        line-height: 27px;
        width: 100%;
        overflow-y: scroll;
        height: 350px;
    }

    .btn-default {
        border: #ccc 1px solid;
        background: #2F9EDA !important;
        color: #fff;
        border-radius: 100px !important;
    }

    .price-item-fiber {
        width: 100%;
        color: #ED0677;
        font-weight: bold;
        margin-top: 20px;
        font-size: 20px;
    }

    .btn-register {
        background: #ED0677 !important;
        color: #fff !important;
        border-radius: 100px !important;
    }

    .btn-detail {
        background: #0aa1df !important;
        margin-right: 10px !important;
        color: #fff !important;
        border: 2px #0aa1df solid !important;
        padding: 7px 20px !important;
    }

    .item_package_fiber {
        height: 485px !important;
    }

    .cus-btn {
        position: absolute;
        width: 100%;
        left: 0;
        bottom: 14px;
    }

    .description-item-mytv {
        height: 100px;
        vertical-align: middle;
        padding-top: 5%;
    }

    .description-item-mytv a {
        border-radius: 0px !important;
        padding: 10px 20px !important;
    }
    .item_package span.discount {
        position: absolute;
        top: 0 !important;
        right: 0;
    }
    .item_package span.discount img, .item_package span.discount label {
        width: 77px !important;
        height: 60px !important;
        position: absolute;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        right: 0;
    }
    .item_package .title h4 {
        font-size: 26px !important;
        font-weight: 700;
        color: #343434;
        -o-transition: all ease-in-out 0.2s;
        -moz-transition: all ease-in-out 0.2s;
        -webkit-transition: all ease-in-out 0.2s;
        transition: all ease-in-out 0.2s;
        width: 74% !important;
    }
    .item_package .price span {
        font-size: 18px !important;
    }
    .item_package .price {
        padding-left: 20px;
        font-size: 26px;
        color: #d91a61;
        margin-bottom: 20px;
        float: left;
        width: 100%;
    }
</style>
<!-- popup -->
<div id="popup_<?php echo $model->id ?>" class="modal fade popuppackage" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Tên gói : <?php echo $model->name ?></h2>
            </div>
            <div class="modal-body">
                <div class="description-item-fiber">
                    <?php echo $model->description ?>
                </div>

            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
                <a href="<?php echo Yii::app()->controller->createUrl('package/registermytv', array('package' => $model->id)); ?>"
                   class="btn btn-register">
                    Đăng ký
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    function nonepopup() {
        var none = document.getElementsByClassName("popuppackage");
        none.style.display = "none";

    }
</script>