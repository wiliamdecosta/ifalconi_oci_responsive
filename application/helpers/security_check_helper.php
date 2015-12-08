<?php

function check_login($ws = '') {
	$ci =& get_instance();
	$isLoggedIn = $ci->session->userdata('logged_in');
	
	if(empty($isLoggedIn)) {
		
		if(!empty($ws)) { //request from Web Service (ws.php)
			throw new Exception('Sorry, Your login session has been expired. <br/> Please <a href="'.BASE_URL.'base/index">Login</a> first so that You can access this page. Thank You');
		}else {
			redirect('base/index');
		}
	}
	return true;
}

function check_permission($module_name, $privileges) {
	
}

?>