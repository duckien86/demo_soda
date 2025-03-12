<?php
/**
 * @var $this AdminController
 * @var $model User
 */

$this->breadcrumbs = array(
    UserModule::t('Users') => array('admin'),
    UserModule::t('Manage'),
);

?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo UserModule::t("Manage Users"); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div id="sidebar">
            <a class="btn btn-warning" href="index.php?r=user/admin/create">Tạo mới</a>

        </div>

        <div class="form-search">
            <?php if (!ADMIN_CSKH): ?>
                <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
            <?php endif; ?>
        </div>
        <?php if (!ADMIN_CSKH): ?>
            <form method="post"
                  action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/userAdmin'); ?>"
                  name="fday">

                <input type="hidden" name="excelExport[province_code]"
                       value="<?php echo $model->province_code ?>">
                <input type="hidden" name="excelExport[sale_offices_id]"
                       value="<?php echo $model->sale_offices_id ?>">
                <input type="hidden" name="excelExport[brand_offices_id]"
                       value="<?php echo $model->brand_offices_id ?>">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
            </form>
        <?php endif; ?>
        <?php if (!ADMIN_CSKH): ?>
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider'  => $model->search(TRUE),
                'filter'        => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
//                    array(
//                        'name'   => 'id',
//                        'filter' => FALSE,
//                        'type'   => 'raw',
//                        'value'  => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
//
//                    ),
                    array(
                        'name'        => 'username',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->username),array("admin/view","id"=>$data->id))',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '100px'),
                    ),
                    array(
                        'name'  => 'phone',
                        'type'  => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->phone),array("admin/view","id"=>$data->id))',
                    ),
                    array(
                        'name'   => 'fullname',
                        'filter' => FALSE,
                        'type'   => 'raw',
                        'value'  => function ($data) {
                            return CHtml::encode(User::model()->getFullName($data->id));
                        },
                    ),
                    array(
                        'header' => 'TTKD',
                        'type'   => 'raw',
//                        'filter' => CHtml::activeDropDownList($model, 'province_code', Province::model()->getAllProvince(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                        'value'  => function ($data) {
                            return CHtml::encode(Province::model()->getProvince($data->province_code));
                        },

                    ),
                    array(
                        'header' => 'Tên PBH',
                        'type'   => 'raw',
//                        'filter' => CHtml::activeDropDownList($model, 'sale_offices_id', SaleOffices::model()->getAllSaleOffices(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                        'value'  => function ($data) {
                            if ($data->sale_offices_id != '') {
                                return CHtml::encode(SaleOffices::model()->getSaleOffices($data->sale_offices_id));
                            }

                            return "";
                        },
                    ),
                    array(
                        'header' => 'Tên ĐGD',
                        'type'   => 'raw',
//                        'filter' => CHtml::activeDropDownList($model, 'brand_offices_id', BrandOffices::model()->getAllBrandOffices(), array('empty' => 'Tất cả', 'class' => 'form-control')),
                        'value'  => function ($data) {
                            if ($data->brand_offices_id != '') {
                                return CHtml::encode(BrandOffices::model()->getBrandOffices($data->brand_offices_id));
                            }

                            return "";
                        },
                    ),
                    array(
                        'name'   => 'lastvisit',
                        'filter' => FALSE,
                        'value'  => '(($data->lastvisit)?date("d.m.Y H:i:s",$data->lastvisit):UserModule::t("Not visited"))',
                    ),
                    array(
                        'name'   => 'regency',
                        'filter' => FALSE,
                        'value'  => function ($data) {
                            if ($data->regency == 'ADMIN') {
                                return "ADMIN";
                            } else if ($data->regency == 'STAFF') {
                                return "Quản lý";
                            } else if ($data->regency == 'ACCOUNTANT') {
                                return "Kế toán";
                            }

                            return "";
                        },
                    ),
                    array(
                        'name'   => 'status',
                        'filter' => CHtml::activeDropDownList($model, 'status', array(User::STATUS_ACTIVE => UserModule::t('Active'),
                                                                                      User::STATUS_BANED  => UserModule::t('Banned')), array('empty' => 'Tất cả', 'class' => 'form-control')),
                        'value'  => 'User::itemAlias("UserStatus",$data->status)',
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
//                        'template'    => '{view}{update}{delete}',
                        'buttons'     => array(
                            'delete' => array(
                                'visible' => '(ADMIN || SUPER_ADMIN)',
                            )
                        ),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            ));
            ?>
        <?php endif; ?>
    </div>
</div>
<style type="text/css">
    .filters {
        background-color: #f5f5f5;
        color: black;
    }

    .hidden-small {
        display: none !important;
    }
</style>
