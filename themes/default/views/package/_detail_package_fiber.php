<div class="title-fiber">
    <span>Tên gói : </span> <span>    <?php echo $detail_package['name'] ?></span>
</div>
<div class="description-fiber-detail">
    <?= nl2br(CHtml::encode($detail_package->short_description)); ?>
</div>
<div class="price-final">
    <span>Thành tiền : </span> <span>    <?php echo number_format($detail_package['price']) . ' vnđ' ?></span>
</div>
<div class="register-package-fiber">
    <a href="<?php echo Yii::app()->controller->createUrl('package/registerfibers', array('package' => $detail_package->id));?>" class="btn btn-register">
        Đăng ký
    </a>
</div>
<style>
    .price-final{
        width: 100%;
        font-size: 16px;
        padding: 10px;
        font-weight: bold;
    }
    .title-fiber{
        font-size: 23px;
        padding: 10px;
        color: #333;
        width: 100%;
    }
    .description-fiber-detail{
        padding: 0px 10px;
        width: 100%;
        font-size: 17px;
    }
    .register-package-fiber{
        width: 85%;
        text-align: center;
        padding: 10px 0px;
        position: absolute;
        bottom: 20px;
    }
    .btn-register{
        background: #f53e6e;
        color: #fff;
    }
</style>