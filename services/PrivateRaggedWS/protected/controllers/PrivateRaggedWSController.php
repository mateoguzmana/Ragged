<?php

/*
 * Create by Activity Technology SAS
 */
ini_set("soap.wsdl.cache_enabled", "0");

class PrivateRaggedWSController extends Controller {

    public function actions() {
        return array(
            'quote' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }

    /*     * **********************************json log********************************************* */

    public function createLog($class, $function, $e) {
        Yii::import('application.extensions.ActivityLog');
        $utility = new ActivityLog();
        $utility->createLog($class, $function, $e);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************AdminUsers********************************************* */

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllUsers($user) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->QueryAllUsers($user);
    }

    /**
     * @return string
     * @soap
     */
    public function DocumentsTypes() {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->DocumentsTypes();
    }

    /**
     * @return string
     * @soap
     */
    public function ProfilesTypes() {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->ProfilesTypes();
    }

    /**
     * @param int
     * @return string
     * @soap
     */
    public function ChangeUserStatus($userid) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->setChangeUserStatus($userid);
    }

    /**
     * @return string
     * @soap
     */
    public function DataforCreateUser() {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->DataforCreateUser();
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveUser($newuser) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->SaveUser($newuser);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DataforEditUser($user) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->DataforEditUser($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveUserEdit($user) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->SaveUserEdit($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SellerFields($profile) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->SellerFields($profile);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DeleteUser($user) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->DeleteUser($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryUserExistence($user) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->QueryUserExistence($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryUserExistenceEdit($user) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->QueryUserExistenceEdit($user);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Privileges********************************************* */

    /**
     * @return string
     * @soap
     */
    public function QueryAllProfiles() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllProfiles();
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllActions($user) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllActions($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllActionsSubmodule($user) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllActionsSubmodule($user);
    }

    /**
     * @param int
     * @return string
     * @soap
     */
    public function QueryProfileEdit($profileid) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryProfileEdit($profileid);
    }

    /**
     * @return string
     * @soap
     */
    public function QueryAllModules() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllModules();
    }

    /**
     * @return string
     * @soap
     */
    public function QueryAllSubmodules() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllSubmodules();
    }

    /**
     * @return string
     * @soap
     */
    public function QueryAllOptions() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllOptions();
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function GetModulesWeb($user) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->GetModulesWeb($user);
    }

    /**
     * @param int
     * @return string
     * @soap
     */
    public function QuerySubmodule($module) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QuerySubmodule($module);
    }

