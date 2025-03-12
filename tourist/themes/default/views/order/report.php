<?php
/**
 * @var $this OrderController
 * @var $model TOrders
 * @var $list_order_details array
 */

$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'report_order') . ' ' . $model->code;
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'report_order') . ' ' . $model->code,
);

$total_quantity = TOrders::getTotalQuantity($model);
?>

<div id="order">
    <table class="table table-striped table-responsive">
        <thead>
        <tr>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','order_code'))?></th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','ordered'))?></th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','produce'))?></th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','remain'))?></th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','status'))?></th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','file_implemented_kit'))?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo CHtml::encode($model->code)?></td>
            <td><?php echo CHtml::encode(number_format($total_quantity, 0, ',', '.'))?></td>
            <td><?php echo CHtml::encode(number_format($model->total_success, 0, ',', '.'))?></td>
            <td><?php echo CHtml::encode(number_format($total_quantity - $model->total_success, 0, ',', '.'))?></td>
            <td><?php echo CHtml::encode(TOrders::getOrdersStatusLabel($model))?></td>
            <td>
                <form target="_blank" method="post" action="<?php echo Yii::app()->createUrl('excelExport/exportDeatailSimTourist')?>" name="f">
                    <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken ?>">
                    <input type="hidden" name="order_id" value="<?php echo $model->id?>">
                    <input type="hidden" name="order_code" value="<?php echo $model->code?>">
                    <button name="submit" type="submit" class="btn btn-primary" value="Xuất Excel">Chi tiết</button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>



</div>
