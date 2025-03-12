<?php
/**
 * @var $this WorldcupController
 * @var $model WWCReport
 */
?>

<div id="worldcup_winners">
    <div class="title">
        <h2>Danh sách trúng thưởng</h2>
    </div>
    <div class="content" style="padding-top: 15px">

        <?php echo $this->renderPartial('_filter_area', array('model' => $model))?>

        <div id="table_winners">
            <?php echo $this->renderPartial('_table_winners', array('model' => $model))?>
        </div>
    </div>
</div>


