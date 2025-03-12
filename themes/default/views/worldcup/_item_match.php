<?php
/**
 * @var $this WorldcupController
 * @var $model WCMatch
 */

?>

<div class="worldcup_item_match">
    <div class="row">
        <div class="col-sm-5 col-xs-4">
            <div class="row">
                <div class="col-sm-8" style="float: right">
                    <div class="worldcup_team_name text-center">
                        <?php echo $model->team_name_1?>
                    </div>
                    <div class="worldcup_team_flag text-center">
                        <?php
                            $team = WWCTeam::getTeam($model->team_code_1);
                        ?>
                        <img src="<?php echo Yii::app()->theme->baseUrl . '/images/' .$team->flag?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-2 col-xs-4">
            <div class="match_time">
                <?php echo date('H:i - d/m',strtotime($model->start_time))?><br/>
                <?php echo WWCMatch::getTypeLabel($model->type)?>

            </div>
            <div class="icon_vs">
                <img src="<?php echo Yii::app()->theme->baseUrl?>/images/worldcup_vs_icon-min.png">
            </div>
        </div>

        <div class="col-xs-5 col-xs-4">
            <div class="row">
                <div class="col-xs-8">
                    <div class="worldcup_team_name text-center">
                        <?php echo $model->team_name_2?>
                    </div>
                    <div class="worldcup_team_flag text-center">
                        <?php
                        $team = WWCTeam::getTeam($model->team_code_2);
                        ?>
                        <img src="<?php echo Yii::app()->theme->baseUrl . '/images/' . $team->flag?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $now = date('Y-m-d H:i:s');
    $accept_date = date('Y-m-d H:i:s', strtotime($now . ' -15 min'));
    if($accept_date <= $model->start_time){?>
        <div class="action">
            <?php echo CHtml::link(Yii::t('web/portal','predict'),'javascript:void(0)',array(
                'class' => 'btnPredict btn',
                'onclick' => "predictMatch($model->id)"
            ))?>
        </div>
    <?php } ?>



</div>
