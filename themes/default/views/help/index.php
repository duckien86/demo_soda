<div class="page_detail">
    <?= $this->renderPartial('_banner'); ?>
    <div class="main_topic">
        <?= $this->renderPartial('_content_main_topic'); ?>
    </div>
    <div class="main_content">
        <?= $this->renderPartial('_content_main_help', array('tab' => $tab, 'cate' => $cate, 'question' => $question)); ?>
    </div>
    <div class="last_content">
        <?= $this->renderPartial('_content_main_last'); ?>
    </div>
</div>
