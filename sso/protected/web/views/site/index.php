<div class="page-home page-news">
    <?php

    ?>
    <!--    Video hot-->
    <?php echo $this->renderPartial('/site/mobile/_video_hot', array(
        'video_hot' => $video_hot
    )) ?>
    <!--    Live TV-->
    <?php echo $this->renderPartial('mobile/_live_tv',array('live_tv'=>$live_tv)) ?>
    <!--    Video ca nhạc-->
    <?php echo $this->renderPartial('/site/mobile/_video_music', array(
        'video_music_hot' => $video_music_hot,
    )) ?>
    <!-- Tin tức -->
    <?php echo $this->renderPartial('/site/mobile/_news_list',array('data_list' =>$data_list))?>

</div>
