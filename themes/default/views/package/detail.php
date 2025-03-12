<?php
    /* @var $this PackageController */
    /* @var $package WPackage */
    /* @var $related_package WPackage */

    if ($package->price_discount) {
        $class_price      = 'txt_sm color_black';
        $class_period     = 'txt_sm color_black';
        $class_price_dis  = 'txt_lg lbl_color_pink';
        $class_period_dis = 'txt_sm lbl_color_pink';
    } else {
        $class_price      = 'txt_lg lbl_color_blue';
        $class_period     = 'txt_sm lbl_color_blue';
        $class_price_dis  = '';
        $class_period_dis = '';
    }
?>
<div id="packages" class="page_detail">
    <!--        --><?php //$this->renderPartial('/layouts/_block_service'); ?>
    <!--        --><?php //$this->renderPartial('/layouts/_block_banner'); ?>
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <div class="package_info">
                        <div class="col-md-4 no_pad_left">
                            <div class="thumbnail">
                                <?= CHtml::image($GLOBALS['config_common']['project']['hostname'] . Yii::app()->params->upload_dir . $package->thumbnail_2, 'image', array()); ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="title"><?= CHtml::encode($package->name); ?></div>
                            <div class="<?= $class_price ?>"><?= number_format($package->price, 0, "", "."); ?>đ
                            </div>
                            <?php if ($package->price_discount > 0): ?>
                                <div class="<?= $class_period_dis ?>">
                                        <span class="lbl_dis">Chỉ còn:
                                            <?= number_format($package->price_discount, 0, "", "."); ?>đ
                                            <?php if ($package->type == WPackage::PACKAGE_PREPAID): ?>
                                                /<?= $package->period; ?> ngày đầu tiên
                                            <?php elseif ($package->type == WPackage::PACKAGE_POSTPAID): ?>
                                                /<?= $package->getPackagePeriodLabel($package->period); ?> đầu tiên
                                            <?php endif; ?>
                                        </span>
                                </div>
                            <?php endif; ?>
                            <?php if ($package->vip_user >= WPackage::VIP_USER): ?>
                                <div class="space_10"></div>
                                <div class="lbl_color_blue font_16">
                                    Gói cước ưu đãi dành riêng cho CTV
                                </div>
                            <?php endif; ?>
                            <div class="short_des">
                                <?= nl2br(CHtml::encode($package->short_description)); ?>
                            </div>
                            <div class="row_btn">
                                <?php if ($package->name == 'HEY' || $package->name == 'HEYTIIN' || $package->name == 'FTIN' || $package->name == 'FLY') { ?>
                                    <a href="" class="btn btn_green" data-toggle="modal"
                                       data-target="#showmodal_<?php echo $package->slug ?>">
                                        Đăng ký ngay
                                    </a>
                                <?php } else { ?>
                                    <?php
                                    /* comment: SIM_FREEDOO && VipUser: confirm ->login||register VipUser
                                     * if (WPackage::checkVipUser() == FALSE && $package->vip_user >= WPackage::VIP_USER): //check aff->login +sim freedoo + vip_user(package)
                                    echo CHtml::link('Đăng ký ngay', 'javascript:void();',
                                    array(
                                        'class'       => 'btn btn_green',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirm',
                                    ));
                                    else: */ ?>
                                    <?= CHtml::link('Đăng ký ngay',
                                        $this->createUrl('package/register', array('package' => $package->id)),
                                        array('class' => 'btn btn_green')); ?>
                                    <?php //endif; ?>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="space_1"></div>
                    </div>
                    <div class="space_10"></div>
                    <div class="full_description">
                        <div class="title">Thông tin chi tiết</div>
                        <div class="description">
                            <?= $package->description; ?>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="showmodal_<?php echo $package->slug ?>" role="dialog">
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

                <div class="space_30"></div>
                <div class="ss-box1-right-all">
                    <div class="ss-box1-right-title">
                        <div class="ss-box1-left-top-tit border_bottom">
                            <span class="uppercase"><?php echo CHtml::encode(Yii::t('web/portal', 'package_related')) ?></span>
                        </div>
                    </div>
                    <div class="list_package">
                        <div class="package_other">
                            <div class="content">
                                <?php $this->renderPartial('/package/_block_package_other', array(
                                    'list_package' => $related_package,
                                    'type'         => $package->type,
                                )) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space_30"></div>
            </div>
        </section>
    </section>
    <!--end section #ss-bg -->
</div>
<?php $this->renderPartial('_modal_confirm', array('package' => $package)); ?>
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
<script>
    function showAllPackage(selector) {
        $(selector).closest('.block_package').find('div.row-plus').each(function () {
            $(this).removeClass('hidden');
        });
        $(selector).addClass('hidden');
        $(selector).closest('.action').find('.btn-prev').first().removeClass('hidden');
    }

    function shortenPackage(selector) {
        $(selector).closest('.block_package').find('div.row-plus').each(function () {
            $(this).addClass('hidden');
        });
        $(selector).addClass('hidden');
        $(selector).closest('.action').find('.btn-next').first().removeClass('hidden');
    }

</script>
