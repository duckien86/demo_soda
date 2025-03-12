<?php
/**
 * @var WNews $data
 */
?>

<div class="news-item">
    <div class="news-thumbnail">
        <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $data->slug, 'id' => $data->id)) ?>">
            <img src="<?php echo Yii::app()->baseUrl. '/uploads/' . $data->thumbnail ?>" alt="<?php echo pathinfo($data->thumbnail,PATHINFO_BASENAME) ?>" title="<?php echo $data->title ?>">
        </a>
    </div>
    <div class="news-title">
        <h3>
            <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $data->slug, 'id' => $data->id)) ?>">
                <?php echo $data->title ?>
            </a>
        </h3>
    </div>
</div>