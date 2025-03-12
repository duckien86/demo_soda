<?php
/**
 * @var $this SiteController
 */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_dktttb')
); ?>
<div class="modal-body">
    <a class="close" data-dismiss="modal">&times;</a>
    <a href="http://my.vinaphone.com.vn/users/updatesubinfo" target="_blank">
        <img src="<?php echo Yii::app()->theme->baseUrl?>/images/banner_dk_tttb_popup.png">
    </a>
</div>
<?php $this->endWidget(); ?>


<style>
    #modal_dktttb .modal-content{
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        border-radius: 0;
    }
    #modal_dktttb .modal-body{
        padding: 0;
        position: relative;
    }
    #modal_dktttb .modal-body img{
        width: 100%;
        height: auto;
    }
    #modal_dktttb .modal-body a.close{
        position: absolute;
        right: 0;
        top: 0;
        font-size: 36px;
        color: #fff;
        display: block;
        padding: 0 15px;
    }
</style>

<script>
    $(window).on('load',function () {
        $('#modal_dktttb').modal('show');
    });
</script>