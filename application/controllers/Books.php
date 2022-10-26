<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Books extends CI_Controller
{
    public $tab_users='users';
    public $tab_pdf='pdf_books';
    public $tab_devices='devices';
    public $tab_books='books';
    public $tab_fav='favorite';
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
  | FUNCTION @addBooks FOR Add Books
  |----------------------------------------------------------------------
  */

    public function addBooks()
    {
    
       $user_id= $this->api->validateApiKey();
      
        extract($_POST);
        $user = array();
        //Validating Required Params
            $this->api->verifyRequiredParams(array("title","description","categoryId","language"));
        $book = $this->api->getSingleRecordWhere($this->tab_books, array('title' => $title, 'user_id' => $user_id));

        if (!empty($book)) {
          $this->api->print_error('Books already exists.');
        }
        $image = $this->uploadImage($_FILES);
        $insertData = $this->api->insertData($this->tab_books, array('title' => $title,'user_id'=>$user_id,'description'=>$description,'category_id'=>$categoryId,'language'=>$language,'image'=>$image,'status'=>'InProgress'));

       $inserted_id = $this->db->insert_id($insertData);
        $insertData=$this->api->getBookProfile($inserted_id);
        if ($insertData) {
       
            $this->api->successResponseWithData("Book added Successfully",$insertData);

        } else {
        
        $this->api->print_error('Book is not added.');

        }
    }

            /*
    |----------------------------------------------------------------------
    | FUNCTION @do_upload
    |----------------------------------------------------------------------
    */
    public function bookFileUpload(){
        
            $user_id= $this->api->validateApiKey();
            extract($_POST);
           // $this->api->verifyRequiredParams(array("bookId"));
        
            $this->load->library('upload');
            $image = array();
            $ImageCount = count($_FILES['filename']['name']);
            for($i = 0; $i < $ImageCount; $i++){
            $_FILES['file']['name']       = $_FILES['filename']['name'][$i];
            $_FILES['file']['type']       = $_FILES['filename']['type'][$i];
            $_FILES['file']['tmp_name']   = $_FILES['filename']['tmp_name'][$i];
            $_FILES['file']['error']      = $_FILES['filename']['error'][$i];
            $_FILES['file']['size']       = $_FILES['filename']['size'][$i];

            // File upload configuration
            $uploadPath = 'uploads/pdf';
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';

            // Load and initialize upload library
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            // Upload file to server
            if($this->upload->do_upload('file')){
                // Uploaded file data
                $imageData = $this->upload->data();
                 $uploadImgData[$i]['filename'] = $imageData['file_name'];
                 $uploadImgData[$i]['book_id'] = $bookId;
                 $uploadImgData[$i]['user_id'] = $user_id;
            }
        }
	
          $insertDataw=$this->db->insert_batch('chapters',$uploadImgData);
           
          $this->api->updateData($this->tab_books,array("status" => "Done"), array('id' => $bookId));
           
          //$this->db->insert_id();
          
          
          $insertData=$this->api->getBookPdfDetails($bookId,$user_id);
          $insertData->chapters=$this->api->getChapter($bookId,$user_id);
          $this->api->successResponseWithData("FIle Uploaded Successfully",$insertData);

  }
        
    
       /*
  |----------------------------------------------------------------------
  | FUNCTION @addBookReviews FOR Add Reviews
  |----------------------------------------------------------------------
  */

    public function addBookReviews()
    {
    
       $user_id= $this->api->validateApiKey();
      
        extract($_POST);
        $user = array();
        //Validating Required Params
        $this->api->verifyRequiredParams(array("bookId","rating","comments"));
        $book = $this->api->getSingleRecordWhere($this->tab_fav, array('book_id' => $bookId, 'user_id' => $user_id));

        if (!empty($book)) {
          $this->api->print_error('You have already given rating!');
        }
        
        $insertData = $this->api->insertData($this->tab_fav, array('book_id' => $bookId,'user_id'=>$user_id,'rating'=>$rating,'comments'=>$comments));

      // $inserted_id = $this->db->insert_id($insertData);
        $insertData=$this->api->getRatingForAdd($bookId);
        
        //var_dump($insertData);die();
        if($insertData) {
       
            $this->api->successResponseWithData("Rating added Successfully",$insertData);

        } else {
        
        $this->api->print_error('Error');

        }
    }
    
    
        
        
       /*
    |----------------------------------------------------------------------
    | FUNCTION @getBookReviews get single Book
    |----------------------------------------------------------------------
    */

    public function getBookReviews()
    {

        $userId = $this->api->validateApiKey();
        // check request type
        extract($_GET);
        $book = array();
        //Validating Required Params
        $this->api->verifyRequiredParamss(array("bookId","language"));
        $book = $this->api->getRating($bookId,$userId,$language);
        // $book = $this->api->getBookDetails($id);

        if (!empty($book)) {
                $this->api->successResponseWithData('Book Reviews', $book);
        } else {  
             $this->api->print_error('Something went Errors!', $book);

         }

    }
    
    
     public function getAllReviewsBook()
    {
        extract($_GET);
        $book = array();
        //Validating Required Params
        $this->api->verifyRequiredParamss(array("bookId"));
        $book = $this->api->getAllRatingOfBooks($bookId);
        if (!empty($book)) {
                $this->api->successResponseWithData('Book Reviews', $book);
        } else {  
             $this->api->print_error('No reviews Exist!', $book);

         }

    }
        
       /*
    |----------------------------------------------------------------------
    | FUNCTION @getSingleBook get single Book
    |----------------------------------------------------------------------
    */

    public function categoryBooks()
    {

        //$user_id = $this->api->validateApiKey();
        // check request type
        extract($_POST);
        $book = array();
        //Validating Required Params
        $this->api->verifyRequiredParams(array("category_id"));
        $book = $this->api->getBookCateDetail($category_id);
        // $book = $this->api->getBookDetails($id);

        if (!empty($book)) {
                $this->api->successResponseWithData('Book detail', $book);
        } else {  
             $this->api->print_error('Book does not exist', $book);

         }

    }


    /*
|----------------------------------------------------------------------
| FUNCTION @upload File
|----------------------------------------------------------------------
*/

    private function uploadFile($data)
    {
        $image = '';
        if ($data) {
            $info = pathinfo($_FILES['filename']['name']);
            $ext = $info['extension'];
            $newname = $_FILES['filename']['name'];
            $target = 'uploads/pdf' . $newname;
            if (move_uploaded_file($_FILES['filename']['tmp_name'], $target)) {
                $image = $newname;
            }
        }
        return $image;
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @getBooks get All Books
    |----------------------------------------------------------------------
    */

   public function getBooks()
    {
         extract($_GET);
        //$this->api->verifyRequiredParamss(array("language"));

        $categories = $this->api->get_categories();

        if (!empty($categories)) {
              $this->api->successResponseWithData('All Books', $categories);
  
        } else {  
             $this->api->print_error('Category is not exist', $categories);

         }

    }


    public function getBooksAll(){
     

        $cats = $this->api->getCategories();

        var_dump($cats->category_id);die();
    foreach($cats as $ct){
        $cat_id = $ct->id;
    }
$data['categories'] = $this->api->getCategories();
$data['subcategories'] = $this->api->getSubcategories($cat_id);
echo json_encode($data);

    }


   /*
    |----------------------------------------------------------------------
    | FUNCTION @getSingleBook get single Book
    |----------------------------------------------------------------------
    */

    public function bookDetails()
    {

        //$user_id = $this->api->validateApiKey();
        // check request type
        extract($_GET);
        //Validating Required Params
        $this->api->verifyRequiredParamss(array("bookId"));
         $books = $this->api->getBookDetails($bookId);
         $books->categories = $this->api->getCategoryByBook($bookId);
         $books->chapters = $this->api->getChapterByBook($bookId);
         
        // $books[''] = $this->api->getCategoryByBook($bookId);
         
        
        if (!empty($books)) {
              $this->api->successResponseWithData('Books Detail', $books);
  
        } else {  
             $this->api->print_error('Category is not exist', $books);

         }
         

    }
    
    
           /*
    |----------------------------------------------------------------------
    | FUNCTION @getBooksOfCategory get single Book
    |----------------------------------------------------------------------
    */

    public function getBooksOfCategory()
    {

        //$user_id = $this->api->validateApiKey();
        // check request type
        extract($_GET);
        $book = array();
        //Validating Required Params
        $this->api->verifyRequiredParamss(array("category_id"));
        $book = $this->api->getBookDashboard($category_id);
        // $book = $this->api->getBookDetails($id);

        if (!empty($book)) {
                $this->api->successResponseWithData('Book detail', $book);
        } else {  
             $this->api->print_error('Book does not exist', $book);

         }

    }
    
          /*
    |----------------------------------------------------------------------
    | FUNCTION @getSingleBook get single Book
    |----------------------------------------------------------------------
    */

    public function categorywisebooks()
    {

        //$user_id = $this->api->validateApiKey();
        // check request type
        extract($_POST);
        $book = array();
        //Validating Required Params
        $this->api->verifyRequiredParams(array("category_id"));
        $book = $this->api->getCategoryBooks($category_id);
        // $book = $this->api->getBookDetails($id);

        if (!empty($book)) {
                $this->api->successResponseWithData('Book detail', $book);
        } else {  
             $this->api->print_error('Book does not exist', $book);

         }

    }
    

       /*
    |----------------------------------------------------------------------
    | FUNCTION @getSingleBook get single Book
    |----------------------------------------------------------------------
    */

    public function getCategoryBooks()
    {

        //$user_id = $this->api->validateApiKey();
        //check request type
        extract($_GET);
        $book = array();
        //Validating Required Params
        $this->api->verifyRequiredParamss(array("categoryId"));
        $books = $this->api->getBookCateDetail($categoryId);

        if (!empty($books)) {
                $this->api->successResponseWithData('Book detail', $books);
        } else {  
             $this->api->print_error('Book does not exist', $books);

         }

    }

           /*
    |----------------------------------------------------------------------
    | FUNCTION @getSingleBook get single Book
    |----------------------------------------------------------------------
    */

    public function getBooksOfUsersIos()
    {

        //$userId = $this->api->validateApiKey();
        // check request type
        extract($_GET);
        $book = array();
        
        $this->api->verifyRequiredParamss(array("userId"));
        $book = $this->api->getBookUserDetail($userId);
        if (!empty($book)) {
                $this->api->successResponseWithData('User Books detail', $book);
        } else {  
             $this->api->print_error('Book does not exist', $book);

         }

    }

    public function getBooksOfUser()
    {

        $user_id = $this->api->validateApiKey();
        // check request type
        extract($_GET);
        $book = array();
             //$this->api->verifyRequiredParamss(array("language"));
        $book = $this->api->getBookUserDetail($user_id);
        if (!empty($book)) {
                $this->api->successResponseWithData('User Books detail', $book);
        } else {  
             $this->api->print_error('Book does not exist', $book);

         }

    }



       /*
  |----------------------------------------------------------------------
  | FUNCTION @deleteProduct FOR Delete Product
  |----------------------------------------------------------------------
  */


    public function deleteProduct()
    {

       $user_id= $this->api->validateApiKey();
        extract($_POST);

        //Validating Required Params
        $this->api->verifyRequiredParams(array("id"));

        $isDelete = $this->api->deleteRecord($this->tab_products, array("id" => $id, "user_id" => $user_id));

       if($isDelete )
       {
        $this->api->successResponse("Product deleted Successfully");
       } else{

        $this->api->print_error('product does not deleted.');
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
