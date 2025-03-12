<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        Yii::t('adm/label', 'news') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'news'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'anews-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:50px;vertical-align:middle;'),
                    ),
                    /*array(
                        'name'        => 'thumbnail',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => 'CHtml::link($data->getImageUrl($data->thumbnail), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%'),
                    ),*/
                    array(
                        'name'        => 'title',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->title), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'categories_id',
                        'type'        => 'raw',
                        'value'       => '$data->newsCategoriesTitle',
                        'htmlOptions' => array('style' => 'width:100px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'sort_order',
                        'htmlOptions' => array('style' => 'width:100px;text-align: center;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'last_update',
                        'htmlOptions' => array('style' => 'width:120px;text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'hot',
                        'value'       => function($data){
                            return CHtml::encode(ANews::getLabelPosition($data->hot));
                        },
                        'htmlOptions' => array('style' => 'width:90px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ANews::NEWS_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ANews::NEWS_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '120px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'width:100px;text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aNews/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#anews-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>
