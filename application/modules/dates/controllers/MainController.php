<?php
class dates_MainController extends My_Controller_Action
{
	protected $_clase = 'mdates';
	
    public function init()
    {
    	try{    		
    		$this->validateSession();
			$this->chatOptions();			
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction()
    {
		try{
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cClientes	= new My_Model_Clientes();
						
			$aCbocitas  = $cTipoCita->getCbo($this->_dataUser['ID_EMPRESA']);
			$aTcitas	= $cTipoCita->getTipoCita($this->_dataUser['ID_EMPRESA']);
			
			$sTipoCita 	= "";
			$saCliente	= "";
			$aFormularios= Array();
			$aData		= Array();
			
			$aClientes	= $cClientes->getCbo($this->_dataUser['ID_EMPRESA']);
			$aNamespace = new Zend_Session_Namespace("sService");
									
			if($this->_dataOp=='new' || $this->_dataOp=='update'){
				if(isset($aNamespace->infoService)){
					unset($aNamespace->infoService);
				}
				
				$aNamespace->infoService = $this->_dataIn;
	            $this->_redirect('/dates/main/location');	            
			}
			
			if(isset($aNamespace->infoService)){
				$aData  	= $aNamespace->infoService;
				$sTipoCita	= $aData['inputTipo'];
				$saCliente	= $aData['inputCliente'];
				$aFormularios = $cTipoCita->getFormularios($aData['inputTipo']);	
			}			
			
			$this->view->aData		= $aData;
			$this->view->aEstatus	= $cFuntions->cboStatus();			
			$this->view->aCbocitas 	= $cFuntions->selectDb($aCbocitas,$sTipoCita);
			$this->view->aClientes 	= $cFuntions->selectDb($aClientes,$saCliente);
			$this->view->aFormluarios= $aFormularios;
			$this->view->aTcitas 	= $aTcitas;			
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->aErrorFields= $this->_aErrorsFields;
        }catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        }
    }

    public function locationAction(){
    	try{
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cClientes	= new My_Model_Clientes();    		
			$aData		= Array();			    		
			$aDirecciones= Array();
			$sAddress	= '';
			$aDataLocation= Array();
    		$aNamespace = new Zend_Session_Namespace("sService");
    		    		
			if(!isset($aNamespace->infoService)){
				$this->_redirect('/dates/main/index');				
			}
									
			if($this->_dataOp=='new' || $this->_dataOp=='update'){
				if(isset($aNamespace->locationService)){
					unset($aNamespace->locationService);
				}
				
				$aNamespace->locationService = $this->_dataIn;
	            $this->_redirect('/dates/main/extras');	            
			}	

			if(isset($aNamespace->locationService)){
				$aDataLocation  = $aNamespace->locationService;	
				$sAddress		= $aDataLocation['inputAddress'];
			}
			
			$aData  		= $aNamespace->infoService;
			$aDirecciones	= $cClientes->getAddress($aData['inputCliente']);
			
			$this->view->aAddress 	= $aDirecciones;
			$this->view->cboAddress = $cFuntions->selectDb($aDirecciones,$sAddress);
			$this->view->aData		= $aData;
			$this->view->aDataLocation = $aDataLocation;
    	}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    /**
     * 
     * Muestra las oopciones extras que tenga el tipo de formulario
     * Int tipo de servicio
     */
    public function extrasAction(){
		try{
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cClientes	= new My_Model_Clientes();    		
			$aData		= Array();	
			$aDataExtras= Array();
			
    		$aNamespace = new Zend_Session_Namespace("sService");
    		
			if(!isset($aNamespace->locationService)){
				$this->_redirect('/dates/main/index');				
			}
			
			if($this->_dataOp=='new' || $this->_dataOp=='update'){
				if(isset($aNamespace->extraService)){
					unset($aNamespace->extraService);
				}
				
				$aNamespace->extraService = $this->_dataIn;
	            $this->_redirect('/dates/main/confirm');
			}		

			if(isset($aNamespace->extraService)){
				$aDataExtras  = $aNamespace->extraService;	
			}
						
			$aData  		= $aNamespace->infoService;   	
			$aDataFields    = $cTipoCita->getFieldsTipo($aData['inputTipo']);
			if(count($aDataFields)>0){
				$aFieldsValues  = $this->setValuesFields($aDataFields,$aDataExtras);
				$aProcessFields = $this->processFields($aFieldsValues);
			}else{				
				$aDataExtra = Array();
				$aDataExtra['result'] 	  = 'ok';
 				$aNamespace->extraService = $aDataExtra;
	            $this->_redirect('/dates/main/confirm');				
			}
    						    
    		$this->view->aFields	= $aProcessFields;
			$this->view->errors 	= $this->_aErrors;
			$this->view->resultOp   = $this->_resultOp;
			$this->view->aErrorFields= $this->_aErrorsFields;
			$this->view->aData		= $aData;
			$this->view->aDataExtras= $aDataExtras;
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }
    
	public function confirmAction(){
		try{
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cPersonal  = new My_Model_Personal();		
			$aData		= Array();	
			$aDataExtras= Array();
			$cCitas 	= new My_Model_Citas();
			
    		$aNamespace = new Zend_Session_Namespace("sService");
    		
			if(!isset($aNamespace->extraService)){
				$this->_redirect('/dates/main/index');				
			}
			
			$aData  		= $aNamespace->infoService;
			$aDataLocation	= $aNamespace->locationService;
			$aDataExtras	= $aNamespace->extraService;			
			
			if($this->_dataOp=='new' || $this->_dataOp=='update'){
				$aDataInsert = array_merge($aData,$this->_dataIn);
				$aDataInsert = array_merge($aDataInsert,$aDataLocation);
				$aDataInsert = array_merge($aDataInsert,$aDataExtras);
				$aDataInsert['ID_EMPRESA'] = $this->_dataUser['ID_EMPRESA'];
				$aDataInsert['ID_USUARIO'] = $this->_dataUser['ID_USUARIO'];
				$bInsert = $cCitas->insertRow($aDataInsert);
				if($bInsert['status']){
					$idCita = $bInsert['id'];
					$aDataInsert['idCita'] = $bInsert['id'];
					$bInsertAddress = $cCitas->insertAddress($aDataInsert);
					if($bInsertAddress['status']){
						$iError = 0;
						$aDataFields    = $cTipoCita->getFieldsTipo($aDataInsert['inputTipo']);
						if(count($aDataFields)>0){
							$aFieldsValues  = $this->setValuesFields($aDataFields,$aDataInsert);							
							foreach($aFieldsValues as $key => $items){
								$aDataExtra = Array();
								$aDataExtra['idCita']	= $idCita;
								$aDataExtra['idExtra']	= $items['ID_EXTRA'];
								$aDataExtra['sTitulo'] 	= $items['DESCRIPCION'];
								$aDataExtra['sValor'] 	= $items['VALUE_INPUT'];
								$aDataExtra['idEmpresa']= $this->_dataUser['ID_EMPRESA'];	
								
								$insertExtra = $cCitas->insertExtraCitas($aDataExtra);
								if(!$insertExtra){
									$iError++;
								}
							}
						}
						
						if($iError==0){
						    if(isset($aNamespace->infoService)){
								unset($aNamespace->infoService);
							}			
				    	    if(isset($aNamespace->locationService)){
								unset($aNamespace->locationService);
							}			
							if(isset($aNamespace->extraService)){
								unset($aNamespace->extraService);
							}
							
							$this->redirect("/dashboard/dates/index");
						}else{
							$this->_aErrors['errorProcess']   = '1';
						}
					}else{
						$this->_aErrors['errorProcess']   = '3';
					}
				}else{
					$this->_aErrors['errorProcess']   = '2';
				}
			}		
			
			$aPersonal		= $cPersonal->getToAssign($aData,$this->_dataUser['ID_EMPRESA']);			
			$this->view->aDataTable = $aPersonal; 
			$this->view->aErrors	= $this->_aErrors;
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        } 		
	}  
	
    public function cancelAction(){
    	try{
			$aNamespace = new Zend_Session_Namespace("sService");

    		if(isset($aNamespace->infoService)){
				unset($aNamespace->infoService);
			}			
    	    if(isset($aNamespace->locationService)){
				unset($aNamespace->locationService);
			}			
			if(isset($aNamespace->extraService)){
				unset($aNamespace->extraService);
			}
		
			$this->_redirect('/dates/main/index');				
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	     	
    }		  
    
       
    public function setValuesFields($aFields,$dataIn){ 	
		$cFunctions    = new My_Controller_Functions();
		$mFunctions    = new My_Model_Functions();
		$aResultFields = Array();

		if(isset($dataIn['optReg'])){						
			foreach($aFields as $key => $itemsFields){
				$nameInput = 'input'.$itemsFields['ID_EXTRA'];				
				$itemsFields['VALUE_INPUT']   = @$dataIn[$nameInput];
				$aResultFields[] = $itemsFields;
			}					
		}else{
			$aResultFields = $aFields;
		}		
		return $aResultFields;
    }

    
	public function processFields($aFields){
		$cFunctions    = new My_Controller_Functions();
		$mFunctions    = new My_Model_Functions();
		$cTipoCitas	   = new My_Model_TipoCitas();
		$aResultFields = Array();			  
		$inputEmpresa  = $this->_dataUser['ID_EMPRESA'];

		foreach($aFields as $key => $items){
			$inputName = 'input'.$items['ID_EXTRA'];			
			$valueInput= (isset($items['VALUE_INPUT'])) ? $items['VALUE_INPUT'] : '' ;
			
			if($items['ID_TIPO']==0){
				$items['INPUT'] = '<div class="spinbox">
                                                    <input id="'.$inputName.'" name="'.$inputName.'" autocomplete="off" type="text" class="spinbox-input form-control" value="'.$valueInput.'">
                                                    <div class="spinbox-buttons	btn-group btn-group-vertical">
                                                        <button type="button" class="btn spinbox-up blue">
                                                            <i class="fa fa-chevron-up"></i>
                                                        </button>
                                                        <button type="button" class="btn spinbox-down danger">
                                                            <i class="fa fa-chevron-down"></i>
                                                        </button>
                                                    </div>
                                                </div>';
			}else if($items['ID_TIPO']==1){
				$items['INPUT']		 = '<input id="'.$inputName.'" name="'.$inputName.'" type="text" class="input-inline form-control"  value="'.$valueInput.'"  autocomplete="off">';
			}else if($items['ID_TIPO']==2){
				$items['INPUT']		 = '<input id="'.$inputName.'" name="'.$inputName.'" type="text" class="input-inline form-control"  value="'.$valueInput.'"  autocomplete="off">';	
			}else if($items['ID_TIPO']==3){
				$items['INPUT']		 = '<select id="'.$inputName.'" name="'.$inputName.'" class="col-sm-8 col-xs-8 col-lg-8 no-padding-right"  autocomplete="off">';
				$items['INPUT']  	.= $cFunctions->cboDelimit($items['VALORES_CONFIG'],$valueInput);
				$items['INPUT']		.= '</select>';
			}else if($items['ID_TIPO']==14){
				$items['INPUT']		 = '<select id="'.$inputName.'" name="'.$inputName.'" class="col-sm-8 col-xs-8 col-lg-8 no-padding-right"  autocomplete="off">';
				$aDataCatalogo		 = $cTipoCitas->getElementosCatalogo($items['ID_USR_CATALOGO']);
				$items['INPUT']  	.= $cFunctions->selectDb($aDataCatalogo,$valueInput);
				$items['INPUT']		.= '</select>';
			}else if($items['ID_TIPO']==13){			
				$items['INPUT']	= '<textarea style="width:100%;" id="'.$inputName.'" name="'.$inputName.'" rows="10" >'.
									$valueInput.'</textarea>';
			}
			
			$aResultFields[] = $items;
		}
		return $aResultFields;
	}
	
    public function getdateinfoAction(){
		try{	
			$this->view->layout()->setLayout('layout_blank');
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cClientes	= new My_Model_Clientes();
			$cCitas		= new My_Model_Citas();
			$idEmpresa  = $this->_dataUser['ID_EMPRESA'];
						
			$aCbocitas  = $cTipoCita->getCbo($idEmpresa);
			$aTcitas	= $cTipoCita->getTipoCita($idEmpresa);
			
			$sTipoCita 	= "";
			$saCliente	= "";
			$aFormularios= Array();
			$aData		= Array();
			
			$aClientes	= $cClientes->getCbo($idEmpresa);
			
			if($this->_idUpdate >-1){
				$aData 		= $cCitas->getData($this->_idUpdate,$idEmpresa);
				$sTipoCita	= $aData['ID_TIPO'];
				$saCliente	= $aData['ID_CLIENTE'];
				$aFormularios = $cTipoCita->getFormularios($aData['ID_TIPO']);
			}
									
			if($this->_dataOp=='update'){
				$updated = $cCitas->updateRow($this->_dataIn);
				if($updated['status']){					
					$aData 		= $cCitas->getData($this->_idUpdate,$idEmpresa);
					$sTipoCita	= $aData['ID_TIPO'];
					$saCliente	= $aData['ID_CLIENTE'];
					$aFormularios = $cTipoCita->getFormularios($aData['ID_TIPO']);
					$this->_resultOp = 'updated';					
				}else{
					$this->_aErrors['problem'] = 1;	
				}					           
			}			
			
			$this->view->aData		= $aData;
			$this->view->aEstatus	= $cFuntions->cboStatus();			
			$this->view->aCbocitas 	= $cFuntions->selectDb($aCbocitas,$sTipoCita);
			$this->view->aClientes 	= $cFuntions->selectDb($aClientes,$saCliente);
			$this->view->aFormluarios= $aFormularios;
			$this->view->aTcitas 	= $aTcitas;			
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->aErrorFields= $this->_aErrorsFields;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }

    public function getlocationAction(){
		try{	
			$this->view->layout()->setLayout('layout_blank');
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cCitas		= new My_Model_Citas();
			$idEmpresa  = $this->_dataUser['ID_EMPRESA'];
						
			$aData		= Array();
			$aDataLocation = Array();		
			
			if($this->_idUpdate >-1){
				$aData 			= $cCitas->getData($this->_idUpdate,$idEmpresa);	
				$aDataLocation  = $cCitas->getLocation($this->_idUpdate);	
			}
									
			if($this->_dataOp=='update'){
				$updated = $cCitas->updateLocationRow($this->_dataIn);
				if($updated['status']){					
					$aData 			= $cCitas->getData($this->_idUpdate,$idEmpresa);	
					$aDataLocation  = $cCitas->getLocation($this->_idUpdate);
					$this->_resultOp = 'updated';
				}else{
					$this->_aErrors['problem'] = 1;	
				}           
			}			
			
			$this->view->aData		 = $aData;
			$this->view->aDataLocation= $aDataLocation;		
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->aErrorFields= $this->_aErrorsFields;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }	 
        
    /**
     * 
     * Muestra las oopciones extras que tenga el tipo de formulario
     * Int tipo de servicio
     */
    public function getextrasAction(){
		try{
			$this->view->layout()->setLayout('layout_blank');
			$cTipoCita 	= new My_Model_TipoCitas();
			$cFuntions	= new My_Controller_Functions();
			$cClientes	= new My_Model_Clientes();  
			$cCitas		= new My_Model_Citas();
			  		
			$aData		= Array();	
			$aDataExtras= Array();
			$aDataFields= Array();
			$aProcessFields= Array();
			$idEmpresa  = $this->_dataUser['ID_EMPRESA'];

			if($this->_idUpdate >-1){
				$aData 		  	= $cCitas->getData($this->_idUpdate,$idEmpresa);
				$aDataExtras  	= $cCitas->getDataExtras($this->_idUpdate);
				
				$aDataFields  	= $cTipoCita->getFieldsTipo($aData['ID_TIPO']);
				$aFieldsValues  = $this->setValuesDb($aDataFields,$aDataExtras);
				$aProcessFields = $this->processFields($aFieldsValues);	
			}
									
			if($this->_dataOp=='update'){
				$iError=0;				
				$aFieldsValues  = $this->setValuesFields($aDataFields,$this->_dataIn);							
				foreach($aFieldsValues as $key => $items){
					$aDataExtra = Array();
					$aDataExtra['idCita']	= $this->_idUpdate;
					$aDataExtra['idExtra']	= $items['ID_EXTRA'];
					$aDataExtra['sTitulo'] 	= $items['DESCRIPCION'];
					$aDataExtra['sValor'] 	= $items['VALUE_INPUT'];
					$aDataExtra['idEmpresa']= $this->_dataUser['ID_EMPRESA'];	
					
					$insertExtra = $cCitas->updateExtraCitas($aDataExtra);
					if(!$insertExtra){
						$iError++;
					}
				} 

				if($iError=0){
					$aData 		  	= $cCitas->getData($this->_idUpdate,$idEmpresa);
					$aDataExtras  	= $cCitas->getDataExtras($this->_idUpdate);
					
					$aDataFields  	= $cTipoCita->getFieldsTipo($aData['ID_TIPO']);
					$aFieldsValues  = $this->setValuesDb($aDataFields,$aDataExtras);
					$aProcessFields = $this->processFields($aFieldsValues);						
					$this->_resultOp = 'updated';
				}
			}		
			
    		$this->view->aFields	= $aProcessFields;
			$this->view->errors 	= $this->_aErrors;
			$this->view->resultOp   = $this->_resultOp;
			$this->view->aErrorFields= $this->_aErrorsFields;
			$this->view->aData		= $aData;
			$this->view->aDataExtras= $aDataExtras;			
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }
    
    public function setValuesDb($aFields,$dataIn){
		$cFunctions    = new My_Controller_Functions();
		$mFunctions    = new My_Model_Functions();
		$aResultFields = Array();
		
		foreach($aFields as $key => $itemsFields){
			foreach($dataIn as $key => $iValueInput){
				if($itemsFields['ID_EXTRA'] == $iValueInput['ID_EXTRA']){
					$itemsFields['VALUE_INPUT']   = $iValueInput['VALOR'];
					$aResultFields[] = $itemsFields;
				}
			}
		}
				
		return $aResultFields;
    }

    public function getpersonalAction(){
    	try{
			$this->view->layout()->setLayout('layout_blank');
			$cFuntions	= new My_Controller_Functions();
			$cCitas		= new My_Model_Citas();
			$cPersonal  = new My_Model_Personal();
			  		
			$aData		= Array();				
			$idEmpresa  = $this->_dataUser['ID_EMPRESA'];
			    		
    		if($this->_idUpdate >-1){
				$aData 		  	= $cCitas->getData($this->_idUpdate,$idEmpresa);
			}			
			
			if($this->_dataOp=='update'){
				$updated = $cCitas->updatePersonal($this->_dataIn);
				if($updated['status']){
					$aData 		  	= $cCitas->getData($this->_idUpdate,$idEmpresa);	
					$this->_resultOp = 'updated';
				}else{
					$this->_aErrors['problem'] = 1;	
				}
			}
			
			$aPersonal	= $cPersonal->getUpdateAssign($aData,$this->_dataUser['ID_EMPRESA']);			
			$this->view->aDataTable = $aPersonal; 
			$this->view->errors 	= $this->_aErrors;
			$this->view->resultOp   = $this->_resultOp;
			$this->view->aErrorFields= $this->_aErrorsFields;
			$this->view->aData		= $aData;			
			$this->view->aErrors	= $this->_aErrors;
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }
    
    public function canceldateAction(){
    	try{
    		$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();   			
	        $answer = Array('answer' => 'no-data');
    		
    		if($this->_idUpdate !="" && $this->_dataOp=='cancel'){
    			$cCitas = new My_Model_Citas();
    			$canceled = $cCitas->cancelDate($this->_dataIn);
    			if($canceled['status']){
    				$answer = Array('answer' => 'canceled');
    			}
    		}
    		
	        echo Zend_Json::encode($answer);
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function reprogdateAction(){
        try{
    		$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();   			
	        $answer = Array('answer' => 'no-data');
    		
    		if($this->_idUpdate !="" && $this->_dataOp=='reprog'){
    			$cCitas = new My_Model_Citas();
    			$canceled = $cCitas->reprogDate($this->_dataIn);
    			if($canceled['status']){
    				$answer = Array('answer' => 'updated');
    			}
    		}
    		
	        echo Zend_Json::encode($answer);
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
			echo "Message: " . $e->getMessage() . "\n";                
        }
    }
}