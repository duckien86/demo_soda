<?php
    /* @var $this OrdersController */
    /* @var $packages */
    /* @var $array */
?>
<div class="space_10"></div>
<?php if ($packages): ?>
    <div class="col-md-12">
        Các gói cước bạn đang sử dụng
    </div>
    <div class="space_10"></div>
    <div class="col-md-12">
        <?php
            $flashMessages = Yii::app()->user->getFlashes();
            if ($flashMessages) {
                echo '<ul class="flashes">';
                foreach ($flashMessages as $key => $message) {
                    echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
                }
                echo '</ul>';
            }
        ?>
    </div>

    <div class="space_10"></div>
    <div class="col-md-6">
        <div class="table-responsive center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'package-grid',
                'dataProvider'  => $packages,
                'enableSorting' => FALSE,
                'columns'       => array(
                    array(
                        'name'        => Yii::t('web/portal', 'package'),
                        'value'       => function ($data) {
                            return Chtml::encode($data['package_name']);
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Thao tác',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $html = '<a class="btnConfirm" href="javascript:void(0)"';
                            $html .= ' data-packagecode="' . CHtml::encode($data['package_code']) . '"';
                            $html .= ' data-csrf="' . Yii::app()->request->csrfToken . '"';
                            $html .= ' >';
                            $html .= '<span>Hủy</span>';
                            $html .= ' </a> ';
                            $html .= WPackage::getLinkChangePackage($data['package_code']);

                            return $html;
                        },
                        'htmlOptions' => array('style' => 'width:180px;word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
    <div class="space_10"></div>
    <?php $this->renderPartial('_modal_confirm'); ?>
<?php else: ?>
    <div class="space_20"></div>
    <div class="font_15">
        Quý khách chưa sử dụng gói cước, dịch vụ của Freedoo. Xin vui lòng đăng ký sử dụng các gói cước <a
                href="<?= Yii::app()->controller->createUrl('package/index'); ?>"><span
                    class="lbl_color_blue">tại đây.</span></a>
    </div>
<?php endif; ?>
<script>
    $(document).on('click', '.btnConfirm', function (e) {
        $(this).bind('click', false);
        // add to link btn confirm
        $('#package_code_cancel').val($(this).attr('data-packagecode'));
        $('#confirm').modal('show');
    });
</script>
