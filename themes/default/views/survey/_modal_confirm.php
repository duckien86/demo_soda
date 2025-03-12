<?php
    /**
     * @var $this SurveyController
     *
     * @var $order_id
     */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id'       => 'modal_survey_confirm',
        'autoOpen' => TRUE,
    )
); ?>
<div class="modal-body" style="min-height: 100px">
    <a class="close" data-dismiss="modal"><i class="fa fa-times"></i></a>

    <div id="modal_survey_confirm_banner" class="text-center">
        <img src="<?php echo Yii::app()->theme->baseUrl?>/images/popup-16_8.png" alt="Gift" />
    </div>

    <h2>Khảo sát nâng cao chất lượng dịch vụ website Freedoo</h2>

    <div id="modal_survey_confirm_content">

        <div class="action text-center">
            <?php
            $url_survey = Yii::app()->createUrl('survey/index', array('od' => isset($order_id) ? $order_id : ''));
            echo CHtml::link('Khảo sát ngay', $url_survey, array(
                'target' => '_blank',
                'class' => 'btn',
                'style' => 'background: #1ea0da',
            )); ?>
        </div>
    </div>


</div>
<?php $this->endWidget(); ?>
