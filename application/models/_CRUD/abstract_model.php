<?php
/**
* Class Abstract Model for CRUD
* @author Wiliam Decosta <wiliamdecosta@gmail.com>
* @version 1.0
* @date 07/05/2015 12:14:51
*/
class Abstract_model extends  CI_Model {

	/*Table Name*/
	public $table = '';

	/*List of table fields*/
	public $fields = array();

	/*Display fields*/
	public $displayFields = array();

	/*Primary Key*/
	public $pkey = '';

	/*Table Alias*/
	public $alias = '';

	/*Referensi*/
	public $refs = array();

	/*Select Clause*/
	public $selectClause = "";

	/*Select Clause*/
	public $fromClause = "";

		/**
	* Join clause structure
	* array(
	*	array('table_name' => 'table_b AS B', 'on' => 'A.a_id = B.a_id', 'join_type' => 'LEFT'),
	*   array('table_name' => 'table_c AS C', 'on' => 'B.b_id = C.b_id', 'join_type' => 'INNER'),
	*	.
	*	.
	*	.
	*
	* )
	*/
	public $joinClause = array();

	/*Record*/
	public $record = array();

	/*Item*/
	public $item = array();

	/*Criteria (Where Condition)*/
	public $criteria = array();

	/* Criteria sql */
	public $criteriaSQL = false;

	/*Action Type : CREATE || UPDATE*/
	public $actionType = '';


	/*total count*/
	public $totalCount = 0;

	/*Combo Display*/
	public $comboDisplay = array();

	public $likeOperator = '';

	function __construct() {

		parent::__construct();

		if( strtolower($this->db->platform()) == 'mysql' || strtolower($this->db->platform()) == 'oci8' ) {
		    $this->likeOperator = " LIKE ";
		}else if (strtolower($this->db->platform()) == 'postgre') {
		    $this->likeOperator = " ILIKE ";
		}
	}

	public function validate(){} // <-- tobe implemented

	public function beforeWrite(){} // <-- tobe implemented

    public function afterWrite(){} // <-- tobe implemented

	public function getAll($start = 0, $limit = 30, $orderby = '', $ordertype = 'ASC') {
		$this->db->_protect_identifiers = false;

		$this->db->select($this->selectClause);
		$this->db->from($this->fromClause);
		if(count($this->joinClause) > 0) {
			foreach($this->joinClause as $with) {
				if(empty($with['table_name']) or
					empty($with['on']) or empty($with['join_type'])) {
					throw new Exception('Error Join Clause');
				}

				$this->db->join($with['table_name'], $with['on'], $with['join_type']);
			}
		}

		$whereCondition = '';
		$condition = array();
		$condition = $this->getCriteria();

		$whereCondition = join(" AND ", $condition);
		if($whereCondition != "")
		    $this->db->where($whereCondition, null, false);

		if(empty($orderby)) $orderby = $this->pkey;
		$this->db->order_by($orderby, $ordertype);

		if($limit != -1)
			$this->db->limit($limit, $start);


		$queryResult = $this->db->get();
		$items = $queryResult->result_array();

		$queryResult->free_result();

		return $items;

	}

	public function getAlias(){
        if (empty($this->alias)) return '';

        return $this->alias.'.';
    }

    public function getDisplayFieldCriteria($value){
        if (empty($value)) return "";

        if (count($this->comboDisplay) == 0) return "";

        $fields = array();
        for($i=0; $i < count($this->comboDisplay);$i++){
            $fields[$i] = $this->comboDisplay[$i].$this->likeOperator.$this->db->escape('%'.$value.'%');
        }

        $query = implode(" OR ", $fields);

        if (count($fields) > 1) $query = "(".$query.")";

        return $query;
    }

	public function setCriteria($criteria) {
		if(empty($criteria))
			throw new Exception('Empty Condition');

		$this->criteria[] = $criteria;
		return $this->criteria;
	}


	public function getCriteria() {
		return $this->criteria;
	}

	public function getCriteriaSQL(){
        if ($this->criteriaSQL === false){
            $this->criteriaSQL = "";
            if (count($this->criteria)){
                $this->criteriaSQL = "WHERE ".implode(' AND ', $this->criteria);
            }
        }
        return $this->criteriaSQL;
    }

	public function countAll() {
	    $this->db->_protect_identifiers = false;

		$query = "SELECT COUNT(1) AS totalcount FROM ".$this->fromClause;
		if(count($this->joinClause) > 0) {

			foreach($this->joinClause as $with) {
				if(empty($with['table_name']) or
						empty($with['on']) or empty($with['join_type'])) {
						throw new Exception('Error Join Clause');
				}
				$query.= " ".$with['join_type']." JOIN ".$with['table_name']." ON ".$with['on'];
			}
		}

		$whereCondition = '';
		$condition = array();
		$condition = $this->getCriteria();

		$whereCondition = join(" AND ", $condition);
		if(!empty($whereCondition)) {
			$query = $query. " WHERE ".$whereCondition;
		}

		$query = $this->db->query($query);
		$row = array_change_key_case($query->row_array(), CASE_LOWER);

		$query->free_result();


		return $row['totalcount'];
	}

