<?php
/**
 * @var $this ACompleteOrdersController
 * @var $order AOrders
 * @var $sim ASim
 * @var $package APackage
 * @var $shipper AShipper
 * @var $user User
 * @var $modal bool
 */
?>


<div class="form-group">
    <?php if($modal){ ?>
    <a class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_esim_qrcode">
        <div class="esim_qrcode"></div>
    </a>
    <?php }else{ ?>
    <div class="esim_qrcode"></div>
    <?php }?>
</div>

<a class="btn btn-primary" onclick="$('#esim_qrcode').printElem()"><i class="fa fa-print"></i> In</a>

<?php if($modal){ ?>
<div id="modal_esim_qrcode" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                QR CODE
                <a class="close" onclick="$('#modal_esim_qrcode').modal('hide')">&times;</a>
            </div>
            <div class="modal-body" style="height: 400px; overflow-y: auto">
                <div id="esim_qrcode">
                    <div class="qr_box">
                        <div class="qr_top">
                            <div style="width: 80%; float: left; text-align: left">
                                <h4 class="text-bold text-uppercase">Trung tâm kinh doanh vnpt <?php echo AProvince::getProvinceNameByCode($order->province_code)?></h4>
                                <h4 class="text-bold text-uppercase"><?php echo ASaleOffices::getSaleOfficesNameByCode($order->sale_office_code)?></h4>
                            </div>
                            <div style="width: 20%; float: left; text-align: right">
                                <img width="100" height="30" src="<?php echo Yii::app()->theme->baseUrl?>/images/logo_vinaphone.jpg">
                            </div>
                        </div>

                        <div class="space_10"></div>

                        <h4 class="qr_title text-bold text-uppercase" style="text-align: center">Đơn hàng esim</h4>

                        <div class="space_10"></div>

                        <div class="qr_content" style="padding-left: 30px">
                            <p>Họ tên khách hàng: <?php echo $order->full_name ?></p>
                            <p>Địa chỉ: <?php echo $order->getAddress() ?></p>
                            <p>Số ĐT liên hệ: <?php echo $order->phone_contact ?></p>
                            <p>Tổng giá trị đơn hàng: <?php echo number_format($order->getTotalRenueveOrder($order->id),0,".",",") ?> đ</p>
                            <p>Gói cước sử dụng: <?php echo $package->name?></p>
                            <p>Số thuê bao: <?php echo $sim->msisdn ?></p>
                            <!--qr_image-->
                            <p>Mã QR Code:</p>
                            <div class="esim_qrcode"></div>

                            <div class="space_10"></div>

                            <ul class="qr_note" style="padding-left: 20px">
                                <li>Quý khách vui lòng bảo mật mã QR Code trước khi khởi tạo thành công số thuê bao.</li>
                                <li>Mã QR Code chỉ có giá trị để KHỞI TẠO trong vòng 72h kể từ ngày <?php echo date('d/m/Y')?></li>
                                <li>Quý khách cần thêm thông tin xin vui lòng liên hệ tổng đài 18001166 hoặc:</li>
                                <?php
                                $staff_name = "";
                                $staff_phone = "";
                                if($shipper){
                                    $staff_name = $shipper->full_name;
                                    $staff_phone = $shipper->phone_1;
                                }else if($user){
                                    $staff_name = $user->getFullName($user->id);
                                    $staff_phone = $user->phone;
                                }
                                ?>
                                <ul>
                                    <li>- Nhân viên giao dịch: <?php echo $staff_name ?></li>
                                    <li>- Điện thoại: <?php echo $staff_phone ?></li>
                                </ul>
                            </ul>

                            <div class="space_10"></div>

                            <p>Trân trọng cảm ơn!</p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-primary" onclick="$('#esim_qrcode').printElem()"><i class="fa fa-print"></i> In</a>
            </div>
        </div>
    </div>
</div>
<?php }?>

<script src="<?= Yii::app()->theme->baseUrl; ?>/js/jquery.qrcode.min.js"></script>
<script>

    $(function(){
        $('.esim_qrcode').html('').qrcode({
            width: 150, height: 150,
            text: '<?php echo $sim->esim_qrcode?>'
        });
    });

    jQuery.fn.extend({
        printElem: function() {
            var cloned = this.clone();
            var printSection = $('#printSection');
            if (printSection.length == 0) {
                printSection = $('<div id="printSection" style="font-size: 16px"></div>');
                $('body').append(printSection);

            }
            printSection.append(cloned);
            var toggleBody = $('body *:visible');
            toggleBody.hide();
            $('#printSection, #printSection *').show();
            $('#printSection .esim_qrcode').html('').qrcode({
                width: 150, height: 150,
                text: '<?php echo $sim->esim_qrcode?>'
            });
            window.print();
            printSection.remove();
            toggleBody.show();
        }
    });
</script>