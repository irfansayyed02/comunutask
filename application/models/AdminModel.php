<?php 
		
	class AdminModel extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function CheckUserExist($TableName, $Condition)
		{			
			$this->db->where($Condition);
			$query = $this->db->order_by('id',"DESC");    
			$query = $this->db->limit(1);    
			$query = $this->db->get($TableName);    
			if($query->num_rows() > 0)
			return $query->row_array();	
		}

		public function GetSingleTable($table)
		{			
			$query = $this->db->get($table);
			return $query->result();
		}

		public function InsertCommon($TableName, $InsertArray)
		{
		    $insertRecord = $this->db->insert($TableName, $InsertArray);
			return $insertRecord;
		}
		
		public function DoLogin($LoginArray) 
		{
			$condition = "useremail="."'".$LoginArray['useremail']."' AND "."password="."'".$LoginArray['password']."'";
			$this->db->where($condition);
			$query = $this->db->get('users');

			if ($query->num_rows() > 0) 
			{
				$result=$query->row_array();
				return $result;
			} 
			else 
			{
				return false;
			}
		}

	

}

?>