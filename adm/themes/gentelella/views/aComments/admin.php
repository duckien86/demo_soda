<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','forum'),
        Yii::t('adm/label', 'manage_comment') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_comment'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'acomments-grid',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'create_date',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => 'CHtml::link($data->create_date, array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'sso_id',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode(ACustomers::getName($data->sso_id));
                        },
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'content',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link($data->content, array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:200px !important;
                        '),
                    ),
                    array(
                        'name'        => 'total_like',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => 'CHtml::link($data->total_like, array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'sc_tbl_post_id',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => 'CHtml::link($data->getPostTitle($data->sc_tbl_post_id), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:200px !important;
                        '),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    AComments::ACTIVE   => Yii::t('adm/label', 'active'),
                                    AComments::INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value,'$data->sso_id')",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '150px', 'style' => 'vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
        <div class="show-popup"></div>
    </div>
</div>

<script language="javascript">
    function changeStatus(id, status, sso_id) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->controller->createUrl('aComments/changeStatus')?>',
            crossDomain: true,
            data: {id: id, status: status, sso_id: sso_id},
            success: function (result) {
                $('.show-popup').html(result);
                var modal_id = 'modal_' + id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }
</script>