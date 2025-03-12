<?php
    /* @var $this ANewsCategoriesController */
    /* @var $model ANewsCategories */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        Yii::t('adm/label', 'news_categories') => array('admin'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'news_categories'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'anews-categories-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:70px;vertical-align:middle;'),
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
                        'name'        => 'parent_id',
                        'type'        => 'raw',
                        'value'       => '$data->newsCategoriesTitle',
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'create_date',
                        'htmlOptions' => array('style' => 'width:100px;text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'sort_order',
                        'htmlOptions' => array('style' => 'width:90px;text-align: center;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'in_home_page',
                        'value'       => '$data->getLabelHomepage($data->in_home_page)',
                        'htmlOptions' => array('style' => 'width:90px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ANewsCategories::NEWS_CATE_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ANewsCategories::NEWS_CATE_INACTIVE => Yii::t('adm/label', 'inactive')
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
                        'template'    => '{update} {delete}',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('style' => 'width:100px;text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aNewsCategories/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#anews-categories-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>