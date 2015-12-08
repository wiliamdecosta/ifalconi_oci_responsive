<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class User_controller
* @version 07/05/2015 12:20:52
*/
class P_user_controller {
    
    function read() {
		
		$start = getVarClean('current','int',0);
    	$limit = getVarClean('rowCount','int',10);

    	$sort = getVarClean('sort','str','p_user_id');
    	$dir  = getVarClean('dir','str','DESC');
    	
        $searchPhrase = getVarClean('searchPhrase', 'str', '');
        $p_user_id = getVarClean('p_user_id', 'int', 0);
    	       
    	$data = array('items' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

    	try {
            
            $ci = & get_instance();
		    $ci->load->model('adm_sistem/p_user');
		    $table = $ci->p_user;
		    
		    //Set default criteria. You can override this if you want
            foreach ($table->fields as $key => $field){
                if (!empty($$key)){ // <-- Perhatikan simbol $$
                    if ($field['type'] == 'str'){
                        $table->setCriteria("UPPER(".$table->getAlias().$key.")".$table->likeOperator." UPPER('".$$key."') ");
                    }else{
                        $table->setCriteria($table->getAlias().$key." = ".$$key);
                    }
                }
            }
		    
		    if(!empty($searchPhrase)) {
		        $table->setCriteria("( UPPER(p_user.user_name) ".$table->likeOperator." UPPER('%".$searchPhrase."%') )"); 
		    }
		      
            $start = (($start-1) * $limit) + 1;
        	$items = $table->getAll($start, $limit, $sort, $dir);
        	$totalcount = $table->countAll();
    
        	$data['items'] = $items;
        	$data['success'] = true;
        	$data['total'] = $totalcount;
        	
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

    	return $data;
    }


    function create() {

    	$ci = & get_instance();
		$ci->load->model('adm_sistem/p_user');
		$table = $ci->p_user;
				
		$data = array('items' => array(), 'success' => false, 'message' => '');

		$jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

		$table->actionType = 'CREATE';
		$errors = array();

		if (isset($items[0])){
			$numItems = count($items);
			for($i=0; $i < $numItems; $i++){
        		try{
        		    
        		    $table->db->trans_begin(); //Begin Trans
                	
                    	$table->setRecord($items[$i]);
                    	$table->create();
            		            		
            		$table->db->trans_commit(); //Commit Trans
            		
        		}catch(Exception $e){
        		    
        		    $table->db->trans_rollback(); //Rollback Trans
        			$errors[] = $e->getMessage();
        		}
        	}

        	$numErrors = count($errors);
        	if ($numErrors > 0){
        		$data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
        	}else{
        		$data['success'] = true;
        		$data['message'] = 'Data added successfully';
        	}
        	$data['items'] =$items;
		}else {

			try{
			    $table->db->trans_begin(); //Begin Trans
			    	
        	        $table->setRecord($items);
            	    $table->create();
                
                $table->db->trans_commit(); //Commit Trans
                
    	        $data['success'] = true;
    	        $data['message'] = 'Data added successfully';
        
	        }catch (Exception $e) {
	            $table->db->trans_rollback(); //Rollback Trans
	            
	            $data['message'] = $e->getMessage();
                $data['items'] = $items;
	        }

		}
		return $data;

    }

    function update() {

    	$ci = & get_instance();
		$ci->load->model('adm_sistem/p_user');
		$table = $ci->p_user;

		$data = array('items' => array(), 'success' => false, 'message' => '');

		$jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'UPDATE';

        if (isset($items[0])){
        	$errors = array();
			$numItems = count($items);
			for($i=0; $i < $numItems; $i++){
        		try{
        		    $table->db->trans_begin(); //Begin Trans
        		    
                		$table->setRecord($items[$i]);
                		$table->update();
                		
                    $table->db->trans_commit(); //Commit Trans
                    
            		$items[$i] = $table->get($items[$i][$table->pkey]);
        		}catch(Exception $e){
        		    $table->db->trans_rollback(); //Rollback Trans
        		    
        			$errors[] = $e->getMessage();
        		}
        	}

        	$numErrors = count($errors);
        	if ($numErrors > 0){
        		$data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
        	}else{
        		$data['success'] = true;
        		$data['message'] = 'Data update successfully';
        	}
        	$data['items'] =$items;
		}else {

			try{
			    $table->db->trans_begin(); //Begin Trans
			    
    	        	$table->setRecord($items);
        	        $table->update();
                
                $table->db->trans_commit(); //Commit Trans
                
    	        $data['success'] = true;
    	        $data['message'] = 'Data update successfully';

	            $data['items'] = $table->get($items[$table->pkey]);
	        }catch (Exception $e) {
	            $table->db->trans_rollback(); //Rollback Trans
	            
	            $data['message'] = $e->getMessage();
                $data['items'] = $items;
	        }

		}
		return $data;

    }

    function destroy() {
    	$ci = & get_instance();
		$ci->load->model('adm_sistem/p_user');
		$table = $ci->p_user;

		$data = array('items' => array(), 'success' => false, 'message' => '');

		$jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

		try{
		    $table->db->trans_begin(); //Begin Trans
		    
			$total = 0;
            if (is_array($items)){
                foreach ($items as $key => $value){
                    if (empty($value)) throw new Exception('Empty parameter');

                    $table->remove($value);
                    $data['items'][] = array($table->pkey => $value);
                    $total++;
                }
            }else{
                $items = (int) $items;
                if (empty($items)){
                    throw new Exception('Empty parameter');
                }

                $table->remove($items);
                $data['items'][] = array($table->pkey => $items);
                $data['total'] = $total = 1;
            }

            $data['success'] = true;
            $data['message'] = $total.' Data deleted successfully';
            
            $table->db->trans_commit(); //Commit Trans
            
        }catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['items'] = array();
            $data['total'] = 0;
        }

        return $data;

    }
    
    function getInfo() {

    	$ci =& get_instance();
	    $uid = $ci->session->userdata('p_user_id');

        $data = array('data' => array(), 'success' => false, 'message' => '');

        try{
            if (empty($uid)){
                throw new Exception('Bad Params : Empty UserID');
            }

            $ci = & get_instance();
		    $ci->load->model('adm_sistem/p_user');
		    $table = $ci->p_user;

            $data['data'] = $table->get((int)$uid);
            $data['success'] = true;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
        return $data;

    }

    function updateInfo() {

		$user_password1 = trim(getVarClean('user_password1', 'str', ''));
		$user_password2 = trim(getVarClean('user_password2', 'str', ''));

		$user_email = trim(getVarClean('user_email', 'str', ''));
		$user_realname = trim(getVarClean('user_realname', 'str', ''));

        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');

    	$ci =& get_instance();
	    $uid = (int)$ci->session->userdata('p_user_id');

        try{
            if (empty($uid)){
                throw new Exception('Bad Params : Empty UserID');
            }

	        $ci->load->model('adm_sistem/p_user');
	        $table = $ci->p_user;
	        
	        $table->actionType = 'UPDATE';

	        $record = array('p_user_id' => $uid,
	                        'email_address' => $user_email,
	                        'full_name' => $user_realname);

            if (!empty($user_password1)){
                if (strcmp($user_password1, $user_password2) != 0) throw new Exception("Your password does not match. Please check again.");

                if (strlen($user_password1) < 5) throw new Exception("Mininum password length is 5 characters");

                $record['user_pwd'] = $user_password1;
	        }
	        
	        if(!empty($user_email)) {
	            if(!isValidEmail($user_email)) {
                    throw new Exception("Your email address format is incorrect");    	                
	            }    
	        }

	        $table->setRecord($record);
	        $table->update();
            
            $userdata = array('p_user_id'	=> $record['p_user_id'],
						  'user_name' 	    => $ci->session->userdata('user_name'),
						  'full_name'       => $record['full_name'],
						  'email_address' 	=> $record['email_address'],
						  'logged_in'	    => true
						  );
						  						  
			$ci->session->set_userdata($userdata);
			
	        $data['success'] = true;
	        $data['message'] = 'Update Profile Success';

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
        return $data;

    }


}

/* End of file User_controller.php */
/* Location: ./application/libraries/P_user_controller.php */