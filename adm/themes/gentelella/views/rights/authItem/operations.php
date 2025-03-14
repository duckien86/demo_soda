<?php
    $this->breadcrumbs = array(
        'Rights' => Rights::getBaseUrl(),
        Rights::t('core', 'Operations'),
    );
?>
<?php $this->renderPartial('/assignment/_menu_actions'); ?>

<div class="x_panel">
    <div id="operations">
        <div class="x_title">
            <h2><?php echo Rights::t('core', 'Operations'); ?></h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <p>
                <?php echo Rights::t('core', 'An operation is a permission to perform a single operation, for example accessing a certain controller action.'); ?>
                <br/>
                <?php //echo Rights::t('core', 'Operations exist below tasks in the authorization hierarchy and can therefore only inherit from other operations.'); ?>
            </p>

            <!--                    <p><code>--><?php
                ////                            echo CHtml::link(Rights::t('core', 'Create a new operation'), array('authItem/create', 'type' => CAuthItem::TYPE_OPERATION), array(
                ////                                'class' => 'add-operation-link',
                ////                            ));
                //                            ?><!--</code></p>-->

            <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'  => $dataProvider,
                    'template'      => '{items}',
                    'emptyText'     => Rights::t('core', 'No operations found.'),
                    'htmlOptions'   => array('class' => 'grid-view operation-table sortable-table'),
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'name',
                            'header'      => Rights::t('core', 'Name'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'name-column'),
                            'value'       => '$data->getGridNameLink()',
                        ),
                        array(
                            'name'        => 'description',
                            'header'      => Rights::t('core', 'Description'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'description-column'),
                        ),
                        array(
                            'name'        => 'bizRule',
                            'header'      => Rights::t('core', 'Business rule'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'bizrule-column'),
                            'visible'     => Rights::module()->enableBizRule === TRUE,
                        ),
                        array(
                            'name'        => 'data',
                            'header'      => Rights::t('core', 'Data'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'data-column'),
                            'visible'     => Rights::module()->enableBizRuleData === TRUE,
                        ),
                        array(
                            'header'      => '&nbsp;',
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'actions-column'),
                            'value'       => '$data->getDeleteOperationLink()',
                        ),
                    )
                ));
            ?>

            <!--                    <p class="info">-->
            <?php ////echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?><!--</p>-->
        </div>
    </div>
</div>
