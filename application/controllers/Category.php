<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }
  public $view = "categories/view";
  public $tbl = 'categories';


  public function index()
  {
    $this->isLoggedIn();
    $aData['data'] = $this->db->get($this->tbl);
    $this->load->view($this->view, $aData);
  }


  public function add()
  {
    $this->load->view('categories/save');
  }

  public function edit($id)
  {
    $query = $this->api->getSingleRecordWhere($this->tbl, array('id' => $id));
    $aData['row'] = $query;
    $this->load->view('categories/save', $aData);
  }


  public function delete()
  {
    extract($_POST);
    $result = $this->api->deleteRecord($this->tbl, array('id' => $id));
    switch ($result) {
      case true:
        $catt_id = $id;
        $arr = array('status' => 1, 'message' => "Deleted Succefully !");
        echo json_encode($arr);
        break;
      case false:
        $arr = array('status' => 0, 'message' => "Not Deleted!");
        echo json_encode($arr);
        break;
      default:
        $arr = array('status' => 0, 'message' => "Not Deleted!");
        echo json_encode($arr);
        break;
    }
  }


  function save()
  {
    extract($_POST);
    $PrimaryID = $_POST['id'];
    unset($_POST['action'], $_POST['id']);
    $this->form_validation->set_rules('title', ' title', 'trim|required');
    if ($this->form_validation->run() == false) {

      $arr = array("status" => "validation_error", "message" => validation_errors());
      echo json_encode($arr);
    } else {
      $_POST['title'] = ucfirst($_POST['title']);
      $_POST['titleAr'] = ucfirst($_POST['titleAr']);

    ///  $_POST['titleAr'] = ucfirst($_POST['titleAr']);

      /*===============================================*/

      // if (!empty($_FILES)) {
      //   //pre($_FILES);
      //   $config['upload_path']          = './uploads/';
      //   $config['allowed_types']        = ALLOWED_TYPES;
      //   $config['encrypt_name'] = TRUE;
      //   $this->load->library('upload');
      //   $this->upload->initialize($config);
      //   if (!$this->upload->do_upload('image')) {
      //     $arr = array('status' => 0, 'message' => "Error " . $this->upload->display_errors());
      //     echo json_encode($arr);
      //     exit;
      //   } else {
      //     $upload_data = $this->upload->data();
      //     $_POST['image'] = $upload_data['file_name'];
      //   }
      // } else {
      //   unset($_POST['image']);
      // }
      /////////////////////////////////////////////////////////
      if (empty($PrimaryID)) {

        $result = $this->api->getSingleRecordWhere($this->tbl, array('title' => $title));
        if (!empty($result)) {
          $arr = array('status' => 0, 'message' => "Category already exists..!");
          echo json_encode($arr);
          exit;
        }

        if ($this->db->insert($this->tbl, $_POST) > 0) {
          $result = 1;
        }
      } else {
        // if (isset($_POST['image']) && !empty($_POST['image'])) {
        //   $this->api->deleteFile($this->tbl, array('id' => $PrimaryID));
        // }
        $result = $this->api->updateData($this->tbl, $_POST, array('id' => $PrimaryID));
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
          $arr = array('status' => 0, 'message' => "Not Saved!");
          echo json_encode($arr);
          break;
        default:
          $arr = array('status' => 0, 'message' => "Not Saved!");
          echo json_encode($arr);
          break;
      }
    }
  }
  /*************************/

  public function  changeStatus()
  {


    extract($_POST);
    if ($status == 1) {
      $chageTo = 'Inactive';
      $status = 0;
    } elseif ($status == 0) {
      $chageTo = 'Active';
      $status = 1;
    }
    $data = array('is_active' => $status);
    $result = $this->db->where("id", $id)->update($this->tbl, $data);

    if ($result == 1) {
      $arr = array('chaged' => true, 'status' => $chageTo);
      echo json_encode($arr);
    }
  }

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
}
