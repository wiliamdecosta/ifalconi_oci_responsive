<?php
/**
* Model for manage P_icon Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_icon extends Abstract_model {

	public $table			= "p_icon";
	public $pkey			= "p_icon_id";
	public $alias			= "icon";

	public $fields 			= array(
								'p_icon_id' 		    => array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_icon'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Icon Code'),
								'icon_name'	            => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Icon Name')
							);

	public $selectClause 	= "icon.*";
	public $fromClause 		= "p_icon icon";

	public $refs			= array();

	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
	}

	function validate() {
	    
		if($this->actionType == 'CREATE') {
			//do something
			$this->record['p_icon_id'] = $this->generate_id('ifl','p_icon','p_icon_id');

		}else {
			//do something
		}
		return true;
	}
    
}

/* End of file P_icon.php */
/* Location: ./application/models/P_icon.php */