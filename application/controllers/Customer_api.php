<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer_api extends CI_Controller
{

    public $tbl_user = "users";
    public $tbl = 'orders';
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

    // function index()
    // {
    //     echo 'restaurantpos.glowingsoft.com';
    // }

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
        | FUNCTION SuccessFully Message
        |----------------------------------------------------------------------
        */

            public function successResponse($message)
            {
            $response = array();
            $response['status'] = 200;
            $response['message'] = $message;
            echo json_encode($response);
            }

            public function successResponseWithData($message,$data)
            {
            $response = array();
            $response['status'] = 200;
            $response['message'] = $message;
            $response['data']=$data;
            echo json_encode($response);
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
            return false;
        } else {
            return true;
        }
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @login FOR LOGGING USER IN
    |----------------------------------------------------------------------
    */

     /*SignUp User*/
    public function customer_register()
    {
        $this->checkRequest();
        extract($_POST);
 
        //Validating Required Params
        if ($this->verifyRequiredParams(array("name","email","mobile","device_id","device_type","mac_address"))) {    

        $name = $this->input->post('name', TRUE);
        $email_id = $this->input->post('email', TRUE); 
        $mobile = $this->input->post('mobile', TRUE);
        $device_id = $this->input->post('device_id', TRUE);
        $device_type = $this->input->post('device_type', TRUE);
        $mac_address = $this->input->post('mac_address', TRUE);

        $table= 'users';
        $tables= 'devices';    

         $el_order=rand(9999, 1000);  
         $is_verification_exist = $this->apis->getSingleRow($table, array('verification_code'=>$el_order));
            if ($is_verification_exist) {
                $response["status"] = 204;
                $response['message'] = 'Verification code already exist';
                echo json_encode($response);
                exit;
            }
     
         //email test if already exists
        $get_user=$this->apis->getSingleRow($table, array('email'=>$email_id));
        if(!$get_user)
        {

        $el_order=rand(9999, 1000);       
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
 

         $datas =  $this->apis->generateRandomString();

         $data = array('name'=>$name,'email'=>$email_id,'user_type'=>5,'mobile'=>$mobile,'verification_code'=>$el_order,'accessToken'=>$datas,'image'=>$image);

          $user_id=$this->apis->insertGetId($table,$data);

          $insert_id = $this->db->insert_id();


           $dev = array('user_id'=>$insert_id,'device_type'=>$device_type,'device_id'=>$device_id,'mac_address'=>$mac_address);

           $user_id=$this->apis->insertGetId($tables,$dev);

           $this->successResponse('User Register successfully.');

          exit;
        }
        else
        {
                // wrong combination case
         $response['status'] = 204;
         $response['message'] = 'Email Already Exist';
         echo json_encode($response);
         exit;
        }

    }
    exit;
    }


    public function resend_code()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params

        if ($this->verifyRequiredParams(array("email")));
            // check for correct email and password
            $user_id = $this->apis->checkLogins($email);

            $el_order=rand(9999, 1000);
          
            $is_updated = $this->apis->updateData($this->tbl_user,array("verification_code" => $el_order), array('id' => $user_id));

                // get the user by Verification Code
            $user = $this->apis->getUserById($user_id, 'verification_code');
                
                //removing password from response
                if ($user != NULL) {
                     $this->successResponseWithData("Code Send on your email",$user);
                }
             else {
                $response["status"] = 204;
                $response['message'] = 'Code could not send!';
                echo json_encode($response);
                exit;
            }
        
        exit;
    }

  
    public function login()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        $is_development=0;
        //Validating Required Params
        if ($this->verifyRequiredParams(array("email")));
            // check for correct email and password
            $user_id = $this->apis->checkLogins($email);

            $el_order=rand(9999, 1000);
          
            $is_updated = $this->apis->updateData($this->tbl_user,array("verification_code" => $el_order), array('id' => $user_id));

                // get the user by Verification Code

            $user = $this->apis->getUserById($user_id, 'verification_code');

                    $response["status"] = 200;
                    $response['message'] = "Successfully Login, please check the email and enter code";
                    if(isset($_POST['is_development'])&&($_POST['is_development']==1)){
                        $response['code'] = $user;   
                    }   
                    echo json_encode($response);
                    exit;
    }


     public function verification_code()
       {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params
        if ($this->verifyRequiredParams(array("verification_code","email"))) {
            // check for correct email and password
            if ($user_id = $this->apis->checkVerification($verification_code,$email)) {

               $baseUrl = base_url('uploads');

                // get the user by email
                $user = $this->apis->getUserById($user_id, "id,name,email,mobile,accessToken,CONCAT('$baseUrl','/',image) as image");
          
               $is_updated = $this->apis->updateData($this->tbl_user,array("verification_code" => Null), array('id' => $user_id));

                if ($user_id != NULL) {

                    $this->successResponseWithData('Login successfully.',$user);
                }

               }
               else{
                // wrong combination case
                $response['status'] = 204;
                $response['message'] = 'Login failed. Incorrect credentials';
                echo json_encode($response);
                exit;
            }
        }
        exit;
    }


    public function tables()
    {
        $this->checkRequest('post');
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        if ($this->verifyRequiredParams(array("accessToken"))) {
            $user = $this->apis->getUserByToken($accessToken, 'id');

            if ($user != NULL) {
                $user_id = $user["id"];

                $response["status"] = 200;
                $query = "SELECT t.*,COALESCE(tos.status, -1) as status FROM t_tables as t 
                LEFT JOIN table_order_status as tos ON (tos.table_id=t.id AND tos.user_id=$user_id)
                WHERE(t.is_active=1)
                ORDER BY t.id ASC";
                // echo $this->db->last_query();
                // exit();
                $response["tables"] = $this->db->query($query)->result_array();
                echo json_encode($response);
                exit;
            } else {
                // user credentials are wrong
                $response['status'] = 204;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }
        }
    }

    public function menu()
    {
        $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        if ($this->verifyRequiredParams(array("table_id"))) {
            $baseUrl = base_url('uploads');
            $user = $this->apis->getUserByToken($accessToken, 'id');

            if ($user != NULL) {
                $user_id = $user["id"];
                $postData["user_id"]=$user_id;
                $query = "SELECT *,CONCAT('$baseUrl','/',image) as image from categories WHERE EXISTS(
                    SELECT NULL
                    FROM products
                    WHERE products.category_id = categories.id AND products.is_active=1
                    HAVING Count(*) > 0) AND is_active=1";
                $response["categories"] = $this->db->query($query)->result_array();
                $response["dishes"] = $this->apis->mobileDishes($postData);
                $response["cart_order"] = $this->apis->cartOrderProfile($postData);
                $response["table_status"] = $this->apis->checkTableStatus($postData);
                echo json_encode($response);
                exit;
            } else {
                // user credentials are wrong
                $response['status'] = 204;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @addDishCart
    | @params: access_token,table_id,product_id,item_price,qty,is_increment
    | @is_increment: 0 ->subtract 1 qty in cart table
    | @is_increment: 1 ->Add 1 qty in cart table
    | @is_increment: 2 ->update update  price_item in cart table 
    | @is_increment: 4 ->update price_item in cart table 
    |----------------------------------------------------------------------
    */

    public function addDishCart()
    {
        
        $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        if ($this->verifyRequiredParams(array("accessToken", "table_id","product_id","item_price","qty","is_increment"))) {
            $user = $this->apis->getUserByToken($accessToken, 'id');
            if ($user != NULL) {
                $user_id = $user["id"];
                $postData['user_id']=$user_id;
                unset($postData["accessToken"]);

                $response["data"] = $this->apis->addDishCart($postData);

                echo json_encode($response);
                exit;
            }  
        }
    }


 /*
    |----------------------------------------------------------------------
    | FUNCTION @completeCartOrder
    |----------------------------------------------------------------------
    */

     public function completeCartOrder()
      {
        
        $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        // if ($this->verifyRequiredParams(array("customer_id"))) {
        $cartOrder = $this->apis->cartCompletedOrder($postData);

              if ($cartOrder != NULL) {
                  $this->successResponse('Your order sent successfully');
                }
              else
              {
                $response["status"] = 204;
                $response['message'] = 'Your order Failed';
                echo json_encode($response);
                exit;
            }

        exit;

      }

      public function cartOrderProfile()
      {
        
        $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        if ($this->verifyRequiredParams(array("user_id"))) {
        $response["data"] = $this->apis->cartOrderProfile($postData);
        echo json_encode($response);
        exit;
        }
        
      }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @orderStatusRequest
    |----------------------------------------------------------------------
    */

    public function orderStatusRequest()
    {  
        $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        if ($this->verifyRequiredParams(array("accessToken", "id","status"))) {
            $user = $this->apis->getUserByToken($accessToken, 'id');
            if ($user != NULL) {
                $user_id = $user["id"];
                $isExisits = $this->apis->getSingleRecordWhere("t_tables", array("id" => $id));
                if($isExisits==null){
                    $response["status"] = 204;
                    $response['message'] = 'Invalid Request';
                    echo json_encode($response);
                    exit;
                }
                $updated = $this->apis->updateData("table_order_status", array("status" => $status), array('table_id' => $id));
                $message = "";
                switch($status){
                    case 2:
                    $message = "Complete Order Request";
                    break;
                }
                $this->apis->insertData("notifications",array("subject_id" => $user_id, "object_id" => $id,"object_type"=>"COMPLETE_ORDER","message"=>$message));
                $response["message"] = "Request sent to admin Successfully";
                echo json_encode($response);
                exit;
            } else {
                // user credentials are wrong
                $response['status'] = 204;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }   
        }
    }


     /*
    |----------------------------------------------------------------------
    | FUNCTION @orderStatusRequest
    |----------------------------------------------------------------------
    */

    public function orderStatus()
    {  
        $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        if ($this->verifyRequiredParams(array("accessToken", "id","status"))) {
            $user = $this->apis->getUserByToken($accessToken, 'id');
            if ($user != NULL) {
                $user_id = $user["id"];
                $isExisits = $this->apis->getSingleRecordWhere("t_tables", array("id" => $id));
                if($isExisits==null){
                    $response["status"] = 204;
                    $response['message'] = 'Invalid Request';
                    echo json_encode($response);
                    exit;
                }
                $updated = $this->apis->updateData("table_order_status", array("status" => $status), array('table_id' => $id));
                $message = "";
                 if($status==0){
                    
                    $message = "Pending Order";
                     $this->apis->insertData("notifications",array("subject_id" => $user_id, "object_id" => $id,"object_type"=>"PREPARE_ORDER","message"=>$message));
                }
                elseif($status==2){
                    
                    $message = "Your Order Successfully Submit";
                     $this->apis->insertData("notifications",array("subject_id" => $user_id, "object_id" => $id,"object_type"=>"COMPLETE_ORDER","message"=>$message));
                }
                elseif($status==1)
                {

                     $message = "Your order ready to pick";
                     $this->apis->insertData("notifications",array("subject_id" => $user_id, "object_id" => $id,"object_type"=>"READY_ORDER","message"=>$message));

                }
                elseif($status==3)
                {
                     $message = "Your Order Preparing";
                     $this->apis->insertData("notifications",array("subject_id" => $user_id, "object_id" => $id,"object_type"=>"PREPARE_ORDER","message"=>$message));
                }
                  elseif($status==4)
                {
                     $message = "Your Order cancelled";
                     $this->apis->insertData("notifications",array("subject_id" => $user_id, "object_id" => $id,"object_type"=>"REJECT_ORDER","message"=>$message));
                }
               
                $response["message"] = "Request sent to admin Successfully";
                echo json_encode($response);
                exit;
            } else {
                // user credentials are wrong
                $response['status'] = 204;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }   
        }
    }

     /*
    |----------------------------------------------------------------------
    | FUNCTION @notifications
    |----------------------------------------------------------------------
    */

    public function notifications()
    {  
          $this->checkRequest('post');
        $postData = $_POST;
        extract($_POST);
        $response = array();
        $response["status"] = 200;
        $response["data"] = $this->apis->getNotifications($postData);
        echo json_encode($response);
        exit;
    }


    public function deleteDishCartItem(){
 
        $response = array();
        $response['status'] = true;
       
      
            unset($_POST["empty_cart"]);
            $isDelete = $this->apis->deleteRecord("cart",$_POST);

            // also remove from notifications if there
            $notiData = array('subject_id'=>$_POST['user_id'],'object_id'=>$_POST['table_id'],'object_type'=>'COMPLETE_ORDER');
            $isDelete = $this->apis->deleteRecord("notifications",$notiData);
            // echo $this->db->last_query();
            $_POST['is_delete'] = 0;
      
            $isDelete = $this->apis->deleteRecord("cart",array("id"=>$_POST["id"]));
            $_POST['is_delete'] = 1;
            unset($_POST["id"]);

            $this->successResponse("Item deleted Successfully");
            exit;
    }

        public function getResturant()
        {  

            $response["status"] = 200;
            $response["data"] = $this->apis->getResturant();
            echo json_encode($response);
            exit;
        }

       public function getCustomerOrders()
       {

         $customer_id = $this->input->post('customer_id', TRUE);
          if ($this->verifyRequiredParams(array("customer_id"))) {
       
           $user = $this->apis->getCustomerOrders($customer_id);

                if ($user != NULL) {
                    $this->successResponseWithData("Customer Orders",$user);
                    exit;
                }
             else {
                $response["status"] = 204;
                $response['message'] = 'No New values exist!';
                echo json_encode($response);
                exit;
            }

       }

    }

      public function customerNotifications()
       {

         $customer_id = $this->input->post('subject_id', TRUE);
          if ($this->verifyRequiredParams(array("subject_id"))) {
       
           $user = $this->apis->customerNotifications($customer_id);

                if ($user != NULL) {
                    // $this->successResponseWithData("Customer Notifications",$user);
                    // exit;

                $response["status"] = 204;
                $response['message'] = 'Customer Notifications!';
                $response['data'] =  $user;
                echo json_encode($response);
                exit;
                }
   
                else {
                $response["status"] = 204;
                $response['message'] = 'No notifications Exist!';
                echo json_encode($response);
                exit;
            }

       }

    }

    


        public function getOrderDetails()
        {

         $customer_id = $this->input->post('order_id', TRUE);
          if ($this->verifyRequiredParams(array("order_id"))) {
    
         $user = $this->apis->getOrderDetails($customer_id);
         $category= $this->apis->getCateProductDetails($customer_id);
        
                if ($user != NULL) {
                    $response["status"] = 200;
                    $response['message'] = "Orders Detail";
                    $response['orders'] = $user;
                    $response["order_items"] = $category;
                    echo json_encode($response);
                    exit;
                }
             else {
                $response["status"] = 204;
                $response['message'] = 'No New values exist!';
                echo json_encode($response);
                exit;
            }

       }

       } 
    
  
    
    public function seenNotifications(){
        $this->apis->updateData("notifications",array('is_seen'=>1),array('is_seen'=>0));
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @proper_parse_str
    |----------------------------------------------------------------------
    */
    private function proper_parse_str($str)
    {

        # result array

        $arr = array();

        # split on outer delimiter

        $pairs = explode('&', $str);

        # loop through each pair

        foreach ($pairs as $i) {

            # split into name and value

            list($name, $value) = explode('=', $i, 2);

            # if name already exists

            if (isset($arr[$name])) {

                # stick multiple values into an array

                if (is_array($arr[$name])) {

                    $arr[$name][] = $value;
                } else {

                    $arr[$name] = array($arr[$name], $value);
                }
            }

            # otherwise, simply stick it in a scalar
            else {

                $arr[$name] = $value;
            }
        }

        # return result array

        return $arr;
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @getprofile FOR GET USER PROFILE
    |----------------------------------------------------------------------
    */

    public function getProfile()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params
        if ($this->verifyRequiredParams(array("email"))) {
            // check for correct email and password
            if ($user_id = $this->apis->checkLogin($email)) {

               $baseUrl = base_url('uploads');
                // get the user by email
                $user = $this->apis->getUserById($user_id, "id,name,mobile,accessToken, CONCAT('$baseUrl','/',image) as image");

                if ($user_id != NULL) {
                    $response["status"] = 200;
                    $response['message'] = "Customer Profile";
                     $response['user'] = $user;
                    echo json_encode($response);
                    exit;
                }
            } else {
                // wrong combination case
                $response['status'] = 204;
                $response['message'] = 'Login failed. Incorrect credentials';
                echo json_encode($response);
                exit;
            }
        }
        exit;
    
        
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @updateProfile FOR UPDATE PROFILE
    |----------------------------------------------------------------------
    */

    public function updateProfile()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params
      if ($this->verifyRequiredParams(array("accessToken", "email", "user_name","mobile")));
            unset($_POST['id']);
            $is_user_exist = $this->apis->getUserByToken($accessToken);
            if (empty($is_user_exist)) {
                $response["status"] = 204;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }
            $user_id = $is_user_exist['id'];

            //Checking Email already exists
            $emailExist = $this->apis->getMultipleRecordWhere($this->tbl_user, array('email' => $email, 'id !=' => $user_id));
            if (count($emailExist) > 0) {
                $response['status'] = 204;
                $response['message'] = "Email exist already, please try another";
                echo json_encode($response);
                exit;
            }

            // upload user picture
            $image = $this->uploadPicture();
            $is_updated = $this->apis->updateData($this->tbl_user, $_POST, array('id' => $user_id));
            if ($is_updated || !empty($image)) {
                $user = $this->apis->getUserByToken($accessToken, 'id,user_name,email,image,mobile');
                // upload user picture
                if (!empty($image)) {
                    $flag = $this->apis->updateData($this->tbl_user, array('image' => $image), array('id' => $user['id']));
                    if ($flag) {
                        $user['image'] = base_url("uploads/" . $image);
                    }
                } else {
                    $user['image'] = base_url("uploads/" . $user['image']);
                }
                //removing password from response
                if ($user != NULL) {

                     $this->successResponseWithData("Successfully updated Profile",$user);

                     }
            } else {
                $response["status"] = 204;
                $response['message'] = 'No New values(changes) to update!';
                echo json_encode($response);
                exit;
            }
        
        exit;
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @uploadPicture FOR UPLOADING IMAGE/AUDIO
    |----------------------------------------------------------------------
    */

    private function uploadPicture()
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

    /*
    |----------------------------------------------------------------------
    | FUNCTION @updatePassword
    |----------------------------------------------------------------------
    */

    public function updatePassword()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params
        if ($this->verifyRequiredParams(array("accessToken", "password"))) {
            unset($_POST['user_id']);
            $is_user_exist = $this->api->getUserByToken($accessToken);
            if (empty($is_user_exist)) {
                $response["status"] = 204;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }

            if (strlen($password) < 8) {
                $response["status"] = 204;
                $response['message'] = 'Password shoul be at least 8 characters long';
                echo json_encode($response);
                exit;
            }

            // if ($is_user_exist['password'] == md5($password)) {
            //     $response["status"] = 500;
            //     $response['message'] = 'You cant use this password';
            //     echo json_encode($response);
            //     exit;
            // }

            $user_id = $is_user_exist['id'];
            $is_updated = $this->api->updateData($this->tbl_user, array('password' => md5($password), 'forgot_password' => ''), array('id' => $user_id));
            $user = $this->api->getUserById($user_id, 'id,username,email,image,accessToken,password,forgot_password');

            unset($user['password']);
            unset($user['forgot_password']);

            //removing password from response
            $user['image'] = base_url("uploads/" . $user['image']);



            //if ($is_updated) {
            $response["status"] = 200;
            $response['message'] = "Password successfully updated";
            $response['user'] = $user;
            echo json_encode($response);
            exit;
            // } else {
            //     $response["status"] = 500;
            //     $response['message'] = 'Unable to update your password';
            //     echo json_encode($response);
            //     exit;
            // }
        }
        exit;
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @deleteAccount
    |----------------------------------------------------------------------
    */
    public function deleteAccount()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params
        if ($this->verifyRequiredParams(array("accessToken"))) {
            unset($_POST['user_id']);
            $is_user_exist = $this->api->getUserByToken($accessToken);
            if (empty($is_user_exist)) {
                $response["status"] = 500;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
            }
            $user_id = $is_user_exist['id'];
            $is_deleted = $this->api->deleteRecord($this->tbl_user, array('id' => $user_id));

            if ($is_deleted) {
                $this->api->deleteRecord($this->tbl_recordings, array('user_id' => $user_id));
                $this->api->deleteRecord($this->tbl_followers, array('fromId' => $user_id));
                $this->api->deleteRecord($this->tbl_followers, array('toId' => $user_id));
                $response["status"] = 200;
                $response['message'] = "Account successfully deleted";
                echo json_encode($response);
                exit;
            } else {
                $response["status"] = 204;
                $response['message'] = 'Unable to delete your account';
                echo json_encode($response);
                exit;
            }
        }
        exit;
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @getprivacy
    |----------------------------------------------------------------------
    */
    public function getprivacy()
    {
        $response = array();
        $cms = $this->api->getSingleRecordWhere($this->tbl_cms, array('title' => 'Privacy Policy'));
        if (count($cms) > 0) {
            header('Content-type: text/html');
            echo $cms->description;
            exit;
        } else {
            $response["status"] = 204;
            $response['message'] = 'No cms pages found';
            echo json_encode($response);
            exit;
        }
    }
    public function getUploadTime()
    {
        $response = array();
        $upload_time = $this->api->getSingleRecordWhere($this->tbl_settings, array('field' => 'upload_time'));
        if (count($upload_time) > 0) {
            return (int) $upload_time->value * 60;
        } else {
            return 5 * 60;
        }
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @gettermsofservices
    |----------------------------------------------------------------------
    */
    public function gettermsofservices()
    {
        $response = array();
        $cms = $this->api->getSingleRecordWhere($this->tbl_cms, array('title' => 'Terms of Service'));
        if (count($cms) > 0) {
            header('Content-type: text/html');

            echo $cms->description;
            exit;
        } else {
            $response["status"] = 500;
            $response['message'] = 'No cms pages found';
            echo json_encode($response);
            exit;
        }
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @forgotPassword
    |----------------------------------------------------------------------
    */
    public function forgotPassword()
    {
        // check request type
        $this->checkRequest();
        extract($_POST);
        $response = array();
        //Validating Required Params
        if ($this->verifyRequiredParams(array("email"))) {

            $userRecord = $this->api->getMultipleRecordWhere($this->tbl_user, array('email' => $email, 'social_type' => ''));


            if (count($userRecord) == 0) {
                $response["status"] = 500;
                $response['message'] = 'Invalid email';
                echo json_encode($response);
                exit;
            }
            $user = $userRecord['0'];
            $password = rand(10000000, 99999999);

            $this->sendForgotPasswordEmail($email, $password, $user->id);
        }
        exit;
    }

    /*
    |----------------------------------------------------------------------
    | FUNCTION @sendForgotPasswordEmail
    |----------------------------------------------------------------------
    */
    public function sendForgotPasswordEmail($email, $password, $id)
    {
        $updatePassword = md5($password);
        $to = $email;
        $subject = 'Vocally REST Password Key';
        $message = "Please login using this temporary password and then update your password.\n Temporary Password: " . $password;
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $is_updated = $this->api->updateData($this->tbl_user, array('forgot_password' => $updatePassword), array('id' => $id));
            if ($is_updated) {
                $response["status"] = 200;
                $response['message'] = 'Temporary password has been sent on your email!';
                echo json_encode($response);
                exit;
            } else {
                $response["status"] = 500;
                $response['message'] = 'Failed to update password!';
                echo json_encode($response);
                exit;
            }
        } else {
            $response["status"] = 500;
            $response['message'] = 'Invalid email';
            echo json_encode($response);
            exit;
        }
    }
       /*
    |----------------------------------------------------------------------
    | FUNCTION @Send Record to Email Address
    |----------------------------------------------------------------------
    */
    public function sendnewEmail(){
        //Load email library
        extract($_POST);
        $file=$this->uploadFile($_FILES);
        if($file)
        {
          $this->load->library('email');
          $this->load->helper('path');
          $path = set_realpath('./uploads/');
          //SMTP & mail configuration
          $config = array(
              'protocol'  => 'smtp',
              'smtp_host' => 'mail.glowingsoft.com',
              'smtp_port' => 465,
              'smtp_user' => 'sendemailexcelfile@glowingsoft.com',
              'smtp_pass' => 'DW@HO6ODn2Hs',
              'mailtype' => 'html',
              'smtp_crypto' => 'ssl',
              'charset' => 'iso-8859-1',
              'wordwrap' => TRUE
          );
          $this->email->initialize($config);
          $this->email->set_mailtype("html");
          $this->email->set_newline("\r\n");
         
         $this->email->from('noreply@glowingsoft.com');
          $this->email->to($_POST['email']);
          $this->email->subject($_POST['subject']);
          $this->email->message("I have attached the file <a href='".BASE_URL_UPLOADS.'appliancestesting/'.$file."'>Download File<a>");
           $this->email->attach(BASE_URL_UPLOADS.'appliancestesting/'.$file);
          if($this->email->send()){
              echo json_encode(array('status'=>200,'message'=>'Email sent successfully.'));
          }else{
            echo json_encode(array('status'=>204,'message'=>'Failed to send mail. '.$this->email->print_debugger()));    
          }
        }
        else 
        {
            echo json_encode(array('status'=>204,'message'=>'File Not Found. ')); 
        }
      }

          /*
    |----------------------------------------------------------------------
    | FUNCTION @upload Excelfile First to send with email
    |----------------------------------------------------------------------
    */

    private function uploadFile($data)
    {
        $image='';
        if ($data) {
            $info = pathinfo($_FILES['message']['name']);
            $ext = $info['extension'];
            $newname =$_FILES['message']['name'];
            $target = 'uploads/appliancestesting/' . $newname;
            if (move_uploaded_file($_FILES['message']['tmp_name'], $target)) {
                $image = $newname;
            }
        }
        return $image;
    }
 
}
