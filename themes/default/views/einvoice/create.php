<?php
/**
 * @var $this EinvoiceController
 * @var $model WOrderEinvoice
 * @var $order_id string
 * @var $key string
 * @var $form TbActiveForm
 */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-8 no_pad_xs">
                    <div id="main_left_section" class="msg">

                        <div class="form sim_checkout">
                            <div class="title text-center">Đăng ký nhận hóa đơn điện tử</div>
                            <div class="space_10"></div>

                            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                'id' => 'einvoice-form',
                                'enableAjaxValidation' => TRUE,
                            ));?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php echo $form->labelEx($model, 'c_name')?>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $form->textField($model, 'c_name', array('class' => 'form-control'))?>
                                        <?php echo $form->error($model, 'c_name')?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php echo $form->labelEx($model, 'c_phone')?>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $form->textField($model, 'c_phone', array('class' => 'form-control'))?>
                                        <?php echo $form->error($model, 'c_phone')?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php echo $form->labelEx($model, 'c_email')?>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $form->textField($model, 'c_email', array('class' => 'form-control'))?>
                                        <?php echo $form->error($model, 'c_email')?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php echo $form->labelEx($model, 'c_tax_code')?>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $form->textField($model, 'c_tax_code', array('class' => 'form-control'))?>
                                        <?php echo $form->error($model, 'c_tax_code')?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php echo $form->labelEx($model, 'c_address')?>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $form->textField($model, 'c_address', array('class' => 'form-control'))?>
                                        <?php echo $form->error($model, 'c_address')?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php echo $form->labelEx($model, 'c_note')?>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $form->textArea($model, 'c_note', array('class' => 'form-control', 'rows' => '5'))?>
                                        <?php echo $form->error($model, 'c_note')?>
                                    </div>
                                </div>
                            </div>

                            <?php echo CHtml::submitButton(Yii::t('web/portal', 'register'), array('class' => 'btn btn_continues')); ?>
                        </div>




                    <?php $this->endWidget();?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
