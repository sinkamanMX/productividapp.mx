<?php

class admin_TdatesController extends My_Controller_Action
{
	protected $_clase 	  = 'mtdates';
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
    
    public function listformsAction(){
    	try{
    		$this->view->layout()->setLayout('blank');
    		$cTipos	 	 = new My_Model_TipoCitas();
			$aForms 	 = Array();
			$tabSelected = 1;
						
    		if($this->_idUpdate){
				$tabSelected = (isset($this->_dataIn['strTabSelected']) &&
    						    $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;
    						    				
				$aForms 	= $cTipos->getFormsByType($this->_idUpdate,$this->_dataUser['ID_EMPRESA']);    						    
    		}
    		
			if($this->_dataOp=='updateElements'){
				$iControlE = 0;
				$aValuesForm = $this->_dataIn['formsValues'];
				if(count($aValuesForm)>0){		
					$delete = $cTipos->deleteForms($this->_idUpdate);					
					for($i=0;$i<count($aValuesForm);$i++){
						$aResult = false;
						$aElement = $aValuesForm[$i];
						
						if(isset($aElement['id']) && @$aElement['id'] !=""){
							$aResult =  $cTipos->insertElement($aElement,$this->_idUpdate,$this->_dataUser['ID_EMPRESA']);							
						}else{
							$aResult = true;
						}
						
						if($aResult){
							$iControlE++;
						}						
					}
					
					if($iControlE==count($aValuesForm)){
						$sUrl		= '/dbman/main/getdatainfo?ssIdource='.$this->aDbManInfo['CLAVE_MODULO'].'&catId='.$this->_idUpdate.'&strTabSelected='.$tabSelected.'&optResult=okOperation';
	    				$this->_redirect($sUrl);						
					}
				}
			}
			    			
			$this->view->aDataForms   = $aForms;
			$this->view->tabSelected  = $tabSelected;    		
		}catch(Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }     	
    }   

    public function extrasAction(){
    	try{
			$this->view->layout()->setLayout('blank');  
			$cFunctions 	= new My_Controller_Functions();  
			$cExtras 		= new My_Model_Extras();
			$aTipos 		= $cExtras->getTipoElementos();		
			$cCatalogos		= new My_Model_Catalogos();	
    		$aElementos	 	= Array();
    		$aCatalogos		= $cCatalogos->getCbo($this->_dataUser['ID_EMPRESA']);
    		$tabSelected 	= (isset($this->_dataIn['strTabSelected']) &&
    						    $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;	    		
    		
			if($this->_idUpdate>0){
				$aElementos	  = $cExtras->getElementos($this->_idUpdate);
			}
				
    		if($this->_dataOp=='updateElements'){
				$iControlE = 0;
				$aValuesForm = $this->_dataIn['aElements'];
				if(count($aValuesForm)>0){
					for($i=0;$i<count($aValuesForm);$i++){	
						$aResult = false;											
						$aElement = $aValuesForm[$i];

						if($aElement['op']=='new' && $aElement['id']==-1){
							$aResult = $cExtras->insertElement($aElement,$this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
						}else if($aElement['op']=='up' && $aElement['id']>-1){
							$aResult = $cExtras->updateRowRel($aElement);
						}else if($aElement['op']=='del' && $aElement['id']>-1){
							$aResult = $cExtras->deleteRowRel($aElement,$this->_idUpdate);
						}
						if($aResult){
							$iControlE++;
						}
					}
										
					if($iControlE==count($aValuesForm)){					
						$sUrl		= '/dbman/main/getdatainfo?ssIdource='.$this->aDbManInfo['CLAVE_MODULO'].'&catId='.$this->_idUpdate.'&strTabSelected='.$tabSelected.'&optResult=okOperation';
	    				$this->_redirect($sUrl);
					}
				}
			}
			
			$this->view->aTipos 		= $aTipos;
    		$this->view->aElements		= $this->processFields($aElementos);
    		$this->view->selectStatus	= $cFunctions->cboStatus();
    		$this->view->selectCatalogs	= $cFunctions->selectDb($aCatalogos);
    		$this->view->selectTipos	= $cFunctions->selectDb($aTipos);
    		$this->view->selectReq 		= $cFunctions->cboOptions();
		}catch(Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }   
    }
    
	public function processFields($aElements){
		$aResult = Array();
		$cExtras		= new My_Model_Extras();
		$cFunctions 	= new My_Controller_Functions();		
		$cCatalogos		= new My_Model_Catalogos();
		$aTipos 		= $cExtras->getTipoElementos();
		$aCatalogos		= $cCatalogos->getCbo($this->_dataUser['ID_EMPRESA']);
		
		foreach($aElements as $key => $items){
			$items['cboStatus'] 	= $cFunctions->cboStatus($items['ACTIVO']);
			$items['cboTipo']		= $cFunctions->selectDb($aTipos,$items['ID_TIPO_ELEMENTO']);
			$items['cboReq'] 		= $cFunctions->cboOptions($items['REQUERIDO']);
			$items['cboCat'] 		= $cFunctions->selectDb($aCatalogos,$items['ID_USR_CATALOGO']);
			$aResult[] = $items;
		}		
		return $aResult;
	}       
}