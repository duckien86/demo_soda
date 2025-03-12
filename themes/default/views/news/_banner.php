<?php
/**
 * @var WBanners $model
 */
?>

<div class="news-banner-item">
    <a href="<?php echo (!empty($model->target_link)) ? $model->target_link : '#' ?>">
        <img src="<?php echo Yii::app()->baseUrl. '/uploads/' . $model->img_desktop ?>">
    </a>
</div>
