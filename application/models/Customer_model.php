<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Customer_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $today =  date('Y-m-d');
    }

    /**
     * All Api functions
     */

    function mail_exists($key)
    {
        $this->db->where('email', $key);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
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

    public function checkLogin($email)
    {
        // fetching user by email
       $user_type = 5;
        $query = "SELECT id
                    FROM `users`
                    WHERE (`email` = '$email')
                    AND `user_type` = $user_type
                    LIMIT 1";
        $user = $this->db->query($query)->result_array();

        if (count($user) > 0) {
            $this->db->where('id', $user[0]['id']);
            $data = array("accessToken" => $this->generateRandomString());
            //    $dbExi = $this->db->update('users', $data);
            return $user[0]['id'];
        } else {
             // wrong combination case
                $response['status'] = 204;
                $response['message'] = 'Login failed. Incorrect credentials';
                echo json_encode($response);
                exit;        }
    }


     public function checkLogins($email)
    {
        // fetching user by email
       $user_type = 5;
        $query = "SELECT id
                    FROM `users`
                    WHERE (`email` = '$email')
                    AND `user_type` = $user_type
                    LIMIT 1";
        $user = $this->db->query($query)->result_array();

        if (count($user) > 0) {
   
            return $user[0]['id'];
        } else {
            return false;
        }
    }


 public function checkVerification($verification_code, $email)
    {
        // fetching user by email
       $user_type = 5;
        $query = "SELECT id
                    FROM `users`
                    WHERE (`verification_code` = '$verification_code')
                    AND `user_type` = $user_type
                    AND `email` = '$email'
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

    public function verification($email)
    {
        // fetching user by email
       $user_type = 5;
        $query = "SELECT id
                    FROM `users`
                    WHERE (`email` = '$email')
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

     /*Get single row data*/
        public function getSingleRow($table, $condition)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($condition);
            $query = $this->db->get();
            return $query->row();       
        }

        /*Insert and get last Id*/
        public function insertGetId($table,$data)
        {
            $this->db->insert($table, $data);
            return $this->db->insert_id();
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
    public function getUserByToken($token, $fields = '*')
    {
        $user = $this->db
            ->select($fields)
            ->where('accessToken', $token)
            ->limit(1)
            ->get('users')
            ->result_array() ;
            // echo $this->db->last_query();
            //  exit();
        if (count($user) > 0) {
            return $user[0];
        } else {
            $response['status'] = 402;
                $response['message'] = 'Invalid or expired access token';
                echo json_encode($response);
                exit;
        }
    }

      public function getUserByVerification($verification, $fields = '*')
    {
        $user = $this->db
            ->select($fields)
            ->where('verification_code', $verification)
            ->limit(1)
            ->get('users')
            ->result_array() ;
            // echo $this->db->last_query();
            //  exit();
        if (count($user) > 0) {
            return $user[0];
        } else {
           return null;
        }
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

    function orderProfile($data)
    {
        $fields = "*";
        $response["is_order"] = false;
        $response["categories"] = $this->getMultipleRecordWhere("categories", array("is_active" => 1), "*", "title", "ASC");
        $response["dishes"] = $this->getCategory("categories",  array('is_active' => 1), $fields, "title", "ASC");
        $response["settings"] = $this->getSingleRecordWhere("settings", array("id >" => 0));
        $response['cart_order'] = $this->cartOrderProfile($data);
        $response['table'] = $this->getSingleRecordWhere("t_tables",array('id'=>$data["table_id"]));
        $response['waiter'] = $this->getSingleRecordWhere("users",array('id'=>$data["user_id"]),"name");
        return $response;
    }
    function getCategory($tbl, $condition =  NULL, $fields = '*', $sortField = NULL, $sortOrder = NULL)
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
        $response = array();
        foreach ($result->result() as $row) {
            $response[] = $this->getCategoriesWithDishes($row->id);
        }
        return $response;
    }
    function getCategoriesWithDishes($category_id)
    {
        $url = BASE_URL_UPLOADS;
        $fields = "products.*,products.title as dish_name,categories.title as cat_title,CONCAT('$url','',products.image) as image";
        $tbls = array(
            array('tbl' => 'products', 'cond' => 'products.category_id = categories.id', 'type' => 'right')
        );
        return $this->selectJoinTablesMultipleRecord("categories", $tbls, array("categories.id" => $category_id, "products.is_active" => 1), $fields);
    }
    function addDishCart($data)
    {
        $response = array();
        if (!isset($data["is_delete"])) {
            //if request is not to delete row
            $isAlready = $this->getSingleRecordWhere("cart", array("product_id" => $data['product_id'], "table_id" => $data['table_id'], "user_id" => $data['user_id']));
            if ($isAlready) {
                if ($data['is_increment'] == 4) {
                    unset($data['is_increment']);
                    $updated = $this->updateData("cart", array("item_price" => $data["item_price"]), array('id' => $isAlready->id));
                } else if ($data['is_increment'] == 0) {
                    unset($data['item_price']);
                    $isDecreased = $this->decreaseCartQty($data, $isAlready);
                } else {
                    unset($data['item_price']);
                    $isIncreased = $this->increaseCartQty($data, $isAlready);
                }
            } else {
                //get item_original price
                $product = $this->getSingleRecordWhere("products", array("id" => $data["product_id"]));
                $data['item_price'] = $product->price;

                unset($data['is_increment']);
                $id = $this->insertData("cart", $data);
            }
        }
        //Delete if any dish inside cart with 0 Quantity
        $this->deleteRecord("cart", array('qty' => 0));
        
        $response["cart_order"] = $this->cartOrderProfile($data);
        // $response["settings"] = $this->getSingleRecordWhere("settings", array("id >" => 0));
        $response["table_status"] = $this->checkTableStatus($data);
        return $response;
    }
    
    function checkTableStatus($data){
        $cartItems = $this->cartOrderProfile($data);
        $isExisits = (object) ["id"=>0];
        if(count($cartItems)>0){
            $isExisits = $this->getSingleRecordWhere("table_order_status", array("table_id" => $data['table_id'], "user_id" => $data['user_id']));
            if($isExisits==null){
                $status_zero = "0";
                $this->insertData("table_order_status",array("table_id" => $data['table_id'], "user_id" => $data['user_id'], "status"=>$status_zero));
            }
            $isExisits = $this->getSingleRecordWhere("table_order_status", array("table_id" => $data['table_id'], "user_id" => $data['user_id']));
        }else{
            $isDelete = $this->deleteRecord("table_order_status", array("table_id" => $data['table_id'], "user_id" => $data['user_id']));
        }
        return $isExisits;
    }

    function increaseCartQty($data, $row)
    {
        $qty = $row->qty + 1;

        if ($data['is_increment'] == 2) {
            $qty = $data['qty'];
        }
        $updated = $this->updateData("cart", array("qty" => $qty), array('id' => $row->id));
    }

    function decreaseCartQty($data, $row)
    {
        $qty = $row->qty - 1;
        if ($qty <= 0) {
            $isDelete = $this->deleteRecord("cart", array('id' => $row->id));
        } else {
            $updated = $this->updateData("cart", array("qty" => $qty), array('id' => $row->id));
        }
    }

    function cartOrderProfile($data)
    {
        $response = array();
        $fields = "cart.*,products.title,products.price,products.id as product_id";
        $tbls = array(
            array('tbl' => 'products', 'cond' => 'products.id = cart.product_id', 'type' => 'left')
        );
        return $this->selectJoinTablesMultipleRecord("cart", $tbls, array("cart.user_id" => $data['user_id']), $fields);
    }
    function mobileDishes($data){
        $baseUrl = base_url('uploads');
        $fields = "*,CONCAT('$baseUrl','/',image) as image";
        $products = $this->api->getMultipleRecordWhere("products", array("is_active" => "1"), $fields, "category_id", "ASC");
        $allProducts = array();
        for($i=0; $i<count($products);$i++){
            $eachProduct = $products[$i];
            $isProductInCart= $this->getSingleRecordWhere("cart",array("product_id"=>$eachProduct->id,"user_id"=>$data["user_id"],"table_id"=>$data["table_id"]));
            if($isProductInCart!=null){
                $eachProduct->qty=$isProductInCart->qty;
                $eachProduct->price=$isProductInCart->item_price;
            }else{
                $eachProduct->qty=0;
            }
            
            $allProducts[]=$eachProduct;
        }
        return $allProducts;
    }

    function completeCartOrder($data)
    {
        $response = array();
        //STEP 1: CREATE ORDER
        $createOrderData = array("user_id" => $data['user_id'], "customer_id" => $data["customer_id"], "table_id" => $data["table_id"], "total_persons" => $data["total_persons"], "cash_received" => $data["cash_received"], "cash_change" => $data["cash_change"]);
        $order_id = $this->insert("orders", $createOrderData);
        if (empty($order_id) || $order_id == null) {
            $response['status'] = false;
        }
        $cartData = $this->cartOrderProfile($data);
        //STEP 2: CREATE ORDER ITEMS
        for ($i = 0; $i < count($cartData); $i++) {
            $cartItem = $cartData[$i];
            $orderItemData = array("order_id" => $order_id, "product_id" => $cartItem->product_id, "quantity" => $cartItem->qty, "item_price" => $cartItem->item_price);
            $item_id = $this->insert("order_items", $orderItemData);
            if ($item_id) {
                $isDelete = $this->api->deleteRecord("cart", array("id" => $cartItem->id));
            } else {
                $response['status'] = false;
            }
        }
        //STEP 2: COMPLETE ORDER
       $response = $this->changeOrderStatus(array("id" => $order_id, "status" => COMPLETED));
        
        //STEP 3: remove from table_order_status
        $this->checkTableStatus($data);
        return $response;
    }



    //CartCompleted Api Function

    function cartCompletedOrder($data)
    {
        $response = array();
        //STEP 1: CREATE ORDER
        $createOrderData = array("user_id" => $data['user_id'], "customer_id" => $data["customer_id"], "table_id" => $data["table_id"], "total_persons" => $data["total_persons"], "cash_received" => $data["cash_received"], "cash_change" => $data["cash_change"]);
        $order_id = $this->insert("orders", $createOrderData);
        if (empty($order_id) || $order_id == null) {
            $response['status'] = false;
        }
        $cartData = $this->cartOrderProfile($data);
        //STEP 2: CREATE ORDER ITEMS
        for ($i = 0; $i < count($cartData); $i++) {
            $cartItem = $cartData[$i];
            $orderItemData = array("order_id" => $order_id, "product_id" => $cartItem->product_id, "quantity" => $cartItem->qty, "item_price" => $cartItem->item_price);
            $item_id = $this->insert("order_items", $orderItemData);
            if ($item_id) {
                $isDelete = $this->api->deleteRecord("cart", array("id" => $cartItem->id));
            } else {
                $response['status'] = false;
            }
        }
        //STEP 2: COMPLETE ORDER
      $response = $this->changeStatusOrders(array("id" => $order_id, "status" => 0));
        
        //STEP 3: remove from table_order_status
        $this->checkTableStatus($data);
        return $response;
    }

      function changeStatusOrders($data)
       {
        $response = array();
        $response['status'] = true;
        extract($data);
        $data = array('updated_at' => date('y-m-d H:i:s'), 'status' => $status);
        if ($status == 0) {
            $order = $this->api->getOrderById($id);
            $data["service_charges"] = $order["extras"]->service_charges;
            $data["total_service_charges"] = $order["extras"]->total_service_charges;
            $data["total_tax"] = $order["extras"]->tax;
            $data["tax_percentage"] = $order["extras"]->tax_percentage;
            $data["total_cost"] = $order["extras"]->totalOrderCost;
        }
        $result = $this->db->where("id", $id)->update($this->tbl, $data);
        if ($this->db->affected_rows() > 0) {
            $response['status'] = true;
            $response['order_id'] = $id;
        } else {
            $response['status'] = false;
        }
        return $response;
      }

    //END CartCompleted

    /////////////////////////////////////
    function changeOrderStatus($data)
    {
        $response = array();
        $response['status'] = true;
        extract($data);
        $data = array('updated_at' => date('y-m-d H:i:s'), 'status' => $status);
        if ($status == COMPLETED) {
            $order = $this->api->getOrderById($id);
            $data["service_charges"] = $order["extras"]->service_charges;
            $data["total_service_charges"] = $order["extras"]->total_service_charges;
            $data["total_tax"] = $order["extras"]->tax;
            $data["tax_percentage"] = $order["extras"]->tax_percentage;
            $data["total_cost"] = $order["extras"]->totalOrderCost;
        }
        $result = $this->db->where("id", $id)->update($this->tbl, $data);
        if ($this->db->affected_rows() > 0) {
            $response['status'] = true;
            $response['order_id'] = $id;
        } else {
            $response['status'] = false;
        }
        return $response;
    }

    function getNotifications(){
        $response = array();
        //Get Order completion requested tables
        $query = "SELECT t.*,COALESCE(tos.status, -1) as status,noti.id as notification_id
        FROM t_tables as t
        RIGHT JOIN table_order_status as tos ON (tos.status=2 AND t.id=tos.table_id)
        RIGHT JOIN notifications as noti ON (noti.object_id=t.id AND noti.object_type='COMPLETE_ORDER')
        WHERE(t.is_active=1 AND tos.table_id=t.id)
        GROUP BY t.id
        ORDER BY t.id ASC";

        $response["complete_orders_req"] = $this->db->query($query)->result_array();
        // echo $this->db->last_query();
        // exit();
        $response["notifications"]=$this->getMultipleRecordWhere("notifications", NULL, "*", "updated_at", "DESC");
        $response["unseen"] = count($this->getMultipleRecordWhere("notifications", array("is_seen"=>0), "*", "updated_at", "DESC"));
        $response["status_count"] = $this->getMultipleRecordWhere("notifications", NULL, "count(*) as status", "updated_at", "DESC")[0]->status;
        $query = "SELECT t.* FROM t_tables as t 
                RIGHT JOIN table_order_status as tos ON (tos.table_id=t.id AND tos.status=2)
                WHERE(t.is_active=1)
                ORDER BY t.id ASC";
             
        $response["tables"] = $this->db->query($query)->result_array();
        // echo $this->db->last_query();
        // exit();
        return $response;
    }
    /*
        |----------------------------------------------------------------------
        | GET ALL ORDERS WITH CONDITION
        |----------------------------------------------------------------------
        */
    function dashboardData($val)
    {
        $fields = "*";
        // $response["all"] = $this->getOrders($this->tbl,  array('status !=' => DELETED_ORDER), $fields);
        $response["all"] = $this->getData('books',  array(), $fields, "id", "DESC");
        // $response["settings"] = $this->getSingleRecordWhere("settings", array("id" => 1));
        // $response["kitchen"] = $this->getData($this->tbl, array('status' => KITCHEN), $fields);
        // $response["dining"] = $this->getData($this->tbl, array('status' => DINING), $fields);
        // $response["completed"] = $this->getData($this->tbl, array('status' => COMPLETED), $fields);
        // $response["cancelled"] = $this->getData($this->tbl, array('status' => CANCELLED_ORDER), $fields);
        // $response["parcels"] = $this->getParcels();
        // $response["topHandies"] = $this->getTopHandies();
        return $response;
    }
  function getOrders($tbl, $condition =  NULL, $fields = '*', $sortField = NULL, $sortOrder = NULL)
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
        $response = array();
        foreach ($result->result() as $row) {
            $response[] = $this->getOrderById($row->id);
        }
        return $response;
    }


        /*
        |----------------------------------------------------------------------
        | GET ALL ORDERS WITH CONDITION
        |----------------------------------------------------------------------
        */
    function ordersData($val)
    {
        $fields = "*";
        // $response["all"] = $this->getOrders($this->tbl,  array('status !=' => DELETED_ORDER), $fields);
        $response["all"] = $this->getOrders('books',  array('id >' => 0), $fields, "id", "DESC");
        // $response["settings"] = $this->getSingleRecordWhere("settings", array("id" => 1));
        // $response["kitchen"] = $this->getOrders($this->tbl, array('status' => KITCHEN), $fields);
        // $response["dining"] = $this->getOrders($this->tbl, array('status' => DINING), $fields);
        // $response["completed"] = $this->getOrders($this->tbl, array('status' => COMPLETED), $fields);
        // $response["cancelled"] = $this->getOrders($this->tbl, array('status' => CANCELLED_ORDER), $fields);
        // $response["parcels"] = $this->getParcels();
        // $response["topHandies"] = $this->getTopHandies();
        return $response;
    }

    function getTopHandies()
    {
        $query = "SELECT oi.product_id,p.title,sum(oi.quantity) as quantity
        FROM order_items oi 
        JOIN products as p ON (p.id=oi.product_id)
        GROUP BY oi.product_id
        ORDER BY quantity DESC";
        return $this->db->query($query)->result_array();
    }

    function getData($tbl, $condition =  NULL, $fields = '*', $sortField = NULL, $sortOrder = NULL)
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
        $response = array();
        foreach ($result->result() as $row) {
            $response[] = $this->getOrderById($row->id);
        }
        return $response;
    }

            

      public function getCustomerOrders($customer_id)
    {   

        $this->db->select("orders.id as orderId,SUM(quantity) as total_items,total_cost as price,orders.created_at as date_time,orders.status");
        $this->db->from('orders');
        $this->db->join('order_items','order_items.order_id= orders.id');
        $this->db->where('orders.customer_id',$customer_id);
        $this->db->group_by('order_items.order_id'); 
        $query = $this->db->get();            
        return $query->result();

    }


      public function getResturant()
    {   

        $this->db->select("restaurant_name,restaurant_address");
        $this->db->from('settings');
        $query = $this->db->get();            
        return $query->row();


    }


      public function customerNotifications($customer_id)
    {   

        $this->db->select("id,subject_id as customer_id, message, created_at as dataTime");
        $this->db->from('notifications');
        $this->db->where('subject_id',$customer_id);
        $query = $this->db->get();            
        return $query->result();


    }


      public function getOrderDetails($customer_id)
    {   

        $this->db->select("orders.id as orderId,SUM(quantity) as total_items,total_cost as price ,orders.created_at as date_time,orders.status");
        $this->db->from('orders');
        $this->db->join('order_items','order_items.order_id= orders.id');
        $this->db->where('orders.id',$customer_id);
        $this->db->group_by('order_items.order_id'); 
        $query = $this->db->get();            
        return $query->result();

    }


     public function getCateProductDetails($customer_id)
    {   
        $baseUrl = base_url('uploads');
         $this->db->select("orders.id,product_id,products.title as product_title,quantity as item_quantity,categories.id as category_id ,categories.title category_title,order_items.item_price,CONCAT('$baseUrl','/',products.image) as product_image");
        $this->db->from('orders');
        $this->db->join('order_items','order_items.order_id= orders.id');
        $this->db->join('users','users.id= orders.customer_id');
        $this->db->join('products','products.id= order_items.product_id');
        $this->db->join('categories','categories.id= products.category_id');

        $this->db->where('orders.id',$customer_id);
        $query = $this->db->get();            
        return $query->result_array();

    }



    function getOrderById($order_id)
    {
        $response = array();
        $orderObj = $this->getSingleRecordWhere("books", array("id" => $order_id));
        if ($orderObj->status == 0 || $orderObj->status == 1) {
            $orderObj->since_time = $this->sinceTime(strtotime($orderObj->updated_at));
        } else {
            $orderObj->since_time = date("d M y h:i a", strtotime($orderObj->updated_at));
        }

        $response['order'] = $orderObj;
       // $response['dishes'] = $this->getOderDishes($order_id);
        //$response['waiter'] = $this->getSingleRecordWhere("users", array("id" => $orderObj->user_id), "id,name");
        //$response['table'] = $this->getSingleRecordWhere("t_tables", array("id" => $orderObj->table_id), "*");
       // $response['extras'] = $this->getOrderExtras($response, $order_id);
        $response['settings'] = $this->getSingleRecordWhere("settings", array("id" => 1));
        return $response;
    }

    function sinceTime($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'Hr',
            60 => 'Min',
            1 => 'Sec'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }
    function getOrderExtras($response, $order_id)
    {
        $obj = (object) [];
        $settings = $this->getSingleRecordWhere("settings", array());
        $tax_percentage = $settings->tax_percentage;
        $service_charges = $settings->service_charges;
        $total_service_charges = $service_charges;


        //if admin ask to add service charges perperson
        $obj->is_perperson_service = $is_perperson_service = $settings->is_perperson_service;
        if ($is_perperson_service == 1) {
            $total_service_charges = $response['order']->total_persons * $service_charges;
        }
        $tax = 0;
        $totalOrderCost = 0;

        // make order cost by dishes loop
        for ($i = 0; $i < count($response['dishes']); $i++) {
            $dish = $response['dishes'][$i];
            $totalOrderCost = $totalOrderCost + ($dish->item_price * $dish->quantity);
        }


        //when ask for tax 
        if ($tax_percentage > 0) {
            $tax = (($tax_percentage * $totalOrderCost) / 100);
        }
        $totalOrderCost = $tax + $totalOrderCost;
        $totalOrderCost = $total_service_charges + $totalOrderCost;

        $obj->service_charges = $settings->service_charges;
        $obj->total_service_charges = $total_service_charges;
        $obj->tax = $tax;
        $obj->tax_percentage = $tax_percentage;
        $obj->totalOrderCost = $totalOrderCost;

        //get values if order is already completed 
        if ($response['order']->status == 2) {
            $obj->service_charges = $response['order']->service_charges;
            $obj->total_service_charges = $response['order']->total_service_charges;
            $obj->tax = $response['order']->total_tax;
            $obj->tax_percentage = $response['order']->tax_percentage;
            $obj->totalOrderCost = $response['order']->total_cost;
        }
        return $obj;
    }
    function getOderDishes($order_id)
    {
        $fields = "order_items.*,products.title as dish_name,categories.title as cat_title,categories.image as cat_image";
        $tbls = array(
            array('tbl' => 'products', 'cond' => 'products.id = order_items.product_id', 'type' => 'left'),
            array('tbl' => 'categories', 'cond' => 'products.category_id = categories.id', 'type' => 'left')
        );
        return $this->selectJoinTablesMultipleRecord("order_items", $tbls, array("order_items.order_id" => $order_id), $fields);
    }
    function getParcels()
    {

        $fields = "orders.*";
        $tbls = array(
            array('tbl' => 'orders', 'cond' => 'orders.table_id = t_tables.id', 'type' => 'right')
        );
        return $this->selectJoinTablesMultipleRecord("t_tables", $tbls, array("t_tables.is_parcel" => 1, "t_tables.is_active" => 1), $fields);
    }

    /*
    |----------------------------------------------------------------------
    | SALES - EARNINGS
    |----------------------------------------------------------------------
    */

    function salesData($data)
    {
        $response = array();
        $response["stats"] = $this->totalStats();
        $response["MonthlyRecapByYear"] = $this->monthlyRecap($data);
        $response["DailtyRecapByMonthYear"] = $this->dailyRecap($data);
        return $response;
    }
    function dailyRecap($data)
    {
        $sales = array();
        $saleDays = array();
        $year = date("y");
        $month = date("m");
        if ($month < 10) {
            $month = "0" . $month;
        }

        if (isset($data["dailyFilter"])) {
            $yearMonth = $data["dailyFilter"];
            $year = explode("-", $yearMonth)[0];
            $month = explode("-", $yearMonth)[1];
        }

        $totalSale = 0;
        $totalExpense = 0;
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $response = array();

        for ($day = 1; $day <= $totalDays; $day++) {
            $saleDays[] = $day . " " . DateTime::createFromFormat('!m', $month)->format('M') . " " . DateTime::createFromFormat('Y', $year)->format('y');
            if ($day < 10) {
                $day = "0" . $day;
            }
            $search = $year . "-" . $month . "-" . $day;

            $query = "SELECT sum(total_cost) as total from orders WHERE updated_at LIKE '%$search%' AND status=" . COMPLETED;
            $data = $this->db->query($query)->result_array();
            $Revenue = 0;
            if ($data[0]["total"] != null) {
                $Revenue = (int) $data[0]["total"];
            }
            $sales[] = $Revenue;
            $totalSale = $totalSale + $Revenue;

            //Expenses
            $query = "SELECT sum(amount) as total from expenses WHERE created_at LIKE '%$search%'";
            $data = $this->db->query($query)->result_array();
            $Expense = 0;
            if ($data[0]["total"] != null) {
                $Expense = (int) $data[0]["total"];
                $totalExpense = $totalExpense + $Expense;
            }
            $expenses[] = $Expense;
        }
        $response["sales"] = $sales;
        $response["saleDays"] = $saleDays;
        $response["totalSale"] = $totalSale;
        $response["expenses"] = $expenses;
        $response["totalExpense"] = $totalExpense;
        return $response;
    }
    function monthlyRecap($data)
    {
        $response = array();
        $year = date('Y');
        if (isset($data["monthlyRecapYr"])) {
            $year = $data["monthlyRecapYr"];
        }
        $sales = array();
        $expenses = array();
        $totalSale = 0;
        $totalExpense = 0;
        for ($month = 1; $month <= 12; $month++) {
            $query = "select SUM(total_cost) as total
                    from orders 
                    where year(updated_at) = " . $year . " && month(updated_at) = " . $month . " AND status=" . COMPLETED;
            $data = $this->db->query($query)->result_array();
            $Revenue = 0;
            if ($data[0]["total"] != null) {
                $Revenue = (int) $data[0]["total"];
            }
            $sales[] = $Revenue;
            $totalSale = $totalSale + $Revenue;

            $query = "select SUM(amount) as total
                    from expenses 
                    where year(created_at) = " . $year . " && month(created_at) = " . $month;
            $data = $this->db->query($query)->result_array();
            $Expense = 0;
            if ($data[0]["total"] != null) {
                $Expense = (int) $data[0]["total"];
                $totalExpense = $totalExpense + $Expense;
            }
            $expenses[] = $Expense;
        }
        $response["sales"] = $sales;
        $response["expenses"] = $expenses;
        $response["totalSale"] = $totalSale;
        $response["totalExpense"] = $totalExpense;
        return $response;
    }
    function salesTableData()
    {
        $q = "SELECT o.*,u.name as created_by ,uu.name as customer_name,(SELECT count(quantity) as total_items from order_items where o.id=order_items.order_id) as total_items
        FROM orders as o
        JOIN users as u ON (o.user_id=u.id)
        JOIN users as uu ON (o.customer_id=uu.id)
        ORDER BY cast(o.updated_at as datetime) DESC";
        $data = $this->db->query($q)->result_array();
        return $data;
    }
    function totalStats()
    {
        $obj = (object) [];
        $obj->totalRevenue = CURRENCY . ' ' . $this->getSum("orders", "total_cost");
        $obj->totalCost = CURRENCY . ' ' . $this->getSum("expenses", "amount");
        $obj->totalProfit = CURRENCY . ' ' . ($this->getSum("orders", "total_cost") - $this->getSum("expenses", "amount"));
        $obj->totalOrders = count($this->getMultipleRecordWhere("orders", array("status" => COMPLETED)));

        $today =  date('Y-m-d');
        //Today Revenue
        $q = "SELECT sum(total_cost) as total from orders WHERE updated_at like '%$today%'";
        $todayEarning = $this->db->query($q)->result_array();
        $obj->todayRevenue = CURRENCY . ' ' . '0';
        if ($todayEarning[0]["total"] != null) {
            $obj->todayRevenue = CURRENCY . ' ' . $todayEarning[0]["total"];
        }

        //Today Expense
        $q = "SELECT sum(amount) as total from expenses WHERE created_at like '%$today%'";
        $todayExpense = $this->db->query($q)->result_array();
        $obj->todayExpense = CURRENCY . ' ' . '0';
        if ($todayExpense[0]["total"] != null) {
            $obj->todayExpense = CURRENCY . ' ' . $todayExpense[0]["total"];
        }

        //Today Total Parcels
        $obj->todayTotalParcels = count($this->todayParcelsOrOrders(1));
        $obj->todayTotalOrders = count($this->todayParcelsOrOrders(0));

        return $obj;
    }
    function todayParcelsOrOrders($is_parcel = 1)
    {
        $today =  date('Y-m-d');
        $query = "SELECT o.* FROM orders as o JOIN t_tables as t ON (o.table_id=t.id) where o.created_at like '%$today%' AND t.is_parcel=" . $is_parcel . " AND o.status=" . COMPLETED;
        return $this->db->query($query)->result();
    }
    function getSum($table, $colum)
    {
        $q = "SELECT sum($colum) as total FROM `$table`";
        if ($table == "orders") {
            $q .= " WHERE status=" . COMPLETED;
        }
        $data = $this->db->query($q)->result_array();
        $Revenue = 0;
        if ($data[0]["total"] != null) {
            $Revenue = $data[0]["total"];
        }
        return $Revenue;
    }


    /*
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
}
