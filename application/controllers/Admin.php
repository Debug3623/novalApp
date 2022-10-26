<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once(APPPATH . '/third_party/PHPMailer/src/Exception.php');
require_once(APPPATH . '/third_party/PHPMailer/src/PHPMailer.php');
require_once(APPPATH . '/third_party/PHPMailer/src/SMTP.php');

class Admin extends CI_Controller
{

	public $dateObj;
	public $tbl = 'users';
	public function __construct()
	{
		parent::__construct();
		//$this->load->helper('admin_main_helper');
		$this->dateObj = new DateTime();
	}

	/*
    |----------------------------------------------------------------------
    | load dashboard
    |----------------------------------------------------------------------
    */


	public function index()
	{
		$this->isLoggedIn();
		$this->isExpired();
	    $response['users']= count($this->dashboard->getMultipleRecordWhere("users", array("user_type" => 2)));
        $response['categories']= count($this->dashboard->getMultipleRecordWhere("categories", array()));
        $response['books']= count($this->dashboard->getMultipleRecordWhere("books", array()));
        $response['favorite']= count($this->dashboard->getMultipleRecordWhere("favorite", array()));  
		$response['allbooks'] = $this->api->getLimitBooks();
	
		
		$this->load->view('admin/dashboard',$response);
	}


	
	public function dashboard()
	{
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		$this->isExpired();
		$response["data"] = $this->dashboard->ordersData($_POST);

		echo json_encode($response);
		exit;
	}
	public function dashboardTable()
	{
		header('Content-Type: application/json');
		$response = array();
		$this->isLoggedIn();
		$this->isExpired();
		$response["data"] = $this->dashboard->dashboardData($_POST)["all"];
		echo json_encode($response);
		exit;
	}

	/*
    |----------------------------------------------------------------------
    | Orders 
    |----------------------------------------------------------------------
    */
	public function order($id=null)
	{
		$this->isLoggedIn();
		$this->isExpired();
		$notification_id = $this->input->get('id', TRUE);
		$data = array();
		if($notification_id!=null){
			$notification=$this->api->getSingleRecordWhere("notifications", array("id"=>$notification_id));
			if($notification!=null){
				$data["notification"]=$this->api->getSingleRecordWhere("table_order_status", array("table_id"=>$notification->object_id));
			}
		}

		$this->load->view('orders/save',$data);
	}

	public function orderProfile()
	{
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		$this->isExpired();
		$response["data"] = $this->dashboard->getOrderById($_POST["order_id"]);
		echo json_encode($response);
		exit;
	}

	public function orderstats()
	{
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		$this->isExpired();
		$response["data"] = $this->api->orderProfile($_POST);
		echo json_encode($response);
		exit;
	}
	public function getCart(){
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		$this->isExpired();
		$response["data"] = $this->api->cartOrderProfile($_POST);
		echo json_encode($response);
		exit;
	}
	public function addDishCart(){
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		$response["data"] = $this->api->addDishCart($_POST);
		echo json_encode($response);
		exit;
	}
	public function deleteDishCartItem(){
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		if($_POST['empty_cart']==1){
			unset($_POST["empty_cart"]);
			$isDelete = $this->api->deleteRecord("cart",$_POST);

			// also remove from notifications if there
			$notiData = array('subject_id'=>$_POST['user_id'],'object_id'=>$_POST['table_id'],'object_type'=>'COMPLETE_ORDER');
			$isDelete = $this->api->deleteRecord("notifications",$notiData);
			// echo $this->db->last_query();
			$_POST['is_delete'] = 0;
		}else{
			$isDelete = $this->api->deleteRecord("cart",array("id"=>$_POST["id"]));
			$_POST['is_delete'] = 1;
			unset($_POST["id"]);
		}
		
		// echo $this->db->last_query();
		// exit;
		$response["data"] = $this->api->addDishCart($_POST);
		echo json_encode($response);
		exit;
	}

	public function completeCartOrder(){
		$response = array();
		$response = $this->api->completeCartOrder($_POST);

		if($response["status"]){
			unset($_POST['empty_cart']);
			$_POST['is_delete']=true;
			$response["data"] = $this->api->addDishCart($_POST);
		}
		
		echo json_encode($response);
		exit;
	}

