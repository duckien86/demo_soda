<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array(
            'class' => 'form-horizontal form-label-left',
        ),
    ));
    ?>

    <div class="form-group">

        <?php
            $this->widget(
                'booster.widgets.TbSelect2',
                array(
                    'model'       => $model,
                    'attribute'   => 'itemname',
                    'data'        => $itemnameSelectOptions,
                    'htmlOptions' => array(
                        'multiple' => FALSE,
//                        'prompt'   => 'Chọn quyền',
                        //reset value selected
                        'style'    => 'width:100%'
                    ),
                )
            );
        ?>
        <ul class="parsley-errors-list">
            <li><?php echo $form->error($model, 'itemname', array('class' => 'parsley-required')); ?></li>
        </ul>
    </div>

    <div class="form-group">
        <span class="btnintbl">
            <span class="icondk">
                <?php echo CHtml::submitButton(Rights::t('core', 'Add')); ?>
            </span>
        </span>
    </div>

    <?php $this->endWidget(); ?>

</div>