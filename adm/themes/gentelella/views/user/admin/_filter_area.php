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
<?php
    if (!ADMIN && !SUPER_ADMIN) {
        if (isset(Yii::app()->user->sale_offices_id)) {
            if (!empty(Yii::app()->user->sale_offices_id)) {
                $model->sale_offices_id = Yii::app()->user->sale_offices_id;
            }
        }
        if (isset(Yii::app()->user->brand_offices_id)) {
            if (!empty(Yii::app()->user->brand_offices_id)) {
                $model->brand_offices_id = Yii::app()->user->brand_offices_id;
            }
        }
        if (isset(Yii::app()->user->province_code)) {
            if (!empty(Yii::app()->user->province_code)) {
                $model->province_code = Yii::app()->user->province_code;
            }
        }
    }

?>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'province_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'province_code',
                                'data'        => AProvince::model()->getAllProvince(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN) ? NULL : Yii::t('report/menu', 'province_code'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('user/admin/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#User_sale_offices_id',
                                        'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => ' $("#User_district_code").select2("val", "");
                                        $("#User_ward_code").select2("val", "");
                                        $("#AOrders_sale_offices_id").select2("val", "");
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'province_code'); ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="form-group">

                    <?php echo $form->labelEx($model, 'sale_offices_id'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'sale_offices_id',
                                'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && Yii::app()->user->sale_offices_id != '') ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('user/admin/getBrandOfficeBySaleCode'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#User_brand_offices_id',
                                        'data'   => array('sale_offices_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#AOrders_brand_offices_id").select2("val", "");
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'sale_offices_id'); ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'brand_offices_id'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'brand_offices_id',
                                'data'        => ($model->sale_offices_id != '') ? ABrandOffices::model()->getBrandOfficesBySaleCode($model->sale_offices_id) : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && isset(Yii::app()->user->brand_offices_id)) ? NULL : Yii::t('report/menu', 'brand_offices_id'),
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'brand_offices_id'); ?>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: 25px;">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>