    /**
     * @param int
     * @return string
     * @soap
     */
    public function QueryOptions($submodule) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryOptions($submodule);
    }

    /**
     * @return string
     * @soap
     */
    public function allModSubOpt() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->allModSubOpt();
    }

    /**
     * @return string
     * @soap
     */
    public function QueryAllPrivilegesTypes() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllPrivilegesTypes();
    }

    /**
     * @return string
     * @soap
     */
    public function QueryAllProfilesTypes() {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->QueryAllProfilesTypes();
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveProfile($profile) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->SaveProfile($profile);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveProfileEdit($profile) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->SaveProfileEdit($profile);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DeleteProfile($profile) {
        Yii::import('application.controllers.PrivilegesController');
        $obj = new PrivilegesController();
        return $obj->DeleteProfile($profile);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Collections********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectCollection($user, $item) {
        Yii::import('application.controllers.ClassEntities.CollectionController');
        $obj = new CollectionController();
        return $obj->getAllCollections($user, $item);
    }

    /**
     * @param string Data
     * @return string
     * @soap
     */
    public function UpdateStatusCollection($data) {
        Yii::import('application.controllers.ClassEntities.CollectionController');
        $obj = new CollectionController();
        return $obj->setStatusCollection($data);
    }

    /**
     * @param string Company
     * @param string Status
     * @return string
     * @soap
     */
    public function UpdateAllStatusCollection($company, $status) {
        Yii::import('application.controllers.ClassEntities.CollectionController');
        $obj = new CollectionController();
        return $obj->setAllStatusCollection($company, $status);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Categories********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectCategories($user, $item) {
        Yii::import('application.controllers.ClassEntities.CategoryController');
        $obj = new CategoryController();
        return $obj->getAllCategories($user, $item);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Colors********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectColors($user, $item) {
        Yii::import('application.controllers.ClassEntities.ColorController');
        $obj = new ColorController();
        return $obj->getAllColors($user, $item);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Lines********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectLines($user, $item) {
        Yii::import('application.controllers.ClassEntities.LineController');
        $obj = new LineController();
        return $obj->getAllLines($user, $item);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Marks********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectMarks($user, $item) {
        Yii::import('application.controllers.ClassEntities.MarkController');
        $obj = new MarkController();
        return $obj->getAllMarks($user, $item);
    }

    /*     * ***************************************************************************************** */


    /*     * **********************************References********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectReferences($user, $item) {
        Yii::import('application.controllers.ClassEntities.ReferenceController');
        $obj = new ReferenceController();
        return $obj->getAllReferences($user, $item);
    }

    /**
     * @param string Collections
     * @param string References
     * @param string PriceList
     * @return string
     * @soap
     */
    public function SelectReferenceDetail($collections, $references, $priceList) {
        Yii::import('application.controllers.ClassEntities.ReferenceController');
        $obj = new ReferenceController();
        return $obj->getAllReferenceDetails($collections, $references, $priceList);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Sizes********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectSizes($user, $item) {
        Yii::import('application.controllers.ClassEntities.SizeController');
        $obj = new SizeController();
        return $obj->getAllSizes($user, $item);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************PriceList********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectPriceLists($user, $item) {
        Yii::import('application.controllers.ClassEntities.PriceController');
        $obj = new PriceController();
        return $obj->getAllPriceLists($user, $item);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Sectors********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectSectors($user, $item) {
        Yii::import('application.controllers.ClassEntities.SectorController');
        $obj = new SectorController();
        return $obj->getAllSectors($user, $item);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************Customers********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectCustomers($user, $item) {
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getAllCustomers($user, $item);
    }

    /**
     * @param string Costumers      
     * @return string
     * @soap
     */
    public function SelectCustomersWallet($customers) {
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getAllCustomersWallet($customers);
    }

    /**
     * @param string Costumers 
     * @return string
     * @soap
     */
    public function SelectCustomersSalePoints($customer) {
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getAllCustomersSalePoints($customer);
    }

    /*     * ***************************************************************************************** */


    /*     * **********************************Orders********************************************* */

    /**
     * @param string User
     * @param string Item
     * @return string
     * @soap
     */
    public function SelectRouters($user, $item) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->getRouters($user);
    }

    /**
     * @param string Routers     
     * @return string
     * @soap
     */
    public function SelectCustomersRouters($routers) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->SelectCustomersRouters($routers);
    }

    /**
     * @param string Collections
     * @param string References
     * @param string PriceList
     * @param string Customers
     * @param string Company
     * @return string
     * @soap
     */
    public function SelectOrderDetail($collections, $references, $priceList, $customers, $company) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->getAllOrderDetails($collections, $references, $priceList, $customers, $company);
    }

    /**
     * @param string User 
     * @param string OrderDetailJson 
     * @param string Routers 
     * @param string priceLists 
     * @param string formPays 
     * @param string company 
     * @param string viewDataJson 
     * @return string
     * @soap
     */
    public function SaveOrderTemp($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $viewDataJson) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->SaveOrderTemp($user, $orderDetailJson, $routers, $priceLists, $formPays, $company, $viewDataJson);
    }

    /**
     * @param string idOrderTemp 
     * @return string
     * @soap
     */
    public function GetOrderObservations($idTempOrder) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->GetOrderObservations($idTempOrder);
    }

    /**
     * @param string idOrderTemp 
     * @param string observation 
     * @param string column 
     * @return string
     * @soap
     */
    public function SaveOrderObservations($idTempOrder, $observation, $column) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->SaveOrderObservations($idTempOrder, $observation, $column);
    }

    /**
     * @param string user 
     * @param string company 
     * @return string
     * @soap
     */
    public function SaveOrder($user, $company) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->SaveOrder($user, $company);
    }

    /**
     * @param string user 
     * @param string company 
     * @return string
     * @soap
     */
    public function SelectUncompletedOrder($user) {
        Yii::import('application.controllers.ClassEntities.OrderController');
        $obj = new OrderController();
        return $obj->SelectUncompletedOrder($user);
    }

    /*     * ***************************************************************************************** */

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveProfileType($profiletype) {
        Yii::import('application.controllers.ProfileTypesController');
        $obj = new ProfileTypesController();
        return $obj->SaveProfileType($profiletype);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveProfileTypeEdit($profiletype) {
        Yii::import('application.controllers.ProfileTypesController');
        $obj = new ProfileTypesController();
        return $obj->SaveProfileTypeEdit($profiletype);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DeleteProfileType($profiletype) {
        Yii::import('application.controllers.ProfileTypesController');
        $obj = new ProfileTypesController();
        return $obj->DeleteProfileType($profiletype);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DataforCreateCustomer($userName) {
        Yii::import('application.controllers.CustomerController');
        $obj = new CustomerController();
        return $obj->DataforCreateCustomer($userName);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function getUserCompany($company) {
        Yii::import('application.controllers.AdminUsersController');
        $obj = new AdminUsersController();
        return $obj->getUserCompany($company);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function getDepartmentCountry($country) {
        Yii::import('application.controllers.CustomerController');
        $obj = new CustomerController();
        return $obj->getDepartmentCountry($country);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function getCitiesDepartment($department) {
        Yii::import('application.controllers.CustomerController');
        $obj = new CustomerController();
        return $obj->getCitiesDepartment($department);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function getCompanyCustomerByUser($userName) {
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getCompanyCustomerByUser($userName);
    }

    /**
     * @param string
     * @param string
     * @return string
     * @soap
     */
    public function saveCustomer($jsonData, $userName) {
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->saveCustomer($jsonData, $userName);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function CheckCustomerExists($accountCustomer) {
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->CheckCustomerExists($accountCustomer);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************CompaniesConfiguration********************************************* */

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllCompanies($user) {
        Yii::import('application.controllers.CompaniesConfigurationController');
        $obj = new CompaniesConfigurationController();
        return $obj->QueryAllCompanies($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function ChangeCompanyStatus($company) {
        Yii::import('application.controllers.CompaniesConfigurationController');
        $obj = new CompaniesConfigurationController();
        return $obj->ChangeCompanyStatus($company);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DataforEditCompany($company) {
        Yii::import('application.controllers.CompaniesConfigurationController');
        $obj = new CompaniesConfigurationController();
        return $obj->DataforEditCompany($company);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveCompanyEdit($data) {
        Yii::import('application.controllers.CompaniesConfigurationController');
        $obj = new CompaniesConfigurationController();
        return $obj->SaveCompanyEdit($data);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function LoadBusinessRules($Company) {
        Yii::import('application.controllers.CompaniesConfigurationController');
        $obj = new CompaniesConfigurationController();
        return $obj->LoadBusinessRules($Company);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function ChangeBusinessStatus($BusinessRule) {
        Yii::import('application.controllers.CompaniesConfigurationController');
        $obj = new CompaniesConfigurationController();
        return $obj->ChangeBusinessStatus($BusinessRule);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************EmailsConfiguration********************************************* */

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllCompaniesforEmails($user) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->QueryAllCompaniesforEmails($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllEmailsforCompany($data) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->QueryAllEmailsforCompany($data);
    }

    /**
     * @return string
     * @soap
     */
    public function QueryDataforCreateEmail() {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->QueryDataforCreateEmail();
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function ChangeSendEmailStatus($SendEmail) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->ChangeSendEmailStatus($SendEmail);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryEmailConfigurationExistence($Email) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->QueryEmailConfigurationExistence($Email);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveNewEmailConfiguration($Email) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->SaveNewEmailConfiguration($Email);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DataforEditEmail($email) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->DataforEditEmail($email);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryEmailConfigurationExistenceEdit($Email) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->QueryEmailConfigurationExistenceEdit($Email);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function SaveEmailConfigurationEdit($data) {
        Yii::import('application.controllers.EmailsConfigurationController');
        $obj = new EmailsConfigurationController();
        return $obj->SaveEmailConfigurationEdit($data);
    }

    /*     * ***************************************************************************************** */

    /*     * **********************************OrdersQuery********************************************* */

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllOrders($user) {
        Yii::import('application.controllers.OrdersQueryController');
        $obj = new OrdersQueryController();
        return $obj->QueryAllOrders($user);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryOrders($userquery) {
        Yii::import('application.controllers.OrdersQueryController');
        $obj = new OrdersQueryController();
        return $obj->QueryOrders($userquery);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DataforEditOrder($order) {
        Yii::import('application.controllers.OrdersQueryController');
        $obj = new OrdersQueryController();
        return $obj->DataforEditOrder($order);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function ChangeOrderDetails($orderdetail) {
        Yii::import('application.controllers.OrdersQueryController');
        $obj = new OrdersQueryController();
        return $obj->ChangeOrderDetails($orderdetail);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryBusinessRulesValidation($Order) {
        Yii::import('application.controllers.OrdersQueryController');
        $obj = new OrdersQueryController();
        return $obj->QueryBusinessRulesValidation($Order);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function ChangeValidationBusinessRule($ValBusRule) {
        Yii::import('application.controllers.OrdersQueryController');
        $obj = new OrdersQueryController();
        return $obj->ChangeValidationBusinessRule($ValBusRule);
    }

    /*     * ***************************************************************************************** */

    /*     * *********************************RaggedProcess******************************************* */

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryAllProcess($user) {
        Yii::import('application.controllers.RaggedProcessController');
        $obj = new RaggedProcessController();
        return $obj->QueryAllProcess($user);
    }

    /**
     * @return string
     * @soap
     */
    public function QueryListProcessExcecution() {
        Yii::import('application.controllers.RaggedProcessController');
        $obj = new RaggedProcessController();
        return $obj->QueryListProcessExcecution();
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryListProcessExcecutionUser($userquery) {
        Yii::import('application.controllers.RaggedProcessController');
        $obj = new RaggedProcessController();
        return $obj->QueryListProcessExcecutionUser($userquery);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function QueryListProcessExcecutionDetail($Process) {
        Yii::import('application.controllers.RaggedProcessController');
        $obj = new RaggedProcessController();
        return $obj->QueryListProcessExcecutionDetail($Process);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function RunProcess($Process) {
        Yii::import('application.controllers.RaggedProcessController');
        $obj = new RaggedProcessController();
        return $obj->RunProcess($Process);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function ExcecuteCompleteProcess($Companies) {
        Yii::import('application.controllers.RaggedProcessController');
        $obj = new RaggedProcessController();
        return $obj->ExcecuteCompleteProcess($Companies);
    }

    /**
     * @param string
     * @return string
     * @soap
     */
    public function DataforDashboard($user) {
        Yii::import('application.controllers.DashboardController');
        $obj = new DashboardController();
        return $obj->DataforDashboard($user);
    }

    /**
     * @param string
     * @param string
     * @return string
     * @soap
     */
    public function DataforDashboardReport($date, $company) {
        Yii::import('application.controllers.DashboardController');
        $obj = new DashboardController();
        return $obj->DataforDashboardReport($date, $company);
    }
}
