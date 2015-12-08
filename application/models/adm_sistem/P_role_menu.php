<?php
/**
* Model for manage P_role_menu Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_role_menu extends Abstract_model {

	public $table			= "p_role_menu";
	public $pkey			= "p_role_menu_id";
	public $alias			= "role_menu";

	public $fields 			= array(
								'p_role_menu_id' 	=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_role_menu'),
								'p_role_id'	        => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Role'),
							    'p_menu_id'	        => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Menu'),
							    
								'creation_date'	            => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'created_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By')
							);

	public $selectClause 	= "*";
	public $fromClause 		= "v_p_role_menu role_menu";

	public $refs			= array();

	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
	}

	function validate() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');

		if($this->actionType == 'CREATE') {
			//do something
			if(isset($this->record['p_role_id'])) {
			    $query = "SELECT COUNT(1) AS total FROM p_role_menu
			                WHERE p_role_id = ? AND p_menu_id = ?";
                
                $query = $this->db->query($query, array($this->record['p_role_id'], $this->record['p_menu_id']));
		        $row = $query->row_array();
		        if($row['total'] > 0) {
		            throw new Exception("The menu has been existed. Please select another menu");    
		        }
			}
			
			$this->record['p_role_menu_id'] = $this->generate_id('ifl','p_role_menu','p_role_menu_id');
			$this->record['creation_date'] = date('d/m/Y');
            $this->record['created_by'] = $user_name;
		}else {
			//do something
			
		}
		return true;
	}
	
	public function create() {
		$this->db->_protect_identifiers = true;
		try {
			$this->validate();
			//$sql = "SELECT ifl.f_add_role_menu(".$this->record['p_role_id'].",".$this->record['p_menu_id'].",'".$this->record['created_by']."')";
			$sql = " CALL PACK_ROLE_MENU.add_role_menu(".$this->record['p_role_id'].",".$this->record['p_menu_id'].",'".$this->record['created_by']."')";
			$stmt = oci_parse($this->db->conn_id, $sql);
			oci_execute($stmt);
			//$query = $this->db->query($sql);
			
		}catch(Exception $e) {
			throw $e;
		}
                
		return true;
	}
	
	public function remove($id) {
	    
        try {
        	//$sql = "SELECT ifl.f_del_role_menu(".$id.")";
        	$sql = " CALL PACK_ROLE_MENU.del_role_menu(".$id.")";
			$stmt = oci_parse($this->db->conn_id, $sql);
			oci_execute($stmt);
        	
        	//$query = $this->db->query($sql);
    	}catch(Exception $e) {
    		throw new Exception($e->getMessage());
    	}
	}

}

/* End of file P_role_menu.php */
/* Location: ./application/models/P_role_menu.php */