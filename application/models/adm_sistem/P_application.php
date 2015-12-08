<?php
/**
* Model for manage P_application Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_application extends Abstract_model {

	public $table			= "p_application";
	public $pkey			= "p_application_id";
	public $alias			= "application";

	public $fields 			= array(
								'p_application_id' 		    => array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_module'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Module Code'),
								'description'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
								'listing_no'	        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Listing No'),
								'is_active'	            => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Is Active'),								
								
								'creation_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'created_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'updated_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'updated_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "application.p_application_id, application.code, application.is_active, application.listing_no, application.description, to_char(application.creation_date, 'yyyy-mm-dd') as creation_date, 
                                    to_char(application.updated_date, 'yyyy-mm-dd') as updated_date, application.created_by, application.updated_by";
	public $fromClause 		= "p_application application";

	public $refs			= array('p_application_role' => 'p_application_id',
	                                 'p_menu' => 'p_application_id');

	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
	}

	function validate() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');

		if($this->actionType == 'CREATE') {
			//do something
			$this->record['p_application_id'] = $this->generate_id('ifl','p_application','p_application_id');

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

/* End of file P_application.php */
/* Location: ./application/models/P_application.php */