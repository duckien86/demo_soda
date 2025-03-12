<?php
/**
 * @var $this WorldcupController
 * @var $modelMatch WWCMatch
 * @var $modelForm WWCReport
 * @var $save bool
 * @var $form TbActiveForm
 */
?>

<div class="title">

    <a class="close" data-dismiss="modal">&times;</a>

    <div class="row">
        <div class="col-md-4 col-xs-5">
            <div class="worldcup_team_flag">
                <?php
                $team = WWCTeam::getTeam($modelMatch->team_code_1);
                ?>
                <img src="<?php echo Yii::app()->theme->baseUrl . '/images/' . $team->flag?>">
            </div>
        </div>

        <div class="col-md-4 col-xs-2 no_pad">
            <div class="match_time">
                <?php
                $time = strtotime($modelMatch->start_time);
                $hour = date('H', $time).'h ';
                $minute = date('i', $time);
                $day = date('d/m/Y', $time);
                echo $hour . ' ' . $minute . ' - ' . $day;
                ?>
            </div>
            <div class="icon_vs">
                <img src="<?php echo Yii::app()->theme->baseUrl?>/images/worldcup_vs_icon-min.png">
            </div>
        </div>

        <div class="col-md-4 col-xs-5">
            <div class="worldcup_team_flag">
                <?php
                $team = WWCTeam::getTeam($modelMatch->team_code_2);
                ?>
                <img src="<?php echo Yii::app()->theme->baseUrl . '/images/' . $team->flag?>">
            </div>
        </div>
    </div>
</div>

<div id="worldcup_predict_form">

    <?php if($save){ ?>
        <?php echo $this->renderPartial('/worldcup/_success', array(
            'model' => $modelForm,
            'match' => $modelMatch
        ));?>
    <?php }else{ ?>

    <h3>
        <?php echo Yii::t('web/portal','worldcup_predict_match_title', array(
            '{type}' => WWCMatch::getTypeLabel($modelMatch->type),
            '{team_name_1}' => $modelMatch->team_name_1,
            '{team_name_2}' => $modelMatch->team_name_2
        ))?>
    </h3>

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'worldcup-form',
            'method' => 'post',
            'enableAjaxValidation' => true,
    //        'enableClientValidation' => true,
            'action' => Yii::app()->createUrl('worldcup/index')
        )); ?>

        <?php echo $form->hiddenField($modelMatch,'id') ?>
        <?php echo $form->error($modelForm,'match_id')?>

        <div class="form-group">
            <?php echo $form->textField($modelForm,'name', array(
                'class'         => 'form-control',
                'required'      => true,
                'placeholder'   => Yii::t('web/portal','name'),
            ))?>
            <?php echo $form->error($modelForm,'name')?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($modelForm,'phone', array(
                'class' => 'form-control',
                'required' => true,
                'placeholder'   => Yii::t('web/portal','phone'),
            ))?>
            <?php echo $form->error($modelForm,'phone')?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($modelForm,'email', array(
                'class' => 'form-control',
                'required' => true,
                'placeholder'   => Yii::t('web/portal','email'),
            ))?>
            <?php echo $form->error($modelForm,'email')?>
        </div>

        <div class="form-group">
            <?php echo $form->dropDownList($modelForm,'team_selected', array(
                $modelMatch->team_code_1 => $modelMatch->team_name_1,
                $modelMatch->team_code_2 => $modelMatch->team_name_2,
            ),array(
                'class'     => 'form-control',
                'required'  => true,
                'empty'     => 'Chọn đội thắng',
            ))?>
            <?php echo $form->error($modelForm,'team_selected')?>
        </div>

        <div class="form-group">
            <?php echo $form->textField($modelForm,'lucky_number', array(
                'class'         => 'form-control',
                'required'      => true,
                'placeholder'   => 'Số may mắn (số bàn đội thắng ghép với số bàn đội thua, vd: 10, 20, 21)',
                'maxlength'     => 4
            ))?>
            <?php echo $form->error($modelForm,'lucky_number')?>
        </div>

        <div class="action text-center">
            <?php echo CHtml::submitButton(Yii::t('web/portal','predict'),array(
                'class' => 'btnPredict btn',
            ))?>
        </div>

        <?php $this->endWidget() ?>

        <script>
            $(document).ready(function() {
                $("#WWCReport_lucky_number").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    <?php } ?>
</div>


