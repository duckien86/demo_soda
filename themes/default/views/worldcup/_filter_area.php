<?php
/* @var $this WorldcupController */
/* @var $model WWCReport*/
/* @var $form CActiveForm */
?>
<div class="fillterarea">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'id'     => 'wwcreport_filter',
    )); ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-xs-6">
            <?php echo $form->dropDownList($model, 'match_type', WWCMatch::getListType(),array(
                'class' => 'form-control',
                'empty' => 'Vòng đấu',
                'onchange' => 'loadMatch(this.value);searchWinner()',
            ));?>
        </div>
        <div class="col-sm-2 col-xs-6">
            <?php echo $form->dropDownList($model, 'match_id',WWCMatch::getAllMatch($model->match_id,true),array(
                'class' => 'form-control',
                'empty' => 'Trận đấu',
                'onchange' => 'searchWinner()',
            ));?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>

<script>
    function loadMatch(type){
        $('#WWCReport_match_id').html('<option value="">Trận đấu</option>');
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('worldcup/getListMatchByType')?>',
            type: 'post',
            dataType: 'html',
            data: {
                'type' : type,
                'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken;?>',
            },
            success: function (result) {
                $('#WWCReport_match_id').append(result);
            }
        })
    }

    function searchWinner() {
        var form = $('#wwcreport_filter');
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('worldcup/searchReport')?>',
            type: 'post',
            dataType: 'html',
            data: form.serialize(),
            success: function (result) {
                $('#table_winners').html(result);
            }
        });
    }
</script>