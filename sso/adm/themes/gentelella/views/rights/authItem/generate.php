<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Generate items'),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div id="generator">
                <div class="x_title">
                    <h2><?php echo Rights::t('core', 'Generate items'); ?></h2>
                    <div class="clearfix"></div>
                </div>                

                <div class="x_content">
                    <p><?php echo Rights::t('core', 'Please select which items you wish to generate.'); ?></p>

                    <div class="form">

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'htmlOptions' => array(
                                'class' => 'form-horizontal form-label-left',
                            ),
                        ));
                        ?>

                        <div class="form-group">

                            <table class="generate-item-table table table-striped table-hover">

                                <tbody>

                                    <tr class="application-heading-row">
                                        <th colspan="3"><?php echo Rights::t('core', 'Application'); ?></th>
                                    </tr>

                                    <?php
                                    $this->renderPartial('_generateItems', array(
                                        'model' => $model,
                                        'form' => $form,
                                        'items' => $items,
                                        'existingItems' => $existingItems,
                                        'displayModuleHeadingRow' => true,
                                        'basePathLength' => strlen(Yii::app()->basePath),
                                    ));
                                    ?>

                                </tbody>

                            </table>

                        </div>

                        <div class="form-group">
                            <code>
                                <?php
                                echo CHtml::link(Rights::t('core', 'Select all'), '#', array(
                                    'onclick' => "jQuery('.generate-item-table tr input:checkbox').prop('checked', true); return false;",
                                    'class' => 'selectAllLink'));
                                ?>
                                /
                                <?php
                                echo CHtml::link(Rights::t('core', 'Select none'), '#', array(
                                    'onclick' => "jQuery('.generate-item-table tr input:checkbox').prop('checked', false); return false;",
                                    'class' => 'selectNoneLink'));
                                ?>
                            </code>
                        </div>

                        <div class="form-group">
                            <?php echo CHtml::submitButton(Rights::t('core', 'Generate'), array('class' => 'btn btn-success')); ?>
                        </div>

                        <?php $this->endWidget(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>