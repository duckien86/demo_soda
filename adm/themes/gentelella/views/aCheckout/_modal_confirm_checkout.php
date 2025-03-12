<?php
    /**
     * Created by PhpStorm.
     * User: staff
     * Date: 9/4/2018
     * Time: 4:42 PM
     */
?>
<style>
    #confirm_checkout .modal-dialog{
        max-width: 450px;
    }
    #confirm_checkout .thumbnail, #confirm_checkout #sim_order .header .title, #confirm_checkout .line{
        display: none;
    }
    #confirm_checkout  .panel {
        margin-bottom: 0;
        box-shadow: 0 0;
    }
    #confirm_checkout .modal-footer{
        margin-top: 0;
    }
    #confirm_checkout #main_right_section .table > tbody > tr > td{
        padding: 0;
    }
</style>
<!-- Modal -->
<div id="confirm_checkout" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center help-block"><b>Xác nhận đơn hàng</b></h4>
            </div>
            <div class="modal-body">
                <div id="main_right_section">
                    <?php $this->renderPartial('_panel_order', array(
                        'modelSim'     => $modelSim,
                        'modelOrder'   => $modelOrder,
                        'modelPackage' => $modelPackage,
                        'amount'       => $amount,
                    )); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php if(Yii::app()->params->confirm_password): ?>
                    <div id="submit_default" style="display: none">
                        <?php echo CHtml::submitButton(Yii::t('web/portal', 'agree'), array('class' => 'btn btn_continue')); ?>
                    </div>
                    <button id="btn_confirm_pw" class="btn btn_continue"
                            type="button" data-toggle="modal" data-target="#confirm_password"><?=Yii::t('web/portal', 'continue') ?></button>
                <?php else: ?>
                    <?php echo CHtml::submitButton(Yii::t('web/portal', 'agree'), array('class' => 'btn btn_continue')); ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>