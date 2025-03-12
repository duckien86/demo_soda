<div id="success_register_fiber" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
           <?php
           $orderdetail = Yii::app()->session['success_register_fiber'];
           $source_mytv = Yii::app()->session['source_mytv'];
           ?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <?php if(!$source_mytv && $source_mytv ==''){ ?>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php }?>
                <h4 class="modal-title">Thành công</h4>
            </div>
            <?php $t = $_GET['t']; ?>
            <?php if($t == 2){ ?>
                <div class="modal-body" style="font-size: 18px">
                    <p style="color: #0aa1df">
                        Quý khách đăng ký thành công dịch vụ: Truyền hình. Mã đơn hàng <?php echo $orderdetail; ?>.
                    </p><p style="color: #0aa1df">
                        Chúng tôi sẽ liên hệ lại để hoàn thiện đơn hàng. Cảm ơn Quý khách đã sử dụng dịch vụ của VNPT. Chi tiết xin LH 18001166 (miễn phí).
                    </p>
                </div>
            <?php }elseif($t ==3){ ?>
            <div class="modal-body" style="font-size: 18px">
                <p style="color: #0aa1df">
                    Quý khách đăng ký thành công dịch vụ: Internet & Truyền hình. Mã đơn hàng <?php echo $orderdetail; ?>.
                </p><p style="color: #0aa1df">
                    Chúng tôi sẽ liên hệ lại để hoàn thiện đơn hàng. Cảm ơn Quý khách đã sử dụng dịch vụ của VNPT. Chi tiết xin LH 18001166 (miễn phí).
                </p>
            </div>
            <?php }elseif($t ==4){ ?>
                <div class="modal-body" style="font-size: 18px">
                    <p style="color: #0aa1df">
                        Quý khách đăng ký thành công dịch vụ: Internet truyền hình và Di động. Mã đơn hàng <?php echo $orderdetail; ?>.
                    </p><p style="color: #0aa1df">
                        Chúng tôi sẽ liên hệ lại để hoàn thiện đơn hàng. Cảm ơn Quý khách đã sử dụng dịch vụ của VNPT. Chi tiết xin LH 18001166 (miễn phí).
                    </p>
                </div>
            <?php }else{  ?>
                <div class="modal-body" style="font-size: 18px">
                    <p style="color: #0aa1df">
                        Quý khách đăng ký thành công dịch vụ: Internet cáp quang. Mã đơn hàng <?php echo $orderdetail; ?>.
                    </p><p style="color: #0aa1df">
                        Chúng tôi sẽ liên hệ lại để hoàn thiện đơn hàng. Cảm ơn Quý khách đã sử dụng dịch vụ của VNPT. Chi tiết xin LH 18001166 (miễn phí).
                    </p>
                </div>
            <?php }?>
            <div class="modal-footer" style="text-align: center">
                <?php if(!$source_mytv && $source_mytv ==''){ ?>
                <a href="<?= Yii::app()->controller->createUrl('site/index') ?>" class="btn btn-info" role="button">Về trang chủ</a>
                <?php }else{?>
                    <a href="<?= Yii::app()->controller->createUrl('package/indexmytv',array('fb'=>'2')) ?>" class="btn btn-info" role="button">Đăng ký gói truyền hình</a>
                <?php }?>
            </div>
        </div>

    </div>
</div>
<script>
    $(window).on('load', function () {
        $('#success_register_fiber').modal('show');
    });
</script>