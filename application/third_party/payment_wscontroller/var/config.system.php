<?php

error_reporting((E_ALL ^ E_DEPRECATED) & ~E_NOTICE);
/*
    Theme Setting
*/
$sysConfig['Theme.siteTitle'] = 'CCBS PAYMENT';
$sysConfig['Theme.defaultTheme'] = 'default';
$sysConfig['Theme.defaultPage'] = 'default';


/* Web Service Connection */
$sysConfig['WS_SERVER'] = base_ci_url().'/ifalconi_ws/wsdl.php?wsdl';

/*
    Module Setting
*/
$sysConfig['Module.defaultModule'] = 'paymentccbs';
$sysConfig['Module.defaultClass'] = 'paymentccbs';
$sysConfig['Module.defaultMethod'] = 'main';

/* Session Setting */
$sysConfig['Session.Duration'] = 7;
$sysConfig['Session.InactivityTimeout'] = 90;

function base_ci_url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}
