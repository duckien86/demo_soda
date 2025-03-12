<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Tasks'),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div id="tasks">
                <div class="x_title">
                    <h2><?php echo Rights::t('core', 'Tasks'); ?></h2>
                    <div class="clearfix"></div>
                </div>                

                <div class="x_content"> 
                    <p>
                        <?php echo Rights::t('core', 'A task is a permission to perform multiple operations, for example accessing a group of controller action.'); ?><br />
                        <?php //echo Rights::t('core', 'Tasks exist below roles in the authorization hierarchy and can therefore only inherit from other tasks and/or operations.'); ?>
                    </p>

<!--                    <p><code>--><?php
////                            echo CHtml::link(Rights::t('core', 'Create a new task'), array('authItem/create', 'type' => CAuthItem::TYPE_TASK), array(
////                                'class' => 'add-task-link',
////                            ));
//                            ?><!--</code></p>-->

                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'template' => '{items}',
                        'emptyText' => Rights::t('core', 'No tasks found.'),
                        'htmlOptions' => array('class' => 'grid-view task-table'),
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'header' => Rights::t('core', 'Name'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'name-column'),
                                'value' => '$data->getGridNameLink()',
                            ),
                            array(
                                'name' => 'description',
                                'header' => Rights::t('core', 'Description'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'description-column'),
                            ),
                            array(
                                'name' => 'bizRule',
                                'header' => Rights::t('core', 'Business rule'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'bizrule-column'),
                                'visible' => Rights::module()->enableBizRule === true,
                            ),
                            array(
                                'name' => 'data',
                                'header' => Rights::t('core', 'Data'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'data-column'),
                                'visible' => Rights::module()->enableBizRuleData === true,
                            ),
                            array(
                                'header' => '&nbsp;',
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'actions-column'),
                                'value' => '$data->getDeleteTaskLink()',
                            ),
                        )
                    ));
                    ?>

<!--                    <p class="info">--><?php ////echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?><!--</p>-->
                </div>
            </div>
        </div>
    </div>
</div>