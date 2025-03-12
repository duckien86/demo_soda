<?php if ($username != ''): ?>
    <div>
        <p>Tài khoản <?= $username ?> đã yêu cầu khôi phục mật khẩu.</p>

        <p> Vui lòng vào link dưới để tạo mật khẩu mới:</p>
        <a href="<?= $url_changepass ?>"><?= $url_changepass ?></a>
    </div>
<?php else: ?>
    <div>
        <p>
            Email của bạn chưa được đăng ký trên Freedoo. Vui lòng thực hiện đăng ký làm thành viên để trải nghiệm dịch
            vụ
        </p>
    </div>
<?php endif; ?>