	public function changeOrderStatus()
	{
		$response = $this->api->changeOrderStatus($_POST);
		echo json_encode($response);
		exit;
	}

	/*
    |----------------------------------------------------------------------
    | Earnings - Sales
    |----------------------------------------------------------------------
    */
	public function earnings()
	{
		$this->isLoggedIn();
		$this->load->view('admin/earnings');
	}
	public function sales(){
		header('Content-Type: application/json');
		$response = array();
		$response['status'] = true;
		$this->isLoggedIn();
		$this->isExpired();
		$response["data"] = $this->api->salesData($_POST);
		echo json_encode($response);
		exit;
	}

	public function salesCSV(){
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="sales.csv"');
		header('Content-Type: application/json');
		$data = $this->api->salesTableData();
		$fp = fopen('php://output', 'wb');
		foreach ( $data as $line ) {
			$val = explode(",", $line);
			fputcsv($fp, $val);
		}
		fclose($fp);
	}
	public function salesTableData(){
		header('Content-Type: application/json');
		$response["data"] = $this->api->salesTableData($_POST);
		echo json_encode($response);
		exit;
	}




	/*
    |----------------------------------------------------------------------
    | Expenses
    |----------------------------------------------------------------------
    */
	public function expenses()
	{
		$data = array();
		$jtbls = array(
			array('tbl' => 'expense_categories as ec', 'cond' => 'ec.id=expenses.category_id', 'type' => 'join')
		);
		$fields = "expenses.*,ec.title as category";
		$data["data"] = $this->api->selectJoinTablesMultipleRecord("expenses", $jtbls, null, $fields,"expenses.id","DESC");
		$this->load->view('admin/expenses/view', $data);
	}
	public function addExpense()
	{
		$this->load->view('admin/expenses/save');
	}
	public function editExpense($id)
	{
		$query = $this->api->getSingleRecordWhere("expenses", array('id' => $id));
		$aData['row'] = $query;
		$this->load->view('admin/expenses/save', $aData);
	}

	function updateExpense()
	{
		extract($_POST);
		$PrimaryID = $_POST['id'];
		unset($_POST['action'], $_POST['id']);
		$this->form_validation->set_rules('description', ' Expense Name', 'trim|required');
		$this->form_validation->set_rules('amount', ' Amount', 'trim|required');
		if ($this->form_validation->run() == false) {
			$arr = array("status" => "validation_error", "message" => validation_errors());
			echo json_encode($arr);
		} else {

			if (empty($PrimaryID)) {

				
				if ($this->db->insert("expenses", $_POST) > 0) {
					$result = 1;
				}
			} else {
				$result = $this->api->updateData("expenses", $_POST, array('id' => $PrimaryID));
				if ($result) {
					$result = 2;
				}
			}



			switch ($result) {
				case 1:
					$arr = array('status' => 1, 'message' => "Inserted Succefully !");
					echo json_encode($arr);
					break;
				case 2:
					$arr = array('status' => 2, 'message' => "Updated Succefully !");
					echo json_encode($arr);
					break;
				case 0:
					$arr = array('status' => 0, 'message' => "No New Values to update!");
					echo json_encode($arr);
					break;
				default:
					$arr = array('status' => 0, 'message' => "Not Saved!");
					echo json_encode($arr);
					break;
			}
		}
	}

	public function deleteExpense()
	{
		extract($_POST);
		$result =$this->api->deleteRecord("expenses",array('id'=>$id));
		switch($result){
			case true:
			$catt_id=$id;
			$arr = array('status' => 1,'message' => "Deleted Succefully !");
			echo json_encode($arr);
			break;
			case false:
			$arr = array('status' => 0,'message' => "Not Deleted!");
			echo json_encode($arr);
			break;
			default:
			$arr = array('status' => 0,'message' => "Not Deleted!");
			echo json_encode($arr);
			break;	
		}
	}

