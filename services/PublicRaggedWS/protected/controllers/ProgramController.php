<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProgramController extends Controller {

    public function actions() {
        return array(
            'quote' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }   

    /**
     * @return bool
     * @soap    
     */
    public function testService() {        
        return true;
    }

    /**
     * 
     * @return string
     * @soap   
     */
    public function startService() {
        return Program::model()->startSincronization();
        //return $response;
    }

    /**
     * 
     * @return string
     * @soap   
     */
    public function endService() {
        $response = Program::model()->endSincronization();
        if (empty($response)) {
            return false;
        }
        return true;
    }
    
    /**
     * 
     * @return string
     * @soap   
     */
    public function endServiceWithError() {
        $response = Program::model()->endSincronizationWithErrors();
        if (empty($response)) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     * @soap   
     */
    public function selectProcessingMethods() {
        $response = Program::model()->getProcessingMethods();
        return json_encode($response);
    }

    /**
     * @return string
     * @soap   
     */
    public function selectStaticMethods() {
        $response = Program::model()->getStaticMethods();
        return json_encode($response);
    }

    /**
     * 
     * @return string
     * @soap   
     */
    public function selectOptionalMethods() {
        $response = Program::model()->getOptionalMethods();
        return json_encode($response);
    }

    /**
     * 
     * @return string 
     * @soap   
     */
    public function getAllCompanies() {
        $response = DatabaseUtilities::model()->callStoredProcedure("`sp_selectcompaniesforprocess`()");
        return json_encode($response);
    }

    /**
     * @param string
     * @param string
     * @return string 
     * @soap   
     */
    public function selectTable($columns, $table) {
        $response = DatabaseUtilities::model()->select($columns, $table);
        return json_encode($response);
    }    
    
    /**
     * @param string method
     * @param string startDate
     * @param string startTime
     * @param string company
     * @return string 
     * @soap   
     */
    public function createStartHistoricalExecution($method, $startDate, $startTime, $idcompany) {        
        $response = Program::model()->createStartHistoricalExecution($method, $startDate, $startTime, $idcompany);
        return $response;
    }    
    
    /**     
     * @param string idExec
     * @return string 
     * @soap   
     */
    public function updateHistoricalExecution($idExec) {        
        $response = Program::model()->updateHistoricalExecution($idExec);
        return $response;
    }    

    /**
     * @param string JSON Categories
     * @param string startDate
     * @param string startTime     
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setCategories($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.CategoryController');
        $obj = new CategoryController();
        return $obj->getCategory($json, $startDate, $startTime, $idCompany);
    }

    /**
     * @param string JSON Collections
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setCollections($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.CollectionController');
        $obj = new CollectionController();
        return $obj->getCollection($json, $startDate, $startTime, $idCompany);
    }

    /**
     * @param string JSON Colors
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setColors($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.ColorController');
        $obj = new ColorController();
        return $obj->getColor($json, $startDate, $startTime, $idCompany);
    }

    /**
     * @param string JSON Lines
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setLines($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.LineController');
        $obj = new LineController();
        return $obj->getLine($json, $startDate, $startTime, $idCompany);
    }

    /**
     * @param string JSON Marks
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setMarks($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.MarkController');
        $obj = new MarkController();
        return $obj->getMark($json, (string) $startDate, (string) $startTime, $idCompany);
    }

    /**
     * @param string JSON Sectors
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setSectors($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.SectorController');
        $obj = new SectorController();
        return $obj->getSector($json, (string) $startDate, (string) $startTime, $idCompany);
    }

    /**
     * @param string JSON Sizes
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setSizes($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.SizeController');
        $obj = new SizeController();
        return $obj->getSize($json, $startDate, $startTime, $idCompany);
    }

    /**
     * @param string JSON References
     * @param string startDate
     * @param string startTime
     * @param string idCompany
     * @return string 
     * @soap
     */
    public function setReferences($json, $startDate, $startTime, $idCompany) {
        Yii::import('application.controllers.ClassEntities.ReferenceController');
        $obj = new ReferenceController();
        return $obj->getReference($json, $startDate, $startTime, $idCompany);
    }

    /**
     * @param string JSON Customers
     * @param string startDate
     * @param string startTime
     * @param string idCompany 
     * @param bool update 
     * @return string 
     * @soap
     */
    public function setCustomers($json, $startDate, $startTime, $idCompany, $update) {             
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getCustomer($json, $startDate, $startTime, $idCompany, $update);
    }
    
     /**
     * @return string 
     * @soap
     */
    public function getCustomersFilters() {                
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getFilters();
    }
    
    /**
     * @param string JSON Plus
     * @param string startDate
     * @param string startTime
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function setPlus($json, $startDate, $startTime, $idCompany) {         
        Yii::import('application.controllers.ClassEntities.PlusController');
        $obj = new PlusController();
        return $obj->getPlus($json, $startDate, $startTime, $idCompany);
    }
    
     /**     
     * @return string 
     * @soap
     */
    public function truncatePlus() {                    
        $table = "tbls_plus";
        $response = DatabaseUtilities::model()->truncateTable($table);
        return json_encode($response);
    }
    
    
    /**   
     * @param string Table
     * @return string 
     * @soap
     */
    public function truncateTable($table) { 
        return "oe";
        $response = DatabaseUtilities::model()->truncateTable($table);
        return json_encode($response);
    }
    
    /**        
     * @return string 
     * @soap
     */
    public function deleteRouters() {                  
        $response = DatabaseUtilities::model()->callStoredProcedure("`sp_deletesynchronizedrouters`()");
        $table = "tbls_routers";
        DatabaseUtilities::model()->updateAutoIncrement($table);     
        return json_encode($response);
    }
    
    /**
     * @param string JSON Addresses
     * @return string 
     * @soap
     */
    public function setAddresses($json) {             
        
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        $tablename = "tbls_salespoints";
        return $obj->setCustomersInfo($json, $tablename);
    }
    
     /**
     * @param string JSON Currencies
     * @return string 
     * @soap
     */
    public function setCustomerCurrencies($json) {             
        
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        $tablename = "tbls_customerscurrencies";
        return $obj->setCustomersInfo($json, $tablename);
    }
    
    /**
     * @param string JSON Sellers
     * @return string 
     * @soap
     */
    public function setCustomerSellers($json) {             
        
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        $tablename = "tbls_sellers";
        $obj->setCustomersInfo($json, $tablename);
        return $obj->getSellersInfo();
    }
    
    /**
     * @param string JSON Routers
     * @return string 
     * @soap
     */
    public function setCustomerRouters($json) {             
        
        
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        $tablename = "tbls_routers";
        return $obj->setCustomersInfo($json, $tablename);
        
    }

    /**
     * @param string identificationTypes
     * @param string currencies
     * @param string saleDistricts
     * @param string taxGroups
     * @return string 
     * @soap
     */
    public function updateRelatedCustomerTables($identificationTypes, $currencies, $saleDistricts, $taxGroups) {

        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getRelatedCustomerTables($identificationTypes, $currencies, $saleDistricts, $taxGroups);
    }
    
    /**
     
     * @param string users
     * @return string 
     * @soap
     */
    public function updateUserTable($usersJson) {     
        Yii::import('application.controllers.ClassEntities.CustomerController');
        $obj = new CustomerController();
        return $obj->getUsersSellers($usersJson);
    }
    
     /**
     * @param string JSON Customers
     * @param string startDate
     * @param string startTime
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function setOnHandProds($json, $startDate, $startTime, $idCompany) {          
        Yii::import('application.controllers.ClassEntities.InventoryController');
        $tablename = "tbls_inventories";
        $method = "getOnHandProd";
        $type = 'Inventory';
        $insertProcess = 'insertOnHandProds';
        $setProcess = 'setOnHandProds';
        $obj = new InventoryController();
        return $obj->insertInventory($json, $startDate, $startTime, $idCompany, $tablename, $method, $type, $insertProcess, $setProcess);
    }
    
     /**
     * @param string JSON Customers
     * @param string startDate
     * @param string startTime
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function setStockInCutting($json, $startDate, $startTime, $idCompany) {          
        Yii::import('application.controllers.ClassEntities.InventoryController');
        $tablename = "tbls_inventories";
        $method = "getStocksInCutting";
        $type = 'Inventory';
        $insertProcess = 'insertStockInCutting';
        $setProcess = 'setStockInCutting';
        $obj = new InventoryController();
        return $obj->insertInventory($json, $startDate, $startTime, $idCompany, $tablename, $method, $type, $insertProcess, $setProcess);
    }
    
    
     /**
     * @return string 
     * @soap
     */
    public function updateStock() {                
        Yii::import('application.controllers.ClassEntities.InventoryController');
        $obj = new InventoryController();        
        return $obj->updateStock();
    }
    
     /**
     * @param string JSON Customers
     * @param string startDate
     * @param string startTime
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function setCustomerTransactions($json, $startDate, $startTime, $idCompany) {                  
        Yii::import('application.controllers.ClassEntities.CustomerTransactionController');
        $obj = new CustomerTransactionController();
        return $obj->getCustomerTransaction($json, $startDate, $startTime, $idCompany);
    }
    
     /**
     * @param string table_name
     * @return string 
     * @soap
     */
    public function getTableInfo($tablename) {             
        
        Yii::import('application.controllers.ClassEntities.CustomerTransactionController');
        $obj = new CustomerTransactionController();        
        return $obj->getTableInfo($tablename);
    }
    
     /**
     * @param string locations    
     * @return string 
     * @soap
     */
    public function setLocations($locations) {

        Yii::import('application.controllers.ClassEntities.InventoryController');
        $obj = new InventoryController();
        $tablename = "tbls_locations";
        $response = $obj->setInventoryInfo($locations,$tablename);
        return $obj->GetTableInfo($tablename);              
    }
    
    /**
     * @return string 
     * @soap
     */
    public function getLocations() {                
        Yii::import('application.controllers.ClassEntities.InventoryController');
        $obj = new InventoryController();
        $table = "tbls_locations";
        return $obj->getTableInfo($table);
    }
    
     /**
     * @param string idCompany  
     * @return string 
     * @soap
     */
    public function getInventory($idcompany) {                
        Yii::import('application.controllers.ClassEntities.InventoryController');
        $obj = new InventoryController();
        $table = "tbls_inventories";
        return $obj->getInventory($table, $idcompany);
    }
    
    
     /**
     * @param string JSON Customers
     * @param string startDate
     * @param string startTime
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function updateInventory($json, $startDate, $startTime, $idCompany) {          
        Yii::import('application.controllers.ClassEntities.InventoryController');
        $tablename = "tbls_inventories";
        $method = "getStocksInCutting";
        $type = 'Inventory';
        $insertProcess = 'updateInventory';
        $setProcess = 'setInventory';
        $obj = new InventoryController();
        return $obj->insertInventory($json, $startDate, $startTime, $idCompany, $tablename, $method, $type, $insertProcess, $setProcess);
    }
    
    
    /**
     * @param string JSON Plus
     * @param string startDate
     * @param string startTime
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function setPrice($json, $startDate, $startTime, $idCompany) {         
        Yii::import('application.controllers.ClassEntities.PriceController');
        $obj = new PriceController();
        return $obj->getPrice($json, $startDate, $startTime, $idCompany);
    }
    
     /**
     * @return string 
     * @soap
     */
    public function getCurrencies() {                
        Yii::import('application.controllers.ClassEntities.PriceController');
        $obj = new PriceController();
        $table = "tbls_currencies";
        return $obj->getTableInfo($table);
    }
    
     /**
     * @return string 
     * @soap
     */
    public function getCreditPrice() {                
        Yii::import('application.controllers.ClassEntities.PriceController');
        $obj = new PriceController();
        $table = "tbls_creditprice";
        return $obj->getTableInfo($table);
    }
    
    /**
     * @param string priceList    
     * @return string 
     * @soap
     */
    public function setPriceList($priceList) {

        Yii::import('application.controllers.ClassEntities.PriceController');
        $obj = new PriceController();
        $tablename = "tbls_priceslists";
        $response = $obj->setPriceInfo($priceList,$tablename);
        return $obj->GetTableInfo($tablename);              
    }
    
    /**
     * @param string company    
     * @return string 
     * @soap
     */
    public function getOrders($company) {        
        Yii::import('application.controllers.ClassEntities.OrderController');        
        $obj = new OrderController();        
        $response = $obj->getOrders($company);
        return $response;              
    }
    
    
    /**
     * @param string descriptionState    
     * @return string 
     * @soap
     */
    public function getOrderState($descriptionState) {        
        Yii::import('application.controllers.ClassEntities.OrderController');        
        $obj = new OrderController();        
        $response = $obj->getOrderState($descriptionState);
        return $response;              
    }
    
    /**
     * @param string idOrder    
     * @return string 
     * @soap
     */
    public function updateReservation($idOrder) {          
        Yii::import('application.controllers.ClassEntities.OrderController');        
        $obj = new OrderController();        
        $response = $obj->updateReservation($idOrder);
        return $response;              
    }
    
    /**
     * @param string Order    
     * @return string 
     * @soap
     */
    public function updateOrderState($Order) {          
        Yii::import('application.controllers.ClassEntities.OrderController');        
        $obj = new OrderController();        
        $response = $obj->updateOrderState($Order);
        return $response;              
    }
    
    
     /**     
     * @param string tablename    
     * @param string idCompany     
     * @return string 
     * @soap
     */
    public function updateIdState($idCompany, $tablename) {                            
        
        $response = DatabaseUtilities::model()->update($tablename, $idCompany);                        
        return json_encode($response);
    }

    /**
     * @param string class
     * @param string method
     * @param string responsable
     * @param string ex
     * @return bool 
     * @soap
     */
    public function setError($class, $method, $responsable, $ex) {
        return Program::model()->saveException($class, $method, $responsable, $ex);
        //return $response;
    }

    /**
     * @param string mailto
     * @param string message
     * @param string files
     * @return string 
     * @soap
     */
    public function testMail($mailto, $message, $files) {
        //return $message;
        return Program::model()->sendMail($mailto, $message, $files);
        //return $response;
    }       
    
     /**     
     * @param string notification     
     * @return string 
     * @soap
     */
    public function createNotification($notification) {        
        return Program::model()->createNotification($notification);
        //return $response;
    }     
    

    /**
     * @return string 
     * @soap
     */
    public function sendMailProcess() {
        //return "1";
        return Program::model()->sendMailProcess();
        //return $response;
    }

}
