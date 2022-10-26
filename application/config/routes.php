<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
| my-controller/my-method -> my_controller/my_method
*/
//$route['default_controller'] = 'auth';

// $route['default_controller'] = 'welcome';
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;

$route['default_controller'] = 'AdminAuth';
$route['login'] = 'AdminAuth/postLogin';
$route['dashboard'] = 'admin';
/*
|----------------------------------------------------------------------
| SUPPLIER API ROUTES
|----------------------------------------------------------------------
*/
$route['auth/signup'] = 'auth/signup';
$route['auth/login'] = 'auth/Login';
$route['auth/profile/update'] = 'auth/updateProfileImage';
$route['auth/profile/user'] = 'auth/userProfile';

$route['auth/social/login'] = 'auth/socialLogin';
$route['auth/password/forgot'] = 'auth/forgotPassword';
$route['auth/password/verifycode'] = 'auth/verifyPasswordCode';
$route['auth/password/reset'] = 'auth/resetPassword';
$route['auth/changePassword'] = 'auth/changePassword';
$route['auth/updatePassword'] = 'auth/updatePassword';
$route['supplier/getcurrency'] = 'supplier/getCurrency';
$route['supplier/getbrands'] = 'supplier/getBrands';
$route['supplier/updatecurrency'] = 'supplier/updateCurrency';
$route['supplier/brand/logo'] = 'supplier/updateBrandLogo';
$route['supplier/brand/name'] = 'supplier/updateBrand';
$route['supplier/profile'] = 'supplier/getProfile';
$route['supplier/profile/update'] = 'supplier/updateProfile';
$route['supplier/getproductscurrinces'] = 'supplier/getBrandCurrency';
$route['supplier/convert/currency'] = 'supplier/currency_file';
$route['invoice'] = 'Welcome/invoice';

/*
|----------------------------------------------------------------------
| CUSTOMER API ROUTES
|----------------------------------------------------------------------
*/
$route['auth/customer/signup'] = 'auth/customerSignup';
$route['customer/updateprofile'] = 'customer/updateProfile';
$route['customer/getprofile'] = 'customer/getProfile';
$route['customer/getcustomers'] = 'customer/getcustomers';


/*
|----------------------------------------------------------------------
| PRODUCT API ROUTES
|----------------------------------------------------------------------
*/
$route['books'] = 'books/getBooks';
$route['books/add'] = 'books/addBooks';
$route['books/add/fileupload'] = 'books/bookFileUpload';

$route['books/getAll'] = 'books/getBooksAll';
$route['books/bookDetails'] = 'books/bookDetails';

$route['products/update'] = 'products/updateProduct';
$route['books/book'] = 'books/getSingleBook';
$route['auth/user/delete'] = 'auth/userDeleteAccount';


$route['products/delete'] = 'products/deleteProduct';
$route['books/getBooksOfCategory'] = 'books/getBooksOfCategory';
$route['categories/books/all'] = 'books/categorywisebooks';
$route['books/getCategoryBooks'] = 'books/getCategoryBooks';
$route['books/getBooksOfUser'] = 'books/getBooksOfUser';
$route['books/add/reviews'] = 'books/addBookReviews';
$route['books/review'] = 'books/getBookReviews';
$route['books/reviews/all'] = 'books/getAllReviewsBook';

/*
|----------------------------------------------------------------------
| CATEGORY API ROUTES
|----------------------------------------------------------------------
*/
$route['categories/all'] = 'categories/getAllCategories';
$route['cate/all'] = 'categories/getCateAll';

$route['categories/add'] = 'categories/addCategory';
$route['categories/delete'] = 'categories/deleteCategory';
$route['categories/update'] = 'categories/updateCategory';
$route['categories/category'] = 'categories/getSingleCategory';




$route['users'] = 'admin/getUsers';
$route['expenses'] = 'admin/expenses';
$route['expenses/add'] = 'admin/addExpense';
$route['expenses/edit/(:num)'] = 'admin/editExpense/$1';
$route['users/add'] = 'admin/createUser';
$route['users/edit/(:num)'] = 'admin/editUser/$1';
$route['users/save'] = 'admin/saveUser';
$route['users/delete/(:num)'] = 'admin/deleteUser/$1';
$route['users/send-materials/(:num)'] = 'admin/sendMaterials/$1';
$route['users/send/materials/(:num)'] = 'admin/sendMaterialsToUser/$1';
$route['users/show/history/(:num)'] = 'admin/showUserHistory/$1';
$route['settings'] = 'admin/settings';
$route['exportDb'] = 'admin/exportDb';
$route['logout'] = 'admin/logout';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['earnings'] = 'admin/earnings';
$route['expenses'] = 'admin/expenses';
