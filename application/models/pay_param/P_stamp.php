<?php
/**
* Model for manage P_stamp Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_stamp extends Abstract_model {

	public $table			= "p_stamp";
	public $pkey			= "p_stamp_id";
	public $alias			= "stamp";

	public $fields 			= array(
								'p_stamp_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_stamp'),
								'p_stamp_group_id'	=> array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'ID Stamp Group'),
								'valid_from'	    => array('nullable' => false, 'type' => 'date', 'unique' => false, 'display' => 'Valid From'),
								'valid_to'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Valid To'),
								'amt_low_limit'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Low Limit Amount'),
								'amt_up_limit'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Up Limit Amount'),
								'stamp_amount'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Stamp Amount'),
								'description'	    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
																
								'create_date'	    => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	    => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "stamp.p_stamp_id, stamp.p_stamp_group_id, to_char(stamp.valid_from, 'yyyy-mm-dd') as valid_from, to_char(stamp.valid_to, 'yyyy-mm-dd') as valid_to, 
	                                stamp.amt_low_limit, stamp.amt_up_limit, stamp.stamp_amount,
	                                stamp.description, to_char(stamp.create_date, 'yyyy-mm-dd') as create_date, 
                                    to_char(stamp.update_date, 'yyyy-mm-dd') as update_date, stamp.create_by, stamp.update_by";
	public $fromClause 		= "p_stamp stamp";

	public $refs			= array();

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
			$this->record['p_stamp_id'] = $this->generate_id('ifp','p_stamp','p_stamp_id');
            
            if(isset($this->record['valid_to']) and empty($this->record['valid_to'])) {
                $this->record['valid_to'] = null;        
            }
            
			$this->record['create_date'] = date('d/m/Y');
            $this->record['create_by'] = $user_name;
            $this->record['update_date'] = date('d/m/Y');
            $this->record['update_by'] = $user_name;
		}else {
			//do something
			
			if(isset($this->record['valid_to']) and empty($this->record['valid_to'])) {
                $this->record['valid_to'] = null;        
            }
            
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

/* End of file P_stamp.php */
/* Location: ./application/models/P_stamp.php */