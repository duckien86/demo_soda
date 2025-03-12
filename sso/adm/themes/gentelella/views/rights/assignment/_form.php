<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array(
            'class' => 'form-horizontal form-label-left',
        ),
    ));
    ?>

    <div class="form-group">
        <?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions, array('class' => 'form-control')); ?>
        <ul class="parsley-errors-list">
            <li><?php echo $form->error($model, 'itemname', array('class' => 'parsley-required')); ?></li>
        </ul>
    </div>

    <div class="form-group">
        <?php echo CHtml::submitButton(Rights::t('core', 'Assign'), array('class' => 'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>