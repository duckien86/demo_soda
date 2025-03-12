<?php
    /* @var $this RoamingController */
    /* @var $data WPackage */
    $hint_link =  WPackage::getAnchorLink($data->name)
?>
<div class="col-md-4 item">
    <div class="thumbnail">
        <?= CHtml::image($GLOBALS['config_common']['project']['hostname'] . Yii::app()->params->upload_dir . $data->thumbnail_2, CHtml::encode($data->name), array('class' => 'img')); ?>
    </div>
    <div class="info">
        <div class="lbl_name"><?= CHtml::encode($data->name); ?></div>
        <div class="lbl_des"><?= CHtml::encode($data->short_description); ?></div>
        <div class="lbl_des"><?= number_format($data->price, 0, "", ".") ?> VNĐ
            <?php if(isset($data->period)){ ?> /<?= CHtml::encode($data->period); ?>
                ngày <?php }?>
        </div>
        <div class="space_20"></div>
        <div class="lbl_nation">
            <?= CHtml::link('Quốc gia/Mạng áp dụng', '', array(
                'title'          => '',
                'data-packageid' => $data->id,
                'class'          => 'view_nation',
            )); ?>
        </div>
        <div class="space_10"></div>
        <div class="line"></div>
        <div class="space_20"></div>
        <div class="list_btn">
            <a href="#<?php echo $hint_link?>" class="btn btn_view" data-toggle="modal" data-target="#popup_<?php echo $data->id ?>">Chi tiết</a>
            <a href="#" onclick="return false" class="btn btn_register btn_reg_rx" data-packageid="<?= $data->id ?>">
                Đăng ký
            </a>
        </div>
    </div>
</div>
<!-- popup -->
<div id="popup_<?php echo $data->id ?>" class="modal fade popuppackage" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Tên gói : <?php echo $data->name ?></h2>
            </div>
            <div class="modal-body">
                <div class="description-item-fiber">
                    <?php echo $data->description ?>
                </div>

            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-detail" data-dismiss="modal">Quay lại</button>
                <a href="#" onclick="return false" class="btn btn-register btn_reg_rx" data-packageid="<?= $data->id ?>">
                    Đăng ký
                </a>
            </div>
        </div>

    </div>
</div>
<style>
    .btn-register {
        background: #ED0677 !important;
        color: #fff !important;
    }

    .btn-detail {
        background: #0aa1df !important;
        margin-right: 10px !important;
        color: #fff !important;
        border: 2px #0aa1df solid !important;
        padding: 7px 20px !important;
    }
    .list_roaming .info .lbl_des{
        height: 100px !important;
    }
</style>