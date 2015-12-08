<?php
/**
* Model for manage P_user_role Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_user_role extends Abstract_model {

	public $table			= "p_user_role";
	public $pkey			= "p_user_role_id";
	public $alias			= "user_role";

	public $fields 			= array(
								'p_user_role_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_role'),
								'p_user_id'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'User'),
							    'p_role_id'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Role'),
							
								'creation_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'created_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By')
							);

	public $selectClause 	= "user_role.p_user_role_id, user_role.p_user_id, user_role.p_role_id, to_char(user_role.creation_date, 'yyyy-mm-dd') as creation_date, 
                                    user_role.created_by,
                                    role.code as role_code";
	public $fromClause 		= "p_user_role user_role
	                                LEFT JOIN p_role role ON user_role.p_role_id = role.p_role_id";

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
			if(isset($this->record['p_user_id'])) {
			    $query = "SELECT COUNT(1) AS total FROM p_user_role
			                WHERE p_user_id = ? AND p_role_id = ?";
                
                $query = $this->db->query($query, array($this->record['p_user_id'], $this->record['p_role_id']));
		        $row = $query->row_array();
		        if($row['total'] > 0) {
		            throw new Exception("The role has been existed. Please select another role");    
		        }
			}
			
			$this->record['p_user_role_id'] = $this->generate_id('ifl','p_user_role','p_user_role_id');
			$this->record['creation_date'] = date('d/m/Y');
            $this->record['created_by'] = $user_name;
		}else {
			//do something
			
		}
		return true;
	}
    
    function afterWrite() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');
	    
	    if($this->actionType == 'CREATE') {
	        $sql = "UPDATE ".$this->table." 
	                SET creation_date = sysdate,
	                    created_by = '$user_name'
	                WHERE ".$this->pkey." = ".$this->record[$this->pkey];
	    }else {
	        //do nothing
	    }
	    
	    $this->db->query($sql);
	}
}

/* End of file P_role.php */
/* Location: ./application/models/P_role.php */