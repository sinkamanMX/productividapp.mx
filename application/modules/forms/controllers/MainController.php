<?php

class forms_MainController extends My_Controller_Action
{
	protected $_clase 	  = 'mforms';
	protected $_keyModule = '';
	public 	  $aDbManInfo = Array();
	
    public function init()
    {
    	try{   					
    		$cPerfiles	= new My_Model_Perfiles();
    		$this->validateSession();
    		$this->chatOptions();
    		
    		$this->_keyModule		= $this->_clase;
    		$this->view->moduleInfo = $cPerfiles->getDataModule($this->_keyModule);

			$cDbman 	   	  = new My_Model_DbmanConfig();
    		$this->aDbManInfo = $cDbman->getData($this->_keyModule);
			$this->view->DbmanInfo   = $this->aDbManInfo;		 
			$this->view->dataUser['allwindow'] = true;     					    	    
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
	public function getinfoAction(){
		try{
			$cFormularios = new My_Model_Formularios();
			$cFunctions   = new My_Controller_Functions();
			$cTipos		  = new My_Model_TipoFormularios();
			$cCatalogos	  = new My_Model_Catalogos();
			
    		$tabSelected  = (isset($this->_dataIn['strTabSelected']) &&
    						    $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;	

			$aDataInfo	  = Array();
			$aElementos	  = Array();
			$aEstatus	  = '';
			$aQrs  		  = ''; 
			$aFirms 	  = '';
			$aFotos		  = '';
			$aLocalizacion= '';		    
			$aTipos		  = $cTipos->getCbo();	
			$aCatalogos	  = $cCatalogos->getCbo($this->_dataUser['ID_EMPRESA']);
			$this->_resultOp= (isset($this->_dataIn['optResult'])) ?  $this->_dataIn['optResult'] : '';
				
			if($this->_idUpdate>0){
				$aDataInfo 	  = $cFormularios->getData($this->_idUpdate);
				$aEstatus	  = $aDataInfo['ESTATUS'];
				$aQrs  		  = $aDataInfo['QRS_EXTRAS'];
				$aFirms 	  = $aDataInfo['FIRMAS_EXTRAS'];
				$aFotos		  = $aDataInfo['FOTOS_EXTRAS'];
				$aLocalizacion= $aDataInfo['LOCALIZACION'];
				$aElementos	  = $cFormularios->getElementos($this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
			}
			
			if($this->_dataOp=='new' || $this->_dataOp=='update'){
				$valdidate = $cFormularios->validateDataBy($this->_dataIn['inputTitulo'],$this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
				if(count($valdidate)==0){
					$resultOp = Array();
					if($this->_dataOp=='new'){
						$resultOp = $cFormularios->insertRow($this->_dataIn);
						if($resultOp['status']){
							$this->_idUpdate = $resultOp['id'];
							$this->_resultOp = 'okRegister';
						}												
					}else if($this->_dataOp=='update'){
						$resultOp = $cFormularios->updateRow($this->_dataIn);
						$this->_resultOp = 'okRegister';
					}
					
					if($resultOp['status']){
						$this->_resultOp = 'okRegister';   
						$aDataInfo 	  = $cFormularios->getData($this->_idUpdate);
						$aEstatus	  = $aDataInfo['ESTATUS'];
						$aQrs  		  = $aDataInfo['QRS_EXTRAS'];
						$aFirms 	  = $aDataInfo['FIRMAS_EXTRAS'];
						$aFotos		  = $aDataInfo['FOTOS_EXTRAS'];
						$aLocalizacion= $aDataInfo['LOCALIZACION'];						
					}else{
						$aItemsError = Array();
						$aItemsError['inputName'] 	 = 'inputTitulo';
						$aItemsError['bValidate']	 = 'error';
						$aItemsError['MessageError'] = "Ha ocurrido un problema al registrar la informaci—n, favor de intentar mas tarde.";			
						$this->_aErrors['errorAction'] = 1;
						$this->_aErrorsFields[]		   = $aItemsError;
					}												
				}else{
					$aItemsError = Array();
					$aItemsError['inputName'] 	 = 'inputTitulo';
					$aItemsError['bValidate']	 = 'error';
					$aItemsError['MessageError'] = "El titulo ya se encuentra registrado, favor de ingresar una diferente.";			
					$this->_aErrors['errorAction'] = 1;
					$this->_aErrorsFields[]		   = $aItemsError;
				}
			}		

			if($this->_dataOp=='updateOrdes'){
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$answer = Array('answer' => 'no-data');				
				$iControlE = 0;
				$aValuesForm = $this->_dataIn['aElements'];
				if(count($aValuesForm)>0){
					for($i=0;$i<count($aValuesForm);$i++){
						$aResult  = false;											
						$aElement = $aValuesForm[$i];
						
						$aResult = $cFormularios->reOrderElement($aElement);
						if($aResult){
							$iControlE++;
						}
					}

					if($iControlE==count($aValuesForm)){				
						$answer = Array('answer' => 're-ordered'); 					
					}
				}
				
				echo Zend_Json::encode($answer);
	        	die();
			}			
			
			$this->view->aDataInfo 	= $aDataInfo;
		
			$this->view->aEstatus	= $cFunctions->cboStatus($aEstatus);
			$this->view->aQrs		= $cFunctions->cboStatusYesNo($aQrs);
			$this->view->aFirms		= $cFunctions->cboStatusYesNo($aFirms);
			$this->view->aFotos		= $cFunctions->cboStatusYesNo($aFotos);
			$this->view->aLocal		= $cFunctions->cboStatusYesNo($aLocalizacion);
			/*
    		$this->view->selectStatus	= $cFunctions->cboStatus();
    		$this->view->selectCatalogs	= $cFunctions->selectDb($aCatalogos);
    		$this->view->selectTipos	= $cFunctions->selectDb($aTipos);
    		$this->view->selectReq 		= $cFunctions->cboOptions();			
			$this->view->aTipos 		= $aTipos;*/
			
			$this->view->aElements	= $this->processFields($aElementos);			
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->aErrorFields= $this->_aErrorsFields;    		
    		$this->view->tabSelected= $tabSelected;		    			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	
	} 
	
	public function processFields($aElements){
		$cFunctions 	= new My_Controller_Functions();
		$cFormularios 	= new My_Model_Formularios();
		$cTipos			= new My_Model_TipoFormularios();
		$aTipos			= $cTipos->getCbo();
		$cCatalogos	  	= new My_Model_Catalogos();
		$aCatalogos	  	= $cCatalogos->getCbo($this->_dataUser['ID_EMPRESA']);
		$aResult	 	= Array();
		$aElementos     = Array();

		$init      = 0;
		$aControl  = Array();
		$iControl  = 0;
		$iRowControl=0;
		
		foreach($aElements as $key => $items){
			$items['cboStatus'] 	= $cFunctions->cboStatus($items['ESTATUS']);
			$items['cboTipo']		= $cFunctions->selectDb($aTipos,$items['ID_TIPO']);
			$items['cboReq'] 		= $cFunctions->cboOptions($items['REQUERIDO']);
			$items['cboCat'] 		= $cFunctions->selectDb($aCatalogos,$items['ID_USR_CATALOGO']);
			
			if($init==0){				
				$aControl[$iControl] = $items;				
				$init=1;
			}else{
				if($aControl[$iControl]['ID'] == $items['DEPENDE']){
					$aControl[$iControl]['aSubElementos'][] = $items;
				}else{
					$iControl++;
					$aControl[$iControl] = $items;		
				}
			}			
		}

		return $aControl;
	}
		
	public function delelementAction(){
		try{
			/**
			 * Para eliminar un registro se hace por medio de ajax.
			 *
			 * Con estas dos primeras lineas se deshabilita la vista del framework
			 */
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$answer = Array('answer' => 'no-data');
			
    		$cDbman 	   	  = new My_Model_DbmanConfig();
    		$cFunctions   	  = new My_Model_Functions();
    		$cFormularios	  = new My_Model_Formularios();						
    		
    	 	if($this->_dataOp=='delete' && isset($this->_dataIn['catId']) && $this->_dataIn['catId']){
    	 		$idFormulario = $this->_dataIn['ssIdource'];
    	 		$siElement	   = $this->_dataIn['catId'];
    	 		
    	 		$deleteElement = $cFormularios->deleteRowRel($siElement,$idFormulario);
    	 		if($deleteElement['status']){
    	 			$answer = Array('answer' => 'deleted'); 
    	 		}
			}
	        echo Zend_Json::encode($answer);
	        die();			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 		
	}
	
	public function getinfoelementAction(){
		try{
			$this->view->layout()->setLayout('layout_blank');
			$aDataInfo = Array();
			$tabSelected	= 1;
			
			$cFormularios = new My_Model_Formularios();
			$cFunctions   = new My_Controller_Functions();
			$cTipos		  = new My_Model_TipoFormularios();
			$cCatalogos	  = new My_Model_Catalogos();

			$aTipos		  = $cTipos->getCbo();	
			$aCatalogos	  = $cCatalogos->getCbo($this->_dataUser['ID_EMPRESA']);
			$sEstatus     = '';
			$sTipo		  = '';
			$sRequerido   = '';
			$sCatalogo	  = '';
			$sDepende	  = '';
			$sIformaulario= -1;
			$aElementRelacion='-1';
			$aRelations		= Array();
			$aElementosForm	= Array();
			
			if(isset($this->_dataIn['catId']) && isset($this->_dataIn['inputFormulario'])){
				$tabSelected 	= (isset($this->_dataIn['strTabSelected']) && $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;				
				$sIformaulario 	= $this->_dataIn['inputFormulario'];
				$aDataInfo 	 	= $cFormularios->getElementoById($this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
				if(count($aDataInfo)>0){
					$sRequerido	= $aDataInfo['REQUERIDO'];
					$sTipo 		= $aDataInfo['ID_TIPO'];
					$sEstatus	= $aDataInfo['ESTATUS'];
					$sCatalogo	= $aDataInfo['ID_USR_CATALOGO'];
					$sDepende	= $aDataInfo['DEPENDE'];	
					$aElementRelacion  	= $cFormularios->getELementsRel($this->_idUpdate,0);
				}
				
				$aElementosForm = $cFormularios->getElementosForm($this->_idUpdate,$sIformaulario);
				
				if($this->_dataOp=='new' || $this->_dataOp=='update'){
					$resultOp = Array();
					if($this->_dataOp=='new'){
						$resultOp = $cFormularios->insertElement($this->_dataIn,$sIformaulario,$this->_dataUser['ID_EMPRESA']);
						if($resultOp['status']){
							$this->_idUpdate = $resultOp['id'];
							$this->_resultOp = 'okRegister';
						}												
					}else if($this->_dataOp=='update'){
						$this->_dataIn['id'] = $this->_idUpdate;
						$resultOp = $cFormularios->updateRowRel($this->_dataIn);
						$aElementosForm = $cFormularios->getElementosForm($this->_idUpdate,$sIformaulario);						
						$this->_resultOp = 'okRegister';
					}
					
					if($resultOp['status']){
						$this->_resultOp = 'okRegister';
						$aElementosForm = $cFormularios->getElementosForm($this->_idUpdate,$sIformaulario);
						//$this->redirect("/forms/main/getinfoelement?catId=".$this->_idUpdate."&inputFormulario=".$sIformaulario."&strTabSelected=".$tabSelected."&optResult=okOperation"); 
					}else{
						$aItemsError = Array();
						$aItemsError['inputName'] 	 = 'inputTitulo';
						$aItemsError['bValidate']	 = 'error';
						$aItemsError['MessageError'] = "Ha ocurrido un problema al registrar la informaci—n, favor de intentar mas tarde.";			
						$this->_aErrors['errorAction'] = 1;
						$this->_aErrorsFields[]		   = $aItemsError;
					}						
				}
				
				if($this->_dataOp=='updateRelation'){															
					$aElementsDel = $cFormularios->getELementsRel($this->_idUpdate,1);					
					$delete 	  = $cFormularios->delRelations($aElementsDel);					
					$aValuesForm 	= $this->_dataIn['aElements'];
					$aErrorsModules = 0;
					
					if(count($aValuesForm)>0){
						for($i=0;$i<count($aValuesForm);$i++){
							$aDataForm 				 = Array();
							$aDataForm['idElemento'] = $this->_idUpdate;
							$aDataForm['idRelation'] = $aValuesForm[$i];
							
							$insertForm = $cFormularios->setRelations($aDataForm);
							if(!$insertForm['status']){
								Zend_Debug::dump("error al insertar el formulario ".$aValuesForm[$i]);
								$aErrorsModules++;
							}
						}
					}else{
						$aErrorsModules++;
					}
					
					if($aErrorsModules==0){
						$this->_resultOp = 'updateElements';
						//$this->redirect("/forms/main/getinfoelement?catId=".$this->_idUpdate."&inputFormulario=".$sIformaulario."&strTabSelected=2&optResult=okOperation");
						$aDataInfo 	 	= $cFormularios->getElementoById($this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
						if(count($aDataInfo)>0){
							$sRequerido	= $aDataInfo['REQUERIDO'];
							$sTipo 		= $aDataInfo['ID_TIPO'];
							$sEstatus	= $aDataInfo['ESTATUS'];
							$sCatalogo	= $aDataInfo['ID_USR_CATALOGO'];
							$sDepende	= $aDataInfo['DEPENDE'];	
							$aElementRelacion  	= $cFormularios->getELementsRel($this->_idUpdate,0);
						}						
					}else{
						$this->_aErrors['errorModules']   = '1';
					}						
				}				
			}
			$aElementosRel  	= $cFormularios->getAllItemsRel($this->_dataUser['ID_EMPRESA'],$sIformaulario,$this->_idUpdate);
			
			$this->view->data 			= $aDataInfo;	
			$this->view->iFormulario  	= $sIformaulario;	
			$this->view->selectStatus	= $cFunctions->cboStatus($sEstatus);
    		$this->view->selectCatalogs	= $cFunctions->selectDb($aCatalogos,$sCatalogo);
    		$this->view->selectTipos	= $cFunctions->selectDb($aTipos,$sTipo);
    		$this->view->selectReq 		= $cFunctions->cboOptions($sRequerido);			
			$this->view->aTipos 		= $aTipos;
			$this->view->selectDepende 	= $cFunctions->selectDb($aElementosForm,$sDepende);
			$this->view->aElementos		= $aElementosForm;			
			$this->view->resultOp		= $this->_resultOp;
			$this->view->aRelations		= $this->processRelations($aElementosRel,$aElementRelacion);
			$this->view->catId			= $this->_idUpdate;
			$this->view->idToUpdate 	= $this->_idUpdate;
			$this->view->tabSelected  	= $tabSelected;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
	}
	
	public function processRelations($aElements,$aRelations){
		$aResult = Array();
		$aitemsExist = explode(',',$aRelations);
		foreach($aElements as $key => $items){
			$items['ASSOC'] = (in_array($items['ID'], $aitemsExist)) ? '1': '0'; 
			$aResult[] = $items;
		}	
		return $aResult;
	}
	
	
}