<?php
    use yii\helpers\Html;
    use kartik\widgets\FileInput;
?>
<script>
    $(document).ready(function(){
        $('.profile-avatar').click(function(){

        });
    }) ;
</script>
<div class="profile-avatar container">
    <div class="wrap-avatar">
        <?php if (!empty($model->avatar)) {
            echo Html::img(
                '@web/upload/' . $model->avatar,
                [
                    'alt' => 'avatar', 'id' => 'imgavr', 'class' => 'upimg profile-avatar'
                ]);
        } else {
            echo Html::img('@web/images/avatar_2x.png', ['alt' => 'avatar', 'id' => 'imgavr', 'class' => 'profile-avatar']);
        } ?>
        <div class="clearfix"></div>
        <?=

            $form->field($model, 'avatar')->widget(FileInput::className(), [
                'options'       => [
                    'accept'   => 'image/*',
                    'multiple' => FALSE,
                ],
                'pluginOptions' => [
                    'showUpload'  => TRUE,
                    'showCaption' => FALSE,
//                    'showRemove'  => TRUE,
                    'browseIcon'  => '<i class="glyphicon glyphicon-camera"></i>',
                    'browseLabel' =>  'Thay ảnh',
                    'showPreview' => TRUE
                ],
            ])->label("<i class=\"glyphicon glyphicon-camera avatar_img\"></i>")
        ?>

    </div>
    <p class="my-name">
        <?= Html::encode($model->full_name) ?>
    </p>
</div>
