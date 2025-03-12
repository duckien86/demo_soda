<?php
/* @var $this AWCReportController */
/* @var $model AWCReport */
/* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <table>
        <tbody>
        <tr>
            <td><?php echo $form->label($model, 'match_type'); ?></td>
            <td>
                <?php echo CHtml::activeDropDownList($model,'match_type',AWCMatch::getListType(), array(
                    'class' => 'form-control',
                    'empty' => 'Tất cả',
                    'onchange' => 'loadMatch(this.value)',
                ))?>
            </td>
            <td><?php echo $form->label($model, 'match_id'); ?></td>
            <td>
                <?php echo CHtml::activeDropDownList($model,'match_id', AWCMatch::getAllMatch($model->match_type), array(
                    'class' => 'form-control',
                    'empty' => 'Tất cả',
                ))?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'team_selected'); ?></td>
            <td>
                <?php echo CHtml::activeDropDownList($model,'team_selected',
                    CHtml::listData(AWCTeam::getAllTeam(), 'code', 'name'),
                    array(
                        'class' => 'form-control',
                        'empty' => 'Tất cả',
                    )
                )?>
            </td>
            <td><?php echo $form->label($model, 'status'); ?></td>
            <td>
                <?php echo CHtml::activeDropDownList($model, 'status', AWCReport::getListStatus(),
                    array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                )?>
            </td>
        </tr>

        <tr>
            <td><?php echo $form->label($model, 'info'); ?></td>
            <td>
                <?php echo CHtml::activeTextField($model, 'info',
                    array('class' => 'form-control')
                )?>
            </td>
            <td><?php echo $form->label($model, 'lucky_number'); ?></td>
            <td>
                <?php echo CHtml::activeTextField($model,'lucky_number', array(
                    'class' => 'form-control',
                    'maxLength'   => 4,
                ))?>
            </td>
        </tr>
        <tr>
            <td rowspan="2" class="col_btn_search">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
        </tbody>
    </table>

    <?php $this->endWidget(); ?>
</div>

<script>
    function loadMatch(type){
        $('#AWCReport_match_id').html('<option value="">Tất cả</option>');
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('aWCReport/getListMatchByType')?>',
            type: 'post',
            dataType: 'html',
            data: {
                'type' : type,
                'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken;?>',
            },
            success: function (result) {
                $('#AWCReport_match_id').append(result);
            }
        })
    }
</script>