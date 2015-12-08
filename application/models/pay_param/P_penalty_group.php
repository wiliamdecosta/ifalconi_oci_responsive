<?php
/**
* Model for manage P_penalty_group Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_penalty_group extends Abstract_model {

	public $table			= "p_penalty_group";
	public $pkey			= "p_penalty_group_id";
	public $alias			= "penalty_group";

	public $fields 			= array(
								'p_penalty_group_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_penalty_group'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Penalty Group Code'),
								'description'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
								
								'create_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "penalty_group.p_penalty_group_id, penalty_group.code, penalty_group.description, to_char(penalty_group.create_date, 'yyyy-mm-dd') as create_date, 
                                    to_char(penalty_group.update_date, 'yyyy-mm-dd') as update_date, penalty_group.create_by, penalty_group.update_by";
	public $fromClause 		= "p_penalty_group penalty_group";

	public $refs			= array('p_payment_penalty' => 'p_penalty_group_id');

	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('ifp_db', TRUE); // <-- Please Modified This : ifp_db,ifb_db,ifc_db
	}

	function validate() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');

		if($this->actionType == 'CREATE') {
			//do something
			$this->record['p_penalty_group_id'] = $this->generate_id('ifp','p_penalty_group','p_penalty_group_id');

			$this->record['create_date'] = date('d/m/Y');
            $this->record['create_by'] = $user_name;
            $this->record['update_date'] = date('d/m/Y');
            $this->record['update_by'] = $user_name;
		}else {
			//do something
			$this->record['update_date'] = date('d/m/Y');
            $this->record['update_by'] = $user_name;
		}
		return true;
	}
	
	function afterWrite() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');
	    
	    if($this->actionType == 'CREATE') {
	        $sql = "UPDATE ".$this->table." 
	                SET create_date = sysdate,
	                    create_by = '$user_name',
	                    update_date = sysdate,
	                    update_by = '$user_name'
	                WHERE ".$this->pkey." = ".$this->record[$this->pkey];
	    }else {
	        $sql = "UPDATE ".$this->table." 
	                SET update_date = sysdate,
	                    update_by = '$user_name'
	                WHERE ".$this->pkey." = ".$this->record[$this->pkey];
	    }
	    
	    $this->db->query($sql);
	}

}

/* End of file P_penalty_group.php */
/* Location: ./application/models/P_penalty_group.php */