<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $form TbActiveForm
 * @var $modelOrder     AFTOrders
 * @var $modelDetail    AFTOrderDetails
 * @var $modelCard      ACardStoreBusiness
 * @var $modelUser      UserLogin
 * @var $contracts      array AFTContracts
 * @var $contract_details array AFTContractsDetails
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

    <p class="note"><?= Yii::t('adm/actions', 'required_field') ?></p>

    <?php echo $form->errorSummary($modelOrder)?>

    <?php echo $form->errorSummary($modelUser)?>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->labelEx($modelOrder, 'customer'); ?>
                <?php $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $modelOrder,
                        'attribute'   => 'customer',
                        'data'        => CHtml::listData(AFTUsers::getListUser(AFTUsers::USER_TYPE_AGENCY), 'id', 'company'),
                        'htmlOptions' => array(
                            'class'     => 'form-control',
                            'multiple'  => FALSE,
                            'prompt'    => Yii::t('adm/label', 'select'),
                            'ajax'     => array(
                                'type'   => 'POST',
                                'url'    => Yii::app()->controller->createUrl('aCardStoreBusiness/getContractsByUser'),
                                'update' => '#AFTOrders_contract_id',
                                'data'   => array('user_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                            ),
                            'onchange' => '$("#AFTOrders_contract_id").select2("val", "");',
                        ),
                    )
                );?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($modelOrder, 'contract_id'); ?>
                <?php $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $modelOrder,
                        'attribute'   => 'contract_id',
                        'data'        => CHtml::listData($contracts, 'id', 'code'),
                        'htmlOptions' => array(
                            'class'     => 'form-control',
                            'multiple'  => FALSE,
                            'prompt'    => Yii::t('adm/label', 'select'),
                            'onchange'  => 'loadContractCards(this.value);',
                        ),
                    )
                );?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $form->labelEx($modelOrder, 'accepted_payment_files'); ?>
                <?php echo $form->fileField($modelOrder, 'accepted_payment_files', array(
                    'accept'    => 'image/*,.pdf',
                )); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->labelEx($modelOrder, 'note'); ?>
                <?php echo $form->textArea($modelOrder,'note', array(
                    'class' => 'textarea',
                ));?>
            </div>
        </div>
    </div>

    <div class="space_20"></div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
            <fieldset id="list_card">
                <legend><?php echo Yii::t('adm/label', 'card');?></legend>
                <div id="table_card_export_container">
                    <?php echo $this->renderPartial('/aCardStoreBusiness/_table_card_export', array(
                        'contract_details' => $contract_details,
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