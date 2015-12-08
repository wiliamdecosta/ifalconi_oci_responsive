<?php
/**
* Model for manage T_deposit Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class T_deposit extends Abstract_model {

	public $table			= "t_deposit";
	public $pkey			= "t_deposit_id";
	public $alias			= "deposit";

	public $fields 			= array(
								't_deposit_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID T_deposit'),
								'trans_no'	        => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Transaction Number'),
								'account_id'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'ID Account'),
								'account_no'	    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Account Number'),
								'subscriber_id'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'ID Subscriber'),
								'trans_date'	    => array('nullable' => false, 'type' => 'date', 'unique' => false, 'display' => 'Trans Date'),
								'service_no'	    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'No Service'),
								'deposit_amount'    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Deposit Amount'),
								'pic_name'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'PIC Name'),
								'bank_branch_id'	=> array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID Bank Branch'),
								'is_returnable'	    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Is Returnable'),
								'cancel_deposit_id'	=> array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID Cancel Deposit'),
								'user_name'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'User Name'),
								'p_user_id'	        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID User'),
								'subs_name'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Subs Name'),
								'subs_number'	    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Subs Number'),
								'subs_address'	    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Subs Address'),
								'subs_zip'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Subs Zip'),
								'subs_active_date'	=> array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Subs Active Date'),
								'subs_inactive_date'    => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Subs Inactive Date'),
								'debtor_segment_code'	=> array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Debtor Segment Code'),
								'subs_segment_code'	=> array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Subs Segment Code'),
								'subs_line_number'	=> array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Subs Line Number'),
								'pm_id'	            => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'ID PM'),
								'pm_code'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'PM Code'),
								'description'	    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
											
								'create_date'	    => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	    => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "deposit.*";
	public $fromClause 		= "v_t_deposit deposit";

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

}

/* End of file T_deposit.php */
/* Location: ./application/models/T_deposit.php */