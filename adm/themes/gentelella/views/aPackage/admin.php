<?php
/* @var $this APackageController */
/* @var $model APackage */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_business'),
    Yii::t('adm/menu', 'website_content'),
    Yii::t('adm/label', 'package') => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'package'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id' => 'apackage-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns' => array(
                    array(
                        'name' => 'id',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->id), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:130px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->name), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'code_vnpt',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->code_vnpt), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:110px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'extra_params',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->extra_params), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'price',
                        'value' => 'number_format($data->price, 0, "", ".")."đ"',
                        'htmlOptions' => array('style' => 'width:90px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'price_no_stb',
                        'value' => 'number_format($data->price_no_stb, 0, "", ".")."đ"',
                        'htmlOptions' => array('style' => 'width:90px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'price_stb',
                        'value' => 'number_format($data->price_stb, 0, "", ".")."đ"',
                        'htmlOptions' => array('style' => 'width:90px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'type',
                        'type' => 'raw',
                        'value' => '$data->getPackageType($data->type)',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header' => 'Quốc gia',
                        'type' => 'raw',
                        'value' => '$data->btnAddNation($data->id, $data->type)',
                        'htmlOptions' => array('style' => 'width:70px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name' => 'status',
                        'type' => 'raw',
                        'value' => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    APackage::PACKAGE_ACTIVE => Yii::t('adm/label', 'active'),
                                    APackage::PACKAGE_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class' => 'dropdownlist',
                                    'onChange' => "js:changeStatus('$data->id',this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header' => '',
                        'class' => 'booster.widgets.TbButtonColumn',
                        'template' => '{update}&nbsp;&nbsp{view}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    function changeStatus(id, status) {
        var r = confirm('Bạn có chắc chắn muốn thay đổi!');
        if (r == true) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->createUrl('aPackage/setStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
                success: function (result) {
                    if (result === true) {
                        $('#apackage-grid').yiiGridView('update', {
                            data: $(this).serialize()
                        });
                        return true;
                    }
                }
            });
        }
    }
</script>

