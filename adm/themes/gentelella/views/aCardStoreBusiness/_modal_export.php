<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model AFTOrders
 */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'modal_export_card_store',
//        'autoOpen' => true,
    )
); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body"></div>

<?php $this->endWidget(); ?>


<style>
    @media(min-width: 1024px){
        #modal_export_card_store .modal-dialog{
            width: 800px;
        }
    }
</style>