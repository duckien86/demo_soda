<?php
/**
 * @var $this AFTOrdersController
 * @var $model AFTOrders
 * @var $files AFTFiles
 * @var $total int
 * @var $first bool
 * @var $form TbActiveForm
 */
?>

<?php if ($model):

    ?>
    <div class="modal" id="modal_kit_<?php echo $model->id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h5 class="modal-title">
                                        <?php if ($first){ ?>
                                        Bạn hãy upload danh sách Sim tối thiếu là <?= $total ?>
                                        và thực hiện ghép kít để hoàn thành đơn hàng</h5>
                                    <?php } else {
                                        ?>
                                        Đơn hàng đang tạm dừng ghép KIT do chưa đạt đủ số lượng yêu cầu.<br/>
                                        Vui lòng kiểm tra báo cáo của đơn hàng và upload thêm Sim để tiếp tục
                                        thực hiện ghép KIT. <br/>Danh sách Sim tối thiếu là <?= $total ?>
                                        <?php
                                    } ?>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="form" id="crop-avatar">

                                        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                            'id'          => 'aftupload-sim-form',
//                                            'action'               => Yii::app()->createUrl('aFTOrders/uploadSim'),
                                            // Please note: When you enable ajax validation, make sure the corresponding
                                            // controller action is handling ajax validation correctly.
                                            // There is a call to performAjaxValidation() commented in generated controller code.
                                            // See class documentation of CActiveForm for details on this.
//                                            'enableAjaxValidation' => TRUE,
                                            'htmlOptions' => array('enctype' => 'multipart/form-data', 'onsubmit' => 'return false;'),
                                        )); ?>
                                        <div class="form-group">
                                            <?php if (Yii::app()->user->hasFlash('error')): ?>
                                                <div role="alert" class="alert alert-danger alert-dismissible fade in">
                                                    <button aria-label="Close" data-dismiss="alert" class="close"
                                                            type="button">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <?php echo Yii::app()->user->getFlash('error'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (Yii::app()->user->hasFlash('success')): ?>
                                                <div role="alert" class="alert alert-info alert-dismissible fade in">
                                                    <button aria-label="Close" data-dismiss="alert" class="close"
                                                            type="button">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <?php echo Yii::app()->user->getFlash('success'); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="form-group">
                                                    <?php
                                                        echo CHtml::link('<i class="fa fa-download"></i> ' . 'Tải về file mẫu tại đây', array('/aFTOrders/getFileTemplate'), array('class' => 'btn btn-warning'));
                                                    ?>
                                                </div>
                                                <div class="form-group">
                                                    <label style="margin-top: 10px;">Chọn file cần Import (.txt)</label>
                                                    <?php echo $form->fileField($files, 'filename', array('class' => 'form-control input-file-upload-sim', 'size' => 60, 'maxlength' => 255)); ?>
                                                    <div class="errorMessage" id="AFTFiles_filename_em_"></div>
                                                </div>
                                                <input type="hidden" name="AFTFiles[order_id]" id="AFTFiles_order_id"
                                                       value="<?= $model->id ?>">
                                                <input type="hidden" name="AFTFiles[length_serial]"
                                                       id="AFTFiles_length_serial"
                                                       value="">

                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <?php echo $form->labelEx($model,'store_id')?>
                                                    <?php echo $form->textField($model,'store_id',array(
                                                        'class' => 'form-control',
                                                        'required' => TRUE,
                                                    ));?>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="button-submit-joinkit">
                                            <?php echo CHtml::button('Ghép kít',
                                                array(
                                                    "onclick" => "joinkit('$model->id');",
                                                    "class"   => "btn btn-success")
                                            );
                                            ?>
                                            <button type="button" class="btn btn-default close-button"
                                                    data-dismiss="modal">
                                                Hủy
                                            </button>
                                        </div>
                                        <?php $this->endWidget(); ?>
                                    </div><!-- form -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
<style>
    .close {
        margin-top: -10px !important;
    }
</style>
<script type="text/javascript">
    // Xác thực mã otp
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });
    function joinkit(order_id) {

        var file = document.getElementById('AFTFiles_filename').files[0];
        if (file) {
            var extension = file.name.split('.').pop();
            if (extension == 'txt') {
                var reader = new FileReader();
                reader.readAsText(file);
                reader.onload = function (e) {
                    $('.errorMessage').html("");
                    // browser completed reading file - display it
                    var content_file = e.target.result;
                    if (/^[0-9 \n\s]+$/.test(content_file)) { // Kiểm tra chứa ký tự đặc biên
                        var text_file = content_file.split('\n');
                        var count_text_file = text_file.length;
                        var array_sim = [];
                        for (var i = 0; i < text_file.length; i++) { // Chú ý loại bỏ cuối cùng.
                            if (text_file[i] != '' && text_file[i].length > 1) {
//                                if (text_file[i].replace(/\s/g, "").length != 20) { // Mỗi số serial number phải 20 chữ số
//                                    $('.errorMessage').html("File không đúng định dạng mỗi số serial phải chứa 20 chữ số!");
//                                    return false;
//                                }

                                if (jQuery.inArray(text_file[i], array_sim) == -1) {
                                    array_sim.push(text_file[i]);
                                } else {
                                    $('.errorMessage').html("File có dữ liệu trùng lặp ở dòng thứ " + (i+1) + " giá trị trùng lặp "+text_file[i]+" !");
                                    return false;
                                }

                            } else {
                                count_text_file = count_text_file - 1;
                            }
                        }
                        $('#AFTFiles_length_serial').val(count_text_file);
                        var form_data = new FormData(document.getElementById("aftupload-sim-form"));//id_form
                        if (count_text_file >=<?= $total ?>) {
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
                                url: '<?= Yii::app()->createUrl('aFTOrders/uploadSim') ?>',
//                            crossDomain: true,
                                processData: false,
                                contentType: false,
//                            dataType: "json",
                                enctype: 'multipart/form-data',
                                data: form_data,
                                success: function (result) {
                                    $('.modal-backdrop').remove();
//                                    $('.popup_join_kit').hide();
                                    $('.popup_confirm_join_kit').html(result);
                                    var modal_id = 'modal_confirm_join_kit_' + order_id;
                                    $('#' + modal_id).modal('show');
                                    return false;
                                }
                            });
                        } else {
                            $('.errorMessage').html("Số lượng serial sim phải lớn hơn <?=$total?>!");
                            return false;
                        }
                    } else {
                        $('.errorMessage').html("File không đúng định dạng! Chứa ký tự không hợp lệ!");
                        return false;
                    }
                };
            } else {
                $('.errorMessage').html("File không đúng định dạng!");
                return false;
            }
        } else {
            $('.errorMessage').html("Chưa có file nào được chọn!");
            return false;
        }
        return false;
    }

</script>

