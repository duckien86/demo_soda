<?php
    /* @var $this AFTPackageController */
    /* @var $model AFTPackage */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'product') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'product'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'aftpackage-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
//                    array(
//                        'name'        => 'id',
//                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'name',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->name), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'code',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->code), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'price',
                        'value'       => 'number_format($data->price, 0, "", ".")."đ"',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'type',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList($model,'type',AFTPackage::getListType(), array(
                            'class'=>'form-control',
                            'empty'    => 'Tất cả',
                        )),
                        'value'       => function($data){
                            return AFTPackage::getTypeLabel($data->type);
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList($model,'status',
                            array(
                                AFTPackage::FT_PACKAGE_ACTIVE   => Yii::t('adm/label', 'active'),
                                AFTPackage::FT_PACKAGE_INACTIVE => Yii::t('adm/label', 'inactive')
                            ),
                            array(
                                'class'=>'form-control',
                                'empty'    => 'Tất cả',
                        )),
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    AFTPackage::FT_PACKAGE_ACTIVE   => Yii::t('adm/label', 'active'),
                                    AFTPackage::FT_PACKAGE_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array(
                                    'class'    => 'dropdownlist',
                                    'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '130px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => '',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view} {update}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>

<script language="javascript">
    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aFTPackage/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#aftpackage-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>
