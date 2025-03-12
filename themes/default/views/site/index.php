<?php
    $detect   = new MyMobileDetect();
    $isMobile = $detect->isMobile();
    if ($isMobile) {
        $this->renderPartial('/layouts/_mobile_banner');
        $this->renderPartial('/site/_mobile_index');
    } else {
        $this->renderPartial('/layouts/_main_banner');
        $this->renderPartial('/site/_desktop_index');
    }
//    $this->renderPartial('/layouts/_modal_promotion', array('isMobile' => $isMobile));
?>