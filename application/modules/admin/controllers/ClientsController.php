<?php

class admin_ClientsController extends My_Controller_Action
{
	protected $_clase 	  = 'mclients';
	protected $_keyModule = '';
	public 	  $aDbManInfo = Array();
	
    public function init()
    {
    	try{   					
    		$cPerfiles	= new My_Model_Perfiles();
    		$this->validateSession();
    		
    		$this->_keyModule		= $this->_clase;
    		$this->view->moduleInfo = $cPerfiles->getDataModule($this->_keyModule);

			$cDbman 	   	  = new My_Model_DbmanConfig();
    		$this->aDbManInfo = $cDbman->getData($this->_keyModule);
			$this->view->DbmanInfo   = $this->aDbManInfo;		    					    	    
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    
    public function listaddressAction(){
    	try{
			$this->view->layout()->setLayout('layout_blank');  			
    		$cClients	= new My_Model_Clientes();
    		$cFuntions	= new My_Controller_Functions();
    		$aAddress	= Array();
    		
    		if($this->_idUpdate){
				$tabSelected = (isset($this->_dataIn['strTabSelected']) &&
    						    $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;    			
    			$aAddress	= $cClients->getAddress($this->_idUpdate);
    			
    			if($this->_dataOp=='updateElements'){
					$iControlE = 0;
					$aValuesForm = $this->_dataIn['aElements'];
					if(count($aValuesForm)>0){
						for($i=0;$i<count($aValuesForm);$i++){	
							$aResult = false;											
							$aElement = $aValuesForm[$i];
							$aElement['idCliente'] = $this->_idUpdate;
							if($aElement['op']=='new' && $aElement['id']==-1){
								$aResult = $cClients->insertAddress($aElement,$this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
							}else if($aElement['op']=='up' && $aElement['id']>-1){
								$aResult = $cClients->updateAddress($aElement,$this->_idUpdate);								
							}else if($aElement['op']=='del' && $aElement['id']>-1){
								$aResult = $cClients->deleteAddress($aElement,$this->_idUpdate);
							}
							
							if($aResult){
								$iControlE++;
							}
						}
						
						if($iControlE==count($aValuesForm)){
							$this->_resultOp = 'okRegister';
							$sUrl		= '/dbman/main/getdatainfo?ssIdource='.$this->aDbManInfo['CLAVE_MODULO'].'&catId='.$this->_idUpdate.'&strTabSelected='.$tabSelected.'&optResult=okOperation';
    						$this->_redirect($sUrl);
						}
					}
				}    			
    		}else{
    			$this->_redirect($this->aDbManInfo['SCRIPT']);
    		}   

    		
    		$this->view->aEstatus	= $cFuntions->cboStatus();
    		$this->view->aAddress = $this->processFields($aAddress);    		
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
	public function processFields($aElements){
		$aResult 	= Array();
		$cFunctions = new My_Controller_Functions();		
		
		foreach($aElements as $key => $items){
			$items['cboStatus'] 	= $cFunctions->cboStatus($items['ESTATUS']);
			$aResult[] = $items;
		}
		return $aResult;
	}     
}