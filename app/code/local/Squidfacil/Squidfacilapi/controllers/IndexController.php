<?php
class Squidfacil_Squidfacilapi_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/squidfacilapi?id=15 
    	 *  or
    	 * http://site.com/squidfacilapi/id/15 	
    	 */
    	/* 
		$squidfacilapi_id = $this->getRequest()->getParam('id');

  		if($squidfacilapi_id != null && $squidfacilapi_id != '')	{
			$squidfacilapi = Mage::getModel('squidfacilapi/squidfacilapi')->load($squidfacilapi_id)->getData();
		} else {
			$squidfacilapi = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($squidfacilapi == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$squidfacilapiTable = $resource->getTableName('squidfacilapi');
			
			$select = $read->select()
			   ->from($squidfacilapiTable,array('squidfacilapi_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$squidfacilapi = $read->fetchRow($select);
		}
		Mage::register('squidfacilapi', $squidfacilapi);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
    
    public function notificationAction(){
        // RECEBE O POST DO WEBSERVICE
    }
}