<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPrepaidToPostpaid
 * @var $form TbActiveForm
 * @var $list_package array
 */
?>

<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'prepaidtopostpaid-form',
    'method' => 'post',
//                        'enableAjaxValidation' => true,
    // 'enableClientValidation' => true,
    'action' => Yii::app()->createUrl('prepaidtopostpaid/choosePackage'),
    'htmlOptions' => array(),
)); ?>
<div class="ptp_form_step2 list_package_ptp">
    <div class="ptp_title text-center">
        <h3><?php echo CHtml::encode(Yii::t('web/portal','select_package_ptp'))?></h3>
        <span class="line"></span>
        <?php echo $form->error($model,'package_code')?>
    </div>
    <div class="ptp_content">
        <?php
        $start = 1;
        $limit = 3;
        $rowLimit = 2;
        $open = '<div class="row">';
        $openHidden = '<div class="row row-plus hidden">';
        $close = '</div>';
        $isOpen = false;
        $rowNum = 1;
        if($this->isMobile){
            $limit = 1;
            $rowLimit = 4;
        }
        foreach ($list_package as $package){
            if($start == 1){
                echo ($rowNum > $rowLimit) ? $openHidden : $open;
                $isOpen = true;
            }
            echo $this->renderPartial('/prepaidtopostpaid/_item_package', array(
                'model' => $package,
                'ptp'   => $model,
            ));
            if($start == $limit){
                echo $close;
                $isOpen = false;
                $start = 1;
                $rowNum++;
            }else{
                $start++;
            }
        }
        if($isOpen){
            echo $close;
        }
        ?>

        <?php if(count($list_package) > ($limit*$rowLimit)){?>
            <div class="action block_action text-center">
                <a class="btn btn-prev hidden" onclick="shortenPackage(this)">
                    <i class="fa fa-angle-double-up"></i>
                </a>
                <a class="btn btn-next" onclick="showAllPackage(this)">
                    <i class="fa fa-angle-double-down"></i>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<?php echo $form->hiddenField($model,'package_code');?>

<div class="action text-center">
    <?php echo CHtml::submitButton(Yii::t('web/portal','register'), array(
        'class' => 'btn btn-lg',
        'id'    => 'btnSubmitPtp'
    ))?>
</div>


<?php $this->endWidget();?>

<script>
    $(document).ready(function () {

        $('#prepaidtopostpaid').on('click','.item_package', function () {
            var code = $(this).attr('data-code');

            $('#WPrepaidToPostpaid_package_code').val(code);

            $('.list_package_ptp').find('.item_package').each(function () {
                $(this).removeClass('selected');
            });
            $(this).addClass('selected');
        });
    });

    function showAllPackage(selector){
        $(selector).closest('.list_package_ptp').find('div.row-plus').each(function () {
            $(this).removeClass('hidden');
        });
        $(selector).addClass('hidden');
        $(selector).closest('.action').find('.btn-prev').first().removeClass('hidden');
    }
    function shortenPackage(selector){
        $(selector).closest('.list_package_ptp').find('div.row-plus').each(function () {
            $(this).addClass('hidden');
        });
        $(selector).addClass('hidden');
        $(selector).closest('.action').find('.btn-next').first().removeClass('hidden');
    }

</script>