<nav id="menu" class="left_menu">
    <ul>
        <?php if (isset(Yii::app()->session['session_data']->current_msisdn)): ?>
            <li class="mparent"><a href="javascript:void(0);"><i
                        class="fa fa-mobile i_mn_phone"></i> <?= Yii::app()->session['session_data']->current_msisdn ?>
                </a></li>
        <?php endif; ?>
        <li class="mparent">
            <a href="<?php echo Yii::app()->controller->createUrl('site/index'); ?>">
                <p>Trang chủ</p>
            </a>
        </li>
        <li class="mparent">
            <a href="<?php echo Yii::app()->controller->createUrl('worldcup/index'); ?>">
                <p>Dự đoán</p>
            </a>
        </li>
        <li class="mparent">
            <a href="<?php echo Yii::app()->controller->createUrl('worldcup/reward'); ?>">
                <p>Thể lệ & giải thưởng</p>
            </a>
        </li>
        <li class="mparent">
            <a href="<?php echo Yii::app()->controller->createUrl('worldcup/winners'); ?>">
                <p>Doanh sách trúng thưởng</p>
            </a>
        </li>
    </ul>
</nav>

<script>

    $(document).ready(function (){
        $('nav#menu').mmenu({
            extensions: true,
            searchfield: false,
            counters: false,
            openingInterval: 0,
            transitionDuration: 5,
            navbar: {
                title: ''
            },
            slidingSubmenus: true
        });
    });
</script>