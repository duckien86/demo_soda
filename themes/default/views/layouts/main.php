<!DOCTYPE html>

<html lang="en">
<head>
    <meta id="viewport" name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8"/>
    <meta property="og:url" content="<?= 'http://' . SERVER_HTTP_HOST . CHtml::encode($_SERVER['REQUEST_URI']); ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="TELMALL"/>	
    <meta property="og:description" content="<?php echo CHtml::encode($this->pageDescription); ?>"/>
    <meta property="og:image" content="<?php echo CHtml::encode($this->pageImage); ?>"/>
    <meta name="google-site-verification" content="6Nzg1_KTuzcIB-7fWbeObr-1QCCXOjaE7CegbeDKI4E" />


    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php $version = '?v=1.2.7'; ?>
    <link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/images/icon-fav.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->theme->baseUrl ?>/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->theme->baseUrl ?>/css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->theme->baseUrl ?>/css/icheck/flat/blue.css">
    <!-- font-awesome -->
    <link href="<?= Yii::app()->theme->baseUrl ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/sim.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/home.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/help.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/step.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/style.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/news.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/simkit.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/package.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/survey.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/fiber.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/seo.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet"
          href="<?= Yii::app()->theme->baseUrl . '/css/prepaid_to_postpaid.css' . $version; ?>"/>
    <link media="screen" rel="stylesheet"
          href="<?= Yii::app()->theme->baseUrl . '/css/desktop_new.css' . $version; ?>"/>

    <script src="<?= Yii::app()->theme->baseUrl ?>/js/owl.carousel.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/wow.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/icheck/icheck.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl . '/js/custom.js' . $version; ?>"></script>
    <script src="<?= Yii::app()->theme->baseUrl . '/js/customize.js' . $version; ?>"></script>
    <script src="<?= Yii::app()->theme->baseUrl . '/js/main.js' . $version; ?>"></script>
    <script>
        var wow = new WOW(
            {
                animateClass: 'animated', // animation css class (default is animated)
                offset: 0,          // distance to the element when triggering the animation (default is 0)
                callback: function (box) {
                }
            }
        );
        wow.init();
    </script>
    <!--Start of Zendesk Chat Script-->
    <script>
        window.$zopim || (function (d, s) {
            var z = $zopim = function (c) {
                z._.push(c)
            }, $ = z.s =
                d.createElement(s), e = d.getElementsByTagName(s)[0];
            z.set = function (o) {
                z.set._.push(o)
            };
            z._ = [];
            z.set._ = [];
            $.async = !0;
            $.setAttribute("charset", "utf-8");
            $.src = "https://v2.zopim.com/?4yEs4DtfB8sQvjAuXBcxvxRA3W0gdyfg";
            z.t = +new Date;
            $.type = "text/javascript";
            e.parentNode.insertBefore($, e)
        })(document, "script");
    </script>
    <!--End of Zendesk Chat Script-->
    <script>

        window.addEventListener("scroll", function () {
            var target = document.getElementsByClassName("young-fix");
            if (window.pageYOffset == 0) {
                target[0].style.display = "none";
            } else if (window.pageYOffset > 10) {

                target[0].style.display = "block";
            }
        }, false);
    </script>
    <script>

        (function (h, o, t, j, a, r) {

            h.hj = h.hj || function () {
                (h.hj.q = h.hj.q || []).push(arguments)
            };

            h._hjSettings = {hjid: 1160287, hjsv: 6};

            a = o.getElementsByTagName('head')[0];

            r = o.createElement('script');
            r.async = 1;

            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;

            a.appendChild(r);

        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');

    </script>
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-TLLGSBG');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PDKSTS6');</script>
    <!-- End Google Tag Manager -->


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '184750646120002');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=184750646120002&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TLLGSBG"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDKSTS6"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="wrapper" class="no-pad">
    <?php $this->renderPartial('/layouts/_main_header'); ?>
    <?php $this->renderPartial('/layouts/_main_menu'); ?>
    <div id="main-content" class="">
        <?php echo $content ?>
    </div>
    <div id="bottom_content"></div>
    <?php $this->renderPartial('/layouts/_social'); ?>
    <!-- End id main-content -->
    <?php $this->renderPartial('//layouts/_main_footer'); ?>
</div>
<!-- End wrapper -->

<?php $this->renderPartial('//layouts/_modal_alert'); ?>
<?php $this->renderPartial('//layouts/_modal_warning'); ?>

<?php $this->renderPartial('//layouts/_modal_change_msisdn_prefix'); ?>

<script data-skip-moving="true">
    (function(w,d,u){
        var s=d.createElement('script');s.async=1;s.src=u+'?'+(Date.now()/60000|0);
        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
    })(window,document,'https://ipcc-vnp1.vnptmedia.vn/upload/crm/site_button/loader_9_24jn9r.js');
</script>
</body>
</html>
