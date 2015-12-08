<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class P_application_role_controller
* @version 07/05/2015 12:18:00
*/
class P_menu_controller {

    function read() {

		$start = getVarClean('current','int',0);
    	$limit = getVarClean('rowCount','int',10);

    	$sort = getVarClean('sort','str','listing_no');
    	$dir  = getVarClean('dir','str','ASC');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');
        $parent_id = getVarClean('parent_id', 'int', 0);
        $p_application_id = getVarClean('p_application_id', 'int', 0);

    	$data = array('items' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

    	try {

            $ci = & get_instance();
		    $ci->load->model('adm_sistem/p_menu');
		    $table = $ci->p_menu;

		    //Set default criteria. You can override this if you want
            /*foreach ($table->fields as $key => $field){
                if (!empty($$key)){ // <-- Perhatikan simbol $$
                    if ($field['type'] == 'str'){
                        $table->setCriteria($table->getAlias().$key.$table->likeOperator." '".$$key."' ");
                    }else{
                        $table->setCriteria($table->getAlias().$key." = ".$$key);
                    }
                }
            }*/

            if(!empty($p_application_id)) {
                $table->setCriteria("menu.p_application_id = $p_application_id");
            }

            $table->setCriteria("coalesce(menu.parent_id,0) = $parent_id");


		    if(!empty($searchPhrase)) {
		        $table->setCriteria("( UPPER(menu.code) ".$table->likeOperator." UPPER('%".$searchPhrase."%') )");
		    }

            $start = (($start-1) * $limit) + 1;        
        	$items = $table->getAll($start, $limit, "coalesce(menu.listing_no,999), menu.p_menu_id", $dir);
        	$totalcount = $table->countAll();

        	$data['items'] = $items;
        	$data['success'] = true;
        	$data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

    	return $data;
    }

    function getMenu() {

		$start = getVarClean('current','int',0);
    	$limit = getVarClean('rowCount','int',10);

    	$sort = getVarClean('sort','str','listing_no');
    	$dir  = getVarClean('dir','str','ASC');

        $p_menu_id = getVarClean('p_menu_id', 'int', 0);

    	$data = array('items' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

    	try {

            $ci = & get_instance();
		    $ci->load->model('adm_sistem/p_menu');
		    $table = $ci->p_menu;


            if(!empty($p_menu_id)) {
                $table->setCriteria("menu.p_menu_id = $p_menu_id");
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
		$ci->load->model('adm_sistem/p_menu');
		$table = $ci->p_menu;

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
		$ci->load->model('adm_sistem/p_menu');
		$table = $ci->p_menu;

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
		$ci->load->model('adm_sistem/p_menu');
		$table = $ci->p_menu;

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
}

/* End of file P_menu_controller.php */
/* Location: ./application/libraries/P_menu_controller.php */