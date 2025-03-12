<div class="page_detail">
    <div class="container">
        <div class="row">
            <div class="col- col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="wrapper">
                 <div class="title-page-seo">
                  <a href="<?= Yii::app()->controller->createUrl('site/sitemap'); ?>" title="Sitemap" class="parent"><h2><?php echo $this->pageTitle; ?></h2></a>
                 </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col- col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="wrapper">
                    <div class="row">
                        <div class="col- col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="title-page-seo-child">
                                <h3>Giới thiệu về Freedoo</h3>
                            </div>
                            <div class="box-page-seo">
                                <ul>
                                    <li><a href="<?= Yii::app()->controller->createUrl('site/about'); ?>" title="Giới thiệu" class="parent">Giới thiệu</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('site/supportChannel'); ?>" title="Các kênh hỗ trợ" class="parent">Các kênh hỗ trợ</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col- col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="title-page-seo-child">
                                <h3>Mua ở đâu</h3>
                            </div>
                            <div class="box-page-seo">
                                <ul>
                                    <li><a href="<?= Yii::app()->controller->createUrl('sim/index'); ?>" title="Sim số" class="parent">Sim số</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('package/index'); ?>" title="Gói cước" class="parent">Gói cước</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('card/topup'); ?>" title="Nạp thẻ" class="parent">Nạp thẻ</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('card/buycard'); ?>" title="Mua mã thẻ" class="parent">Mua mã thẻ</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('roaming/index'); ?>" title="Gói cước Roaming" class="parent">Nạp thẻ</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('package/packageFlexible'); ?>" title="Gói cước linh hoạt" class="parent">Gói cước linh hoạt</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col- col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="title-page-seo-child">
                                <h3>Hỗ trợ</h3>
                            </div>
                            <div class="box-page-seo">
                                <ul>
                                    <li><a href="<?= Yii::app()->controller->createUrl('help/supportSell'); ?>" title="Hướng dẫn mua hàng" class="parent">Hướng dẫn mua hàng</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('help/supportProduct'); ?>" title="Hướng dẫn mua gói cước - thẻ nạp" class="parent">Hướng dẫn mua gói cước - thẻ nạp</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('help/index'); ?>" title="Các câu hỏi thường gặp" class="parent">Các câu hỏi thường gặp</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('social'); ?>" title="Hỏi cộng đồng">Hỏi cộng đồng</a></li>
                                    <li><a href="<?= Yii::app()->controller->createUrl('orders/searchOrder'); ?>" title="Tra cứu đơn hàng">Tra cứu đơn hàng</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col- col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="title-page-seo-child">
                                <h3>Tin tức</h3>
                            </div>
                            <div class="box-page-seo">
                                <ul>
                                    <li><a href="<?= Yii::app()->controller->createUrl('news/index'); ?>" title="Tin tức mới" class="parent">Tin tức mới</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col- col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="title-page-seo-child">
                                <h3>Cộng đồng</h3>
                            </div>
                            <div class="box-page-seo">
                                <ul>
                                    <li><a href="<?= $GLOBALS['config_common']['domain_related']['social'] ?>" title="Diễn đàn" class="parent">Diễn đàn</a></li>
                                    <li><a href="<?= $GLOBALS['config_common']['domain_related']['social'] ?>index.php?r=landing/index">Đổi quà</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>