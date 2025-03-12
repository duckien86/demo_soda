<?php
    /* @var $this ANewsCommentsController */
    /* @var $model ANewsComments */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        'Bình luận tin tức' => array('admin'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Bình luận tin tức</h2>

        <div class="clearfix"></div>
    </div>

    <?php $this->renderPartial('/aNewsComments/_filter_area', array('model' => $model))?>

    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'anewscomments-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'  => 'news_id',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '80px'),
                    ),
                    array(
                        'header' => 'Mã bình luận',
                        'name' => 'id',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '90px'),
                    ),
                    array(
                        'name'  => 'comment_parent',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '90px'),
                    ),
                    array(
                        'name' => 'content',
                        'type' => 'raw',
                        'value' => function($data){
                            $value = nl2br($data->content);
                            return $value;
                        },
                    ),
                    array(
                        'name'  => 'created_on',
                        'filter' => CHtml::activeHiddenField($model, 'start_date')
                            . CHtml::activeHiddenField($model, 'end_date'),
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '90px'),
                    ),
                    array(
                        'name'  => 'username',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '100px'),
                    ),
                    array(
                        'name'  => 'email',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '120px'),
                    ),
                    array(
                        'name'  => 'status',
                        'filter' => CHtml::activeDropDownList($model, 'status',
                            ANewsComments::getListStatus(),
                            array(
                                'class' => 'form-control',
                                'empty' => 'Tất cả',
                            )
                        ),
                        'sortable' => false,
                        'type'  => 'raw',
                        'value' => function($data){
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ANewsComments::ACTIVE   => Yii::t('adm/label', 'active'),
                                    ANewsComments::INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                    'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );

                        },
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '110px', 'class' => 'text-center'),
                    ),
                    array(
                        'header'      => '',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{update} {delete}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '70px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aNewsComments/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#anewscomments-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>