	public function get($id) {
        $this->db->_protect_identifiers = false;

		$this->db->select($this->selectClause, false);
		$this->db->from($this->fromClause);
		if(count($this->joinClause) > 0) {
			foreach($this->joinClause as $with) {
				if(empty($with['table_name']) or
					empty($with['on']) or empty($with['join_type'])) {
					throw new Exception('Error Join Clause');
				}

				$this->db->join($with['table_name'], $with['on'], $with['join_type']);
			}
		}

		if(!empty($this->alias))
			$this->db->where($this->alias.".".$this->pkey, $id, false);
		else
			$this->db->where($this->pkey, $id, false);


		$queryResult = $this->db->get();
		$item = array_change_key_case($queryResult->row_array(), CASE_LOWER);


		return $item;
	}

	public function setRecord($record) {

	    $this->item = $record;
		$this->record = array();

		foreach($this->fields as $key => $field) {

			if ($field['nullable']){
                if (!isset($record[$key])){
                    continue;
                }
            }

			if($this->actionType == 'CREATE') {
				if(isset($field['pkey'])) {
					continue;
				}
			}else {
				if(isset($field['pkey'])) {
					if(empty($record[$this->pkey])) {
						throw new Exception("Required ID for update");
					}
					$this->record[$this->pkey] = $record[$this->pkey];
					continue;
				}

			}

			if($field['nullable'] == false) {
				if($this->actionType == 'CREATE') {
					if(!isset($record[$key]) or $record[$key] == '') {
						throw new Exception($field['display']." required");
					}
				}else {
					if (!isset($record[$key])) continue;
					if($record[$key] == '') {
						throw new Exception($field['display']." required");
					}
				}
			}

			if(!empty($record[$key])) {

				if($field['unique'] === true) {
					if($this->actionType == 'CREATE') {
						if(!$this->isUnique($key, $record[$key])) {
							throw new Exception($field['display']. " must be a unique value");
						}
					}else {
						if(!$this->isUnique($key, $record[$key], $this->record[$this->pkey])) {
							throw new Exception($field['display']. " must be a unique value");
						}
					}
				}

				if($field['type'] == 'str') {
					$record[$key] = htmlentities($record[$key]);
				}elseif($field['type'] == 'int' || $field['type'] == 'float') {
					$value = $record[$key];
					if(!is_numeric($value)) {
						throw new Exception($field['display']." must be a number");
					}
				}elseif($field['type'] == 'date') {
					$date = $record[$key];
					$date = substr(0,10);

					$expDate = explode("-",$record[$key]);
					if(count($expDate) != 3) {
						throw new Exception("Date type invalid : ".$record[$key]);
					}
					if(strlen($expDate[2]) == 4) {//year
						$record[$key] = $expDate[2]."-".$expDate[1]."-".$expDate[0];
					}
				}
			}

			$this->record[$key] = $record[$key];
		}

		return $this->record;
	}


	public function isUnique($field, $value, $exid = false) {
		$type = gettype($value);
		if($type == 'string') {
			$operator = $this->likeOperator;
		}else {
			$operator = ' = ';
		}

		$query = "SELECT COUNT(1) AS isunique FROM ".$this->table. " WHERE ".$field." ".$this->likeOperator.$this->db->escape($value);
		if($exid !== false) {
			$query .= " AND ".$this->pkey." != ".$this->db->escape($exid);
		}

		$query = $this->db->query($query);
		$row = array_change_key_case($query->row_array(), CASE_LOWER);

		$countitems = $row['isunique'];
		$query->free_result();

		if($countitems > 0) return false;

		return true;
	}

	public function create() {
		$this->db->_protect_identifiers = true;
		try {
			$this->validate();

			$this->db->set( $this->record );
			$this->db->insert( $this->table );

			$this->afterWrite();
		}catch(Exception $e) {
			throw $e;
		}

		return true;
	}

	public function update() {
		$this->db->_protect_identifiers = false;
		try {
			$this->validate();

			$this->db->set($this->record, false);
			$this->db->where($this->pkey, $this->record[$this->pkey], false);
			$this->db->update( $this->table );

			$this->afterWrite();
		}catch(Exception $e) {
			throw $e;
		}

		return $this->db->affected_rows();
	}

	public function remove($id) {

		if(empty($id)) throw new Exception("ID is empty");

		if ($this->isRefferenced($id)){
            throw new Exception('ID '.$id.' cannot be removed because it was in reference to another data');
        }

        try {
        	$this->db->where($this->pkey, $id, false);
        	$this->db->delete($this->table);
    	}catch(Exception $e) {
    		throw new Exception($e->getMessage());
    	}
	}

	public function isRefferenced($id){
        if (count($this->refs) == 0) return false;

        foreach ($this->refs as $table => $field){
            $sql = "SELECT COUNT(1) as totalcount FROM ".$table." WHERE ".$field." = $id";
            $query = $this->db->query($sql);
			$row = array_change_key_case($query->row_array(), CASE_LOWER);
			$query->free_result();

            if ($row['totalcount'] > 0) return true;
        }
        return false;
    }

    public function generate_id($owner, $table_name, $col_name) {
        $sql = "SELECT generate_id('$owner','$table_name','$col_name') AS generated_id from dual";
        $query = $this->db->query($sql);
		$row = array_change_key_case($query->row_array(), CASE_LOWER);

		return $row['generated_id'];
    }

}

/* End of file abstract_model.php */
/* Location: ./application/models/abstract_model.php */