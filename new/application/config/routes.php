<?php

defined('BASEPATH') OR exit('No direct script access allowed');


$autoload['model'] = array('menumodel');


$route['default_controller'] = 'LoginController/index';

$route['404_override'] = '';

$route['translate_uri_dashes'] = FALSE;

$route['Authentication'] = 'LoginController/Authentication';

$route['logout'] = 'LoginController/Logout';
$route['Login'] = 'LoginController/index';
$route['dashboard'] = 'Admin/dashboard';
$route['underconstruction'] = 'Admin/underconstruction';

$route['user/add'] = 'User/add';
$route['supplier/manage'] = 'Supplier/manage';
$route['setting/mainmenu'] = 'Setting/manage_mainmenu';


$route['configrations/company'] = 'Configrations/company_manage';
$route['configrations/add-company'] = 'Configrations/company_add';
$route['configrations/edit-company/(:num)'] = 'Configrations/company_edit';

$route['configrations/location/add'] = 'Configrations/location_add';
$route['configrations/location'] = 'Configrations/location';
$route['configrations/location/edit/(:num)'] = 'Configrations/location_edit/$1';

$route['master/accounts/add'] = 'Master/account_add';
$route['master/accounts/edit/(:num)'] = 'Master/account_edit/$1';
$route['master/gst/add'] = 'Master/gst_add';
$route['master/gst/edit/(:num)'] = 'Master/gst_edit/$1';


$route['master/cost-center/add'] = 'Master/cost_add';
$route['master/cost-center/edit/(:num)'] = 'Master/cost_edit/$1';
$route['master/cost-center'] = 'Master/cost/';

$route['master/products/add'] = 'Master/product_add';
$route['master/products'] = 'Master/products';
$route['master/products/edit/(:num)'] = 'Master/product_edit/$1';

$route['master/supplier/add'] = 'Master/supplier_add';
$route['master/supplier'] = 'Master/supplier';
$route['master/supplier/edit/(:num)'] = 'Master/supplier_edit/$1';

$route['configrations/workflow/add'] = 'Configrations/workflow_add';
$route['configrations/workflow'] = 'Configrations/workflow';
$route['configrations/workflow/edit/(:num)'] = 'Configrations/workflow_edit/$1';

$route['p2p/purchase-requisition/add'] = 'P2p/purchase_requisition_add';
$route['p2p/purchase-requisition'] = 'P2p/purchase_requisition';
$route['p2p/purchase-requisition/edit/(:num)'] = 'P2p/purchase_requisition_edit/$1';

$route['configrations/access_allow/(:num)'] = 'Configrations/access_edit/$1';
























//FUNCTIONAL ROUTES

