<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base extends CI_Controller {

	function index() {

	    /* jika sudah login */
	    if($this->session->userdata('logged_in')) {
	        //go to default page
			redirect($this->config->item('default_page'));
	    }

		$data = array();
		$data['login_url'] = BASE_URL."base/login";
		$this->load->view('base/login', $data);
	}

	function login() {
	    
		if($this->session->userdata('logged_in')) {
		    //go to default page
			redirect($this->config->item('default_page'));
		}

		$username = $this->input->post('uname');
		$password  = $this->input->post('password');

        $data = array();
		if(empty($username) or empty($password)) {
		    $data['errormsg'] = 'Please Enter Your Username and Password';
			$this->load->view('base/login', $data);
			return;
		}

		$password = md5($password);

		$query = "SELECT * FROM p_user WHERE user_name = ".$this->db->escape($username)." AND user_pwd = ".$this->db->escape($password);

		$query = $this->db->query($query);
		$row = array_change_key_case($query->row_array(), CASE_LOWER);

		if(empty($row['p_user_id'])) {
			$data['errormsg'] = 'Your Username or Password is Incorrect, Please check again.';
			$this->load->view('base/login', $data);
			return;
		}elseif($row['user_status'] != 1){
		    $data['errormsg'] = "Sorry, The user '".$username."' is not active. Please contact your Administrators.";
			$this->load->view('base/login', $data);
			return;
		}else {
			$userdata = array('p_user_id'	=> $row['p_user_id'],
						  'user_name' 	    => $row['user_name'],
						  'full_name'       => $row['full_name'],
						  'email_address' 	=> $row['email_address'],
						  'logged_in'	    => true,
						  'module_id'       => ''
						  );
            
			$this->session->set_userdata($userdata);
			//go to default page
			redirect($this->config->item('default_page'));
		}

	}

	function logout() {

		$userdata = array('p_user_id'     => '',
						  'user_name' 	  => '',
						  'full_name' 	  => '',
						  'email_address' => '',
						  'logged_in' 	  => '',
						  'module_id'     => ''
						  );

		$this->session->unset_userdata($userdata);
		$this->session->sess_destroy();
		redirect('base/index');
	}
}

/* End of file pages.php */
/* Location: ./application/controllers/base.php */