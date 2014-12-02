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
				$this->_redirect("/main/main/index");
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
								
				if($this->_dataOp=="new"){
					$aDataInsert = Array();
					$aDataInsert['inputSend'] 	= $this->_dataUser['ID_USUARIO'];
					$aDataInsert['inputTo'] 	= $this->_idUpdate;
					$aDataInsert['inputMsg'] 	= $this->_dataIn['inputMsg'];	
					$insert = $cMensajes->newMessage($aDataInsert);
					$aConversation = $cMensajes->getConversacion($this->_dataUser['ID_USUARIO'],$this->_idUpdate);	
				}
				
				$aConversation = $cMensajes->getConversacion($this->_dataUser['ID_USUARIO'],$this->_idUpdate);				
				$this->view->aConversacion = $aConversation;
			}else{
        		$this->_redirect("/main/main/index");
        	}  
			$this->view->data		= $this->_dataIn;        	
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }    	
    }
}	