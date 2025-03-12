<?php
/**
 * @var $this WorldcupController
 * @var $model WWCReport
 * @var $match WWCMatch
 */
?>
<p>Cảm ơn bạn đã tham gia sự kiện Dự đoán WorldCup - trúng thưởng cùng Freedoo</p>
<p>Bạn đã dự đoán trận <?php echo WWCMatch::getTypeLabel($match->type)?> giữa <?php echo $match->team_name_1?> và <?php echo $match->team_name_2?> với đội thắng là <?php echo WWCTeam::getTeamName($model->team_selected)?>, số may mắn là <?php echo $model->lucky_number?></p>
