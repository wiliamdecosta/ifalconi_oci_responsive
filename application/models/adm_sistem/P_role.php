<?php
/**
* Model for manage P_role Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_role extends Abstract_model {

	public $table			= "p_role";
	public $pkey			= "p_role_id";
	public $alias			= "role";

	public $fields 			= array(
								'p_role_id' 		    => array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_role'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Role Code'),
								'is_active'	            => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Is Active'),								
								'description'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
								
								'creation_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'created_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'updated_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'updated_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "role.p_role_id, role.code, role.is_active, role.description, to_char(role.creation_date, 'yyyy-mm-dd') as creation_date, 
                                    to_char(role.updated_date, 'yyyy-mm-dd') as updated_date, role.created_by, role.updated_by";
	public $fromClause 		= "p_role role";

	public $refs			= array('p_role_menu' => 'p_role_id',
	                                'p_application_role' => 'p_role_id');

	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
	}

	function validate() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');

		if($this->actionType == 'CREATE') {
			//do something
			$this->record['p_role_id'] = $this->generate_id('ifl','p_role','p_role_id');

			$this->record['creation_date'] = date('d/m/Y');
            $this->record['created_by'] = $user_name;
            $this->record['updated_date'] = date('d/m/Y');
            $this->record['updated_by'] = $user_name;
		}else {
			//do something
			$this->record['updated_date'] = date('d/m/Y');
            $this->record['updated_by'] = $user_name;
		}
		return true;
	}
	
	function afterWrite() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');
	    
	    if($this->actionType == 'CREATE') {
	        $sql = "UPDATE ".$this->table." 
	                SET creation_date = sysdate,
	                    created_by = '$user_name',
	                    updated_date = sysdate,
	                    updated_by = '$user_name'
	                WHERE ".$this->pkey." = ".$this->record[$this->pkey];
	    }else {
	        $sql = "UPDATE ".$this->table." 
	                SET updated_date = sysdate,
	                    updated_by = '$user_name'
	                WHERE ".$this->pkey." = ".$this->record[$this->pkey];
	    }
	    
	    $this->db->query($sql);
	}

}

/* End of file P_role.php */
/* Location: ./application/models/P_role.php */