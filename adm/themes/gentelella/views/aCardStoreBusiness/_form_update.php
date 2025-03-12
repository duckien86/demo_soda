<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $form TbActiveForm
 * @var $modelOrder     AFTOrders
 * @var $modelUser      UserLogin
 * @var $customer       AFTUsers
 * @var $details        AFTOrderDetails[]
 * @var $contract       AFTContracts
 */
?>

<div class="form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'acardstorebusiness-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
//        'enableAjaxValidation' => TRUE,
        'htmlOptions'          => array(
            'enctype' => 'multipart/form-data',
            'class' => 'form-horizontal',
        ),
    )); ?>

    <?php echo $form->errorSummary($modelOrder)?>

    <?php echo $form->errorSummary($modelUser)?>

    <div class="space_20"></div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <fieldset id="list_card">
                    <legend><?php echo Yii::t('adm/label', 'card');?></legend>
                    <div id="table_card_export_container">
                        <?php echo $this->renderPartial('/aCardStoreBusiness/_table_card_export_update', array(
                            'details'   => $details,
                            'contract'   => $contract,
                        ))?>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo CHtml::link(Yii::t('adm/label','export_card'),'#', array(
                    'id'            => 'btnConfirm',
                    'class'         => 'btn btn-warning',
                    'data-toggle'   => 'modal',
                    'data-target'   => '#modal_confirm_export',
                ));?>
            </div>
        </div>
    </div>

    <?php
    $open = false;
    if(!$modelOrder->hasErrors() && $modelUser->hasErrors()){
        $open = true;
    }

    $this->renderPartial('/aCardStoreBusiness/_modal_confirm', array(
        'model' => $modelUser,
        'form'  => $form,
        'open'  => $open,
    ));
    ?>

    <?php $this->endWidget()?>
</div>

<script>

    function loadContractCards(contract_id) {

        var emptyHtml = '<td colspan="5"><?php echo Yii::t('adm/label', 'empty_card');?></td>';

        if(!contract_id || contract_id.length == 0 || contract_id <= 0){
            $('#table_card_export tbody').html(emptyHtml);
            return;
        }

        var form_data = new FormData(document.getElementById("acardstorebusiness-form"));//id_form

        $.ajax({
            url: '<?php echo Yii::app()->createUrl('aCardStoreBusiness/getContractsCards')?>',
            type: 'post',
            dataType: 'json',
            data: {
                'contract_id' : contract_id,
                'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (result) {
                $('#table_card_export_container').html(result.data_html);
            }
        });
    }

</script>