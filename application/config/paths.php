<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| PATHS
| -------------------------------------------------------------------
|
*/
$config['WS_URL'] = define('WS_URL',COM_URL.'index.php/ws/json/');
$config['WS_URL2'] = define('WS_URL2',COM_URL.'index.php/ws/json2/');
$config['BASE_URL'] = define('BASE_URL',COM_URL.'index.php/');
$config['IMAGE_APP_PATH'] = define('IMAGE_APP_PATH',COM_URL.'application/third_party/images/');

$config['BS_PATH'] = define('BS_PATH',COM_URL.'application/third_party/assets/');
$config['BS_CSS_PATH'] = define('BS_CSS_PATH',COM_URL.'application/third_party/assets/css/');
$config['BS_JS_PATH'] = define('BS_JS_PATH',COM_URL.'application/third_party/assets/js/');

$config['PAYMENT_WS_URL'] = define('PAYMENT_WS_URL',COM_URL.'application/third_party/payment_wscontroller/');

/* End of file autoload.php */
/* Location: ./application/config/paths.php */