<?php
/**
 * @var NewsController $this
 */
?>
<?php $this->beginContent('//layouts/main') ?>

<div class="container">
    <div id="news" class="row">
        <div id="news-content" class="col-md-9 col-sm-9">
            <?php echo $content ?>
        </div>
        <div id="sticky">
            <div id="news-banner" class="col-md-3 col-sm-3">
                <?php
                $models = WBanners::getListBannerByType(WBanners::TYPE_RIGHT_SIDE, false, 2, 0);
                foreach ($models as $model) {
                    $this->renderPartial('_banner', array(
                        'model' => $model,
                    ));
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endContent() ?>