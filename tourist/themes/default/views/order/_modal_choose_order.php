<?php
/**
 * @var $this OrderController
 */
?>

<div id="modal_choose_order" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('tourist/label','create_order') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-6 text-right">
                    <?php echo CHtml::link(Yii::t('tourist/label', 'order_normal'),
                        Yii::app()->createUrl('order/create'), array(
                            'class' => 'btn btn-lg'
                        ))?>
                    </div>
                    <div class="col-xs-6 text-left">
                    <?php echo CHtml::link(Yii::t('tourist/label', 'order_file_sim'),
                        Yii::app()->createUrl('order/create', array('type' => TOrders::TYPE_WITH_FILE_SIM)), array(
                            'class' => 'btn btn-lg'
                        ))?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<script>
    $('#modal_choose_order').appendTo("body");
</script>
