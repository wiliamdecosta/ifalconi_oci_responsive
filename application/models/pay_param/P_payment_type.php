<?php
/**
* Model for manage P_payment_type Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_payment_type extends Abstract_model {

	public $table			= "p_payment_type";
	public $pkey			= "p_payment_type_id";
	public $alias			= "payment_type";

	public $fields 			= array(
								'p_payment_type_id' 	=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_payment_type'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Payment Type Code'),
								'description'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
								
								'create_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "payment_type.p_payment_type_id, payment_type.code, payment_type.description, to_char(payment_type.create_date, 'yyyy-mm-dd') as create_date, 
                                    to_char(payment_type.update_date, 'yyyy-mm-dd') as update_date, payment_type.create_by, payment_type.update_by";
	public $fromClause 		= "p_payment_type payment_type";

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
			$this->record['p_payment_type_id'] = $this->generate_id('ifp','p_payment_type','p_payment_type_id');

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

/* End of file P_payment_type.php */
/* Location: ./application/models/P_payment_type.php */