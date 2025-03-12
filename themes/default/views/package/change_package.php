<?php
    /* @var $this PackageController */
    /* @var $modelPackage WPackage */
    /* @var $packages WPackage */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>

    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="ss-box1-right-all">
                            <div class="ss-box1-right-title">
                                <div class="ss-box1-left-top-tit border_bottom">
                                    <div class="text-center font_16">
                                        Bạn đang sử dụng gói cước <strong
                                                class="lbl_color_blue"><?= CHtml::encode($modelPackage->name); ?></strong>
                                        các gói cước có thể chuyển đổi
                                    </div>
                                    <div class="space_10"></div>
                                    <div class="font_16">
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
                                    <div class="space_1"></div>
                                </div>
                            </div>
                            <div class="ss-box1-right-bottom">
                                <?php if ($packages): ?>
                                    <div class="table-responsive">
                                        <?php
                                            $this->widget('booster.widgets.TbGridView', array(
                                                'id'            => 'package-grid',
                                                'dataProvider'  => $packages,
                                                'enableSorting' => FALSE,
                                                'columns'       => array(
                                                    array(
                                                        'name'        => Yii::t('web/portal', 'package'),
                                                        'value'       => function ($data) {
                                                            return Chtml::encode($data['name']);
                                                        },
                                                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                                    ),
                                                    array(
                                                        'name'        => Yii::t('web/portal', 'price'),
                                                        'value'       => function ($data) {
                                                            return Chtml::encode(number_format($data['price'], 0, "", "."));
                                                        },
                                                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                                    ),
                                                    array(
                                                        'name'        => Yii::t('web/portal', 'period'),
                                                        'value'       => function ($data) {
                                                            return $data->getPackagePeriodLabel($data['period']);
                                                        },
                                                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                                                    ),
                                                    array(
                                                        'name'        => '',
                                                        'type'        => 'raw',
                                                        'value'       => function ($data) {
                                                            $html = '<a class="btnConfirm" href="javascript:void(0)"';
                                                            $html .= ' data-newcode="' . CHtml::encode($data['code']) . '"';
                                                            $html .= ' data-newname="' . CHtml::encode($data['name']) . '"';
                                                            $html .= ' data-csrf="' . Yii::app()->request->csrfToken . '"';
                                                            $html .= ' >';
                                                            $html .= '<span>Chuyển đổi gói cước</span>';
                                                            $html .= ' </a> ';

                                                            return $html;
                                                        },
                                                        'htmlOptions' => array('class' => 'link', 'style' => 'width:180px;word-break: break-word;vertical-align:middle;'),
                                                    ),
                                                ),
                                            )); ?>
                                    </div>
                                    <?php
                                else:
                                    echo 'Hiện không có gói cước nào phù hợp.';
                                endif;
                                ?>
                            </div>
                            <div class="space_30"></div>
                        </div>
                    </div>
                </div>
        </section>
    </section>
    <!--end section #ss-bg -->
    <!-- modal confirm change package -->
    <?php $this->beginWidget(
        'booster.widgets.TbModal',
        array('id' => 'confirm_change')
    ); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
    </div>
    <div id="msg_confirm_change" class="modal-body text-center">
    </div>
    <?php $this->endWidget(); ?>
    <!-- End modal confirm change package -->
</div>
<script>
    $(document).on('click', '.btnConfirm', function (e) {
        var btn = $(this);
        btn.bind('click', false);
        var new_package_code = btn.attr('data-newcode');

        $.ajax({
            url: "<?=Yii::app()->controller->createUrl('package/getFormChangePackage')?>",
            type: 'post',
            cache: false,
            dataType: "json",
            data: {
                new_package_code: new_package_code,
                old_package_code: <?=$modelPackage->code;?>,
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                $('#msg_confirm_change').html(result.html_content);
                $('#confirm_change').modal('show');
            },
            error: function (request, status, err) {
                btn.unbind('click', false);
            }
        });
    });
    // close modal
    $('#confirm_change').on('hidden.bs.modal', function () {
        $('a.btnConfirm').unbind('click', false);
    });
</script>
