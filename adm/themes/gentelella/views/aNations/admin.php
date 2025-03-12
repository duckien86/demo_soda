<?php
    /* @var $this ANationsController */
    /* @var $model ANations */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','location'),
        Yii::t('adm/label', 'nations') => array('admin'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'nations'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'anations-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'filter'       => $model,
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:60px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'name',
                        'htmlOptions' => array('style' => 'width:150px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'code',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'telco_prepaid',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'telco_postpaid',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'continent',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList(
                            $model,
                            'continent',
                            $model->arrayContinent(),
                            array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                        ),
                        'value'       => '$data->getContinentLabel($data->continent)',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => CHtml::activeDropDownList(
                            $model,
                            'status',
                            array(
                                ANations::NATION_ACTIVE   => Yii::t('adm/label', 'active'),
                                ANations::NATION_INACTIVE => Yii::t('adm/label', 'inactive')
                            ),
                            array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                        ),
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ANations::NATION_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ANations::NATION_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '120px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => '',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{update}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>

        </div>
    </div>
</div>