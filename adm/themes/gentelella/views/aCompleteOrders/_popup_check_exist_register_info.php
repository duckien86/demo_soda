<?php if ($order_id): ?>
    <div class="modal" id="modal_check_exist_info_<?php echo $order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>

                <!--kiểm tra đăng ký thông tin thuê bao của gói heyz-->
                <div class="box_exist_info">
                    <div class="modal-body">
                        <div class="row" style="width: 65%;margin: auto; text-align: center">
                            <?php if($result && $result['msg']): ?>
                                <p class="result_msg <?= $result['success'] ? 'success' : 'error'?>"><?= $result['msg']?></p>
                            <?php endif; ?>
                            <?php if ($result && $result['success'] == true): ?>
                                <div class="success_exist_info">
                                    <div class="errorMessage" id="error_serial_number" style="display:none"></div>
                                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                                        Đóng
                                    </button>
                                </div>
                            <?php else :  ?>
                                <p>Kiểm tra đăng ký thông tin thuê bao</p>
                                <div class="form_check_exist_info">
                                    <?php echo CHtml::button('Thực hiện',
                                        array("onclick" => "check_exist_info('$order_id' , '$msisdn');",
                                              "id"      => "exist_info_$order_id",
                                              "class"   => "btn btn-success")); ?>
                                    <div class="errorMessage" id="error_serial_number" style="display:none"></div>
                                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                                        Hủy
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
                <!--./END kiểm tra đăng ký thông tin thuê bao của gói heyz-->
            </div>

        </div>
    </div>
    <style>
        .success{ color: green}
        .error{ color: red}
    </style>
    <script>
        function check_exist_info(order_id , msisdn) {
            $('.result_msg').text('');
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
                url: '<?=Yii::app()->createUrl('aCompleteOrders/checkExistRegisterInfo')?>',
                crossDomain: true,
                data: {
                    order_id: order_id,
                    msisdn : msisdn,
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
                },
                success: function (data) {
                    $('.loading-div').removeAttr("style");
                    $('.loading-div').css("display", "none");
                    $('.modal-backdrop').remove();
                    var modal_exist_info_id = 'modal_check_exist_info_' + order_id;
                    $('.popup_data').html('');
                    $('.popup_data_exist_info').html(data);
                    $('#' + modal_exist_info_id).modal("show");
                },
                error: function (data) {
                    alert(data);
                }
            });
        }
    </script>
<?php endif; ?>