<?php
    /* @var $this ABrandOfficesController */
    /* @var $model ABrandOffices */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','location'),
        Yii::t('adm/label', 'brand_offices') => array('admin'),
    );
?>


<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'brand_offices'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--    --><?php //$this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'abrand-offices-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->id), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:70px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'name',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->name), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'width:200px;word-break: break-word;vertical-align:middle;'),
                    ),
                    'address',
                    array(
                        'name'        => 'hotline',
                        'htmlOptions' => array('style' => 'width:200px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'filter'      => CHtml::activeDropDownList(
                            $model,
                            'status',
                            array(
                                ABrandOffices::BRAND_OFFICE_ACTIVE   => Yii::t('adm/label', 'active'),
                                ABrandOffices::BRAND_OFFICE_INACTIVE => Yii::t('adm/label', 'inactive')
                            ),
                            array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                        ),
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ABrandOffices::BRAND_OFFICE_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ABrandOffices::BRAND_OFFICE_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '130px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => '',
                        'template'    => '{update}&nbsp;{delete}',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aBrandOffices/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#abrand-offices-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>