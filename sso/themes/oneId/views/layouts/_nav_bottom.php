<?php
    $detect = new MyMobileDetect();
    $os     = $detect->getOs();
?>
<div id="nav_bottom">
    <div class="item_link">
        <?= CHtml::link(Yii::t('web/home', 'home'), WCategories::createHomeUrl('root'), array('class' => 'txt_title')) ?>
    </div>
    <?php if (Utils::check_os_content($os, 'game', Yii::app()->params->os_content)): ?>
        <div class="item_link">
            <?= CHtml::link(Yii::t('web/home', 'game_app'), WCategories::createHomeUrl('games'), array('class' => 'txt_title')) ?>
        </div>
    <?php endif; ?>
    <?php if (Utils::check_os_content($os, 'video', Yii::app()->params->os_content)): ?>
        <div class="item_link">
            <?= CHtml::link(Yii::t('web/home', 'videos'), WCategories::createHomeUrl('videos'), array('class' => 'txt_title')) ?>
        </div>
    <?php endif; ?>
    <!--<div class="item_link">
        <?= CHtml::link(Yii::t('web/home', 'characters'), WCategories::getLinkCharactersPage(), array('class' => 'txt_title')) ?>
    </div>-->
    <?php if (Utils::check_os_content($os, 'ebook', Yii::app()->params->os_content)): ?>
        <div class="item_link">
            <?= CHtml::link(Yii::t('web/home', 'ebook'), WCategories::createHomeUrl('ebooks'), array('class' => 'txt_title')) ?>
        </div>
    <?php endif; ?>
    <?php if (Utils::check_os_content($os, 'wallpaper', Yii::app()->params->os_content)): ?>
        <div class="item_link">
            <?= CHtml::link(Yii::t('web/home', 'wallpaper'), WCategories::createHomeUrl('wallpapers'), array('class' => 'txt_title')) ?>
        </div>
    <?php endif; ?>
    <div class="space_20"></div>
    <div class="bottom_search">
        <div class="col-md-offset-3 col-md-6 col-xs-12">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'action' => WCategories::createHomeUrl('root') . '/search',
                'method' => 'get',
            )); ?>
            <div class="input-group">
                <input maxlength="500" type="text" name="q" placeholder="<?= Yii::t('web/home', 'search') ?>"
                       class="form-control"/>
                <span class="input-group-btn">
                    <?php echo CHtml::submitButton(Yii::t('web/home', 'search'), array('class' => 'btn btn_search_right')); ?>
                </span>
            </div>
            <!-- /input-group -->
            <?php $this->endWidget(); ?>
        </div>
        <!-- /.col-md-6 -->
    </div>
    <div class="space_20"></div>
</div>