		/*
		|----------------------------------------------------------------------
		| settings
		|----------------------------------------------------------------------
		*/
		public function settings()
		{
			$query = $this->api->getSingleRecordWhere("settings", array('id' => 1));
			$aData['row'] = $query;
			$this->load->view('admin/settings', $aData);
		}
		function updateSettings()
		{
			extract($_POST);

			$PrimaryID = $_POST['id'];
			unset($_POST['action'], $_POST['id']);
			$this->form_validation->set_rules('software_house_name', ' Company Name', 'trim|required');

			if ($this->form_validation->run() == false) {
				$arr = array("status" => "validation_error", "message" => validation_errors());
				echo json_encode($arr);
			} else {

				if (empty($PrimaryID)) {

					$result = $this->api->getSingleRecordWhere($this->tbl, array('title' => $title));

					if (count($result) > 0) {
						$arr = array('status' => 0, 'message' => "Category already exists..!");
						echo json_encode($arr);
						exit;
					}

					if ($this->db->insert($this->tbl, $_POST) > 0) {
						$result = 1;
					}
				} else {
					$result = $this->api->updateData("settings", $_POST, array('id' => $PrimaryID));
					if ($result) {
						$result = 2;
					}
				}
				switch ($result) {
					case 1:
						$arr = array('status' => 1, 'message' => "Inserted Succefully !");
						echo json_encode($arr);
						break;
					case 2:
						$arr = array('status' => 2, 'message' => "Updated Succefully !");
						echo json_encode($arr);
						break;
					case 0:
						$arr = array('status' => 0, 'message' => "No New Values to update!");
						echo json_encode($arr);
						break;
					default:
						$arr = array('status' => 0, 'message' => "Not Saved!");
						echo json_encode($arr);
						break;
				}
			}
		}

			/*
		|----------------------------------------------------------------------
		| Export Database
		|----------------------------------------------------------------------
		*/
		public function exportDb($fileName='db_backup.zip')
		{

		$date = date('m/d/Y h:i:s a', time());
	
		$query = $this->api->getSingleRecordWhere("settings", array('id' => 1));
		$restaurant_name = "";
		if($query!=NULL){
			$restaurant_name = $query->restaurant_name;
		}
		$fileName = $restaurant_name."_DATE_".$date."_".$fileName;		
		  // Load the DB utility class
		  $this->load->dbutil();

		  // Backup your entire database and assign it to a variable
		  $backup =$this->dbutil->backup();
	  
		  // Load the file helper and write the file to your server
		  $this->load->helper('file');
		  write_file(FCPATH.'/downloads/'.$fileName, $backup);
	  
		  // Load the download helper and send the file to your desktop
		  $this->load->helper('download');
		  force_download($fileName, $backup);

		}
		
			/*
		|----------------------------------------------------------------------
		| Date Redirect
		|----------------------------------------------------------------------
		*/
		public function expired(){
			$this->load->view('expired');
		}

	/*
    |----------------------------------------------------------------------
    | load all users except admin
    |----------------------------------------------------------------------
    */
	public function getUsers()
	{
		$this->isLoggedIn();
		$fields = "*";
		$data['users'] = $this->api->getMultipleRecordWhere('users', NULL, $fields, "user_type", "ASC");
		$this->load->view('users/users', $data);
	}

	/*
    |----------------------------------------------------------------------
    | load add user view for admin
    |----------------------------------------------------------------------
    */
	public function createUser()
	{
		$this->isLoggedIn();
		$this->load->view('users/edit-user');
	}

	/*
    |----------------------------------------------------------------------
    | load all users except admin
    |----------------------------------------------------------------------
    */
	public function editUser($id)
	{
		$this->isLoggedIn();
		$user = $this->api->getSingleRecordWhere('users', array('id' => $id));
		if (empty($user)) {
			$this->session->set_flashdata('errors', 'Requested user not found');
			redirect(base_url('users'));
		}
		$this->load->view('users/edit-user', array('user' => $user));
	}

	/*
    |----------------------------------------------------------------------
    | load all users except admin
    |----------------------------------------------------------------------
    */
	public function saveUser()
	{
		$this->isLoggedIn();
		$data = $this->input->post(NULL);
		$id = $data['id'];

		$this->form_validation->set_rules('fname', 'lname', 'required|max_length[50]');
		$this->form_validation->set_rules('email', 'Name', 'required|valid_email');
		if (empty($id) || (!empty($data['password']))) {
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
		}

		$this->form_validation->set_rules('user_type', 'User type', 'required');
		// validate login
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('data', $data);
			if (!empty($id)) {
				redirect(base_url('users/edit/' . $id));
			} else {
				redirect(base_url('users/add'));
			}
		}

