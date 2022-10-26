<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public $tab_users = 'users';
    public $tab_devices = 'devices';
    public $tab_products = 'products';
    public $tab_supplier_product = 'supplier_products';

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
    | FUNCTION @getCurrency get All Currency
    |----------------------------------------------------------------------
    */


    public function getCurrency()
    {
        // check request type
        $this->api->validateApiKey();
        extract($_GET);

        $currency = $this->apis->getCurrency();
        if (!empty($currency)) {

            $this->api->successResponseWithData('All Currencies', $currency);
        } else {
            $this->api->print_error('currency is not exist');
        }
    }


    /*
 |----------------------------------------------------------------------
 | FUNCTION @getBrandCurrency get All brands and currency
 |----------------------------------------------------------------------
 */


    public function getBrandCurrency()
    {
        // check request type
        $user_id = $this->api->validateApiKey();
        extract($_GET);
        $products = $this->api->getMultipleRecordWhere($this->tab_products, array('user_id' => $user_id));
        $currency = $this->apis->getCurrency();
        if (count($products) > 0) {

            $obj = new stdClass();
            $obj->products = $products;
            $obj->currencies = $currency;
            $this->api->successResponseWithData('Get Products By Currencies', $obj);

        } else {
            $this->api->print_error('Products not found.');
        }
    }


    /*
 |----------------------------------------------------------------------
 | FUNCTION @getProductsByCurrency
 |----------------------------------------------------------------------
 */


    public function getProductsByCurrency()
    {
        // check request type
        $user_id = $this->api->validateApiKey();
        extract($_GET);
        $products = $this->api->getMultipleRecordWhere($this->tab_products, array('user_id' => $user_id));
        $currency = $this->apis->getProductsByCurrency($user_id );
     
        if (count($products) > 0) {

            $obj = new stdClass();
            $obj->products = $products;
            $obj->currencies = $currency;
            $this->api->successResponseWithData('Get Products By Currencies', $obj);

        } else {
            $this->api->print_error('Products not found.');
        }
    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @getprofile FOR GET USER PROFILE
    |----------------------------------------------------------------------
    */

    public function getProfile()
    {
        // check request type
        $user_id = $this->api->validateApiKey();
        extract($_GET);
        $response = array();
        //Validating Required Params
        unset($_GET['id']);

        // check profile
        $profile = $this->auth->getSupplierProfile($user_id);
        // get the user by email
        if ($profile != NULL) {

            $this->api->successResponseWithData('Profile Fetched successfully.', $profile);

        } else {
            $this->print_r('User do not exits.');

        }
    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @updateCurrency FOR UPDATE CURRENCY
    |----------------------------------------------------------------------
    */

    public function updateCurrency()
    {

        $user_id = $this->api->validateApiKey();
        extract($_POST);
        $response = array();
        //Validating Required Params
        $this->verifyRequiredParams(array("currency_id"));
        unset($_POST['id']);
        $is_updated = $this->apis->updateData($this->tab_users, array('currency_id' => $currency_id), array('id' => $user_id));
        if ($is_updated) {
            $this->api->successResponse("Currency Successfully updated");

        } else {

            $this->api->print_error('currency is not updated');

        }

        exit;
    }

    /*
   |----------------------------------------------------------------------
   | FUNCTION @updateBrand FOR UPDATE BRAND
   |----------------------------------------------------------------------
   */

    public function updateBrand()
    {
        // check request type
        $user_id = $this->api->validateApiKey();
        extract($_POST);
        //Validating Required Params
        $this->verifyRequiredParams(array("brand_name"));
        unset($_POST['id']);

        $is_updated = $this->apis->updateData($this->tab_users, array('brand_name' => $brand_name), array('id' => $user_id));
        if ($is_updated) {
            $this->api->successResponse("Brand Successfully updated");

        } else {

            $this->api->print_error("Brand does not updated");
        }


    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @updateBrandLogo FOR UPDATE update brand logo
    |----------------------------------------------------------------------
    */

    public function updateBrandLogo()
    {
        $user_id = $this->api->validateApiKey();
        extract($_POST);
        $response = array();
        //$this->verifyRequiredParams(array("brand_logo"));
        unset($_POST['id']);

        $user = $this->api->getSingleRecordWhere($this->tab_users, array('id' => $user_id));


        // upload user picture
        $brand_logo = $this->uploadBrand($_FILES);
        $is_updated = $this->apis->updateData($this->tab_users, array('brand_logo' => $brand_logo), array('id' => $user_id));
        if (!empty($is_updated) || !empty($brand_logo)) {
            $this->api->successResponse("logo Successfully updated");

        } else {

            $this->api->print_r('logo does not updated.');
        }

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | FUNCTION @uploadPicture FOR UPLOADING IMAGE/AUDIO
    |----------------------------------------------------------------------
    */

    public function uploadFiles()
    {
        //$this->pm->validateApiCodeCommon();
        if (!empty($_FILES['file']['name'])) {
            $data = $this->apis->uploadFile();
            $this->successResponse($data, 'Image Uploaded successfully');
        } else {
            $response["status"] = 204;
            $response['message'] = 'No selected image!';
            echo json_encode($response);
            exit;

        }
    }


    private function uploadBrand()
    {
        $image = '';

        if (isset($_FILES['brand_logo']['name'])) {
            $info = pathinfo($_FILES['brand_logo']['name']);
            $ext = $info['extension'];
            $newname = rand(5, 3456) * date(time()) . "." . $ext;
            $target = 'uploads/' . $newname;
            if (move_uploaded_file($_FILES['brand_logo']['tmp_name'], $target)) {
                $image = $newname;
            }
        }
        return $image;
    }


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
| FUNCTION @updateProfile FOR update Profile
|----------------------------------------------------------------------
*/

    public
    function updateProfile()
    {
        $user_id = $this->apis->validateApiKey();
        extract($_POST);
        $this->api->verifyRequiredParams(array("name", "email", "password", "brand_name"));

        //email test if already exists
        $get_user = $this->apis->getSingleRow($this->tab_users, array('email' => $email, 'id' => !($user_id)));
        if (!empty($get_user)) {
            $this->print_error('Email already exist.');
        }

        if (!empty($image)) {
            $userupdate = $this->api->updateData($this->tab_users, array('name' => $name, 'email' => $email, 'password' => md5($password), 'brand_name' => $brand_name, 'image' => $image), array('id' => $user_id));
        } else {
            $userupdate = $this->api->updateData($this->tab_users, array('name' => $name, 'email' => $email, 'password' => md5($password), 'brand_name' => $brand_name, 'image' => 'noimg.png'), array('id' => $user_id));
        }

        if ($userupdate) {

            $user = $this->auth->getSupplier($user_id);
            $this->api->successResponseWithData('Supplier Successfully updated.', $user);
        } else {
            $this->api->print_error('No New values(changes) to update!');
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
            $info = pathinfo($_FILES['message']['name']);
            $ext = $info['extension'];
            $newname = $_FILES['message']['name'];
            $target = 'uploads/appliancestesting/' . $newname;
            if (move_uploaded_file($_FILES['message']['tmp_name'], $target)) {
                $image = $newname;
            }
        }
        return $image;
    }


    public function currency_file()
    {
            extract($_POST);
            $fromCurrency = urlencode($fromCurrency);
            $toCurrency = urlencode($toCurrency);
            $encode_amount = 1;
            $url  = "https://www.google.com/search?q=".$fromCurrency."+to+".$toCurrency;
            $get = file_get_contents($url);
            $data = preg_split('/\D\s(.*?)\s=\s/',$get);
            $exhangeRate = (float) substr($data[1],0,7);
            $convertedAmount = $amount*$exhangeRate;
            $data = array('fromCurrency' => strtoupper($fromCurrency), 'toCurrency' => strtoupper($toCurrency), 'exhangeRate' => $exhangeRate, 'convertedAmount' =>$convertedAmount);

            if(empty($data))
            {
                $this->api->print_error('currency dont change.');
            }
            else{
                $this->api->successResponseWithData('Successfully Currency converted.', $data);
            }

            //echo json_encode( $data );

    }

}
