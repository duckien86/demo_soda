<?php if ($campaignConfig):
    ?>
    <div class="modal" id="modal_<?php echo $campaignConfig->id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tạo link</h4>
                </div>
                <div class="modal-body">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'action' => Yii::app()->createUrl($this->route),
                        'method' => 'post',
                    )); ?>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-10">
                            <div class="form-group">
                                <?php echo $form->textField($campaignConfig, 'new_link', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                                <?php echo $form->error($campaignConfig, 'new_link'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <?php echo CHtml::button('Tạo link',
                                array("onclick" => "create_link('$campaignConfig->id');",
                                      "id"      => "$campaignConfig->id",
                                      "class"   => "btn btn-success")); ?>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
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

    function create_link(id) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aCampaignConfigs/createLink')?>',
            crossDomain: true,
            data: {
                id: id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                $('#ACampaignConfigs_new_link').val(data);
                return true;
            }
        });
    }

</script>