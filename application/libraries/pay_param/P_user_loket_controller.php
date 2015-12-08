<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class P_user_loket_controller
* @version 07/05/2015 12:18:00
*/
class P_user_loket_controller {

    function read() {
		
		$start = getVarClean('current','int',0);
    	$limit = getVarClean('rowCount','int',10);

    	$sort = getVarClean('sort','str','p_user_loket_id');
    	$dir  = getVarClean('dir','str','DESC');
    	
        $searchPhrase = getVarClean('searchPhrase', 'str', '');
        $p_user_loket_id = getVarClean('p_user_loket_id', 'int', 0);
        $p_bank_branch_id = getVarClean('p_bank_branch_id', 'int', 0);
        
    	       
    	$data = array('items' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

    	try {
            
            $ci = & get_instance();
		    $ci->load->model('pay_param/p_user_loket');
		    $table = $ci->p_user_loket;
		    
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
		        $table->setCriteria("( UPPER(user_loket.user_name) ".$table->likeOperator." UPPER('%".$searchPhrase."%') OR UPPER(user_loket.full_name) ".$table->likeOperator." UPPER('%".$searchPhrase."%') )"); 
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
		$ci->load->model('pay_param/p_user_loket');
		$table = $ci->p_user_loket;
				
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
		$ci->load->model('pay_param/p_user_loket');
		$table = $ci->p_user_loket;

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
		$ci->load->model('pay_param/p_user_loket');
		$table = $ci->p_user_loket;

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
    
    /*function login_payment() {
        
        $ci = & get_instance();
		$ci->load->model('pay_param/p_user_loket');
		$table = $ci->p_user_loket;

		$data = array('items' => array(), 'success' => false, 'message' => '');

		$user_name = getVarClean('user_name', 'str', '');
        $password = getVarClean('password', 'str', '');

		try{
            $p_user_loket_id = $table->valid_login($user_name, $password);
            if(empty($p_user_loket_id)) {
                throw new Exception("Your username or password is incorrect or not valid anymore.");    
            }
            $data['success'] = true;
            $data['items'] = $p_user_loket_id;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['total'] = 0;
        }

        return $data;
    }*/
    
    function login_payment() {
        
        
		$data = array('items' => array(), 'success' => false, 'message' => '');

		$user_name = getVarClean('user_name', 'str', '');
        $password = getVarClean('password', 'str', '');

		try{
            $data = file_get_contents(PAYMENT_WS_URL.'ws.php?type=json&module=paymentccbs&class=p_user_loket&method=valid_login&user_name='.$user_name.'&password='.$password);
            $data = json_decode($data, true);
            
            $p_user_loket_id = $data['rows'];
            
            if( $p_user_loket_id < 0 and $p_user_loket_id == -11 ) {
                $data['success'] = false;
                throw new Exception("Your password has been expired. Please contact Your administrators.");           
            }else if( $p_user_loket_id < 0 or empty($p_user_loket_id) ) {
                $data['success'] = false;
                throw new Exception("Your username or password is incorrect");
            }
            
            $data['success'] = true;
            $data['items'] = $p_user_loket_id;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['total'] = 0;
        }

        return $data;
    }
    
}

/* End of file P_user_loketcontroller.php */
/* Location: ./application/libraries/P_user_loketcontroller.php */