<?php
    $this->breadcrumbs = array(
        'Rights'                                        => Rights::getBaseUrl(),
        Rights::getAuthItemTypeNamePlural($model->type) => Rights::getAuthItemRoute($model->type),
        $model->name,
    );
?>

<div class="x_panel container-fluid">
    <div id="updatedAuthItem">
        <div class="x_title">
            <h2><?php
                    echo Rights::t('core', 'Update :name', array(
                        ':name' => $model->name,
                        ':type' => Rights::getAuthItemTypeName($model->type),
                    ));
                ?></h2>

            <div class="clearfix"></div>
        </div>

        <?php if ($model->type == 2) { ?>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php $this->renderPartial('_form', array('model' => $formModel)); ?>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12">

                        <h3><?php echo Rights::t('core', 'Relations'); ?></h3>

                        <?php if ($model->name !== Rights::module()->superuserName): ?>

                            <div class="parents">

                                <h4><?php echo Rights::t('core', 'Parents'); ?></h4>

                                <?php
                                    $this->widget('zii.widgets.grid.CGridView', array(
                                        'dataProvider'  => $parentDataProvider,
                                        'template'      => '{items}',
                                        'hideHeader'    => TRUE,
                                        'emptyText'     => Rights::t('core', 'This item has no parents.'),
                                        //                                        'htmlOptions' => array('class' => 'grid-view parent-table mini'),
                                        'itemsCssClass' => 'table table-striped table-bordered table-hover responsive-utilities jambo_table',
                                        'columns'       => array(
                                            array(
                                                'name'        => 'name',
                                                'header'      => Rights::t('core', 'Name'),
                                                'type'        => 'raw',
                                                'htmlOptions' => array('class' => 'name-column'),
                                                'value'       => '$data->getNameLink()',
                                            ),
                                            array(
                                                'name'        => 'type',
                                                'header'      => Rights::t('core', 'Type'),
                                                'type'        => 'raw',
                                                'htmlOptions' => array('class' => 'type-column'),
                                                'value'       => '$data->getTypeText()',
                                            ),
                                            array(
                                                'header'      => '&nbsp;',
                                                'type'        => 'raw',
                                                'htmlOptions' => array('class' => 'actions-column'),
                                                'value'       => '',
                                            ),
                                        )
                                    ));
                                ?>

                            </div>

                            <div class="children">

                                <h4><?php echo Rights::t('core', 'Children'); ?></h4>

                                <?php
                                    $this->widget('zii.widgets.grid.CGridView', array(
                                        'dataProvider'  => $childDataProvider,
                                        'template'      => '{items}',
                                        'hideHeader'    => TRUE,
                                        'emptyText'     => Rights::t('core', 'This item has no children.'),
                                        //                                        'htmlOptions' => array('class' => 'grid-view parent-table mini'),
                                        'itemsCssClass' => 'table table-striped table-bordered table-hover responsive-utilities jambo_table',
                                        'columns'       => array(
                                            array(
                                                'name'        => 'name',
                                                'header'      => Rights::t('core', 'Name'),
                                                'type'        => 'raw',
                                                'htmlOptions' => array('class' => 'name-column'),
                                                'value'       => '$data->getNameLink()',
                                            ),
                                            array(
                                                'name'        => 'type',
                                                'header'      => Rights::t('core', 'Type'),
                                                'type'        => 'raw',
                                                'htmlOptions' => array('class' => 'type-column'),
                                                'value'       => '$data->getTypeText()',
                                            ),
                                            array(
                                                'header'      => '&nbsp;',
                                                'type'        => 'raw',
                                                'htmlOptions' => array('class' => 'actions-column'),
                                                'value'       => '$data->getRemoveChildLink()',
                                            ),
                                        )
                                    ));
                                ?>

                            </div>

                            <div class="addChild">

                                <h5><?php echo Rights::t('core', 'Add Child'); ?></h5>

                                <?php if ($childFormModel !== NULL): ?>

                                    <?php
                                    $this->renderPartial('_childForm', array(
                                        'model'                 => $childFormModel,
                                        'itemnameSelectOptions' => $childSelectOptions,
                                    ));
                                    ?>

                                <?php else: ?>

                                <p class="info"><?php echo Rights::t('core', 'No children available to be added to this item.'); ?>

                                    <?php endif; ?>

                            </div>

                        <?php else: ?>

                            <p class="info">
                                <?php echo Rights::t('core', 'No relations need to be set for the superuser role.'); ?>
                                <br/>
                                <?php echo Rights::t('core', 'Super users are always granted access implicitly.'); ?>
                            </p>

                        <?php endif; ?>

                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        <?php } else { ?>
            <div class="x_content">
                <?php $this->renderPartial('_form', array('model' => $formModel)); ?>
            </div>
        <?php } ?>
    </div>
</div>
