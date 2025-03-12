<?php
/**
 * @var $this WorldcupController
 * @var $model WWCReport
 * @var $match WWCMatch
 */
?>

<p>Chào bạn,</p>

<p>Chúc mừng đã dự đoán thành công chương trình “Dự đoán World Cup cùng Freedoo.”</p>

<p>Bạn vừa dự đoán kết quả <?php echo WWCTeam::getTeamName($model->team_selected)?> thắng trong trận <?php echo WWCMatch::getTypeLabel($match->type)?> “<?php echo $match->team_name_1 . ' - ' . $match->team_name_2?>” vào lúc <?php echo date('H:i',strtotime($model->create_time))?></p>

<p>Số may mắn của bạn là <?php echo $model->lucky_number?>.</p>

<p>Trong khi chờ đợi kết quả, bạn có thể tham khảo nhiều sim Freedoo số đẹp nhân dịp World Cup tại <a href="https://freedoo.vnpt.vn/sim-so">https://freedoo.vnpt.vn/sim-so</a></p>

<p>Đăng ký gói cước giá rẻ ưu đã nhất thị trường chỉ có tại Freedoo: <br>
    Gói FHappy có 60Gb data tốc độ cao/tháng + 1.000 phút gọi nội mạng VinaPhone. Đăng ký tại <a href="https://goo.gl/MLGUKp">https://goo.gl/MLGUKp</a> chỉ với 49.000 đồng.</br>
    Gói FClub có 90Gb data tốc độ cao/tháng + 1.500 phút gọi nội mạng VinaPhone + 60 phút ngoại mạng. Đăng ký tại <a href="https://goo.gl/KGAt6D">https://goo.gl/KGAt6D</a> chỉ với 79.000 đồng.
</p>

<p>Fanpage: <a href="https://www.facebook.com/freedoo.vnpt.vn/">https://www.facebook.com/freedoo.vnpt.vn/</a></p>
<p>Group kiếm tiền cùng Freedoo: <a href="https://www.facebook.com/groups/kiemtienonlinecungFreedoo/">https://www.facebook.com/groups/kiemtienonlinecungFreedoo/</a></p>

