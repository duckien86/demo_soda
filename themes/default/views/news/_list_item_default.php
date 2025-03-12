<?php
/**
 * @var array $list
 * @var integer $page
 */
?>

<div class="news-page news-page-<?php echo $page?>" data-page="<?php echo $page ?>">
    <?php
    foreach ($list as $data){
        $this->renderPartial('_item_default', array(
            'data' => $data
        ));
    }
    ?>
</div>
