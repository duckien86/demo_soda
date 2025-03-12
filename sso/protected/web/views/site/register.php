<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
    $partner_url = '';
    if ($pid) {
        $partner = WPartner::model()->findByAttributes(array('id' => $pid));
        if ($partner) {
            $partner_url = $partner->return_url;
        }
    }
?>
<div class="container form-register">
    <div class="row main">
        <div class="login-heading">
            <div class="login-title text-center">
                <?php if($_GET['pid'] != 004){ ?>
                    <h1 class="title"><a href="<?= $partner_url ?>"><img
                                    src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_sso.png"></a></h1>
                <?php }else{?>
                    <div class="flexins" >
                        Flexins
                    </div>
                <?php  }?>
                <style>
                    .flexins{
                        color: rgb(10, 183, 117);
                        font-size: 70px;
                        padding: 12px 10px;
                        font-weight: bold;
                        text-transform: uppercase;
                        font-family: "SanFranciscoDisplay-Bold";
                    }
                    .login-flexinss{
                        background: rgb(10, 183, 117) !important;
                        border: rgb(10, 183, 117) 1px solid;
                    }
                </style>
            </div>
            <div class="login-title text-center">
                <h2 class="title-info">ĐĂNG KÝ</h2>
            </div>
        </div>
        <div class="login-error text-center">
            <h5 class="title-error"><?= $error ?></h5>
        </div>
        <!--            <form class="form-horizontal" id='RegisterForm' method="post" action="#">-->
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                     => 'registration-form',
            'enableAjaxValidation'   => TRUE,
            'enableClientValidation' => TRUE,
            'htmlOptions'            => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left avatar-form')
        )); ?>
        <?php if ($pid == "002") { ?>
            <div class="container register">
                <div class="row">

                    <div class="main-login main-center">
                        <div class="form-group">
                            <div class="cols-sm-10">
                                <label for="username" class="cols-sm-2 control-label" id="username_title">Tên đăng
                                    nhập</label>
                                <?php echo $form->textField($model, 'username', array('class' => 'form-control form-design', 'autofocus' => 'on')); ?>
                                <input type="hidden" class="form-control" name="pid" id="pid" value="<?= $pid ?>"/>
                                <?php echo $form->error($model, 'username'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="cols-sm-10">
                                <label for="email" class="cols-sm-2 control-label" id="email_title">Email</label>
                                <?php echo $form->textField($model, 'email', array('class' => 'form-control form-design', 'autocomplete' => 'off')); ?>
                                <?php echo $form->error($model, 'email'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="cols-sm-10">
                                <label for="phone" class="cols-sm-2 control-label" id="phone_title">Số điện
                                    thoại</label>
                                <?php echo $form->textField($model, 'phone', array('class' => 'form-control form-design', 'autocomplete' => 'off')); ?>
                                <?php echo $form->error($model, 'phone'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="cols-sm-10">
                                <label for="password" class="cols-sm-2 control-label" id="password_title">Mật
                                    khẩu</label>
                                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control form-design', 'autocomplete' => 'off')); ?>
                                <?php echo $form->error($model, 'password'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="cols-sm-10">
                                <label for="confirm_password" class="cols-sm-2 control-label"
                                       id="confirm_password_title">Nhập
                                    lại mật khẩu</label>
                                <?php echo $form->passwordField($model, 'confirm_password', array('class' => 'form-control form-design', 'autocomplete' => 'off')); ?>
                                <?php echo $form->error($model, 'confirm_password'); ?>
                            </div>
                        </div>
                        <?php if ($accept_capcha): ?>
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'verifyCode'); ?>
                                <div id="captcha_place_holder"
                                     class="g-recaptcha"
                                     data-sitekey="6LfzSCcTAAAAAPpaGmWa5NlOClELiir9K_HyUSgq">
                                    <!--                                     data-sitekey="6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t">-->

                                </div>
                                <?php echo $form->error($model, 'verifyCode'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group" style="margin-bottom: -20px;">
                            <?php echo $form->checkbox($model, 'agree'); ?>
                            <a class="cols-sm-2 control-label" id="rule_title">

                                <label for="agree" class="cols-sm-2 control-label" id="agree_title">Tôi đồng ý điều
                                    khoản,
                                    quy định tại hợp đồng dành cho Cộng tác viên Freedoo</label>
                                <?php echo $form->error($model, 'agree'); ?></a>
                        </div>
                    </div>

                    <div class="rule-toggle" style="padding-left: 0px;">

                        <div class="agree-info">
                            <div class="rule-info">
                                <p><strong>ĐIỀU 1. </strong><strong>ĐỊNH NGHĨA</strong></p>

                                <ol>
                                    <li><em>&ldquo;Hệ thống Freedoo&rdquo;</em>: l&agrave; c&aacute;c trang mạng v&agrave;
                                        phần hệ thống l&agrave;m việc do B&ecirc;n A vận h&agrave;nh, quản l&yacute; để
                                        cung cấp sản phẩm/dịch vụ, nền tảng tiếp thị cho kh&aacute;ch h&agrave;ng, cộng
                                        t&aacute;c vi&ecirc;n tại <a href="http://freedoo.vnpt.vn/">http://freedoo.vnpt.vn</a>.
                                    </li>
                                    <li><em>&ldquo;T&agrave;i khoản&rdquo;</em>: l&agrave; t&ecirc;n đăng nhập v&agrave;
                                        mật khẩu tr&ecirc;n hệ thống Freedoo, do B&ecirc;n A khởi tạo cho B&ecirc;n B để
                                        quản l&yacute; nội dung, kết quả thực hiện c&ocirc;ng việc của B&ecirc;n B.
                                    </li>
                                    <li><em>&ldquo;M&atilde; giới thiệu&rdquo;</em>: l&agrave; d&atilde;y k&yacute; tự
                                        duy nhất do B&ecirc;n A tạo ra tr&ecirc;n hệ thống Freedoo v&agrave; cấp cho B&ecirc;n
                                        B để l&agrave;m căn cứ ghi nhận c&aacute;c hoạt động/giao dịch ph&aacute;t sinh
                                        của B&ecirc;n B trong hệ thống Freedoo.
                                    </li>
                                    <li><em>&ldquo;Gi&aacute; b&aacute;n&rdquo;</em>: l&agrave; gi&aacute; của c&aacute;c
                                        sản phẩm, dịch vụ do B&ecirc;n A cung cấp v&agrave; được đăng tải tr&ecirc;n hệ
                                        thống Freedoo để b&aacute;n cho kh&aacute;ch h&agrave;ng.
                                    </li>
                                    <li><em>&ldquo;Kh&aacute;ch h&agrave;ng&rdquo;</em>: l&agrave; người d&ugrave;ng
                                        truy cập hệ thống Freedoo th&ocirc;ng qua c&aacute;c h&igrave;nh thức li&ecirc;n
                                        kết tiếp thị, giới thiệu sản phẩm do B&ecirc;n B thực hiện.
                                    </li>
                                    <li><em>&ldquo;Sản phẩm/dịch vụ&rdquo;</em>: l&agrave; c&aacute;c sản phẩm dịch vụ
                                        viễn th&ocirc;ng do B&ecirc;n A cung cấp tr&ecirc;n hệ thống Freedoo như: số thu&ecirc;
                                        bao di động, g&oacute;i cước, thẻ nạp, m&atilde; thẻ, dịch vụ gi&aacute; trị gia
                                        tăng,&hellip;</li>
                                    <li><em>&ldquo;Đơn h&agrave;ng ho&agrave;n tất&rdquo;</em>: l&agrave; đơn h&agrave;ng
                                        kh&aacute;ch h&agrave;ng đặt mua, kh&aacute;ch h&agrave;ng đ&atilde; thanh to&aacute;n,
                                        nhận h&agrave;ng v&agrave; được hệ thống Freedoo ghi nhận th&agrave;nh c&ocirc;ng.
                                    </li>
                                    <li><em>&ldquo;Th&ugrave; lao cộng t&aacute;c vi&ecirc;n&rdquo;</em>: l&agrave;
                                        khoản tiền do B&ecirc;n A trả cho B&ecirc;n B khi B&ecirc;n B giới thiệu được kh&aacute;ch
                                        h&agrave;ng mua c&aacute;c sản phẩm/dịch vụ v&agrave; c&aacute;c khoản khuyến kh&iacute;ch/hỗ
                                        trợ kh&aacute;c (nếu c&oacute;).
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 2. </strong><strong>NỘI DUNG HỢP ĐỒNG</strong></p>

                                <ol>
                                    <li>B&ecirc;n A đồng &yacute; giao v&agrave; B&ecirc;n B đồng &yacute; l&agrave;m
                                        nhận l&agrave;m cộng t&aacute;c vi&ecirc;n ph&aacute;t triển kh&aacute;ch h&agrave;ng
                                        của B&ecirc;n A để thực hiện c&aacute;c c&ocirc;ng việc sau:
                                        <ol style="list-style-type:lower-alpha">
                                            <li>T&igrave;m kiếm, ph&aacute;t triển hệ thống kh&aacute;ch h&agrave;ng cho
                                                B&ecirc;n A.
                                            </li>
                                            <li>Tiếp thị, tư vấn, giới thiệu c&aacute;c sản phẩm, dịch vụ của B&ecirc;n
                                                A đến cho kh&aacute;ch h&agrave;ng.
                                            </li>
                                            <li>Hướng dẫn kh&aacute;ch h&agrave;ng đặt mua c&aacute;c sản phẩm, dịch vụ
                                                tr&ecirc;n hệ thống Freedoo của B&ecirc;n A.
                                            </li>
                                            <li>Hướng dẫn kh&aacute;ch h&agrave;ng thủ tục mua, thanh to&aacute;n gi&aacute;
                                                trị sản phẩm, dịch vụ.
                                            </li>
                                        </ol>
                                    </li>
                                    <li>Địa điểm, thời gian l&agrave;m việc: Do B&ecirc;n B chủ động sắp xếp để đảm bảo
                                        chất lượng c&ocirc;ng việc thực hiện.
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 3. </strong><strong>GI&Aacute; B&Aacute;N </strong><strong>SẢN
                                        PHẨM</strong><strong>, DỊCH VỤ</strong></p>

                                <ol>
                                    <li>Gi&aacute; b&aacute;n từng loại sản phẩm, dịch vụ &aacute;p dụng cho kh&aacute;ch
                                        h&agrave;ng l&agrave; gi&aacute; do B&ecirc;n A quy định cho kh&aacute;ch h&agrave;ng
                                        mua sản phẩm, dịch vụ th&ocirc;ng qua hệ thống Freedoo v&agrave; được ni&ecirc;m
                                        yết c&ocirc;ng khai tr&ecirc;n hệ thống Freedoo.
                                    </li>
                                    <li>Gi&aacute; b&aacute;n c&oacute; thể thay đổi tại từng thời điểm. Trường hợp c&oacute;
                                        thay đổi, B&ecirc;n A sẽ cập nhật th&ocirc;ng tin tr&ecirc;n hệ thống Freedoo.
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 4. </strong><strong>TH&Ugrave; LAO</strong> <strong>CỘNG T&Aacute;C VI&Ecirc;N</strong>
                                </p>

                                <ol>
                                    <li>Th&ugrave; lao cộng t&aacute;c vi&ecirc;n bao gồm:
                                        <ul style="list-style-type:circle">
                                            <li>Hoa hồng b&aacute;n sản phẩm/dịch vụ.</li>
                                            <li>Hoa hồng ph&aacute;t triển cộng t&aacute;c vi&ecirc;n.</li>
                                            <li>Khuyến kh&iacute;ch b&aacute;n h&agrave;ng.</li>
                                        </ul>
                                    </li>
                                </ol>

                                <p><strong>Tổng th&ugrave; lao cộng t&aacute;c vi&ecirc;n nhận được</strong> = Tổng tất
                                    cả (Hoa hồng b&aacute;n sản phẩm/dịch vụ + Hoa hồng ph&aacute;t triển cộng t&aacute;c
                                    vi&ecirc;n + Khuyến kh&iacute;ch b&aacute;n h&agrave;ng).</p>

                                <ol>
                                    <li>Th&ugrave; lao cộng t&aacute;c vi&ecirc;n chỉ được t&iacute;nh cho c&aacute;c
                                        đơn h&agrave;ng ho&agrave;n tất.
                                    </li>
                                    <li>Trong mỗi chu kỳ thanh to&aacute;n, nếu tổng mức th&ugrave; lao cộng t&aacute;c
                                        vi&ecirc;n từ 2.000.000 đồng <em>(Hai triệu đồng)</em> trở l&ecirc;n, B&ecirc;n
                                        A sẽ giữ lại phần thuế thu nhập c&aacute; nh&acirc;n của B&ecirc;n B, k&ecirc;
                                        khai v&agrave; nộp thay cho B&ecirc;n B theo quy định của ph&aacute;p luật.
                                    </li>
                                    <li>Trong qu&aacute; tr&igrave;nh thực hiện Hợp đồng, B&ecirc;n A c&oacute; quyền
                                        thay đổi ch&iacute;nh s&aacute;ch th&ugrave; lao v&agrave; đăng tải mức th&ugrave;
                                        lao mới c&ugrave;ng thời gian &aacute;p dụng tr&ecirc;n hệ thống Freedoo.
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 5. </strong>&nbsp;<strong>ĐỐI SO&Aacute;T V&Agrave; </strong><strong>THANH
                                        TO&Aacute;N </strong></p>

                                <ol>
                                    <li>Đối so&aacute;t số liệu:</li>
                                </ol>

                                <ol style="list-style-type:lower-alpha">
                                    <li>Số liệu đối so&aacute;t l&agrave; số liệu được ghi nhận tr&ecirc;n hệ thống
                                        Freedoo v&agrave; l&agrave; cơ sở để x&aacute;c định tổng số tiền th&ugrave; lao
                                        B&ecirc;n A chi trả cho B&ecirc;n B theo chu kỳ đối so&aacute;t.
                                    </li>
                                    <li>B&ecirc;n B c&oacute; thể tiến h&agrave;nh tra cứu số liệu đối so&aacute;t h&agrave;ng
                                        th&aacute;ng bằng c&aacute;ch truy cập v&agrave;o hệ thống Freedoo th&ocirc;ng
                                        qua t&agrave;i khoản của B&ecirc;n B.
                                    </li>
                                    <li><a name="_Hlk491135078">Trường hợp đến thời điểm thanh to&aacute;n m&agrave; hai
                                            b&ecirc;n chưa thống nhất được về số liệu đối so&aacute;t th&igrave; B&ecirc;n
                                            A sẽ tạm thanh to&aacute;n theo số liệu do B&ecirc;n A ghi nhận của kỳ đối
                                            so&aacute;t đ&oacute;, phần ch&ecirc;nh lệch sẽ đối so&aacute;t lại v&agrave;
                                            ghi nhận cho th&aacute;ng kế tiếp.</a></li>
                                    <li>Trong trường hợp c&oacute; sai lệch về số liệu đối so&aacute;t m&agrave; c&aacute;c
                                        b&ecirc;n kh&ocirc;ng thể thống nhất, quyết định cuối c&ugrave;ng thuộc về B&ecirc;n
                                        A.
                                        <ol>
                                            <li>Chu kỳ đối so&aacute;t, thanh to&aacute;n: theo th&aacute;ng (Quy định
                                                th&aacute;ng ph&aacute;t sinh doanh thu l&agrave; th&aacute;ng T).
                                                <ol style="list-style-type:lower-alpha">
                                                    <li>V&agrave;o ng&agrave;y 1 đến ng&agrave;y 10 th&aacute;ng (T+1),
                                                        B&ecirc;n A tiến h&agrave;nh cập nhật t&igrave;nh trạng kết quả
                                                        kinh doanh của B&ecirc;n B.
                                                    </li>
                                                    <li>Đến hết ng&agrave;y 15 của th&aacute;ng (T+1), nếu kh&ocirc;ng
                                                        nhận được bất kỳ phản hồi n&agrave;o của B&ecirc;n B về số liệu
                                                        kết quả kinh doanh th&igrave; mặc nhi&ecirc;n kết quả số liệu tr&ecirc;n
                                                        hệ thống Freedoo đ&atilde; được coi l&agrave; thống nhất giữa
                                                        hai b&ecirc;n v&agrave; l&agrave; cơ sở để B&ecirc;n A thanh to&aacute;n
                                                        cho B&ecirc;n B.
                                                    </li>
                                                    <li>Trường hợp ng&agrave;y cuối c&ugrave;ng của chu kỳ đối so&aacute;t,
                                                        thanh to&aacute;n tr&ugrave;ng v&agrave;o ng&agrave;y nghỉ/ng&agrave;y
                                                        lễ th&igrave; việc đối so&aacute;t, thanh to&aacute;n sẽ được
                                                        thực hiện v&agrave;o ng&agrave;y l&agrave;m việc đầu ti&ecirc;n
                                                        tiếp theo.
                                                    </li>
                                                </ol>
                                            </li>
                                        </ol>
                                    </li>
                                    <li>Phương thức thanh to&aacute;n:
                                        <ol style="list-style-type:lower-alpha">
                                            <li>B&ecirc;n A sẽ thanh to&aacute;n 01 lần/chu kỳ đối so&aacute;t cho B&ecirc;n
                                                B trong v&ograve;ng 05 ng&agrave;y kể từ ng&agrave;y kết th&uacute;c
                                                thời hạn đối so&aacute;t.
                                            </li>
                                            <li>B&ecirc;n A chỉ thanh to&aacute;n th&ugrave; lao cộng t&aacute;c vi&ecirc;n
                                                khi đạt mức tối thiểu từ 200.000 đồng (trừ kỳ thanh to&aacute;n cuối c&ugrave;ng).
                                                Trường hợp tổng th&ugrave; lao trong chu kỳ đối so&aacute;t kh&ocirc;ng
                                                đạt mức tối thiểu 200.000 đồng, việc thanh to&aacute;n sẽ được thực hiện
                                                trong chu kỳ thanh to&aacute;n tiếp theo.
                                            </li>
                                        </ol>
                                    </li>
                                    <li>H&igrave;nh thức thanh to&aacute;n: chuyển khoản, bằng đồng Việt Nam (VNĐ).</li>
                                </ol>

                                <p><strong>ĐIỀU 6. </strong><strong>QUYỀN V&Agrave; TR&Aacute;CH NHIỆM CỦA B&Ecirc;N
                                        A</strong></p>

                                <ol>
                                    <li>Quyền của B&ecirc;n A:
                                        <ol style="list-style-type:lower-alpha">
                                            <li>Cung cấp hoặc dừng cung cấp c&aacute;c sản phẩm dịch vụ tr&ecirc;n hệ
                                                thống Freedoo theo chiến lược kinh doanh của B&ecirc;n A.
                                            </li>
                                            <li>Quyết định gi&aacute; b&aacute;n sản phẩm, dịch vụ, mức th&ugrave; lao
                                                cộng t&aacute;c vi&ecirc;n theo từng thời điểm. Trường hợp thay đổi gi&aacute;
                                                b&aacute;n, mức th&ugrave; lao, B&ecirc;n A sẽ th&ocirc;ng b&aacute;o tr&ecirc;n
                                                hệ thống Freedoo cho B&ecirc;n B.
                                            </li>
                                            <li>Y&ecirc;u cầu B&ecirc;n B tu&acirc;n thủ c&aacute;c quy định, quy tr&igrave;nh
                                                do B&ecirc;n A ban h&agrave;nh li&ecirc;n quan đến hoạt động của cộng t&aacute;c
                                                vi&ecirc;n v&agrave; hoạt động b&aacute;n sản phẩm, dịch vụ.
                                            </li>
                                            <li>Quy định c&aacute;c điều kiện B&ecirc;n B phải đ&aacute;p ứng để được
                                                hưởng th&ugrave; lao cộng t&aacute;c vi&ecirc;n theo quy định tại Hợp
                                                đồng n&agrave;y.
                                            </li>
                                            <li>C&oacute; quyền kiểm tra, gi&aacute;m s&aacute;t việc tổ chức thực hiện
                                                Hợp đồng của B&ecirc;n B; được quyền &aacute;p dụng c&aacute;c chế t&agrave;i
                                                xử l&yacute; nếu B&ecirc;n B c&oacute; sai s&oacute;t, vi phạm c&aacute;c
                                                quy định của B&ecirc;n A v&agrave;/hoặc Hợp đồng;
                                            </li>
                                            <li>Được thu hồi to&agrave;n bộ trang thiết bị, ấn phẩm,... đ&atilde; cung
                                                cấp cho B&ecirc;n B trong qu&aacute; tr&igrave;nh thực hiện Hợp đồng
                                                (nếu c&oacute;).
                                            </li>
                                            <li>Kh&ocirc;ng chịu tr&aacute;ch nhiệm về c&aacute;c tranh chấp, hậu quả do
                                                B&ecirc;n B, nh&acirc;n sự của B&ecirc;n B g&acirc;y ra với kh&aacute;ch
                                                h&agrave;ng hoặc B&ecirc;n thứ ba trong qu&aacute; tr&igrave;nh B&ecirc;n
                                                B thực hiện Hợp đồng n&agrave;y.
                                            </li>
                                            <li>Kh&ocirc;ng giải quyết bất cứ trường hợp khiếu kiện n&agrave;o li&ecirc;n
                                                quan đến thanh to&aacute;n th&ugrave; lao sau khi B&ecirc;n A đ&atilde;
                                                ho&agrave;n th&agrave;nh việc thanh to&aacute;n th&ugrave; lao đ&atilde;
                                                được hai B&ecirc;n thống nhất theo c&aacute;c chu kỳ thanh to&aacute;n.
                                            </li>
                                            <li>Giữ lại khoản tiền thuế thu nhập c&aacute; nh&acirc;n v&agrave; k&ecirc;
                                                khai, nộp thay cho B&ecirc;n B theo quy định của ph&aacute;p luật (nếu c&oacute;).
                                            </li>
                                            <li>C&oacute; quyền tạm ngừng hoạt động hệ thống FreeDoo để bảo tr&igrave;/n&acirc;ng
                                                cấp. Trong thời gian n&agrave;y B&ecirc;n A kh&ocirc;ng c&oacute; tr&aacute;ch
                                                nhiệm giải quyết c&aacute;c khiếu nại/tổn thất ph&aacute;t sinh của B&ecirc;n
                                                B do hệ thống Freedoo tạm ngưng hoạt động.
                                            </li>
                                        </ol>
                                    </li>
                                    <li>Tr&aacute;ch nhiệm của B&ecirc;n A:
                                        <ol style="list-style-type:lower-alpha">
                                            <li>Thực hiện đầy đủ nội dung c&ocirc;ng việc đ&atilde; cam kết trong Hợp
                                                đồng.
                                            </li>
                                            <li>Chịu tr&aacute;ch nhiệm k&yacute; hợp đồng v&agrave; cung cấp c&aacute;c
                                                sản phẩm, dịch vụ đến kh&aacute;ch h&agrave;ng.
                                            </li>
                                            <li>Cung cấp đầy đủ c&aacute;c ấn phẩm, t&agrave;i liệu, văn bản, quy tr&igrave;nh,
                                                quy định nghiệp vụ c&oacute; li&ecirc;n quan (nếu c&oacute;) để tạo điều
                                                kiện cho B&ecirc;n B thực hiện c&ocirc;ng việc.
                                            </li>
                                            <li>Cung cấp c&aacute;c văn bản, giấy tờ, thẻ, ph&ugrave; hiệu,... (nếu c&oacute;)
                                                để hỗ trợ B&ecirc;n B trong hoạt động tiếp thị, giới thiệu sản phẩm,
                                                dịch vụ với kh&aacute;ch h&agrave;ng.
                                            </li>
                                            <li>X&acirc;y dựng c&aacute;c quy tr&igrave;nh, quy định cần thiết để triển
                                                khai c&aacute;c c&ocirc;ng việc theo Hợp đồng với B&ecirc;n B. Trong
                                                trường hợp cần thiết, B&ecirc;n A c&oacute; nghĩa vụ tập huấn về dịch
                                                vụ, hướng dẫn nghiệp vụ cho B&ecirc;n B theo quy tr&igrave;nh, quy định
                                                về cung cấp dịch vụ, sản phẩm v&agrave; c&aacute;c hoạt động kh&aacute;c
                                                của B&ecirc;n A.
                                            </li>
                                            <li>Th&ocirc;ng b&aacute;o tr&ecirc;n hệ thống Freedoo (v&agrave;/hoặc
                                                email) cho B&ecirc;n B khi c&oacute; thay đổi về ch&iacute;nh s&aacute;ch,
                                                hợp đồng, quy định, mức th&ugrave; lao, gi&aacute; b&aacute;n sản phẩm,
                                                dịch vụ, quy tr&igrave;nh nghiệp vụ, c&aacute;c chương tr&igrave;nh
                                                khuyến mại,... phục vụ c&ocirc;ng t&aacute;c chăm s&oacute;c kh&aacute;ch
                                                h&agrave;ng, giải đ&aacute;p, tư vấn, hỗ trợ kh&aacute;ch h&agrave;ng
                                                trong qu&aacute; tr&igrave;nh cung cấp sản phẩm, dịch vụ.
                                            </li>
                                            <li>Phối hợp đối so&aacute;t v&agrave; thanh to&aacute;n th&ugrave; lao cho
                                                B&ecirc;n B theo đ&uacute;ng quy định tại Hợp đồng.
                                            </li>
                                        </ol>
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 7. </strong><strong>QUYỀN V&Agrave; TR&Aacute;CH NHIỆM CỦA B&Ecirc;N
                                        B</strong></p>

                                <ol>
                                    <li>Quyền của B&ecirc;n B:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <ol style="list-style-type:lower-alpha">
                                            <li>Được hưởng tiền th&ugrave; lao cộng t&aacute;c vi&ecirc;n theo quy định
                                                của Hợp đồng n&agrave;y.
                                            </li>
                                            <li>Được cung cấp t&agrave;i khoản, mật khẩu t&agrave;i khoản tr&ecirc;n hệ
                                                thống Freedoo; chủ động tổ chức thực hiện c&aacute;c c&ocirc;ng việc cần
                                                thiết để tiếp x&uacute;c, trao đổi, đ&agrave;m ph&aacute;n tư vấn, giới
                                                thiệu sản phẩm, dịch vụ của B&ecirc;n A với kh&aacute;ch h&agrave;ng.
                                            </li>
                                            <li>Được sử dụng hệ thống Freedoo hỗ trợ cho hoạt động b&aacute;n h&agrave;ng
                                                v&agrave; quảng b&aacute; sản phẩm dịch vụ của B&ecirc;n A.
                                            </li>
                                            <li>Được tra cứu số liệu đối so&aacute;t h&agrave;ng th&aacute;ng của m&igrave;nh
                                                tr&ecirc;n hệ thống Freedoo.
                                            </li>
                                            <li>Được quyền chủ động về thời gian, địa điểm v&agrave; c&aacute;ch thức
                                                thực hiện c&ocirc;ng việc ph&ugrave; hợp với quy định của ph&aacute;p
                                                luật.
                                            </li>
                                            <li>Được y&ecirc;u cầu B&ecirc;n A hướng dẫn, đ&agrave;o tạo c&aacute;c kỹ
                                                năng, nghiệp vụ cần thiết, cung cấp c&aacute;c th&ocirc;ng tin, t&agrave;i
                                                liệu, văn bản, c&aacute;c chương tr&igrave;nh khuyến mại, thay đổi gi&aacute;
                                                cước v&agrave; c&aacute;c quy tr&igrave;nh, nghiệp vụ,... li&ecirc;n
                                                quan đến c&aacute;c sản phẩm, dịch vụ m&agrave; B&ecirc;n B l&agrave;m
                                                cộng t&aacute;c vi&ecirc;n để cung cấp cho kh&aacute;ch h&agrave;ng.
                                            </li>
                                        </ol>
                                    </li>
                                    <li>Tr&aacute;ch nhiệm của B&ecirc;n B:
                                        <ol style="list-style-type:lower-alpha">
                                            <li>Tự chịu tr&aacute;ch nhiệm về ph&aacute;p l&yacute; trong mọi hoạt động
                                                của m&igrave;nh khi thực hiện Hợp đồng n&agrave;y.
                                            </li>
                                            <li>Cam kết cung cấp ch&iacute;nh x&aacute;c c&aacute;c th&ocirc;ng tin của
                                                m&igrave;nh cho B&ecirc;n A để phục vụ cho việc kiểm tra, đối chiếu v&agrave;
                                                thanh to&aacute;n.
                                            </li>
                                            <li>Đọc v&agrave; đồng &yacute; với c&aacute;c Quy định v&agrave; Điều khoản
                                                của B&ecirc;n A tr&ecirc;n hệ thống Freedoo, cam kết thực hiện đầy đủ c&aacute;c
                                                nội dung c&ocirc;ng việc đ&atilde; cam kết trong Hợp đồng n&agrave;y.
                                            </li>
                                            <li>Cập nhật th&ocirc;ng tin tr&ecirc;n hệ thống Freedoo về c&aacute;c ch&iacute;nh
                                                s&aacute;ch, quy định, hợp đồng, gi&aacute; b&aacute;n sản phẩm/dịch vụ,
                                                mức th&ugrave; lao v&agrave; c&aacute;c th&ocirc;ng tin kh&aacute;c từ B&ecirc;n
                                                A cung cấp tr&ecirc;n hệ thống Freedoo.
                                            </li>
                                            <li>Thực hiện việc t&igrave;m kiếm kh&aacute;ch h&agrave;ng, tư vấn, chăm s&oacute;c,
                                                giới thiệu sản phẩm, dịch vụ v&agrave; c&aacute;c hoạt động kh&aacute;c
                                                trung thực, r&otilde; r&agrave;ng, minh bạch v&agrave; theo đ&uacute;ng
                                                c&aacute;c quy định của B&ecirc;n A. Hướng dẫn kh&aacute;ch h&agrave;ng
                                                đặt mua c&aacute;c sản phẩm, dịch vụ tr&ecirc;n hệ thống Freedoo.
                                            </li>
                                            <li>Bảo mật b&iacute; mật t&agrave;i khoản, mật khẩu t&agrave;i khoản của B&ecirc;n
                                                B tr&ecirc;n hệ thống Freedoo; cam kết sử dụng t&agrave;i khoản, c&aacute;c
                                                vật dụng, t&agrave;i liệu v&agrave; c&aacute;c t&agrave;i sản kh&aacute;c
                                                do B&ecirc;n A cung cấp (nếu c&oacute;) theo đ&uacute;ng c&aacute;c mục
                                                đ&iacute;ch v&agrave; y&ecirc;u cầu của B&ecirc;n A trong việc chăm s&oacute;c
                                                kh&aacute;ch h&agrave;ng, quảng b&aacute;, giới thiệu sản phẩm, dịch vụ
                                                v&agrave; h&igrave;nh ảnh của B&ecirc;n A.
                                            </li>
                                            <li>Giữ g&igrave;n, bảo quản, kh&ocirc;ng cho người kh&aacute;c sử dụng c&aacute;c
                                                vật dụng, t&agrave;i liệu v&agrave; c&aacute;c t&agrave;i sản kh&aacute;c
                                                do B&ecirc;n A cung cấp (nếu c&oacute;) bao gồm: thẻ, ph&ugrave; hiệu,
                                                đồng phục v&agrave; c&aacute;c vật dụng cần thiết kh&aacute;c phục vụ
                                                cho hoạt động cộng t&aacute;c vi&ecirc;n,&hellip;. Trường hợp B&ecirc;n
                                                B cho người kh&aacute;c sử dụng, l&agrave;m mất, hoặc l&agrave;m hư hỏng
                                                c&aacute;c vật dụng, tư liệu v&agrave; t&agrave;i sản tr&ecirc;n th&igrave;
                                                B&ecirc;n B c&oacute; nghĩa vụ bồi thường to&agrave;n bộ thiệt hại cho B&ecirc;n
                                                A.
                                            </li>
                                            <li>Quản l&yacute;, kiểm so&aacute;t c&aacute;c nội
                                                dung B&ecirc;n B tải xuống từ hệ thống của B&ecirc;n A v&agrave;
                                                chịu mọi tr&aacute;ch nhiệm nếu c&aacute;c nội dung nay bị thay đổi
                                                so với nội dung sẵn c&oacute; tại hệ thống.
                                            </li>
                                            <li>Kh&ocirc;ng tiến h&agrave;nh quảng b&aacute;, kinh doanh, ph&acirc;n
                                                phối sản phẩm, dịch vụ bằng h&igrave;nh thức SPAM, gian lận như
                                                cheating, hacking,...; Kh&ocirc;ng quảng b&aacute; sản phẩm, dịch vụ tr&ecirc;n
                                                c&aacute;c k&ecirc;nh vi phạm ph&aacute;p luật, tr&aacute;i với thuần
                                                phong mỹ tục, k&ecirc;nh bị tranh chấp v&agrave;/hoặc c&aacute;c h&igrave;nh
                                                thức kh&ocirc;ng được ph&aacute;p luật cho ph&eacute;p hoặc chưa quy
                                                định r&otilde; r&agrave;ng,...; Kh&ocirc;ng được cung cấp sản phẩm, dịch
                                                vụ giống/tương tự của B&ecirc;n A, kh&ocirc;ng hợp t&aacute;c với c&aacute;c
                                                đối t&aacute;c kh&aacute;c với nội dung giống hoặc tương tự nội dung hợp
                                                t&aacute;c tại Hợp đồng n&agrave;y, trừ trường hợp được B&ecirc;n A chấp
                                                thuận trước khi thực hiện.
                                            </li>
                                            <li>Đảm bảo th&aacute;i độ l&agrave;m việc t&iacute;ch cực, đ&uacute;ng quy
                                                định, giao tiếp lịch sự, kh&ocirc;ng l&agrave;m tổn hại đến uy t&iacute;n,
                                                h&igrave;nh ảnh v&agrave; sản phẩm, dịch vụ của B&ecirc;n A.
                                            </li>
                                            <li>Tổng hợp, th&ocirc;ng b&aacute;o v&agrave; cung cấp cho B&ecirc;n A c&aacute;c
                                                y&ecirc;u cầu, &yacute; kiến, g&oacute;p &yacute; của kh&aacute;ch h&agrave;ng
                                                về sản phẩm, dịch vụ của B&ecirc;n A (nếu c&oacute;).
                                            </li>
                                            <li>Kh&ocirc;ng tiết lộ cho bất kỳ b&ecirc;n thứ ba n&agrave;o kh&aacute;c c&aacute;c
                                                th&ocirc;ng tin về b&iacute; mật kinh doanh, th&ocirc;ng tin về dịch vụ
                                                của B&ecirc;n A, th&ocirc;ng tin về kh&aacute;ch h&agrave;ng sử dụng
                                                dịch vụ của B&ecirc;n A khi chưa nhận được sự đồng &yacute; của B&ecirc;n
                                                A.
                                            </li>
                                            <li>Kh&ocirc;ng được chuyển giao một phần hoặc to&agrave;n bộ quyền, nghĩa
                                                vụ của m&igrave;nh theo Hợp đồng n&agrave;y cho người kh&aacute;c dưới
                                                bất kỳ h&igrave;nh thức n&agrave;o nếu kh&ocirc;ng được sự chấp thuận
                                                của B&ecirc;n A.
                                            </li>
                                            <li>Trường hợp B&ecirc;n B vi phạm Hợp đồng, tư vấn sai hoặc, g&acirc;y
                                                thiệt hại cho kh&aacute;ch h&agrave;ng/b&ecirc;n thứ ba kh&aacute;c dẫn
                                                đến B&ecirc;n A bị thiệt hai, B&ecirc;n B c&oacute; tr&aacute;ch nhiệm:
                                                (i) bồi thường thiệt hại cho B&ecirc;n A, (ii) nộp cho B&ecirc;n A một
                                                khoản tiền phạt vi phạm tương ứng 200% tổng mức th&ugrave; lao B&ecirc;n
                                                B nhận được của 03 th&aacute;ng trước đ&oacute;; (iii) trong trường hợp
                                                n&agrave;y, B&ecirc;n A kh&ocirc;ng phải thanh to&aacute;n cho B&ecirc;n
                                                B số tiền th&ugrave; lao c&ograve;n lại của B&ecirc;n B tr&ecirc;n hệ
                                                thống Freedoo.
                                            </li>
                                            <li>Thực hiện c&aacute;c nghĩa vụ về thuế, ph&iacute; v&agrave; c&aacute;c
                                                chi ph&iacute; kh&aacute;c li&ecirc;n quan (nếu c&oacute;) đến qu&aacute;
                                                tr&igrave;nh thực hiện Hợp đồng n&agrave;y.
                                            </li>
                                        </ol>
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 8. </strong><strong>CHẤM DỨT HỢP ĐỒNG </strong></p>

                                <ol>
                                    <li>Hợp đồng n&agrave;y chấm dứt trong c&aacute;c trường hợp sau:</li>
                                </ol>

                                <ol style="list-style-type:lower-alpha">
                                    <li>C&aacute;c b&ecirc;n thỏa thuận chấm dứt Hợp đồng.</li>
                                    <li>B&ecirc;n A th&ocirc;ng b&aacute;o chấm dứt Hợp đồng khi B&ecirc;n B thuộc một
                                        trong c&aacute;c trường hợp: (i) kh&ocirc;ng đ&aacute;p ứng điều kiện cộng t&aacute;c
                                        vi&ecirc;n, (ii) vi phạm quy định của ph&aacute;p luật, (iii) vi phạm nghĩa vụ
                                        Hợp đồng hoặc quy định của B&ecirc;n A, (iv) kh&ocirc;ng ph&aacute;t sinh th&ugrave;
                                        lao cộng t&aacute;c vi&ecirc;n trong 12 th&aacute;ng li&ecirc;n tiếp (trường hợp
                                        n&agrave;y, B&ecirc;n A kh&ocirc;ng c&oacute; tr&aacute;ch nhiệm ho&agrave;n trả
                                        số tiền th&ugrave; lao c&ograve;n lại của B&ecirc;n B tr&ecirc;n hệ thống
                                        Freedoo, nếu c&oacute;); hoặc (v) khi B&ecirc;n A x&eacute;t thấy việc hợp t&aacute;c
                                        với B&ecirc;n B kh&ocirc;ng mang lại hiệu quả kinh tếC&aacute;c trường hợp kh&aacute;c
                                        theo quy định của ph&aacute;p luật.
                                        <ol>
                                            <li>Khi chấm dứt Hợp đồng, B&ecirc;n B chấm dứt mọi hoạt động cộng t&aacute;c
                                                vi&ecirc;n được quy định tại Hợp đồng n&agrave;y, B&ecirc;n A c&oacute;
                                                quyền x&oacute;a t&agrave;i khoản của B&ecirc;n B tại hệ thống Freedoo.
                                                Hợp đồng chỉ được thanh l&yacute; khi c&aacute;c b&ecirc;n đ&atilde; ho&agrave;n
                                                th&agrave;nh mọi nghĩa vụ c&ograve;n lại trong Hợp đồng.
                                            </li>
                                        </ol>
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 9. </strong><strong>BẢO MẬT TH&Ocirc;NG TIN</strong></p>

                                <ol>
                                    <li>Trừ trường hợp sử dụng cho mục đ&iacute;ch thực hiện Hợp đồng n&agrave;y, c&aacute;c
                                        b&ecirc;n cam kết giữ b&iacute; mật tất cả c&aacute;c th&ocirc;ng tin li&ecirc;n
                                        quan đến nội dung của Hợp đồng như nội dung Hợp đồng, thỏa thuận, cam kết giữa c&aacute;c
                                        b&ecirc;n, th&ocirc;ng tin kh&aacute;ch h&agrave;ng,... v&agrave; c&aacute;c th&ocirc;ng
                                        tin kh&aacute;c c&oacute; li&ecirc;n quan m&agrave; c&aacute;c b&ecirc;n được
                                        biết trong qu&aacute; tr&igrave;nh l&agrave;m việc giữa c&aacute;c b&ecirc;n.
                                    </li>
                                </ol>

                                <p>C&aacute;c b&ecirc;n kh&ocirc;ng được tiết lộ hoặc để lộ th&ocirc;ng tin tr&ecirc;n
                                    cho bất kỳ b&ecirc;n thứ ba n&agrave;o kh&aacute;c trừ trường hợp b&ecirc;n c&ograve;n
                                    lại đồng &yacute; bằng văn bản hoặc theo quy định của ph&aacute;p luật.</p>

                                <ol>
                                    <li>C&aacute;c quy định tại khoản 1 điều n&agrave;y r&agrave;ng buộc c&aacute;c b&ecirc;n
                                        về nghĩa vụ bảo mật kh&ocirc;ng giới hạn về kh&ocirc;ng gian, thời gian. Mọi vi
                                        phạm nghĩa vụ bảo mật dẫn đến thiệt hại cho một b&ecirc;n (nếu c&oacute;) sẽ
                                        được b&ecirc;n vi phạm bồi thường theo thực tế thiệt hại xảy ra.
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 10. </strong><strong>LUẬT ĐIỀU CHỈNH V&Agrave; GIẢI QUYẾT TRANH
                                        CHẤP</strong></p>

                                <ol>
                                    <li>Hợp đồng n&agrave;y được giải th&iacute;ch v&agrave; điều chỉnh theo ph&aacute;p
                                        luật Việt Nam.
                                    </li>
                                    <li>Mọi tranh chấp ph&aacute;t sinh từ việc k&yacute; kết v&agrave; thực hiện Hợp
                                        đồng n&agrave;y sẽ được hai b&ecirc;n ưu ti&ecirc;n giải quyết bằng thương
                                        lượng, ho&agrave; giải tr&ecirc;n tinh thần thiện ch&iacute;. Trường hợp c&aacute;c
                                        b&ecirc;n kh&ocirc;ng thể giải quyết được bằng thương lượng, h&ograve;a giải,
                                        mọi tranh chấp ph&aacute;t sinh hoặc li&ecirc;n quan đến Hợp đồng n&agrave;y sẽ
                                        được giải quyết bởi T&ograve;a &aacute;n c&oacute; thẩm quyền. Mọi chi ph&iacute;
                                        ph&aacute;t sinh trong qu&aacute; tr&igrave;nh giải quyết tranh chấp sẽ do B&ecirc;n
                                        thua kiện trả theo ph&aacute;n quyết của T&ograve;a &aacute;n.
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 11. </strong><strong>TH&Ocirc;NG B&Aacute;O</strong></p>

                                <ol>
                                    <li>B&ecirc;n A c&oacute; quyền thay đổi mọi nội dung li&ecirc;n quan đến ch&iacute;nh
                                        s&aacute;ch cộng t&aacute;c vi&ecirc;n, quy định hợp đồng, phụ lục hợp đồng, gi&aacute;
                                        b&aacute;n sản phẩm/dịch vụ, mức th&ugrave; lao, chương tr&igrave;nh khuyến mại,&hellip;
                                        v&agrave; cung cấp th&ocirc;ng tin tr&ecirc;n hệ thống Freedoo v&agrave;/hoặc
                                        email cho B&ecirc;n B khi thay đổi.
                                    </li>
                                    <li>Trường hợp B&ecirc;n A gửi nhiều th&ocirc;ng b&aacute;o, th&ocirc;ng b&aacute;o
                                        cuối c&ugrave;ng theo thời gian sẽ được &aacute;p dụng. Nếu th&ocirc;ng b&aacute;o
                                        nhận được c&ugrave;ng thời gian sẽ &aacute;p dụng theo thứ tự ưu ti&ecirc;n: hệ
                                        thống Freedoo/email/văn bản trao tay/thư bảo đảm (nếu c&oacute;). Th&ocirc;ng b&aacute;o
                                        thể hiện bằng điện thoại chỉ c&oacute; gi&aacute; trị tham khảo.
                                    </li>
                                    <li>B&ecirc;n B thay đổi th&ocirc;ng tin c&aacute; nh&acirc;n/t&agrave;i khoản phải
                                        được thực hiện bằng văn bản v&agrave; gửi đến B&ecirc;n A để B&ecirc;n A ph&ecirc;
                                        duyệt.
                                    </li>
                                </ol>

                                <p><strong>ĐIỀU 12. </strong><strong>ĐIỀU KHOẢN THI H&Agrave;NH</strong></p>

                                <ol>
                                    <li>Hợp đồng n&agrave;y được lập th&agrave;nh s&aacute;u trang, c&oacute; hiệu lực
                                        kể từ ng&agrave;y B&ecirc;n B k&yacute; văn bản x&aacute;c nhận đồng &yacute;
                                        giao kết Hợp đồng cộng t&aacute;c vi&ecirc;n hoặc ng&agrave;y k&iacute;ch hoạt t&agrave;i
                                        khoản của B&ecirc;n B tr&ecirc;n hệ thống Freedoo (t&ugrave;y theo điều kiện đến
                                        trước).
                                    </li>
                                    <li>C&aacute;c quy định của B&ecirc;n A li&ecirc;n quan đến ch&iacute;nh s&aacute;ch
                                        cộng t&aacute;c vi&ecirc;n v&agrave; văn bản x&aacute;c nhận đồng &yacute; giao
                                        kết Hợp đồng cộng t&aacute;c vi&ecirc;n của B&ecirc;n B l&agrave; một bộ phận
                                        cấu th&agrave;nh Hợp đồng n&agrave;y.
                                    </li>
                                    <li>C&aacute;c b&ecirc;n cam kết tu&acirc;n thủ đầy đủ c&aacute;c điều khoản v&agrave;
                                        điều kiện đ&atilde; thỏa thuận tại Hợp đồng n&agrave;y với tinh thần thiện ch&iacute;,
                                        trung thực v&agrave; tạo điều kiện thuận lợi cho nhau trong qu&aacute; tr&igrave;nh
                                        thực hiện.
                                    </li>
                                </ol>


                                <p>&nbsp;</p>

                                <p>&nbsp;</p>


                            </div>
                        </div>
                    </div>
                    <div class="main-login main-button">
                        <div class="form-group">
                            <?php if ($pid == "002") { ?>
                                <div class="login-register">
                                    <button class="btn btn-primary btn-lg btn-block login-button button-sso-login"
                                            type="submit">
                                        Tiếp tục
                                    </button>
                                </div>
                            <?php } else { ?>
                                <div class="login-register">
                                    <button class="btn btn-primary btn-lg btn-block login-button button-sso-login "
                                            type="submit">
                                        Đăng ký
                                    </button>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="login-register">
                                <a href="../login/<?= $pid ?>"
                                   class="btn btn-primary btn-lg btn-block login-button button-sso-login a_href"
                                >Đăng
                                    Nhập</a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        <?php } else { ?>
            <div class="container">
                <div class="row main-login main-center">
                    <div class="form-group">
                        <div class="cols-sm-10">
                            <label for="username" class="cols-sm-2 control-label" id="username_title">Tên đăng
                                nhập</label>
                            <?php echo $form->textField($model, 'username', array('class' => 'form-control form-design')); ?>
                            <input type="hidden" class="form-control" name="pid" id="pid" value="<?= $pid ?>"/>
                            <?php echo $form->error($model, 'username'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cols-sm-10">
                            <label for="email" class="cols-sm-2 control-label" id="email_title">Email</label>
                            <?php echo $form->textField($model, 'email', array('class' => 'form-control form-design')); ?>
                            <?php echo $form->error($model, 'email'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cols-sm-10">
                            <label for="phone" class="cols-sm-2 control-label" id="phone_title">Số điện
                                thoại</label>
                            <?php echo $form->textField($model, 'phone', array('class' => 'form-control form-design')); ?>
                            <?php echo $form->error($model, 'phone'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cols-sm-10">
                            <label for="password" class="cols-sm-2 control-label" id="password_title">Mật
                                khẩu</label>
                            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control form-design')); ?>
                            <?php echo $form->error($model, 'password'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cols-sm-10">
                            <label for="confirm_password" class="cols-sm-2 control-label"
                                   id="confirm_password_title">Nhập
                                lại mật khẩu</label>
                            <?php echo $form->passwordField($model, 'confirm_password', array('class' => 'form-control form-design')); ?>
                            <?php echo $form->error($model, 'confirm_password'); ?>
                        </div>
                    </div>
                    <?php if ($accept_capcha): ?>
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'verifyCode'); ?>
                            <div id="captcha_place_holder"
                                 class="g-recaptcha"
                                 data-sitekey="6LfzSCcTAAAAAPpaGmWa5NlOClELiir9K_HyUSgq">

                            </div>
                            <?php echo $form->error($model, 'verifyCode'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="checkbox" class="form-check-input" id="checkYes" onclick="checkYess()">
                        <label class="form-check-label" for="checkYes">Tôi đồng ý với các <a target="_blank" href="https://freedoo.vnpt.vn/9-dieu-khoan-va-dieu-kien-giao-dich.html">điều khoản</a> của VNPT VinaPhone</label>
                    </div>
                    <div class="form-group" style="margin-top: 40px;">
                        <div class="login-register regiter-320">
                            <button class="btn btn-primary btn-lg btn-block login-button button-sso-login <?php if($_GET['pid'] == 004){ ?> login-flexinss <?php }?>"
                                    type="submit" id="reg_sso"> 
                                Đăng ký
                            </button>
                        </div>
                        <div class="login-register regiter-redirect-320">
                            <a href="../login/<?= $pid ?>"
                               class="btn btn-primary btn-lg btn-block login-button button-sso-login a_href"
                            >Đăng
                                nhập</a>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
        <?php $this->endWidget(); ?>
    </div>
    <?php
        if ($pid == '001') {
            echo Utils::genGA('UA-104621508-4');
        } else if ($pid == '002') {
            echo Utils::genGA('UA-104621508-6');
        }else if ($pid=='003'){
            echo Utils::genGA('UA-104621508-8');
        }
    ?>
</div>
<script>
    $('#rule_title').click(function () {
        $('.rule-toggle').toggle();
        return false;
    });
	
	function checkYess() {
  var checkYes = document.getElementById("checkYes").checked
	if(checkYes === true){
		$('#reg_sso').show();
	}else{
		$('#reg_sso').hide();
	}
}
</script>
<style>
    .rule-toggle {
        display: none;
    }
	#reg_sso{
		display: none;
	}


</style>
