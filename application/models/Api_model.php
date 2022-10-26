<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $today =  date('Y-m-d');
    }


    function mail_exists($key,$user_type=WRITER_USER_TYPE)
    {

        $this->db->where('email', $key);
        $this->db->where('user_type', $user_type);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

 /*Update any data*/
         public function updateSingleRow($table, $where, $data)
        {                 
            $this->db->where($where);
            $this->db->update($table, $data);

            if ($this->db->affected_rows() > 0)
            {
              return TRUE;
            }
            else
            {
              return FALSE;
            }
        }
    

    function username_exists($key)
    {
        $this->db->where('username', $key);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insert($table, $data_array)
    {
        $dbExi = $this->db->insert($table, $data_array);
        if ($dbExi) {
            return  $this->db->insert_id();
        } else {
            return  0;
        }
    }

    public function checkSocialLogin($social_id, $social_type)
    {
        // fetching user by email
        $user = $this->db
            ->select('id')
            ->where('social_id', $social_id)
            ->where('social_type', $social_type)
            ->limit(1)
            ->get('users')
            ->result_array();

        if (count($user) > 0) {
            return $user[0]['id'];
        } else {
            return false;
        }
    }

    public function checkLogin($email, $password)
    {
        // fetching user by email
        $user_type = WAITER_USER_TYPE;
        $query = "SELECT id
                    FROM `users`
                    WHERE (`user_name` = '$email')
                    AND `password` = '$password'
                    AND `user_type` = $user_type
                     LIMIT 1";
        $user = $this->db->query($query)->result_array();

        if (count($user) > 0) {
            $this->db->where('id', $user[0]['id']);
            $data = array("accessToken" => $this->generateRandomString());
            //    $dbExi = $this->db->update('users', $data);
            return $user[0]['id'];
        } else {
            return false;
        }
    }

    function generateRandomString($length = 80)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function checkLoginForgot($email, $password)
    {
        // fetching user by email
        $query = "SELECT id
                    FROM `users`
                    WHERE (`email` = '$email' OR `username` = '$email')
                    AND `forgot_password` = '$password'
                     LIMIT 1";
        $user = $this->db->query($query)->result_array();

        if (count($user) > 0) {
            return $user[0]['id'];
        } else {
            return false;
        }
    }

/* get user by id */
    public function getUserById($id, $fields = '*')
    {
        $user = $this->db
            ->select($fields)
            ->where('id', $id)
            ->limit(1)
            ->get('users')
            ->result_array();
        if (count($user) > 0) {
            return $user[0];
        } else {
            return null;
        }
    }

    /*Insert and get last Id*/
    public function insertGetId($table,$data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    public function getUserByToken($token, $fields = '*')
    {
        $user = $this->db
            ->select($fields)
            ->where('accessToken', $token)
            ->limit(1)
            ->get('users')
            ->result_array();
        if (count($user) > 0) {
            return $user[0];
        } else {
            return null;
        }
    }

        public function checkAuth()
    {
        $apiKey = '';
        $headers = getallheaders();
        if (array_key_exists('accesstoken', $headers)) {
            $apiKey = $headers['accesstoken'];
        } else if (array_key_exists('Accesstoken', $headers)) {
            $apiKey = $headers['Accesstoken'];
        } else {
            return 'accesstoken';
        }
        if (empty($apiKey)) {
            return 'unauthorized';
        }
        $CI = &get_instance();
        $errors = array();
        $query = 'SELECT id from users where accesstoken = "' . $apiKey . '"';
        $res = $CI->db->query($query);

        if ($res->num_rows() > 0) {

            $res = $res->row_array();
            return $res['id'];
        } else {

            return 'unauthorized';
        }
    }

    public function validateApiKey()
    {
        $errors = array();
        $userId = $this->checkAuth();
        if ($userId == 'unauthorized') {
            array_push($errors, "Invalid Api Key");
            $this->print_errors("Unauthorised User");
        }
        if ($userId == 'accesstoken') {
            //array_push($errors, " Api Key");
            
            $this->print_errors("Accesstoken Missing");

        }
        return $userId;
    }

    public function updateDevice($user_id, $device_type, $device_id)
    {
        $data = array(
            'id' => $user_id,
            'devicetype' => $device_type,
            'device_id' => $device_id
        );
        $this->db->where('id', $user_id);
        $dbExi = $this->db->update('users', $data);
    }

    /*
        |----------------------------------------------------------------------
        | FUNCTION TO GET SINGLE RECORD
        |----------------------------------------------------------------------
        */
    function getSingleRecordWhere($tbl, $condition = NULL, $fields = '*')
    {
        $result = array();
        $this->db->select($fields);
        $this->db->from($tbl);
        if (!empty($condition)) {
            $this->db->where($condition);
        }
        $result  = $this->db->get();
        return $result->row();
    }
    
        function getMulRecordWhere($tbl, $condition = NULL, $fields = '*')
    {
        $result = array();
        $this->db->select($fields);
        $this->db->from($tbl);
        if (!empty($condition)) {
            $this->db->where($condition);
        }
        $result  = $this->db->get();
        return $result->result();
    }
    /*
      |----------------------------------------------------------------------
      | FUNCTION TO SEND EMAIL
      |----------------------------------------------------------------------
      */
    function sendEmail($email, $subject, $html)
    {
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from('mjawadsagheer@gmail.com', 'Widggin');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($html);
        $send=$this->email->send();
    }

    function sendEmailSendGrid($email, $subject, $html)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("mjawadsagheer@gmail.com", "Example User");
        $email->setSubject($subject);
        $email->addTo($email);
        $email->addContent("text/plain", $html);
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
        } catch (Exception $e) {
            //echo 'Caught exception: '. $e->getMessage() ."\n";
        }

    }    /*
    |----------------------------------------------------------------------
    | FUNCTION TO GET MULTIPE RECORDS
    |----------------------------------------------------------------------
    */
    function getMultipleRecordWhere($tbl, $condition =  NULL, $fields = '*', $sortField = NULL, $sortOrder = NULL)
    {
        $result = array();
        $this->db->select($fields);
        $this->db->from($tbl);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if (!empty($sortOrder) && !empty($sortField)) {
            $this->db->order_by($sortField, $sortOrder);
        }

        $result  = $this->db->get();
        return $result->result();
    }
    
     public function multiple_images($image = array()){

     return $this->db->insert_batch('chapters',$image);
             }

    /*
        |----------------------------------------------------------------------
        | FUNCTION TO GET MULTIPE RECORDS
        |----------------------------------------------------------------------
        */
    function getCountWhere($tbl, $condition =  NULL)
    {
        $result = array();
        $this->db->select('*');
        $this->db->from($tbl);
        if (!empty($condition)) {
            $this->db->where($condition);
        }
        $result  = $this->db->count_all_results();
        return $result;
    }

    /*
        |----------------------------------------------------------------------
        | FUNCTION TO INSERT RECORD
        |----------------------------------------------------------------------
        */
    function insertData($tbl, $fields)
    {
        $result = array();
        $query = $this->db->insert($tbl, $fields);
        if ($this->db->affected_rows() == 1)
            return true;
        else
            return false;
    }

    /*
        |----------------------------------------------------------------------
        | FUNCTION TO UPDATE RECORD
        |----------------------------------------------------------------------
        */
    function updateData($tbl, $fields, $condition)
    {
        $result = array();
        $this->db->where($condition);
        $query = $this->db->update($tbl, $fields);
        if ($this->db->affected_rows() == 1)
            return true;
        else
            return false;
    }



    /*
        |----------------------------------------------------------------------
        | FUNCTION TO DELETE RECORD
        |----------------------------------------------------------------------
        */
    function deleteRecord($tbl, $condition)
    {
        $result = array();
        $this->db->where($condition);
        $this->db->delete($tbl);
        if ($this->db->affected_rows() == 1)
            return true;
        else
            return false;
    }

    function selectJoinTablesMultipleRecord($tbl, $jtbls = array(), $condition = NULL, $fields = '*', $sortField = NULL, $sortOrder = NULL)
    {
        $result = array();
        $this->db->select($fields);
        $this->db->from($tbl);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        // check if jtables supplied
        if (count($jtbls) > 0) {
            foreach ($jtbls as $tb) {
                $this->db->join($tb['tbl'], $tb['cond'], $tb['type']);
            }
        }

        if (!empty($sortOrder) && !empty($sortField)) {
            $this->db->order_by($sortField, $sortOrder);
        }

        $result  = $this->db->get();
        return $result->result();
    }

    /*
        |----------------------------------------------------------------------
        | FUNCTION TO DELETE RECORD
        |----------------------------------------------------------------------
        */
    function deleteFile($tbl, $condition, $column = "image")
    {
        $row = $this->getSingleRecordWhere($tbl, $condition);

        if (!empty($row)) {

            if (isset($row->$column) && $row->$column != "noimg.png" && $row->$column != "dish.png") {
                if (PROJECT_DIRECTORY != '') {
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/' . PROJECT_DIRECTORY . '/uploads/' . $row->$column;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                } else {
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $row->$column;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
        }
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



 function selectJoinTablesSingleRecordRow($tbl, $jtbls = array(), $condition = NULL, $fields = '*', $sortField = NULL, $sortOrder = NULL)
    {
        $result = array();
        $this->db->select($fields);
        $this->db->from($tbl);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        // check if jtables supplied
        if (count($jtbls) > 0) {
            foreach ($jtbls as $tb) {
                $this->db->join($tb['tbl'], $tb['cond'], $tb['type']);
            }
        }

        if (!empty($sortOrder) && !empty($sortField)) {
            $this->db->order_by($sortField, $sortOrder);
        }

        $result  = $this->db->get();
        return $result->row();
    }
    
    		/*
    |----------------------------------------------------------------------
    | Get All details of category
    |----------------------------------------------------------------------
    */

          public function get_categories()
        {

            $query = "SELECT id as categoryId, title as categoryTitle,titleAr FROM `categories`";
            $books = $this->db->query($query)->result();
            $return = array();
            foreach($books as $category)
            {
            
                $category->books = $this->get_sub_categories($category->categoryId); 
                //Get the categories sub categories
                $return[]=$category;
                
        }

            return $return;
        }


        public function get_sub_categories($category_id)
        {
          $newpath = base_url('uploads/');
          $query = "SELECT id, title as bookTitle,description,CONCAT('$newpath','',image) as bookImage,created_at as createdAt FROM `books` WHERE (`category_id` = '$category_id')";
          $books = $this->db->query($query)->result_array();
          return $books;

        }

	/*
    |----------------------------------------------------------------------
    | Get Book @getCategoryBooks Functions
    |----------------------------------------------------------------------
    */
    
     public function getBookDetails($bookId)
        {
            $newpath = base_url('uploads/');
            // $query = "SELECT  FROM `books` join  WHERE (`id` = '$bookId')";
            // $books = $this->db->query($query)->result();
            
            
        $this->db->select("books.id,books.title as bookTitle,books.description,CONCAT('$newpath','',books.image) as bookImage,books.created_at as publishedDate,
            books.updated_at as modifiedDate, user_id as userId");
        $this->db->from('books');
        $this->db->join('users', 'users.id=books.user_id','left');
        $this->db->where('books.id', $bookId);
        $this->db->where('books.is_active', 1);
        $query = $this->db->get();
        $books = $query->result();
        
            //$return = array();
        foreach($books as $category)
         {
            
          $category->author = $this->getUsers($category->userId); 
                //Get the categories sub categories
          $return=$category;
                
        }
        
        if(empty($return))
        {
            $response['status'] = 204;
            $response['message'] = 'No data Exist';
            echo json_encode($response);
            exit;
        }
        else
        {
            return $return;
        }
            

            
        }


        public function getUsers($userId)
        {
          $newpath = base_url('uploads/');
          $query = "SELECT id,CONCAT(users.fname, '', users.lname) as name,CONCAT('$newpath','',users.image) as img FROM `users` WHERE (`id` = '$userId' AND `is_active` = '1' )";
          $books = $this->db->query($query)->row();
          return $books;

        }
        
        public function getChapterByBook($bookId)
        {
            
        $newpath = base_url('uploads/pdf/');
        $this->db->select("chapters.filename as name,chapters.image,CONCAT('$newpath','',filename) as url");
        $this->db->from('chapters');
        $this->db->join('books', 'books.id=chapters.book_id');
        $this->db->where('books.id', $bookId);
         $this->db->where('books.is_active', 1);
        $query = $this->db->get();
        return $query->result();

        }
        
    /*
    |----------------------------------------------------------------------
    | END Book Detail Functions
    |----------------------------------------------------------------------
    */
    
        
        
        
        	/*
    |----------------------------------------------------------------------
    | Get Book PDF Details Functions
    |----------------------------------------------------------------------
    */
    
     public function getBookPdfDetails($bookId,$userId)
        {
            $newpath = base_url('uploads/');
  
        $this->db->select("books.id,books.title as bookTitle,books.description,books.language,chapters.status,categories.id as categoryId,categories.title as categoryTitle");
        $this->db->from('books');
        $this->db->join(' chapters', ' chapters.book_id=books.id','left');
        $this->db->join(' categories', ' categories.id=books.category_id','left');
        $this->db->where('chapters.book_id', $bookId);
        $this->db->where('chapters.user_id', $userId);
        $this->db->order_by('chapters.id','DESC');
        $query = $this->db->get();
        $books = $query->row();
         return $books;
        }
        
        public function getChapter($bookId,$userId)
        {
            
        $newpath = base_url('uploads/pdf/');
        $this->db->select("chapters.id as name,chapters.image,CONCAT('$newpath','',filename) as url");
        $this->db->from('chapters');
        $this->db->join('books','books.id=chapters.book_id');
        $this->db->where('chapters.book_id', $bookId);
        $this->db->where('chapters.user_id', $userId);
        $this->db->order_by('chapters.id','DESC');
        $query = $this->db->get();
        return $query->result();

        }
        
           public function getAdmminChapter($bookId)
        {
            
        $newpath = base_url('uploads/pdf/');
        $this->db->select("chapters.id,chapters.image,CONCAT('$newpath','',filename) as url");
        $this->db->from('chapters');
        $this->db->join('books','books.id=chapters.book_id');
        $this->db->where('books.id', $bookId);
        $this->db->order_by('chapters.id','DESC');
        $query = $this->db->get();
        return $query->result();

        }
        
  
    /*
    |----------------------------------------------------------------------
    | END Book PDF Detail Functions
    |----------------------------------------------------------------------
    */
        
        
        public function getBookProfile($bookId)
        {
         $newpath = base_url('uploads/');
         $fields= "id,category_id as categoryId,title as bookTitle,description,language,CONCAT('$newpath','',image) as bookImage ";
        //$user=array();
        $user= $this->api->getSingleRecordWhere('books',array('id'=>$bookId),$fields);

        //checking users
        if(empty($user))
        {
            $this->api->print_error('No user found');
        }
 
        return $user;

        }
        
        
        public function getRating($bookId,$userId)
        {
             $newpath = base_url('uploads/pdf/');
        $this->db->select("books.id,books.title as bookTitle,favorite.rating,favorite.comments,CONCAT('$newpath','',image) as bookImage,favorite.language");
        $this->db->from('favorite');
        $this->db->join('books','books.id=favorite.book_id');
        $this->db->where('favorite.book_id', $bookId);
        $this->db->where('favorite.user_id', $userId);
        $this->db->order_by('favorite.id','DESC');
       
        $query = $this->db->get();
        return $query->result();

        }
        
          public function getRatingForAdd($bookId)
        {
             $newpath = base_url('uploads/pdf/');
        $this->db->select("books.id,books.title as bookTitle,favorite.rating,favorite.comments,CONCAT('$newpath','',image) as bookImage");
        $this->db->from('favorite');
        $this->db->join('books','books.id=favorite.book_id');
        $this->db->where('favorite.book_id', $bookId);
        $this->db->order_by('favorite.id','DESC');
       
        $query = $this->db->get();
        return $query->result();

        }
        
        
        public function getAllRatingOfBooks($bookId)
        {
             $newpath = base_url('uploads/pdf/');
        $this->db->select("books.id,books.title as bookTitle,favorite.rating,favorite.comments,CONCAT('$newpath','',books.image) as bookImage,CONCAT(users.fname, '', users.lname) as username,CONCAT('$newpath','',users.image)as userImage");
        $this->db->from('favorite');
        $this->db->join('books','books.id=favorite.book_id');
        $this->db->join('users','users.id=favorite.user_id');
        $this->db->where('favorite.book_id', $bookId);
        $this->db->order_by('favorite.id','ASC');
        $this->db->limit(10);
        
        $query = $this->db->get();
        return $query->result();

        }
         
         
         
        public function getLimitBooks()
        {
        $newpath = base_url('uploads/pdf/');
        $this->db->select("books.id,books.title as book_title,books.is_active,books.description,books.image as book_image,books.category_id,categories.title as category_title,CONCAT('$newpath','',categories.image) as category_image,books.user_id, CONCAT(users.fname, '', users.lname) as username,CONCAT('$newpath','',users.image) as user_image,books.created_at");
        $this->db->from('books');
        $this->db->join('categories','categories.id=books.category_id');
        $this->db->join('users','users.id=books.user_id');
         $this->db->where('books.is_active', 1);
        $this->db->order_by('books.id','DESC');
        $this->db->limit(10);
        
        $query = $this->db->get();
        return $query->result();

        }

        
          public function getCategoryByBook($bookId)
        {
     
        $this->db->select("categories.id,categories.title as titleEn");
        $this->db->from('books');
        $this->db->join('categories', 'categories.id=books.category_id');
        $this->db->where('books.id', $bookId);
        
        $query = $this->db->get();
        return $query->result();

        }
        
  
        
		public function insertfile($file){
			return $this->db->insert(' pdf_books', $file);
		}


  /*
        |----------------------------------------------------------------------
        | FUNCTION @getBookDashboard
        |----------------------------------------------------------------------
        */

       public function getBookDashboard($data)
        {

        $newpath = base_url('uploads/');
        $response = array();
         $fields = "books.id,books.title as book_title,books.description,CONCAT('$newpath','',books.image) as book_image,books.category_id,categories.title as category_title,CONCAT('$newpath','',categories.image) as category_image,books.user_id, CONCAT(users.fname, '', users.lname) as username,CONCAT('$newpath','',users.image) as user_image,books.created_at

         ";
        $tbls = array(
            array('tbl' => 'categories', 'cond' => 'categories.id = books.category_id', 'type' => 'left'),
            array('tbl' => 'users', 'cond' => 'users.id = books.user_id', 'type' => 'left'),

        );
       
        // $response["books"] = $this->getSingleRecordWhere($this->tab_books, array('id'=>$data));
         $response = $this->api->selectJoinTablesMultipleRecord("books", $tbls, array("books.category_id" => $data), $fields);


        return $response;
}

    /*
        |---------------------------------------------------------s-------------
        | FUNCTION SuccessFully Message
        |----------------------------------------------------------------------
        */

    public function successResponse($message,$data = array())
    {
        $response = array();
        $response['status'] = 200;
        $response['message'] = $message;
        if (!empty($data)) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit;
    }

    public function successResponseWithData($message, $data)
    {
        $response = array();
        $response['status'] = 200;
        $response['message'] = $message;
        $response['data'] = $data;
        echo json_encode($response);
    }

    public function print_error($message, $data = array())
    {
        $response = array();
        $response['status'] = 204;
        $response['message'] = $message;
        if (!empty($data)) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit;
    }
    
       public function print_errors($message, $data = array())
    {
        $response = array();
        $response['status'] = 401;
        $response['message'] = $message;
        if (!empty($data)) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit;
    }

  /*
        |----------------------------------------------------------------------
        | FUNCTION @getBookCateDetail
        |----------------------------------------------------------------------
        */

     public function getBookCateDetail($categoryId)
    {

        $newpath = base_url('uploads/');
        $response = array();
         $fields = "books.id,books.title as bookTitle,books.description,CONCAT('$newpath','',books.image) as bookImage

         ";
        $tbls = array(
            array('tbl' => 'categories', 'cond' => 'categories.id = books.category_id', 'type' => 'left'),

        );
       
        // $response["books"] = $this->getSingleRecordWhere($this->tab_books, array('id'=>$data));
         $response = $this->api->selectJoinTablesMultipleRecord("books", $tbls,array('books.category_id'=>$categoryId), $fields);


        return $response;
      }


 /*
        |----------------------------------------------------------------------
        | FUNCTION @getCategoryBooks
        |----------------------------------------------------------------------
        */

       public function getCategoryBooks($data)
      {

        $newpath = base_url('uploads/');
        $response = array();
         $fields = "books.id,books.title as book_title,books.description,CONCAT('$newpath','',books.image) as book_image,books.category_id,categories.title as category_title,CONCAT('$newpath','',categories.image) as category_image,books.user_id, CONCAT(users.fname, '', users.lname) as username,CONCAT('$newpath','',users.image) as user_image,books.created_at

         ";
        $tbls = array(
            array('tbl' => 'categories', 'cond' => 'categories.id = books.category_id', 'type' => 'left'),
            array('tbl' => 'users', 'cond' => 'users.id = books.user_id', 'type' => 'left'),

        );
       
        // $response["books"] = $this->getSingleRecordWhere($this->tab_books, array('id'=>$data));
         $response = $this->api->selectJoinTablesMultipleRecord("books", $tbls, array("books.category_id" => $data), $fields);


         return $response;
       }


  /*
        |----------------------------------------------------------------------
        | FUNCTION @getBookUserDetail
        |----------------------------------------------------------------------
        */

    public function getBookUserDetail($data)
    {

        $newpath = base_url('uploads/');
        $response = array();
         $fields = "books.id,books.title as bookTitle,CONCAT('$newpath','',books.image) as bookImage,categories.title as categoryTitle,titleAR,books.status,books.user_id as userId,books.created_at as publishedDate,books.updated_at as modifiedDate";
        $tbls = array(
            array('tbl' => 'categories', 'cond' => 'categories.id = books.category_id', 'type' => 'left'),
            array('tbl' => 'users', 'cond' => 'users.id = books.user_id', 'type' => 'left')
        );
       
         $respons = $this->api->selectJoinTablesMultipleRecord("books", $tbls, array("books.user_id" => $data), $fields);

        return $respons;
}
    /*
        |----------------------------------------------------------------------
        | FUNCTION @verifyRequiredParams TO VALIDATE REQUIRED PARAMS
        |----------------------------------------------------------------------
        */

    function verifyRequiredParams($required_fields, $method = 'post')
    {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = ($method == 'post') ? $_POST : $_GET;
        // check post/get values
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }
        if ($error) {
            // Required field(s) are missing or empty
            $response = array();
            $response["status"] = 204;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            echo json_encode($response);
            exit();
        } else {
            return true;
        }
    }

   function verifyRequiredParamss($required_fields, $method = 'get')
    {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = ($method == 'post') ? $_POST : $_GET;
        // check post/get values
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }
        if ($error) {
            // Required field(s) are missing or empty
            $response = array();
            $response["status"] = 204;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            echo json_encode($response);
            exit();
        } else {
            return true;
        }
    }
             
}
