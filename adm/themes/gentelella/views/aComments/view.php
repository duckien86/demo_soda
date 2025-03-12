<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_comment') => array('admin'),
        $model->content
    );
?>
<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        array(
            'name'  => 'content',
            'value' => function ($data) {
                return CHtml::encode($data->content);
            }
        ),
        array(
            'name'  => 'total_like',
            'value' => function ($data) {
                return CHtml::encode($data->total_like);
            }
        ),
        array(
            'name'  => 'content',
            'value' => function ($data) {
                return CHtml::encode($data->content);
            }
        ),
        array(
            'name'  => 'sc_tbl_post_id',
            'value' => function ($data) {
                return CHtml::encode(AComments::model()->getPostTitle($data->sc_tbl_post_id));
            }
        ),
        array(
            'name'  => 'create_date',
            'value' => function ($data) {
                return CHtml::encode($data->create_date);
            }
        ),
        array(
            'name'  => 'get_award',
            'value' => function ($data) {
                return CHtml::encode($data->get_award);
            }
        ),
    ),
)); ?>
