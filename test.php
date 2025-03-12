<?php 
/*---------------------------------------------------------------*/
//?sim_number=0886017062&sim_price=70000&sim_type=2&sim_term=18&sim_priceterm=600000

$url_origin     = 'http://10.2.0.107:8694'; // http://localhost , http://10.2.0.107:8694
$sim_number     =  isset($_GET['sim_number']) ? $_GET['sim_number'] : '0886010409';

$sim_price      = isset($_GET['sim_price']) ? $_GET['sim_price'] :  '60000'; 
$sim_type       = isset($_GET['sim_type']) ? $_GET['sim_type'] :  '2'; 
$sim_term       = isset($_GET['sim_term']) ? $_GET['sim_term'] :  '0'; 
$sim_priceterm  = isset($_GET['sim_priceterm']) ? $_GET['sim_priceterm'] :  '0'; 
$sim_store      = '32878'; 
$transaction_id = '123'; 
$channel        = 'chonsovnp';
$otp            = '12345';
$option         = 'couple';
    $delivery_type = isset($_GET['delivery_type']) ? $_GET['delivery_type'] :  '';
    $array_param = array(
                'sim_number'     => $sim_number,
                'sim_price'      => $sim_price,
                'sim_type'       => $sim_type,
                'sim_term'       => $sim_term,
                'sim_priceterm'  => $sim_priceterm,
                'sim_store'      => $sim_store,
                'transaction_id' => $transaction_id,
                'channel'        => $channel,
                'otp'            => $otp,
                'option'         => $option,
                'delivery_type'  => $delivery_type
            );
// echo '<pre>';
// print_r($array_param);
// echo '</pre>';
// die;
$dataCover = implode('', array_values($array_param));

$secret_key = '40c8010092b6ae2b4280384490a48ca9'; // chonsovnp
//    $secret_key = '1149107f22479616182bd6f8824ef7b3'; //(freedoo)
//$secret_key    = 'c093aa1576102e99bde917c669c52e4f'; //(mhtn)
//$secret_key    = '94e60ad203dabd84838b4d38c18ad7d6'; //(mhtn)
$secure = md5($dataCover . $secret_key); //stech

$base_url = $url_origin.'/vnpt_online/portal/source/';
$url = $base_url."apisgw/addtocart?sim_number=".$sim_number."&sim_price=".$sim_price."&sim_type=".$sim_type."&sim_term=".$sim_term."&sim_priceterm=".$sim_priceterm."&sim_store=".$sim_store."&transaction_id=".$transaction_id."&channel=".$channel."&otp=".$otp."&option=".$option."&delivery_type=".$delivery_type."&secure=".$secure."";

?>
<a href="<?= $url ?>" target="_blank">Click here</a>

<!--delivery_type  : 1 => tại nhà-->
<!--delivery_type  : 2 => điểm giao dịch-->
<!--delivery_type  : 0 => được chọn cả 2-->

<!--$district_code = array_unique(array_keys(CHtml::listData($data, 'district_code', 'district_code')));-->
<!--$province_code = array_unique(array_keys(CHtml::listData($data, 'province_code', 'province_code')));-->
<!--$ward_code = array_unique(array_keys(CHtml::listData($data, 'ward_code', 'ward_code')));-->