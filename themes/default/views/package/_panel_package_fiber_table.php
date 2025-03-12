<div class="title-info-fiber" id="title-info-fiber">
    <?php echo $data->name ?>
</div>
<div class="use-right">
    <div class="row mar-bottom-7">
        <div class="col-lg-12">
            <?= nl2br(CHtml::encode($data->short_description)); ?>
        </div>
    </div>
</div>
<div class="next-step">
    <?= CHtml::link('Đăng ký',
        $this->createUrl('package/registerfiber', array('package' => $data->id)),
        array('class' => 'btn btn_green')); ?>
</div>
<style>
    .use-right {
        width: 100%;
        padding: 5px;
        font-size: 14px;
    }

    .use-right i.fa {
        font-size: 6px;
    }

    .t-info {
        font-size: 11px;
    }

    .no-pad-right {
        padding-right: 0px !important;
    }

    .mar-bottom-7 {
        margin-bottom: 7px;
    }

    .f-bold {
        font-weight: bold;
    }

    .use-right br {
        margin-bottom: 7px !important;
        width: 100%;
    }

    .next-step {
        width: 100%;
        text-align: center;
        margin-top: 215px;
    }

    .btn_register {
        color: #FFF;
        background-color: #ed0677;
        font-size: 16px;
        font-family: SanFranciscoDisplay-Regular;
    }

    .title-info-fiber {
        font-weight: bold;
        font-size: 20px;
    }
</style>