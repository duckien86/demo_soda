<?php
/**
 * @var $this WorldcupController
 * @var $modelMatch WWCMatch
 * @var $modelForm WWCReport
 * @var $show bool
 * @var $save bool
 */
?>

<div class="title">
    <h2><?php echo Yii::t('web/portal','worldcup_predict_match');?></h2>
</div>
<div class="content">

    <?php foreach (WWCMatch::getAllMatch() as $match){
        echo $this->renderPartial('_item_match',array(
            'model' => $match,
        ));
    }?>

</div>

<?php echo $this->renderPartial('/worldcup/_modal_predict', array(
    'modelMatch'    => $modelMatch,
    'modelForm'     => $modelForm,
    'show'          => $show,
    'save'          => $save,
));?>

<script>
    function predictMatch(match_id){


        $.ajax({
            url: '<?php echo Yii::app()->createUrl('worldcup/getMatchContent')?>',
            crossDomain: true,
            type: 'post',
            dataType: 'json',
            data: {
                'id' : match_id,
                'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (result) {
                $('#modal_worldcup_predict .modal-body').html(result.dataHtml);
                $('#modal_worldcup_predict').modal('show');
            }
        });
    }
</script>
