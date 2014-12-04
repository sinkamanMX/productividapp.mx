<?php
class messages_MainController extends My_Controller_Action
{
	protected $_clase = 'mmessages';
	
    public function init()
    {
    	try{    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession(); 	
			}else{
				$this->_redirect("/");
			}    		
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules($this->_dataUser['ID_PERFIL']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
						
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}
			
			if(isset($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];				
			}			
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction()
    {    	
		try{		
			$cMensajes	 	= new My_Model_Mensajes();
			$cFunciones		= new My_Controller_Functions();
	
			$idTipoUsuario  = ($this->_dataUser['VISUALIZACION']!=2) ? $this->_dataUser['ID_SUCURSAL'] : -1;
			$aContactos		= $cMensajes->getContactos($this->_dataUser['ID_USUARIO'],$this->_dataUser['ID_EMPRESA'],$idTipoUsuario);
			$aProcesado  	= $cMensajes->processListContactos($aContactos,$this->_dataUser['ID_USUARIO']);
			if(isset($this->_dataIn['strNotif']) && strlen($this->_dataIn['strNotif']) ==5){
				$this->view->resultOp = 1;	
			}					
			$this->view->listContact = $aProcesado;
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }

    public function chatmessagesAction(){
    	try{		
			if(isset($this->_idUpdate)){
				$this->view->layout()->setLayout('layout_blank');			
				$cMensajes = new My_Model_Mensajes();
				$cUsuarios = new My_Model_Usuarios();
				$timeShow  = (isset($this->_dataIn['iTime'])) ? $this->_dataIn['iTime'] : 1;
								
				if($this->_dataOp=="new"){
					$aDataInsert = Array();
					$aDataInsert['inputSend'] 	= $this->_dataUser['ID_USUARIO'];
					$aDataInsert['inputTo'] 	= $this->_idUpdate;
					$aDataInsert['inputMsg'] 	= $this->_dataIn['inputMsg'];	
					$insert = $cMensajes->newMessage($aDataInsert);
					$aConversation = $cMensajes->getConversacion($this->_dataUser['ID_USUARIO'],$this->_idUpdate,$timeShow);	
				}
				
				$aConversation = $cMensajes->getConversacion($this->_dataUser['ID_USUARIO'],$this->_idUpdate,$timeShow);				
				$this->view->aConversacion = $aConversation;
				$this->view->aContacto     = $cUsuarios->getData($this->_idUpdate);
			}else{
        		$this->_redirect("/main/main/index");
        	} 
        	
			$this->view->data		= $this->_dataIn;        	
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }    	
    }
    
    public function newmessageAction(){
    	try{		
			$cUsuarios = new My_Model_Usuarios();
			$cMensajes = new My_Model_Mensajes();	
			$cFunctions= new My_Controller_Functions();					
			$iCountMsg = 0;
			$counter   = 0;
			$aDataInfo = Array();
			$sFilter   = ($this->_dataUser['VISUALIZACION']==1) ? $this->_dataUser['VISUALIZACION'] : -1;
			$aContactos= $cUsuarios->getUserOperation($this->_dataUser['ID_EMPRESA'],$sFilter);
			
    		if($this->_dataOp=="newGroup" && isset($this->_dataIn['idContacts'])){
    			$sDestino = $this->_dataIn['idContacts'];
    			if($sDestino=="all"){
    				foreach($aContactos as $key => $items){
    					$counter++;
    				    $aDataInsert = Array();
						$aDataInsert['inputSend'] 	= $this->_dataUser['ID_USUARIO'];
						$aDataInsert['inputTo'] 	= $items['ID_USUARIO'];
						$aDataInsert['inputMsg'] 	= $this->_dataIn['inputMessage'];
						$insert = $cMensajes->newMessage($aDataInsert);
						if($insert){
							$iCountMsg++;
						}    					
    				}
    				
    				if($iCountMsg==$counter){
						$this->_redirect($this->view->moduleInfo['SCRIPT']."?strNotif=".$cFunctions->getRandomCode());    						
    				}else{
    					$this->_aErrors['PROBLEM']=1;
    				}      				
    			}else{
    				$aDestino = explode(",", $sDestino);
    				for($i=0;$i<count($aDestino);$i++){
    					$aDataInsert = Array();
						$aDataInsert['inputSend'] 	= $this->_dataUser['ID_USUARIO'];
						$aDataInsert['inputTo'] 	= $aDestino[$i];
						$aDataInsert['inputMsg'] 	= $this->_dataIn['inputMessage'];
						$insert = $cMensajes->newMessage($aDataInsert);
						if($insert){
							$iCountMsg++;
						}
    				}	
    				
    				if($iCountMsg==count($aDestino)){
						$this->_redirect($this->view->moduleInfo['SCRIPT']."?strNotif=".$cFunctions->getRandomCode());    						
    				}else{
    					$this->_aErrors['PROBLEM']=1;
    				}    				
    			}    	
	
    			if(count($this->_aErrors)>0){
    				$aDataInfo = $this->_dataIn;
    			}
    			
    			$this->view->aErrors = $this->_aErrors;
    			$this->view->data 	 = $this->_aErrors;	
			}			
			
			$this->view->dataTable = $aContactos;
        }catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }     	
    }
}	