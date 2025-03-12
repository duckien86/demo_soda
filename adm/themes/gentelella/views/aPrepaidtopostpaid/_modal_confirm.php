<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Xuan Thanh
 * Date: 08-05-2018
 * Time: 3:47 PM
 */
?>

<!-- Modal -->
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'ptpAdminModal',
//        'autoOpen' => true,
    )
); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" style="margin-top:-10px">&times;</button>
</div>

<div class="modal-body"></div>

<?php $this->endWidget(); ?>
