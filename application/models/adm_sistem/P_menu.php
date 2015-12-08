<?php
/**
* Model for manage P_menu Data
* @author wiliamdecosta@gmail.com
* @version 07/05/2015 12:14:29
*
*/

class P_menu extends Abstract_model {

	public $table			= "p_menu";
	public $pkey			= "p_menu_id";
	public $alias			= "menu";

	public $fields 			= array(
								'p_menu_id' 		    => array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID P_menu'),
								'p_application_id'	    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Module'),
								'code'	                => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Menu Code'),
								'parent_id'	            => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Parent ID'),
								'file_name'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'File Name'),
								'listing_no'	        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Listing No'),
								'is_active'	            => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Is Active'),
								'description'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
                                'menu_icon'	            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Icon'),

								'creation_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Creation Date'),
								'created_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
								'updated_date'	        => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
								'updated_by'	        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')
							);

	public $selectClause 	= "menu.p_menu_id, menu.p_application_id, menu.code, menu.parent_id, menu.file_name,  menu.listing_no, menu.is_active, menu.description, menu.menu_icon, icon.icon_name, to_char(menu.creation_date, 'yyyy-mm-dd') as creation_date,
                                    to_char(menu.updated_date, 'yyyy-mm-dd') as updated_date, menu.created_by, menu.updated_by,
                                    application.code as application_code,
                                    (CASE WHEN menu.is_active = 'N' OR menu.is_active = '' THEN 3
                                    ELSE 4
                                    END) as status
                                    ";
	public $fromClause 		= "p_menu menu
	                            LEFT JOIN p_application application ON menu.p_application_id = application.p_application_id
	                            LEFT JOIN p_icon icon ON menu.menu_icon = icon.code";

	public $refs			= array('p_menu' => 'parent_id',
	                                    'p_role_menu' => 'p_menu_id');

	public $comboDisplay	= array();

	function __construct() {
		parent::__construct();
	}

	function validate() {
	    $ci =& get_instance();
	    $user_name = $ci->session->userdata('user_name');

		if($this->actionType == 'CREATE') {
			//do something

            if(!isset($this->record['parent_id'])) {
                $this->record['parent_id'] = null;
            }
            $this->record['p_menu_id'] = $this->generate_id('ifl','p_menu','p_menu_id');

			$this->record['creation_date'] = date('d/m/Y');
            $this->record['created_by'] = $user_name;
            $this->record['updated_date'] = date('d/m/Y');
            $this->record['updated_by'] = $user_name;
		}else {
			//do something
			if(isset($this->record['parent_id']) && empty($this->record['parent_id'])) {
                $this->record['parent_id'] = null;
            }
            
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

	public function getMenuItems($is_admin, $p_application_id, $p_user_id) {
	    if($is_admin) {
	        $sql = "select p_menu_id as p_menu_id, nvl (parent_id, 0) parent_id, menu, menu title, '#' link, file_name, description, listing_no, menu_icon "
			."from (select p_menu_id, parent_id, code as menu, nvl (file_name, '-') as file_name,"
			."	description, listing_no, menu_icon "
			."	from p_menu "
			."	where is_active = 'Y' "
			."	and p_application_id = ".$p_application_id." "
			." start with nvl(parent_id,0) = 0 connect by prior p_menu_id = parent_id order siblings by nvl(listing_no, 9999))";
			
	    }else {
	        
		    $sql = "select p_menu_id as p_menu_id, nvl (parent_id, 0) parent_id, menu,  menu as title, '#' as link, file_name, description, listing_no, menu_icon "
			."from (select p_menu_id, parent_id, code as menu, nvl (file_name, '-') as file_name,"
			."	description, listing_no, menu_icon "
			."	from p_menu "
			."	where is_active = 'Y' "
			."	and p_application_id = ".$p_application_id." "
			."	and p_menu_id in ( "
			."	select rm.p_menu_id "
			."	from p_role_menu rm, p_user_role ur, p_user u "
			."	where rm.p_role_id = ur.p_role_id "
			."	and ur.p_user_id = u.p_user_id "
			."	and ur.p_user_id = " . $p_user_id . ") "
			." start with nvl(parent_id,0) = 0 connect by prior p_menu_id = parent_id order siblings by nvl(listing_no, 9999))";
	    }
	    
	    
        $query = $this->db->query($sql);
        $items = $query->result_array();
        return $items;
        
	}

}

/* End of file P_menu.php */
/* Location: ./menu/models/P_menu.php */