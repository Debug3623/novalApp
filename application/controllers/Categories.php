<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends CI_Controller
{
    public $tab_users='users';
    public $tab_devices='devices';
    public $tab_categories='categories';

    /*
    |----------------------------------------------------------------------
    | CLASS CONSTRUCTOR
    |----------------------------------------------------------------------
    */

    function __construct()
    {
        parent::__construct();
        header('Content-type: application/json');
    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @checkRequest FOR CHEKCING REQUEST TYPE
    |----------------------------------------------------------------------
    */


    private function checkRequest($method = 'post')
    {
        $response = array();
        if ($this->input->method() != $method) {
            $response['status'] = 500;
            $response['message'] = "Only " . strtoupper($method) . " method allowed for this request";
            echo json_encode($response);
            exit;
        }
    }





    /*
  |----------------------------------------------------------------------
  | FUNCTION @addProducts FOR Add Products
  |----------------------------------------------------------------------
  */

    public function addCategory()
    {
    
        $user_id= $this->api->validateApiKey();
        extract($_POST);
        $response = array();
        //Validating Required Params
        $this->api->verifyRequiredParams(array("title"));
        $category = $this->api->getSingleRecordWhere($this->tab_categories, array('title' => $title, 'created_by' => $user_id));

        if (!empty($category)) {

          $this->api->print_error('Category already exists.');

        }
        $updated_at= new DateTime();
        $updated =date_format($updated_at, 'U = Y-m-d H:i:s') . "\n";
        $image = $this->uploadImage($_FILES);
        $insertData = $this->api->insertData($this->tab_categories, array('title' => $title,'image' => $image,'created_by'=>$user_id,'updated_at' => $updated));
        $inserted_id = $this->db->insert_id($insertData);

        if ($insertData) {
         
            $this->api->successResponse("Category Successfully added." );

        } else {
        
        $this->api->print_error('Category is not added.');

        }
    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @getProducts get All Products
    |----------------------------------------------------------------------
    */

    public function getAllCategories()
    {

        //$user_id = $this->api->validateApiKey();
        //$response = array();
           $newpath = base_url('uploads/');

           $query = "SELECT id,title as titleEn,titleAr
            FROM categories" ;
            $allCategories = $this->db->query($query)->result();
             if (empty($allCategories)) {
            $categories="No categories Exist";
            }else{
             $categories =  $allCategories;
             }

   
        if (!empty($categories)) {
                $this->api->successResponseWithData('All Category', $categories);
        } else {  
             $this->api->print_error('Category is not exist', $categories);

         }

    }
    
    
       /*
    |----------------------------------------------------------------------
    | FUNCTION @getCateAll 
    |----------------------------------------------------------------------
    */

    public function getCateAll()
    {

           $newpath = base_url('uploads/');
           $query = "SELECT id as category_id,title,titleAr
            FROM categories" ;
            $allCategories = $this->db->query($query)->result();
             if (empty($allCategories)) {
            $categories="No categories Exist";
            }else{
             $categories =  $allCategories;
             }
           echo json_encode($categories);

    }

   /*
    |----------------------------------------------------------------------
    | FUNCTION @getProduct get single Product
    |----------------------------------------------------------------------
    */

    public function getSingleCategory()
    {

        $user_id = $this->api->validateApiKey();
        // check request type
        extract($_POST);
        $response = array();
        //Validating Required Params
       $this->api->verifyRequiredParams(array("id"));
    
        $categories = $this->api->getSingleRecordWhere($this->tab_categories, array('id'=>$id,'user_id' => $user_id));

        if (!empty($categories)) {
                $this->api->successResponseWithData('Category detail', $categories);
        } else {  
             $this->api->print_error('Category does not exist', $categories);

         }

    }

       /*
  |----------------------------------------------------------------------
  | FUNCTION @addProducts FOR Add Products
  |----------------------------------------------------------------------
  */

    public function updateCategory()
    {
    
        $user_id= $this->api->validateApiKey();
        extract($_POST);
        //Validating Required Params
        $this->api->verifyRequiredParams(array("title","id"));
        $products = $this->api->getSingleRecordWhere($this->tab_categories, array('title'=>$title,'user_id' => $user_id));
        if (!empty($products)) {

          $this->api->print_error('category is already exist.');

        }
        //update product
        $is_updated = $this->api->updateData($this->tab_categories, array('title' => $title), array('user_id' => $user_id,'id' => $id));
        $updated_product= $this->api->getSingleRecordWhere($this->tab_categories, array('id'=>$id));

        if ($is_updated) {
            //$user = $this->apis->getProductsByUserId($user_id);
            $this->api->successResponseWithData("category Successfully updated.",$updated_product);

        } else {
        
        $this->api->print_error('category does not updated.');

        }
    }



       /*
  |----------------------------------------------------------------------
  | FUNCTION @deleteProduct FOR Delete Product
  |----------------------------------------------------------------------
  */


    public function deleteCategory()
    {

       $user_id= $this->api->validateApiKey();
        extract($_POST);

        //Validating Required Params
        $this->api->verifyRequiredParams(array("id"));

        $isDelete = $this->api->deleteRecord($this->tab_categories, array("id" => $id, "user_id" => $user_id));

       if($isDelete )
       {
        $this->api->successResponse("Category deleted Successfully");
       } else{

        $this->api->print_error('Category does not deleted.');
        }

    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @uploadPicture FOR UPLOADING IMAGE/AUDIO
    |----------------------------------------------------------------------
    */


        private function uploadImage()
    {
        $image = '';

        if (isset($_FILES['image']['name'])) {
            $info = pathinfo($_FILES['image']['name']);
            $ext = $info['extension'];
            $newname = rand(5, 3456) * date(time()) . "." . $ext;
            $target = 'uploads/' . $newname;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image = $newname;
            }
        }
        return $image;
    }


}
