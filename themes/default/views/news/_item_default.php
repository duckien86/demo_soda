<?php
/**
 * @var WNews $data
 */
?>

<div class="news-item">
    <div class="row">
        <div class="news-col-35 news-thumbnail">
            <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $data->slug, 'id' => $data->id)) ?>">
                <img src="<?php echo Yii::app()->baseUrl. '/uploads/' .  $data->thumbnail ?>" alt="<?php echo pathinfo($data->thumbnail,PATHINFO_BASENAME) ?>" title="<?php echo $data->title ?>">
            </a>
        </div>
        <div class="news-col-65 news-title">
            <h2 title="<?php echo $data->title ?>">
                <a href="<?php echo Yii::app()->createUrl('news/view', array('slug' => $data->slug, 'id' => $data->id)) ?>">
                    <?php echo $data->title ?>
                </a>
            </h2>
            <p class="news-publish_date">
                <i class="fa fa-clock-o"></i>
                <?php echo CHtml::encode(date('d-m-Y', strtotime($data->last_update))) ?>
            </p>
            <p title="<?php echo $data->short_des ?>" class="news-short_des">
                <?php echo $data->short_des ?>
            </p>
        </div>
    </div>
    <div class="news-item-default-separator"></div>
</div>