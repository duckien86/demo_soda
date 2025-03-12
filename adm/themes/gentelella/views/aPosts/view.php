<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_post') => array('admin'),
        $model->title
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        array(
            'name'  => 'image',
            'type'  => 'raw',
            'value' => $model->getImageUrl($model->image),
        ),
        array(
            'name'  => 'title',
            'value' => function ($data) {
                return CHtml::encode($data->title);
            }
        ),
        array(
            'name'  => 'content',
            'value' => function ($data) {
                return CHtml::encode($data->content);
            }
        ),
        array(
            'name'  => 'sso_id',
            'value' => function ($data) {
                return ACustomers::getName($data->sso_id);
            }
        ),
        array(
            'name'  => 'total_like',
            'value' => function ($data) {
                return CHtml::encode($data->total_like);
            }
        ),
        array(
            'name'  => 'total_comment',
            'value' => function ($data) {
                return CHtml::encode($data->total_comment);
            }
        ),
        array(
            'name'  => 'post_category_id',
            'value' => function ($data) {
                return CHtml::encode($data->getPostCate($data->post_category_id), array('view', 'id' => $data->id));
            }
        ),
        array(
            'name'  => 'sort_order',
            'value' => function ($data) {
                return CHtml::encode($data->sort_order);
            }
        ),
        array(
            'name'  => 'note',
            'value' => function ($data) {
                return CHtml::encode($data->note);
            }
        ),
        array(
            'name'  => 'create_date',
            'value' => function ($data) {
                return CHtml::encode($data->create_date);
            }
        ),
        array(
            'name'  => 'last_update',
            'value' => function ($data) {
                return CHtml::encode($data->last_update);
            }
        ),
        array(
            'name'  => 'status',
            'value' => function ($data) {
                return CHtml::encode($data->getStatusLabel($data->status));
            }
        ),
    ),
)); ?>
