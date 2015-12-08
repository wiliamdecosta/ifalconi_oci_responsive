<?php
/**
* Model for manage P_area Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_user_loket extends Abstract_model {

	public $table			= "p_user_loket";
	public $pkey			= "p_user_loket_id";
	public $alias			= "user_loket";

	public $fields 			= array(
								'p_user_loket_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID User Loket'),
								'p_bank_branch_id'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'ID Bank Branch'),
								'user_name'	            => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'User Name'),
								'user_pwd'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Password'),
								'full_name'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Full Name'),
								'description'           => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Descripiton'),
								'status'                => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Status'),
								'user_level'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'User Level'),
								'client_ip'             => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'IP Client'),
								'exp_pass'              => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Exp. Pass'),
								'total_error_login'     => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Total Error Login'),
								'last_change_pwd'       => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Last Change Password'),
								
								'create_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'create_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'update_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'update_by'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "user_loket.p_user_loket_id, user_loket.p_bank_branch_id, user_loket.user_name, 
                                user_loket.full_name, user_loket.description, user_loket.status as user_loket_status,
                                user_loket.user_level, user_loket.client_ip, to_char(user_loket.exp_pass, 'yyyy-mm-dd') as exp_pass, user_loket.total_error_login, user_loket.last_change_pwd,
                                to_char(user_loket.create_date, 'yyyy-mm-dd') as create_date, 
                                to_char(user_loket.update_date, 'yyyy-mm-dd') as update_date, 
                                user_loket.create_by, user_loket.update_by,
                                bank_branch.code as bank_branch_code";
	public $fromClause 		= "p_user_loket user_loket
	                            LEFT JOIN p_bank_branch bank_branch ON user_loket.p_bank_branch_id = bank_branch.p_bank_branch_id";

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
		    
		    if(empty($this->record['exp_pass'])) {
		        $this->record['exp_pass'] = null;        
		    }
		    
		    if(isset($this->record['user_pwd'])) {
		        if(empty($this->record['user_pwd'])) {
		            throw new Exception('Password required');    
		        }else {
		            if(strlen($this->record['user_pwd']) < 6) {
                        throw new Exception('Password minimum character is 6 characters');
		            }
		            $this->record['user_pwd'] = $this->encrypt_pwd( $this->record['user_pwd'] );
		        }
		    }
		    
			//do something
			$this->record['p_user_loket_id'] = $this->generate_id('ifp','p_user_loket','p_user_loket_id');
            
			$this->record['create_date'] = date('d/m/Y');
            $this->record['create_by'] = $user_name;
            $this->record['update_date'] = date('d/m/Y');
            $this->record['update_by'] = $user_name;
		}else {
		    if(empty($this->record['exp_pass'])) {
		        $this->record['exp_pass'] = null;        
		    }
		    
		    if(isset($this->record['user_pwd'])) {
		        if(empty($this->record['user_pwd'])) {
		            unset($this->record['user_pwd']); 
		        }else {
		            if(strlen($this->record['user_pwd']) < 6) {
                        throw new Exception('Password minimum character is 6 characters');
		            }
		            $this->record['user_pwd'] = $this->encrypt_pwd( $this->record['user_pwd'] );
		        }
		    }
		    
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
	
	function valid_login($user_name, $password) {
	    
	    if(empty($user_name) or empty($password)) return "";
	    
	    $sql = "SELECT p_user_loket_id FROM p_user_loket WHERE user_name = ? AND md5(user_pwd) = ?";
	    $query = $this->db->query($sql, array($user_name, $password));
		$row = $query->row_array();
		
		return $row['p_user_loket_id'];
	}
	
	function encrypt_pwd($text) {
	        
	    $sql = "SELECT encrypt('".$text."') AS hasil";
	    $query = $this->db->query($sql);
		$row = $query->row_array();
		
		return $row['hasil'];
	}

}

/* End of file P_area.php */
/* Location: ./application/models/P_area.php */