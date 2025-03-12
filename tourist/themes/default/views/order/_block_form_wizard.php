<?php
/**
 * @var $this OrderController
 */
$btnClass = 'btn btn-lg';
if($this->isMobile) $btnClass = 'btn btn-sm';
?>

<div class="order_form_wizard clearfix">
    <ul>
        <li class="<?php echo $this->getStepTagClass(OrderController::STEP_FILL_ORDER)?>">
            <a class="<?php echo $btnClass ?>">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                <?php
                if($this->isMobile){
                    echo CHtml::encode('1. ' . Yii::t('tourist/label', 'fill_info'));
                }else{
                    echo CHtml::encode('1. ' . Yii::t('tourist/label', 'fill_in_order_info'));
                }
                ?>
            </a>
        </li>
        <li>
            <i class="glyphicon glyphicon-arrow-right"></i>
        </li>
        <li class="<?php echo $this->getStepTagClass(OrderController::STEP_CONFIRM_ORDER)?>">
            <a class="<?php echo $btnClass ?>">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                <?php
                if($this->isMobile){
                    echo CHtml::encode('2. ' . Yii::t('tourist/label', 'confirm'));
                }else{
                    echo CHtml::encode('2. ' . Yii::t('tourist/label', 'confirm_order'));
                }
                ?>

            </a>
        </li>
        <li>
            <i class="glyphicon glyphicon-arrow-right"></i>
        </li>
        <li class="<?php echo $this->getStepTagClass(OrderController::STEP_COMPLETE_ORDER)?>">
            <a class="<?php echo $btnClass ?>">
                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                <?php
                if($this->isMobile){
                    echo CHtml::encode('3. ' . Yii::t('tourist/label', 'finish'));
                }else{
                    echo CHtml::encode('3. ' . Yii::t('tourist/label', 'finish_order'));
                }
                ?>
            </a>
        </li>
    </ul>
</div>


