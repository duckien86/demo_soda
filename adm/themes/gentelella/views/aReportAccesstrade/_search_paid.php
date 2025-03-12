<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $form CActiveForm */

?>

<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>
    <div class="row">
        <div class="col-md-2" style="width: 10%;">
            <?php echo $form->label($model, 'month'); ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo $form->dropDownList($model, 'month', $model->getMonthArray(), array(
                        'class'  => 'form-control',
                        'prompt' => 'Chọn tháng',
                        'style'  => 'width:100%'
                    )
                ); ?>
                <?php echo $form->error($form_validate, 'month'); ?>

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-2" style="width: 10%;">
            <?php echo $form->label($model, 'year'); ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">

                <?php echo $form->dropDownList($model, 'year', $model->getYearArray(), array(
                        'class'  => 'form-control',
                        'prompt' => 'Chọn năm',
                        'style'  => 'width:100%',
                    )
                ); ?>
                <?php echo $form->error($form_validate, 'year'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2" style="width: 10%;">
            <?php echo $form->label($model, 'province_code'); ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">


                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'province_code',
                            'data'        => AProvince::model()->getAllProvinceVnp(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('report/menu', 'province_code'),
                                'onchange' => ' $("#ReportForm_district_code").select2("val", "");
                                        $("#ReportForm_ward_code").select2("val", "");
                                        $("#ReportForm_brand_offices_id").select2("val", "");
                                        $("#ReportForm_sale_office_code").select2("val", "");
                                    ',
                                //reset value selected
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2" style="width: 10%;">
            <?php echo $form->label($model, 'ctv_type'); ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">

                <?php echo $form->dropDownList($model, 'ctv_type', $model->getTypeCTV(), array(
                        'class'  => 'form-control',
//                        'prompt' => 'Chọn loại CTV',
                        'style'  => 'width:100%',
                    )
                ); ?>
                <?php echo $form->error($form_validate, 'ctv_type'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2" style="width: 10%;">
            <?php echo $form->label($model, 'ctv_id'); ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'ctv_id',
                            'data'        => $model->getAllCtv(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => 'Tất cả',
                                //reset value selected
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-warning',)); ?>
            </div>
        </div>
    </div>


    <?php $this->endWidget(); ?>
</div>
<style>
    #ReportForm_input_type label {
        margin-right: 15px;
    }

    #ReportForm_offices_id {
        font-size: 11px;
    }
</style>
