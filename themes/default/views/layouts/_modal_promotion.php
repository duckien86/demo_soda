<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_promotion')
); ?>
<div class="modal-body no_pad">
    <a class="close" data-dismiss="modal">&times;</a>
    <?php
        if (isset($isMobile) && $isMobile) {
            $src_img = Yii::app()->theme->baseUrl . '/images/popup_promotion_wap.png';
        } else {
            $src_img = Yii::app()->theme->baseUrl . '/images/popup_promotion.png';
        }
        echo CHtml::image($src_img, 'promotion', array('class' => 'img_promotion'));
    ?>
</div>
<?php $this->endWidget(); ?>
<script>
    $(document).ready(function () {
        $('#modal_promotion').modal('show');
    });
</script>