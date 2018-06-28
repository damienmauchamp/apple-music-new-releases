<?php

require_once("functions.php");

$detect = new Mobile_Detect;
$mobile = $detect->isMobile();

//if ($detect->isMobile())
//    var_dump("mobile");
//if ($detect->isTablet())
//    var_dump("tablette");
//if ($detect->isMobile() && !$detect->isTablet())
//    var_dump("mobile mais pas tablette");
//if ($detect->isiOS())
//    var_dump("iOS");
//if ($detect->isAndroidOS())
//    var_dump("Android");
