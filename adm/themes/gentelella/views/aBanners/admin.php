<?php
    /* @var $this ABannersController */
    /* @var $model ABanners */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        'Banner' => array('admin')
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Banner</h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'id'           => 'abanners-grid',
                    'dataProvider' => $model->search(),
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'      => array(
                        array(
                            'name'        => 'img_desktop',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => 'CHtml::link($data->getImageUrl($data->img_desktop), array(\'update\', \'id\' => $data->id))',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%'),
                        ),
                        array(
                            'name'        => 'img_mobile',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => 'CHtml::link($data->getImageUrl($data->img_mobile), array(\'update\', \'id\' => $data->id))',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%'),
                        ),
                        array(
                            'name'        => 'title',
                            'type'        => 'raw',
                            'value'       => 'CHtml::link(CHtml::encode($data->title), array(\'update\', \'id\' => $data->id))',
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'file_ext',
                            'type'        => 'raw',
                            'value'       => 'CHtml::link(CHtml::encode($data->file_ext), array(\'update\', \'id\' => $data->id))',
                            'htmlOptions' => array('width' => '100px', 'style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'sort_order',
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'type',
                            'type'        => 'raw',
                            'value'       => 'CHtml::encode($data->getBannerTypeLabel($data->type))',
                            'htmlOptions' => array('width' => '150px', 'style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'status',
                            'filter'      => CHtml::activeDropDownList(
                                $model,
                                'status',
                                array(
                                    ABanners::BANNER_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ABanners::BANNER_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                            ),
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                return CHtml::activeDropDownList($data, 'status',
                                    array(
                                        ABanners::BANNER_ACTIVE   => Yii::t('adm/label', 'active'),
                                        ABanners::BANNER_INACTIVE => Yii::t('adm/label', 'inactive')
                                    ),
                                    array('class'    => 'dropdownlist',
                                          'onChange' => "js:changeStatus($data->id,this.value)",
                                    )
                                );
                            },
                            'htmlOptions' => array('width' => '130px', 'style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'header'      => Yii::t('adm/actions', 'action'),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'template'    => '{update}&nbsp;&nbsp;{delete}',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '100px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aBanners/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#abanners-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>