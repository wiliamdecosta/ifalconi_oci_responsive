<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Menu_controller
* @version 07/05/2015 12:18:34
*/
class Variables_controller {

    function get_var() {

		$var_name = getVarClean('var_name','str','');
    	
    	$data = array('items' => array(), 'success' => false, 'message' => '');

    	try {

            $ci = & get_instance();
		    $ci->load->model('base/variables');
		    $table = $ci->variables;
            
        	$data['items'] = $table->get_var($var_name);
        	$data['success'] = true;
        	$data['total'] = 1;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

    	return $data;
    }
    
    function set_var() {
        $var_name = getVarClean('var_name','str','');
        $var_value = getVarClean('var_value','str','');
        
        $data = array('items' => array(), 'success' => false, 'message' => '');

    	try {

            $ci = & get_instance();
		    $ci->load->model('base/variables');
		    $table = $ci->variables;
            
            $table->set_var($var_name, $var_value);
            
        	$data['success'] = true;
        	$data['total'] = 1;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

    	return $data;
    }
    
    
    function get_theme() {

		$var_name = getVarClean('var_name','str','');
    	
    	$data = array('items' => array(), 'success' => false, 'message' => '');

    	try {

            $ci = & get_instance();
		    $ci->load->model('base/variables');
		    $table = $ci->variables;
            
            $skin = $table->get_theme($ci->session->userdata('user_name'), $var_name);
        	$data['items'] =  empty($skin) ? "no-skin" : $skin;
        	$data['success'] = true;
        	$data['total'] = 1;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

    	return $data;
    }
    
    function set_theme() {
        $var_name = getVarClean('var_name','str','');
        $var_value = getVarClean('var_value','str','');
        
        $data = array('items' => array(), 'success' => false, 'message' => '');

    	try {

            $ci = & get_instance();
		    $ci->load->model('base/variables');
		    $table = $ci->variables;
            
            $table->set_theme($ci->session->userdata('user_name'), $var_name, $var_value);
            
        	$data['success'] = true;
        	$data['total'] = 1;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

    	return $data;
    }
    
    function set_app_module() {
        
        $ci = & get_instance();
        $module_id = getVarClean('module_id','str','');
        
        $data = array('items' => array(), 'success' => false, 'message' => '');
        try {
            check_login(true);
            
            $userdata = array('p_user_id'	    => $ci->session->userdata('p_user_id'),
        					  'user_name' 	    => $ci->session->userdata('user_name'),
        					  'full_name'       => $ci->session->userdata('full_name'),
        					  'email_address' 	=> $ci->session->userdata('email_address'),
        					  'logged_in'	    => true,
        					  'module_id'         => $module_id
        					  );
        					  
        			  
        	$ci->session->set_userdata($userdata);
        	$data['success'] = true;
        	
        }catch(Exception $e) {
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

}

/* End of file Menu_controller.php */
/* Location: ./application/libraries/base/Menu_controller.php */