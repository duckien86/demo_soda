<script src="<?= Yii::app()->theme->baseUrl ?>/js/qrcode.min.js"></script>
<style>
    .qr_box{
        background-color: #fff;
        padding: 20px;
        border-radius: 4px;
        width: 90%;
        margin: 20px auto;
    }
    .qr_box p{
        margin-bottom: 5px;
    }
    .qr_top{
        text-transform: uppercase;
        font-size: 14px;
        font-weight: bold;
    }
    .qr_title{
        font-size: 16px;
        text-transform: uppercase;
        margin: 30px 0 20px;
        font-weight: bold;
    }
    .qr_content{

    }
    .qr_note{
        margin: 20px 0;
    }
    .qr_note ul{
        padding-left: 10px;
    }
    .qr_note >li{
        position: relative;
        padding-left: 10px;
    }
    .qr_note >li:before{
        content: '';
        /*width: 2px;*/
        /*height: 2px;*/
        /*border-radius: 50%;*/
        /*color: #333;*/
        border-radius: 50%;
        position: absolute;
        border-color: #333;
        border-style: solid;
        border-width: 2px;
        height: 2px;
        width: 2px;
        top: 50%;
        left: 0;
        margin-top: -1px;

    }
</style>
<div class="container" style="min-height: 300px; padding: 20px 0">
    <?php if(!$success){ ?>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading" style="font-size: 16px; font-weight: bold">Lấy mã QR code</div>
                <div class="panel-body">
                    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id'                   => 'activeQrCode',
                        'method'               => 'POST',
                        'action'               => Yii::app()->controller->createUrl('sim/activeQrCode', array('order_id' => $_GET['order_id'])),
                        'enableAjaxValidation' => TRUE,
                    )); ?>
                    <div class="form-group">
                        Vui lòng nhập mã xác thực để lấy mã QR code cho đơn hàng <b><?php echo $_GET['order_id'] ?></b>
                        <?php echo $form->error($model, 'id'); ?>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo $form->textField($model, 'otp', array('class' => 'form-control', 'placeholder' => 'Nhập mã xác thực')); ?>
                            <div class="input-group-btn">
                                <?php echo CHtml::htmlButton('Lấy mã', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Tra cứu',
                                )); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $form->error($model, 'otp'); ?>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <?php } ?>
    <!--esim detail-->
    <?php if($success){ ?>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="qr_box">
                <div class="qr_top">
                    <p>Trung tâm kinh doanh vnpt tỉnh thành phố <?php  echo $province ?></p>
                    <p>Phòng bán hàng <?php  echo $sale_office_code ?></p>
                </div>
                <h3 class="qr_title">Đơn hàng esim</h3>
                <div class="qr_content">
                    <p>Họ tên khách hàng: <?php echo $model->full_name ?></p>
                    <p>Địa chỉ: <?php echo $model->getAddress() ?></p>
                    <p>Số ĐT liên hệ: <?php echo $model->phone_contact ?></p>
                    <p>Tổng giá trị đơn hàng: <?php echo number_format($total_revenue,0,".",",") ?> đ</p>
                    <p>Gói cước sử dụng: <?php echo $package?></p>
                    <p>Số thuê bao: <?php echo $sim->msisdn ?></p>
                    <!--qr_image-->
                    <?php if(!empty($sim->esim_qrcode)){ ?>
                        <p>Mã QR Code:</p>
                        <div id="qrcode"></div>
                        <script>
                            var qr_code = "<?php echo $sim->esim_qrcode ?>";
                            $('#qrcode').qrcode({width: 150,height: 150,text: qr_code});
                        </script>
                    <?php } ?>
                </div>

                <ul class="qr_note">
                    <li>Quý khách vui lòng bảo mật mã QR Code trước khi khởi tạo thành công số thuê bao.</li>
                    <li>Quý khách cần thêm thông tin xin vui lòng liên hệ tổng đài 18001166 hoặc:</li>
                    <ul>
                        <li>- Nhân viên giao dịch: <?php echo $shipper['full_name'] ?> (người được phân công ĐH)</li>
                        <li>- Điện thoại: <?php echo $shipper['phone'] ?></li>
                    </ul>
                </ul>
                <p>Trân trọng cảm ơn!</p>
             </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
    <?php } ?>
</div>
    
