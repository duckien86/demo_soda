<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPrepaidToPostpaid
 * @var $form TbActiveForm
 * @var $province array
 * @var $district array
 * @var $ward array
 */
?>
<div class="ptp_form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'prepaidtopostpaid-form',
        'method' => 'post',
        'enableAjaxValidation' => true,
        // 'enableClientValidation' => true,
        'action'=> Yii::app()->createUrl('prepaidtopostpaid'),
        'htmlOptions' => array(),
    )); ?>

    <div class="ptp_form_step1">
        <div class="ptp_title text-center">
            <h2><?php echo CHtml::encode(Yii::t('web/portal','prepaid_to_postpaid_title'))?></h2>
            <span class="line"></span>
        </div>
        <div class="ptp_content">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"><?php echo $form->labelEx($model, 'msisdn')?></div>
                            <div class="col-md-7">
                                <?php echo $form->telField($model, 'msisdn', array(
                                    'class' => 'textbox',
                                    'style' => 'width:100%',
                                    'onchange'  => 'changeMsisdnPrefix(this, null);',
                                ))?>
                                <?php echo $form->error($model, 'msisdn')?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"><?php echo $form->labelEx($model, 'full_name')?></div>
                            <div class="col-md-7">
                                <?php echo $form->textField($model, 'full_name', array(
                                    'class' => 'textbox',
                                    'style' => 'width:100%',
                                ))?>
                                <?php echo $form->error($model, 'full_name')?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"><?php echo $form->labelEx($model, 'personal_id')?></div>
                            <div class="col-md-7">
                                <?php echo $form->textField($model, 'personal_id', array(
                                    'class' => 'textbox',
                                    'style' => 'width:100%',
                                ))?>
                                <?php echo $form->error($model, 'personal_id')?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"><?php echo $form->labelEx($model, 'address_detail', array('for' => ''))?></div>
                            <div class="col-md-7">
                                <?php
                                $this->widget(
                                    'booster.widgets.TbSelect2',
                                    array(
                                        'model'       => $model,
                                        'attribute'   => 'province_code',
                                        'data'        => $province,
                                        'htmlOptions' => array(
                                            'multiple' => FALSE,
                                            'prompt'   => Yii::t('web/portal', 'select_province'),
                                            'ajax'     => array(
                                                'type'   => 'POST',
                                                'url'    => Yii::app()->controller->createUrl('prepaidtopostpaid/getDistrictByProvince'),
                                                'update' => '#WPrepaidToPostpaid_district_code',
                                                'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                            ),
                                            'onchange' => ' $("#WPrepaidToPostpaid_district_code").select2("val", "");
                                                                                        $("#WPrepaidToPostpaid_ward_code").select2("val", "");',
                                        ),
                                    )
                                );
                                ?>
                                <?php echo $form->error($model, 'province_code')?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-7">
                                <?php
                                $this->widget(
                                    'booster.widgets.TbSelect2',
                                    array(
                                        'model'       => $model,
                                        'attribute'   => 'district_code',
                                        'data'        => $district,
                                        'htmlOptions' => array(
                                            'multiple' => FALSE,
                                            'prompt'   => Yii::t('web/portal', 'select_district'),
                                            'ajax'     => array(
                                                'type'     => 'POST',
                                                'dataType' => 'json',
                                                'url'      => Yii::app()->controller->createUrl('prepaidtopostpaid/getWardBrandOfficesByDistrict'),
                                                'success'  => 'function(data){
                                                                                $("#WPrepaidToPostpaid_ward_code").html(data.html_ward);
                                                                            }',
                                                'data'     => array('district_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                            ),
                                            'onchange' => '$("#WPrepaidToPostpaid_ward_code").select2("val", "");',
                                        ),
                                    )
                                );
                                ?>
                                <?php echo $form->error($model, 'district_code')?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-7">
                                <?php
                                $this->widget(
                                    'booster.widgets.TbSelect2',
                                    array(
                                        'model'       => $model,
                                        'attribute'   => 'ward_code',
                                        'data'        => $ward,
                                        'htmlOptions' => array(
                                            'multiple' => FALSE,
                                            'prompt'   => Yii::t('web/portal', 'select_ward'),
                                        ),
                                    )
                                );
                                ?>
                                <?php echo $form->error($model, 'ward_code')?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-7">
                                <?php echo $form->textField($model, 'address_detail', array(
                                    'class' => 'textbox',
                                    'style' => 'width:100%',
                                    'placeholder' => Yii::t('web/portal','apartment_number'),
                                ))?>
                                <?php echo $form->error($model, 'address_detail')?>
                            </div>
                        </div>
                    </div>

                   <!-- <div class="form-group">
                        <div class="row">
                            <div class="col-md-5"><?php /*echo $form->labelEx($model, 'promo_code')*/?></div>
                            <div class="col-md-7">
                                <?php /*echo $form->textField($model, 'promo_code', array(
                                    'class' => 'textbox',
                                    'style' => 'width:100%',
                                ))*/?>
                                <?php /*echo $form->error($model, 'promo_code')*/?>
                            </div>
                        </div>
                    </div>-->

                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
    
    <div class="action text-center">
        <?php echo CHtml::submitButton('Tiếp tục', array(
            'class' => 'btn btn-lg',
            'id'    => 'btnSubmitPtp'
        ))?>
    </div>
    <?php $this->endWidget();?>
</div>