<?php
/**
* Model for manage Variables Data
* @author wiliamdecosta@gmail.com
* @version 26/06/2015 14:46:25
*
*/

class Variables extends Abstract_model {
	
	public $table			= "core_variables";
	public $pkey			= "var_id";
	public $alias			= "variables";

	public $fields 			= array(
								'var_id' 		=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID Variable'),
								'var_component'	=> array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Nama Component'),
								'var_name'	    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Nama Variable'),
								'var_value'	    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Nilai Variable'),
								'var_type'	    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Type Variable')		
							);

	public $selectClause 	= "variables.*";
	public $fromClause 		= "core_variables AS variables";
	
	public $refs			= array();
	
	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
	}

	function validate() {
		if($this->actionType == 'CREATE') {
			//do something
			$this->record['var_id'] = $this->generate_id('ifl','core_variables','var_id');
		}else {
			//do something
		}
		return true;
	}
	
	function get_var($var_name) {
	    if(empty($var_name)) return "";
	    
	    $sql = "SELECT var_value FROM core_variables WHERE var_name = '".$var_name."'";       
	    	    
	    $query = $this->db->query($sql);
		$row = array_change_key_case($query->row_array(), CASE_LOWER);
		
		$query->free_result();
		
		return $row['var_value'];
	}
	
	function set_var($var_name, $var_value) {
	    $sql = "UPDATE core_variables
	                SET var_value = '".$var_value."'
	                WHERE var_name = '".$var_name."'";
        
        $query = $this->db->query($sql);

	}
	
	
	function get_theme($user_name, $var_name) {
	    if(empty($var_name)) return "";
	    
	    $sql = "SELECT var_value FROM core_variables WHERE var_name = '".$var_name."'
	                and var_component = '".$user_name."'";       
	    	    
	    $query = $this->db->query($sql);
		$row = array_change_key_case($query->row_array(), CASE_LOWER);
		
		$query->free_result();
		
		return $row['var_value'];
	}
	
	function set_theme($user_name, $var_name, $var_value) {
	    $sql = "SELECT COUNT(1) as total FROM core_variables
	                WHERE var_component = ?
	                AND var_name = ?";
        $query = $this->db->query($sql, array($user_name, $var_name));
		$row = array_change_key_case($query->row_array(), CASE_LOWER);
	    if(empty($row['total'])) {
	        $record = array(
	                        'var_component' => $user_name,
	                        'var_name' => $var_name,
	                        'var_value' => $var_value,
	                        'var_type' => 's'
	                    );
	        $this->actionType = 'CREATE';
	        $this->setRecord($record);
	        $this->create();
	    }else {
            	    
    	    $sql = "UPDATE core_variables
    	                SET var_value = '".$var_value."'
    	                WHERE var_name = '".$var_name."'
    	                AND var_component = '".$user_name."'";
    	                
            $query = $this->db->query($sql);
        }

	}
	
	
}

/* End of file variables.php */
/* Location: ./application/models/variables.php */