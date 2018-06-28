<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$detect = new Mobile_Detect;

if ($detect->isMobile()) {
    var_dump("mobile");
}

// Any tablet device.
if ($detect->isTablet()) {
    var_dump("tablette");
}

// Exclude tablets.
if ($detect->isMobile() && !$detect->isTablet()) {
    var_dump("mobile mais pas tablette");
}

// Check for a specific platform with the help of the magic methods:
if ($detect->isiOS()) {
    var_dump("iOS");
}

if ($detect->isAndroidOS()) {
    var_dump("Android");
}
var_dump($detect);