		// set password
		$ToUpdateData = $data;
		unset($ToUpdateData['id'], $ToUpdateData['confirm_password']);
		if (!empty($data['password'])) {
			$ToUpdateData['password'] = md5($data['password']);
		} else {
			unset($ToUpdateData['password']);
		}

		if (!empty($id)) {
			$is_email_exist = $this->api->getSingleRecordWhere('users', array('id !=' => $id, 'email' => $data['email']));
			if (!empty($is_email_exist)) {
				$this->session->set_flashdata('errors', 'Email already taken, please try another');
				$this->session->set_flashdata('data', $data);
				redirect(base_url('users/edit/' . $id));
			}

			$result = $this->api->updateData('users', $ToUpdateData, array('id' => $id));

			if ($result) {
				$this->session->set_flashdata('success', 'User updated successfully');
				redirect(base_url('users/edit/' . $id));
			} else {
				$this->session->set_flashdata('errors', 'Unable to update user at the moment');
				$this->session->set_flashdata('data', $data);
				redirect(base_url('users/edit/' . $id));
			}
		} else {
			$is_email_exist = $this->api->getSingleRecordWhere('users', array('email' => $data['email']));
			if (!empty($is_email_exist)) {
				$this->session->set_flashdata('errors', 'Email already taken, please try another');
				$this->session->set_flashdata('data', $data);
				redirect(base_url('users/add'));
			}

			$ToUpdateData['accesstoken'] = bin2hex(openssl_random_pseudo_bytes(64));
			$result = $this->api->insertData('users', $ToUpdateData);

			if ($result) {
				$this->session->set_flashdata('success', 'User added successfully');
				redirect(base_url('users/add'));
			} else {
				$this->session->set_flashdata('errors', 'Unable to add user at the moment');
				$this->session->set_flashdata('data', $data);
				redirect(base_url('users/add'));
			}
		}
	}

	/*
    |----------------------------------------------------------------------
    | FUNCTION @deleteUser
    |----------------------------------------------------------------------
    */
	public function deleteUser($id)
	{
		// check request type
		$this->isLoggedIn();
		//Validating Required Params
		$user = $this->api->getSingleRecordWhere('users', array('id' => $id));
		if (empty($user)) {
			$this->session->set_flashdata('errors', 'Requested user not found');
			echo json_encode(array("status"=>false,"Requested user not found"));
		}
		$user_id = $user->id;

		//Assign Orders to Super admin
		$superAdmin = $this->api->getSingleRecordWhere('users', array('user_type' => '1','id !='=>$user_id));
		if(!empty($superAdmin)){
			$updated = $this->api->updateData("orders", array("user_id" => $superAdmin->id), array('user_id' => $user_id));
		}else{
			$this->session->set_flashdata('errors', 'User can not be Deleted without Super Admin Account');
			echo json_encode(array("status"=>false,"User can not be Deleted without Super Admin Account"));
		}
		$is_deleted = $this->api->deleteRecord('users', array('id' => $user_id));

		if ($is_deleted) {
			$this->session->set_flashdata('success', 'Requested user deleted successfully');
			echo json_encode(array("status"=>1,"Requested user deleted successfully"));
		} else {
			$this->session->set_flashdata('errors', 'Unable to delete requested user at the moment');
			echo json_encode(array("status"=>2,"Requested user deleted successfully"));
		}
	}

	



	/*
    |----------------------------------------------------------------------
    | load history against requested user
    |----------------------------------------------------------------------
    */
	public function showUserHistory($id)
	{
		$this->isLoggedIn();
		$user = $this->api->getSingleRecordWhere('users', array('id' => $id));
		if (empty($user)) {
			$this->session->set_flashdata('errors', 'Requested user not found');
			redirect(base_url('users'));
		}
		$jtbls = array(array('tbl' => 'users', 'cond' => 'history.reciever_id=users.id', 'type' => 'join'));
		$fields = "history.id, history.attachments, history.created_at, users.name";
		$data['history'] = $this->api->selectJoinTablesMultipleRecord('history', $jtbls, array('history.reciever_id' => $id), $fields, 'history.created_at', 'desc');
		$this->load->view('user-history', $data);
	}

	/*
    |----------------------------------------------------------------------
    | load total history
    |----------------------------------------------------------------------
    */
	public function showAllHistory()
	{
		$this->isLoggedIn();
		$jtbls = array(array('tbl' => 'users', 'cond' => 'history.reciever_id=users.id', 'type' => 'join'));
		$fields = "history.id, history.attachments, history.created_at, users.name";
		$data['history'] = $this->api->selectJoinTablesMultipleRecord('history', $jtbls, NULL, $fields, 'history.created_at', 'desc');
		$this->load->view('user-history', $data);
	}




	/*
    |----------------------------------------------------------------------
    | send admin password email
    |----------------------------------------------------------------------
    */
	public function adminForgotPassword()
	{
		// check if user exists
		$exists = $this->api->getMultipleRecordWhere('users', array('is_admin' => 1), '*');
		if (count($exists) == 0 || count($exists) > 1) {
			$this->session->set_flashdata('errors', 'Unable to find admin account');
			redirect(base_url('/'), 'refresh');
		}
		$admin = $exists[0];
		$password = rand(10000000, 99999999);
		$updatePassword = md5($password);
		$subject = 'Vocally RESET Password Key';
		$message = "Please login using this password now.\n Password: " . $password;
		$headers = 'From: webmaster@example.com' . "\r\n" .
			'Reply-To: webmaster@example.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		try {
			if (mail($admin->email, $subject, $message, $headers)) {
				$is_updated = $this->api->updateData('users', array('forgot_password' => $updatePassword), array('id' => $admin->id));
				if ($is_updated) {
					$this->session->set_flashdata('success', 'New password has been sent on your email. Please use that password to login in future');
					redirect(base_url('/'), 'refresh');
				} else {
					$this->session->set_flashdata('errors', 'Failed to update password at the moment');
					redirect(base_url('/'), 'refresh');
				}
			} else {
				$this->session->set_flashdata('errors', 'Admin account email does not exist or invalid!');
				redirect(base_url('/'), 'refresh');
			}
		} catch (Exception $ex) {
			$this->session->set_flashdata('errors', 'Unexpected error occured, please try again');
			redirect(base_url('/'), 'refresh');
		}
	}

	/*
    |----------------------------------------------------------------------
    | update admin password view
    |----------------------------------------------------------------------
    */
	public function adminUpdatePassword()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || !$is_logged_in) {
			redirect(base_url('/'));
		}

		$this->load->view('update-password');
	}

	/*
    |----------------------------------------------------------------------
    | save admin password
    |----------------------------------------------------------------------
    */
	public function adminSavePassword()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || !$is_logged_in) {
			redirect(base_url('/'));
		}

		$data = $this->input->post(NULL);
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
		// validate login
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect(base_url('update/password'), 'refresh');
		}

		$user = $this->session->userdata('user');
		$is_updated = $this->api->updateData('users', array('password' => md5($data['password']), 'forgot_password' => ''), array('id' => $user->id));
		if ($is_updated) {
			$user->forgot_password = '';
			$this->session->set_userdata(array('user' => $user));
			redirect(base_url('/'));
		} else {
			$this->session->set_flashdata('errors', 'Unable to update your password. please try again');
			redirect(base_url('update/password'), 'refresh');
		}
	}

	/*
    |----------------------------------------------------------------------
    | loog user out of admin
    |----------------------------------------------------------------------
    */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('/'));
	}

	/*
    |----------------------------------------------------------------------
    | check if user is logged in
    |----------------------------------------------------------------------
    */
	public function isLoggedIn()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || !$is_logged_in) {
			redirect(base_url('/'));
		}

		if (!empty($this->session->userdata('user')->forgot_password)) {
			redirect(base_url('update/password'));
		}
	}
	public function isExpired(){
		$CurrentdateObj = date('y-m-d'); 
		$SL_EX_DT = date(SL_EX_DT);
		if($CurrentdateObj > $SL_EX_DT){
			redirect(base_url('expired'));
			return;
		}
	}
	public function qrCodeGen(){
		$this->load->library('ciqrcode');
		$host=$_SERVER['HTTP_HOST'];
		$url = 'api/';
		if (strpos($host, 'localhost') !== false) {
			$url = 'waitersys/api/';
		}
		$params['data'] = "http://".$host.'/'.$url;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = FCPATH.'tes.png';
		$this->ciqrcode->generate($params);
		$data['image'] = '<img src="'.base_url().'tes.png" />';
		$this->load->view("admin/qrcode",$data);
	}
	
	
}
