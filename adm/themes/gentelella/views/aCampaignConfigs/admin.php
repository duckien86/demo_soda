<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        'Quản lý link quảng cáo' => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2>Quản lý link quảng cáo</h2>
        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'acampaign-configs-grid',
                    'dataProvider'  => $model->search(),
                    'filter'        => $model,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'utm_source',
                            'type'        => 'raw',
                            'value'       => 'CHtml::encode($data->utm_source)',
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                        array(
                            'name'        => 'utm_medium',
                            'type'        => 'raw',
                            'filter'      => FALSE,
                            'value'       => 'CHtml::encode($data->utm_medium)',
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                        array(
                            'name'        => 'utm_campaign',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => 'CHtml::encode($data->utm_campaign)',
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                        array(
                            'name'        => 'target_link',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => 'CHtml::encode($data->target_link)',
                            'htmlOptions' => array(),
                        ),
                        array(
                            'name'        => 'type',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => 'CHtml::encode($data->type)',
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                        array(
                            'name'        => 'create_date',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => 'CHtml::encode($data->create_date)',
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),

                        array(
                            'name'        => 'status',
                            'type'        => 'raw',
                            'filter'      => CHtml::activeDropDownList($model, 'status', array(ACampaignConfigs::ACTIVE => ACampaignConfigs::TEXT_ACTIVE, ACampaignConfigs::INACTIVE => ACampaignConfigs::TEXT_INACTIVE), array('empty' => 'Tất cả', 'class' => 'form-control')),
                            'value'       => function ($data) {
                                $icon   = $data->status == ACampaignConfigs::ACTIVE ? "<i class=\"fa fa-check-circle\"></i>" : "<i class=\"fa fa-times-circle\"></i>";
                                $status = $data->status == ACampaignConfigs::ACTIVE ? ACampaignConfigs::INACTIVE : ACampaignConfigs::ACTIVE;

                                return CHtml::link($icon, "javascript:;", array(
                                    'title'               => '',
                                    'class'               => '',
                                    'data-toggle'         => 'tooltip',
                                    'data-original-title' => 'Thay đổi trạng thái',
                                    'onclick'             => 'changeStatus(' . $data->id . ',' . $status . ');',
                                ));

                            },
//                        'htmlOptions' => array( 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                        array(
                            'header'      => 'Tạo link',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $link = $data->generateLink();
                                return CHtml::link($link, 'javascript:void(0)',
                                    array('data-toggle' => "modal", 'data-target' => "#modal_" . $data->id, 'style' => 'color:blue;'));
                            },
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                        array(
                            'header'      => Yii::t('adm/actions', 'action'),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'template'    => '{update}&nbsp;&nbsp;{delete}',
//                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '100px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                            'htmlOptions' => array('nowrap' => 'nowrap'),
                        ),
                    ),
                )); ?>
        </div>
    </div>
    <div class="popup_data">
    </div>
</div>
<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
    function changeStatus(id, status) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aCampaignConfigs/setStatus')?>',
            crossDomain: true,
            dataType: 'json',
            data: {id: id, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
            success: function (result) {
                if (result === true) {
                    $('#acampaign-configs-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            }
        });
    }
    function createLink(id) {
        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aCampaignConfigs/showForm') ?>',
            crossDomain: true,
            data: {
                id: id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function (result) {
                $('.modal-backdrop').remove();
                $('.popup_data').html(result);

                var modal_id = 'modal_' + id;
                $('#' + modal_id).modal('show');

                return false;
            }
        });
    }


</script>

