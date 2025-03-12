<?php
    /* @var $this PackageController */
    /* @var $category_package */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <!--        --><?php //$this->renderPartial('/layouts/_block_banner'); ?>
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container no_pad_xs">
                <?php
                    if ($category_package):
                        foreach ($category_package as $category):
                            $cate = $category['category'];
                            $packages = $category['package'];
                            if ($packages):
                                ?>
                                <div class="ss-box1-right-all">
                                    <div class="ss-box1-right-title">
                                        <div class="ss-box1-left-top-tit border_bottom">
                                            <span class="uppercase"><?= CHtml::encode($cate['name']); ?></span>
                                            <span class="link fr">
                                                    <a href="<?= Yii::app()->controller->createUrl('package/category', array('id' => $cate['id'], 'title' => Utils::unsign_string($cate['name']))); ?>">
                                                        Xem toàn bộ<i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                    </a>
                                                </span>

                                            <div class="space_1"></div>
                                        </div>
                                    </div>
                                    <div id="package_<?= $cate['id']; ?>"
                                         class="list_package list_package_slider owl-carousel owl-theme">
                                        <?php
                                            foreach ($packages as $item)://item->package
                                                $this->renderPartial('_block_package', array('data' => $item));
                                            endforeach;
                                        ?>

                                    </div>
                                </div>
                                <div class="space_30"></div>
                            <?php endif;
                        endforeach;
                    endif;//end category
                ?>
                <!--package flexible-->
                <div class="ss-box1-right-all">
                    <div class="ss-box1-right-title">
                        <div class="ss-box1-left-top-tit border_bottom">
                            <span class="uppercase">Gói cước linh hoạt</span>
                            <div class="space_1"></div>
                        </div>
                    </div>
                    <div id="package_flexible" class="list_package list_package_slider owl-carousel owl-theme">
                        <?php $this->renderPartial('_block_package_flexible'); ?>
                    </div>
                </div>
                <div class="space_30"></div>
            </div>
        </section>
    </section>
    <!--end section #ss-bg -->
</div>
