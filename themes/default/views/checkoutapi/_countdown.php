<?php
    $time = Yii::app()->session['session_cart'] + Yii::app()->params['sessionTimeoutApi'];
?>
<a id="back_chon_so" class="btn btn-primary" href="http://chonso.vinaphone.com.vn/p/chonso/chon-so-ca-nhan.html"><< Về trang chọn số</a>
<p>Quý khách vui lòng điền thông tin và thanh toán trong vòng <span id="count_down"></span></p>

<script>
    document.getElementById("back_chon_so").style.display = 'none'; // Hide
    // Set the date we're counting down to
    //    var countDownDate = new Date("Jul 21, 2017 10:00:25").getTime();
    var countDownDate = new Date("<?= date('M d, Y H:i:s',$time); ?>").getTime();

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("count_down").innerHTML =
            minutes + " phút " + seconds + " giây ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("count_down").innerHTML = "(HẾT HẠN)";
            document.getElementById("back_chon_so").style.display = 'block';          // Show
        }
    }, 1000);
</script>
