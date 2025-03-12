<div id="section_social">
    <div class="container">
        <div class="col-md-6 col-xs-12 no_pad">
            <div class="txt">
                <a href="<?= Yii::app()->controller->createUrl('help/index', array('t' => 'CTV')); ?>" class="link">
                    Chính sách
                </a>
                <a href="<?= Yii::app()->controller->createUrl('help/index'); ?>" class="link border_left">
                    Các câu hỏi thường gặp
                </a>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 no_pad">
            <div class="fr">
                <div class="fl txt">Theo dõi chúng tôi trên</div>
                <div class="fl">
                    <a href="#">
                        <span class="social">
                            <?= CHtml::image(Yii::app()->theme->baseUrl . '/images/icon_gg.png') ?>
                        </span>
                    </a>
                    <a href="https://www.facebook.com/freedoo.vnpt.vn/">
                    <span class="social">
                        <?= CHtml::image(Yii::app()->theme->baseUrl . '/images/icon_fb.png') ?>
                    </span>
                    </a>
                    <a href="#">
                    <span class="social">
                        <?= CHtml::image(Yii::app()->theme->baseUrl . '/images/icon_tw.png') ?>
                    </span>
                    </a>
                    <a href="#">
                    <span class="social">
                        <?= CHtml::image(Yii::app()->theme->baseUrl . '/images/icon_in.png') ?>
                    </span>
                    </a>
                    <a href="#">
                    <span class="social">
                        <?= CHtml::image(Yii::app()->theme->baseUrl . '/images/icon_map.png') ?>
                    </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>