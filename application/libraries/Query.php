<?php
class Query
{
	var $CI;
	public function __construct($params = array()){

		$this->CI =& get_instance();
		$this->CI->load->database();
	}

	function chechUsers($email)
	{
		$sql 			= "SELECT * FROM registration where email = '$email'";
		$results 		= $this->CI->db->query($sql);
		$pageMetadata 	= $results->result_array();
		/*
	 print_r($pageMetadata);*/
		return $pageMetadata;
	}
	function chechUsersById($userId)
	{
		$sql 			= "SELECT * FROM registration where userId = '$userId'";
		$results 		= $this->CI->db->query($sql);
		$pageMetadata 	= $results->result_array();
		die($pageMetadata);

		return $pageMetadata;
	}

}
