<?php

class admin_ModulesController extends My_Controller_Action
{
	protected $_clase 	  = 'modules';
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

    public function listfieldsAction(){
		try{
			$tabSelected	= 1;		
    		$this->view->layout()->setLayout('layout_blank');	
    		$cModulos 	= new My_Model_Modulos();
    		$cFunctions = new My_Controller_Functions();
    		
    		$aValidaciones 	= $cModulos->getValidaciones();
    		$aTipos 		= $cModulos->getTipos();
			$aElementos	 	= Array();
    		$tabSelected 	= (isset($this->_dataIn['strTabSelected']) &&
    						    $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;			
			
			if($this->_idUpdate>0){
				$aElementos	  = $cModulos->getElementos($this->_idUpdate);
			}
    		
			if($this->_dataOp=='updateElements'){
				$iControlE = 0;
				$aValuesForm = $this->_dataIn['aElements'];
				if(count($aValuesForm)>0){
					for($i=0;$i<count($aValuesForm);$i++){
						$aResult = false;
						$aElement = $aValuesForm[$i];
											
						if($aElement['op']=='new' && $aElement['id']==-1){
							$aResult = $cModulos->insertElement($aElement,$this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
						}else if($aElement['op']=='up' && $aElement['id']>-1){
							$aResult = $cModulos->updateRowRel($aElement);
						}else if($aElement['op']=='del' && $aElement['id']>-1){
							$aResult = $cModulos->deleteRowRel($aElement,$this->_idUpdate);
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
		
    		$this->view->selectStatus   = $cFunctions->cboStatus();
    		$this->view->selectVal     	= $cFunctions->selectDb($aValidaciones,'');
    		$this->view->selectTipos   	= $cFunctions->selectDb($aTipos,'');			
			$this->view->tabSelected  	= $tabSelected;	
			$this->view->aElements		= $this->processFields($aElementos);
			$this->view->selectOpts     = $cFunctions->cboOptions(); 		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }          
    }    
    
	public function processFields($aElements){
		$cFunctions 	= new My_Controller_Functions();
    	$cModulos 		= new My_Model_Modulos();
    	$cFunctions 	= new My_Controller_Functions();   
    	 	
		$aValidaciones 	= $cModulos->getValidaciones();
    	$aTipos 		= $cModulos->getTipos();
		$aResult = Array();
		
		foreach($aElements as $key => $items){
			$items['cboStatus'] 	= $cFunctions->cboStatus($items['ESTATUS']);
			$items['cboValida'] 	= $cFunctions->selectDb($aValidaciones,$items['ID_VALIDACION']);
			$items['cboTipo']		= $cFunctions->selectDb($aTipos,$items['ID_TIPO_CAMPO']);
			
			$items['cboContenedor'] = $cFunctions->cboOptions($items['DIV_CONTENEDOR']);
			$items['cboOnupdate'] 	= $cFunctions->cboOptions($items['ON_UPDATES']);
			$items['cboOninsert'] 	= $cFunctions->cboOptions($items['ON_INSERTS']);
			$items['cboUsQuery'] 	= $cFunctions->cboOptions($items['QUERY_DEPENDENCIES']);
			$items['cboVisible'] 	= $cFunctions->cboOptions($items['VISIBLE']);
			
			$aResult[] = $items;
		}
		
		return $aResult;
	}    
}