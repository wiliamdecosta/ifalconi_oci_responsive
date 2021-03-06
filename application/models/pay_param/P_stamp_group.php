<?php
/**
* Model for manage P_stamp_group Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_stamp_group extends Abstract_model {

	public $table			= "p_stamp_group";
	public $pkey			= "p_stamp_group_id";
	public $alias			= "stamp_group";

	public $fields 			= array(
								'p_stamp_group_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_stamp_group'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Stamp Group Code'),
								'description'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
								
								'create_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "stamp_group.p_stamp_group_id, stamp_group.code, stamp_group.description, to_char(stamp_group.create_date, 'yyyy-mm-dd') as create_date, 
                                    to_char(stamp_group.update_date, 'yyyy-mm-dd') as update_date, stamp_group.create_by, stamp_group.update_by";
	public $fromClause 		= "p_stamp_group stamp_group";

	public $refs			= array('p_stamp' => 'p_stamp_group_id');

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
			$this->record['p_stamp_group_id'] = $this->generate_id('ifp','p_stamp_group','p_stamp_group_id');

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

/* End of file P_stamp_group.php */
/* Location: ./application/models/P_stamp_group.php */