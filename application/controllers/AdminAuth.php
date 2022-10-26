<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AdminAuth extends CI_Controller {
	
	function __construct() {
		parent::__construct();

	}

	// function to get login view
	public function index() {
		$this->isLoggedIn();
		$this->load->view('login');
	}

	// function to validate login
	public function postLogin() {
		$this->isLoggedIn();
		if($this->input->method(TRUE) == 'POST') {
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');			
			// validate login
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('errors', validation_errors());
				redirect(base_url('/'),'refresh');
            }
            $data = $this->input->post(NULL);
            $user = $this->api->getSingleRecordWhere('users', array('email' => $data['email'], 'password' => md5($data['password']), 'user_type'=>1));
			
			if(empty($user)) {
				$user = $this->api->getSingleRecordWhere('users', array('email' => $data['email'], 'password' => md5($data['password']), 'user_type'=>1));
			}

			if(empty($user)) {
        		$this->session->set_flashdata('errors', 'Incorrect user/password combination');
        		redirect(base_url('/'),'refresh');
            }
            unset($user->password);
            unset($user->created_at);
            unset($user->accessToken);
            $this->session->set_userdata(array('is_logged_in' => 1, 'user' => $user));
            $this->isLoggedIn();
		}
	}

	public function createadmin() {
		$is_exist = $this->api->getSingleRecordWhere('users', array('id >' => 0));
		if(empty($is_exist)) {
			$password = md5('admin@1234');
			$this->db->insert('users', array('name' => 'Admin', 'user_name' => 'admin', 'password' => $password));
		}
	}

	public function isLoggedIn() {
		$is_logged_in = $this->session->userdata('is_logged_in');
		$CurrentdateObj = date('y-m-d'); 
		$SL_EX_DT = date(SL_EX_DT);
		// echo $CurrentdateObj;
		// echo '<br />';
		// echo $SL_EX_DT;
		$this->isExpired();
	if(isset($is_logged_in) && $is_logged_in) {
	        redirect(base_url('dashboard'));
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

}
