<?php
    /* @var $this APackageController */
    /* @var $modelPackage APackage */
    /* @var $nations ANations */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'package') => array('admin'),
        'Chọn quốc gia áp dụng',
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Chọn quốc gia áp dụng cho gói cước: <?= CHtml::encode($modelPackage->name); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="container-fluid">
            <?= CHtml::hiddenField('package_id', $modelPackage->id, array('id' => 'package_id')); ?>
            <div class="col-md-6">
                <fieldset>
                    <legend style="margin-bottom: 0;">Trả trước</legend>
                    <div class="table-responsive">
                        <?php $this->widget('booster.widgets.TbExtendedGridView', array(
                            'id'           => 'nation_grid_pre',
                            'type'         => '',
                            'dataProvider' => $nations,
                            'summaryText'  => '',
                            'bulkActions'  => array(
                                'checkBoxColumnConfig' => array(
                                    'name' => 'id'
                                ),
                            ),
                            'columns'      => array(
                                array(
                                    'header'      => Yii::t('adm/label', 'nations'),
                                    'name'        => 'name',
                                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                ),
                                array(
                                    'header'      => Yii::t('adm/label', 'telco_prepaid'),
                                    'name'        => 'telco_prepaid',
                                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                ),
                                array(
                                    'filter'      => FALSE,
                                    'class'       => 'CDataColumn',
                                    'type'        => 'raw',
                                    'htmlOptions' => array('style' => 'width:100px;text-align:center'),
                                    'value'       => 'CHtml::checkBox("chk_nation_".$data->code,$data->checkActive($data->package_id, $data->code, APackagesNations::PACKAGE_PREPAID), array(
                            "value"=>$data->code,
                            "data-packagetype"=>APackagesNations::PACKAGE_PREPAID,
                            "class"=>"chk_nation",
                            ))',
                                ),
                            ),
                        )); ?>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-6">

                <fieldset>
                    <legend style="margin-bottom: 0;">Trả sau</legend>
                    <div class="table-responsive">
                        <?php $this->widget('booster.widgets.TbExtendedGridView', array(
                            'id'           => 'nation_grid_post',
                            'type'         => '',
                            'dataProvider' => $nations,
                            'summaryText'  => '',
                            'bulkActions'  => array(
                                'checkBoxColumnConfig' => array(
                                    'name' => 'id'
                                ),
                            ),
                            'columns'      => array(
                                array(
                                    'header'      => Yii::t('adm/label', 'nations'),
                                    'name'        => 'name',
                                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                ),
                                array(
                                    'header'      => Yii::t('adm/label', 'telco_postpaid'),
                                    'name'        => 'telco_postpaid',
                                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                ),
                                array(
                                    'filter'      => FALSE,
                                    'class'       => 'CDataColumn',
                                    'type'        => 'raw',
                                    'htmlOptions' => array('style' => 'width:100px;text-align:center'),
                                    'value'       => 'CHtml::checkBox("chk_nation_".$data->code,$data->checkActive($data->package_id, $data->code, APackagesNations::PACKAGE_POSTPAID), array(
                            "value"=>$data->code,
                            "data-packagetype"=>APackagesNations::PACKAGE_POSTPAID,
                            "class"=>"chk_nation",
                            ))',
                                ),
                            ),
                        )); ?>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.chk_nation', function (e) {
        var package_id = $('#package_id').val();
        var nation_code = $(this).val();
        $(this).unbind('click');
//        if ($(this).is(':checked')) {
//            console.log(true);
//        }
        console.log($(this).attr('data-packagetype'));
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('aPackage/addNation');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                type: $(this).attr('data-packagetype'),
                nation_code: nation_code,
                package_id: package_id,
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                $.fn.yiiGridView.update('nation_grid_pre');
                $.fn.yiiGridView.update('nation_grid_post');
            }
        });
    });
</script>