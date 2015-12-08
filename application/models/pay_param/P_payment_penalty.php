<?php
/**
* Model for manage P_payment_penalty Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_payment_penalty extends Abstract_model {

	public $table			= "p_payment_penalty";
	public $pkey			= "p_payment_penalty_id";
	public $alias			= "payment_penalty";

	public $fields 			= array(
								'p_payment_penalty_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_payment_penalty'),
								'p_penalty_group_id'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'ID Stamp Group'),
								'month_late'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Month Late'),
								'day_low_limit'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Day Low Limit'),
								'day_up_limit'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Day Up Limit'),
								'penalty_amount'	        => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Penalty Amount'),
								'penalty_pct'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Penalty Pct'),
								'added_amount'	            => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Added Amount'),
								'valid_from'	            => array('nullable' => false, 'type' => 'date', 'unique' => false, 'display' => 'Valid From'),
								'valid_to'	                => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Valid To'),
								'description'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),

								'create_date'	            => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	                => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	            => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	                => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "payment_penalty.p_payment_penalty_id, payment_penalty.p_penalty_group_id,
	                                payment_penalty.month_late, payment_penalty.day_low_limit, payment_penalty.day_up_limit,
	                                payment_penalty.penalty_amount, payment_penalty.penalty_pct, payment_penalty.added_amount,
	                                to_char(payment_penalty.valid_from, 'yyyy-mm-dd') as valid_from, to_char(payment_penalty.valid_to, 'yyyy-mm-dd') as valid_to,

	                                payment_penalty.description, to_char(payment_penalty.create_date, 'yyyy-mm-dd') as create_date,
                                    to_char(payment_penalty.update_date, 'yyyy-mm-dd') as update_date, payment_penalty.create_by, payment_penalty.update_by";
	public $fromClause 		= "p_payment_penalty payment_penalty";

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
			$this->record['p_payment_penalty_id'] = $this->generate_id('ifp','p_payment_penalty','p_payment_penalty_id');

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

/* End of file P_payment_penalty.php */
/* Location: ./application/models/P_payment_penalty.php */