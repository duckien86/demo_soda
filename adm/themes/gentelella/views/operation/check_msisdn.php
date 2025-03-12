<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'orders') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
    if (empty($data_output)) {
        $data_output = '';
    }
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Kiểm tra thuê bao</h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_search_check_msisdn', array('model' => $model)); ?>
    <div class="x_content">
        <div class="row">
            <div class="col-md-12" style="margin-left: 10px;">
                <h5>Kết quả trả về</h5>
                <?php echo CHtml::textArea('output', $data_output, array('class' => 'form-control', 'style' => 'width:900px;height:120px;', 'placeholder' => 'Kết quả trả về')); ?>
            </div>
        </div>
    </div>
</div>
<style>
    #content {
        overflow-x: scroll;
    }
</style>
<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
</script>