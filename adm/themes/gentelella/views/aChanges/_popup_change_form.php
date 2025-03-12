<?php if ($model):
    ?>
    <div class="modal" id="modal_<?php echo $model->id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 40%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Điều chuyển phòng bán hàng.</h4>
                </div>
                <div class="modal-body">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'action' => Yii::app()->createUrl($this->route),
                        'method' => 'post',
                    )); ?>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <?php echo $form->dropDownList($model, 'sale_office_code', ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : array(), array(
                                        'class' => 'form-control change-order',
                                        'empty' => 'Chọn tất cả',

                                    )
                                ); ?>

                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                    <?php $this->endWidget(); ?>
                </div>
                <div class="modal-footer">
                    <?php echo CHtml::button('Điều chuyển',
                        array("onclick" => "change_order('$model->id');",
                              "id"      => "$model->id",
                              "class"   => "btn btn-success")); ?>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<style>
    .close {
        margin-top: -10px !important;
    }

    .modal-title {
        color: red;
        text-align: center;
    }
</style>
<script type="text/javascript">
    // Xác thực mã otp
    var sale_office_code;
    $('.change-order').change(function () {
        sale_office_code = $(this).val();
    });
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });

    function change_order(order_id) {

        $('.loading-div').css({
            "float": "left",
            "width": "100%",
            "height": "100%",
            "z-index": "999999",
            "position": "absolute",
            "text-align": "center"
        });
        $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();


        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aChanges/changeOrders')?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                sale_office_code: sale_office_code,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                alert("Điều chuyển thành công!");
                window.location.reload();
                return true;
            }
        });
    }

</script>