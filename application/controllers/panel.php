<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel extends CI_Controller {

	function index() {
	    if($this->session->userdata('module_id')) {
	        check_login();
		    $this->load->view('index');
	    }else {
	        redirect('application/index');
	    }
		
	}
	
	function load_content($id) {
	    try {
	        $file_exist = true;
	        check_login(true);
	        $file = explode("-", $id);
	        $url_file = "";
	        if(count($file) > 1) {
	            if(strtolower(substr($file[1],-4)) != ".php")
	                $file[1] .= ".php";
	            
	            if(file_exists(APPPATH."views/".$file[0].'/'.$file[1])) {
	                $this->load->view($file[0].'/'.$file[1]);
	            }else {
	                $file_exist = false;   
	            }
	            
	            $url_file = APPPATH."views/".$file[0].'/'.$file[1];
	        }else {
	            if(strtolower(substr($id,-4)) != ".php")
	                $id .= ".php";
	                
	            if(file_exists(APPPATH."views/".$id)) {
	                $this->load->view($id);
	            }else {
	                $file_exist = false;   
	            }
	            
	            $url_file = APPPATH."views/".$id;
	        }
	        
	        if(!$file_exist) {
	            $this->load->view("error_404.php");        
	        }
	        	        
	    }catch(Exception $e) {
	        echo "
    	        <script>
    	            showBootDialog(false,
    	                            BootstrapDialog.TYPE_WARNING,
                                    'Attention',
                                    '".$e->getMessage()."' );
    	        </script>
	        ";
	        exit;
	    }
	}
	
}

/* End of file pages.php */
/* Location: ./application/controllers/panel.php */