<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application extends CI_Controller {
	function index() {
	    check_login();
		$this->load->view('application_board');
	}
}

/* End of file pages.php */
/* Location: ./application/controllers/application.php */