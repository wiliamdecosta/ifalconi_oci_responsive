<?php
/**
* Model for manage P_application_role Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_application_role extends Abstract_model {

	public $table			= "p_application_role";
	public $pkey			= "p_application_role_id";
	public $alias			= "application_role";

	public $fields 			= array(
								'p_application_role_id' 	=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_application_role'),
								'p_role_id'	                => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Role'),
							    'p_application_id'	        => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Module'),
							    
								'creation_date'	            => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'created_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By')
							);

	public $selectClause 	= "application_role.p_application_role_id, application_role.p_role_id, application_role.p_application_id, to_char(application_role.creation_date, 'yyyy-mm-dd') as creation_date, 
                                    application_role.created_by,
                                    application.code as application_code";
	public $fromClause 		= "p_application_role application_role
	                                LEFT JOIN p_application application ON application_role.p_application_id = application.p_application_id";

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
			    $query = "SELECT COUNT(1) AS total FROM p_application_role
			                WHERE p_role_id = ? AND p_application_id = ?";
                
                $query = $this->db->query($query, array($this->record['p_role_id'], $this->record['p_application_id']));
		        $row = $query->row_array();
		        if($row['total'] > 0) {
		            throw new Exception("The module has been existed. Please select another module");    
		        }
			}
			
			$this->record['p_application_role_id'] = $this->generate_id('ifl','p_application_role','p_application_role_id');
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

/* End of file P_application_role.php */
/* Location: ./application/models/P_application_role.php */