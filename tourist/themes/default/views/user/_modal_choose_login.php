<?php
/**
 * @var $this UserController
 *
 */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'modal_choose_login',
        'autoOpen' => true,
    )
); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Chọn kiểu đăng nhập</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 text-center">
                <?php echo CHtml::link('<i class="fa fa-user"></i> Khách hàng cá nhân', Yii::app()->createUrl('user/loginCtv'), array(
                    'class' => 'btn btn-info',
                    'style' => 'width: 220px',
                ));?>
            </div>

            <div class="space_20"></div>

            <div class="col-sm-12 text-center">
                <?php echo CHtml::link('<i class="fa fa-users"></i> Khách hàng Doanh nghiệp', Yii::app()->createUrl('user/login'), array(
                    'class' => 'btn btn-primary',
                    'style' => 'width: 220px',
                ));?>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        $('#modal_choose_login').appendTo('body');
    });
</script>
