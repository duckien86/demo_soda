<div class="content-1 step_make_money">
    <div class="container">
        <div class="row">
            <div class="content-1-row">
                <div class="space_40"></div>
                <div class="title-content-1-row wow fadeInUp" data-wow-offset="300">
                    <p class="title_section">
                    <h2 class="title_section_seo-new"><a href="<?= $GLOBALS['config_common']['domain_related']['affiliate'] ?>">3 BƯỚC MUA HÀNG CÙNG
                            GSHOP</a></h2>
                    </p>
                </div>
            </div>
        </div>
        <div class="space_40"></div>
        <div class="row">
            <div class="col-md-4 col-xs-12 wow fadeInUp" data-wow-offset="300">
                <a href="https://Gshop.centech.vn/goi-cuoc.html" style="width: 100%; color: #333 !important;">
                    <div class="item-step-make-money">
                        <div class="thumbnail thumbnail-new">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/step-1.png" alt="link">
                        </div>
                        <div class="des">
                            <div class="title">
                                <h3 style="color: #ffffff;">Chọn sản phẩm/dịch vụ</h3>
                            </div>
                            <div class="description">
                                <!--                                Miễn phí đăng ký và sử dụng các công cụ bán hàng online-->
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-xs-12 wow fadeInUp" data-wow-offset="300">
                <a style="color:#333;" href="https://Gshop.centech.vn/goi-cuoc.html">
                    <div class="item-step-make-money">
                        <div class="thumbnail thumbnail-new">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/step-2.png" alt="link-2">
                        </div>
                        <div class="des">
                            <div class="title">
                                <h3 style="color: #ffffff">Điền thông tin</h3>
                            </div>
                            <div class="description">
                                <!--                                Chia sẻ link hoặc nhập mã CTV-->
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-xs-12 wow fadeInUp " data-wow-offset="300">
                <a href="https://Gshop.centech.vn/goi-cuoc.html" style="color: #333">
                    <div class="item-step-make-money">
                        <div class="thumbnail thumbnail-new">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/step-3.png" alt="link-3">
                        </div>
                        <div class="des">
                            <div class="title">
                                <h3 style="color: #ffffff">Nhận hàng</h3>
                            </div>
                            <div class="description">
                                <!--                                Nhận hoa hồng qua tài khoản ngân hàng-->
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="space_30"></div>

    <div class="wrap-white">
        <div class="content-1">
            <div class="container">
                <div class="row">
                    <div class="content-1-row">
                        <div class="space_60"></div>
                        <div class="title-content-1-row wow fadeInUp" data-wow-offset="300">
                            <p class="title_section">
                            <h2 class="title_section_seo-new">SẢN PHẨM HOT</h2>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space_60"></div>
                <div class="row">
                    <?php
                    $packages = WBanners::getListBannerByType(WBanners::TYPE_PACKAGE, false, 8, 0);
                    if ($packages) :
                        foreach ($packages as $item) :
                    ?>
                            <div class="col-lg-3 wow fadeInUp no_pad_right_1">
                                <div class="item-package-desktop-new">
                                    <a href="<?= $item->target_link; ?>">
                                        <img src="<?= Yii::app()->params->upload_dir . $item->img_desktop ?>" alt="<?php $item->target_link; ?>">
                                    </a>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <div class="space_100"></div>
    </div>
</div>
<div class="news-new">
    <div class="space_30"></div>
    <div class="container">
        <div class="row">
            <div class="content-1-row">
                <div class="space_40"></div>
                <div class="title-content-1-row wow fadeInUp" data-wow-offset="300">
                    <p class="title_section">
                    <h2 class="title_section_seo-new">TIN TỨC</h2>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">

        </div>
        <div class="row">
            <?php

            $page = 0;
            $limit = $this->item_per_page;
            $offset = $this->item_per_page * $page;
            $list_top = WNews::getListNewsByType(WNews::POSITION_HOT_NEWS, false, $limit, $offset);
            if ($list_top) :
                foreach ($list_top as $item) :
            ?>
                    <div class="col-lg-3 wow fadeInUp">
                        <div class="image-news-new">
                            <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $item->slug, 'id' => $item->id)) ?>">
                                <img src="<?php echo Yii::app()->baseUrl . '/uploads/' . $item->thumbnail ?>" alt="<?php echo pathinfo($item->thumbnail, PATHINFO_BASENAME) ?>" title="<?php echo $item->title ?>">
                            </a>
                        </div>
                        <div class="date-title">
                            <div class="date">
                                <div class="day-new">
                                    <?php echo date("d", strtotime(str_replace('/', '-', Chtml::encode($item->create_date)))) ?>
                                </div>
                                <div class="month-new">
                                    <?php echo date("M", strtotime(str_replace('/', '-', Chtml::encode($item->create_date)))) ?>
                                </div>
                            </div>
                            <div class="title-news-new">
                                <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $item->slug, 'id' => $item->id)) ?>">
                                    <h3 style="color: #333; font-size: 17px; margin: 0px !important;padding: 0px !important; padding-left: 5px !important;"><?php echo Chtml::encode($item->title) ?></h3>
                                </a>
                            </div>
                        </div>
                        <div class="description-new">
                            <?php echo Chtml::encode(substr($item->short_des, 0, 130)) . ' ...' ?>
                        </div>
                        <div class="read-more-new">
                            <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $item->slug, 'id' => $item->id)) ?>">Chi
                                tiết</a>
                        </div>
                    </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
        <div class="space_40"></div>
    </div>
    <div class="space_30"></div>

</div>
<style>
    .des .title h3 {
        margin-top: 38px;
        font-size: 18px !important;
    }
</style>