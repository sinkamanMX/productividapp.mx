<?php
/**
 * Administracion de los Usuarios
 * @author epena
 */
class users_UsersController extends My_Controller_Action
{
	/**
	 *	Se especifica la clase del Modulo (Obtiene la informacion del mismo para ver en la vista).
	 * 
	 **/
	protected $_clase = 'musers';
	
	/** 
	 * En esta funcion se ejecuta antes que cualquier otra funcion en el modulo y que no tiene una vista (template)
	 * 
	 **/
    public function init()
    {
    	try{    
			/** 
			 * Se crean las instacias de las clases a utilizar
			 *
			 */    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
			$this->validateNumbers = new Zend_Validate_Digits();
			
			/**
			 * Se confirma que el usuario este logeado, de lo contrario redirecciona al login
			 */			
	        if($sessions->validateSession()){
	        	/**
	        	 * En esta variable se guarda toda la informacion de la session (informacion del usuario)
	        	 * Utilizar esta variable para acceder, al tipo de perfil,nombre del usuario, id,etc...
	        	 */
		        $this->_dataUser   = $sessions->getContentSession(); 	
			}else{
				$this->_redirect("/main/main/index");
			}
			
			/**
			 * Se envian las variables a la vista para poder utilizarlas
			 * */			
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules($this->_dataUser['ID_PERFIL']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
						
			/**
			 * 
			 * En el caso de modulos de administracion todos los parametros de entrada (POST,GET)
			 * se almacenan en la varialbe dataIn para poder utililzarlas en todas las funciones
			 */			
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['idEmpresa'] 	= $this->_dataUser['ID_EMPRESA'];						
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
			
			/**
			 * Se recibira una variable optReg que indicara cualquier accion a realiar (update,new)
			 */			
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}

			/**
			 * Cuando sea un update, se debe de enviar el id del registro y se almacenara en la variable
			 * idUpdate
			 */						
			if(isset($this->_dataIn['catId']) && $this->validateNumbers->isValid($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];
			}else{
				$this->_idUpdate 	   	  = -1;
				$this->_aErrors['status'] = 'no-info';				
			}
			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }   

    /**
     * 
     * Funcion inicial que se ejecuta cuando se invoca este modulo (requiere template)
     */
    public function indexAction(){
    	try{
    		/**
    		 * Se obtienen los usuarios que se han creado por empresa
    		 */
			$classObject = new My_Model_Usuarios();
			$this->view->datatTable = $classObject->getDataTables($this->_dataUser['ID_EMPRESA']);
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	
    }

    public function getinfoAction(){
    	try{
    		$dataInfo = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Usuarios();
    		$cPerfiles 	= new My_Model_Perfiles();
    		$cSucursales= new My_Model_Cinstalaciones();
    		$cHorarios 	= new My_Model_Horarios();
    		
    		$sPerfil	= '';
    		$sEstatus	= '';
    		$sOperaciones= '';
    		$sSucursales= '';  
    		$aHorarios  = Array();

			$aPerfiles	= $cPerfiles->getCbo($this->_dataUser['ID_EMPRESA']);
    		$aSucursales= $cSucursales->getCbo($this->_dataUser['ID_EMPRESA']);
    		
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
    	    	$sPerfil	= $dataInfo['ID_PERFIL'];
    	    	$sEstatus	= $dataInfo['ACTIVO'];
				$sOperaciones= $dataInfo['FLAG_OPERACIONES'];
				$sSucursales=$dataInfo['ID_SUCURSAL'];
				
				$aHorarios  = $cHorarios->getAllDataByUser($dataInfo['ID_SUCURSAL'],$this->_idUpdate);
			}

    		if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
					 $validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],$this->_idUpdate,'user');
					 if($validateUser){
						$updated = $classObject->updateRow($this->_dataIn);
						 if($updated['status']){	
					 		$dataInfo    	= $classObject->getData($this->_idUpdate);
			    	    	$sPerfil		= $dataInfo['ID_PERFIL'];
			    	    	$sEstatus		= $dataInfo['ACTIVO'];
							$sOperaciones	= $dataInfo['FLAG_OPERACIONES'];
							$sSucursales	= $dataInfo['ID_SUCURSAL'];	
							$aHorarios  	= $cHorarios->getAllDataByUser($dataInfo['ID_SUCURSAL'],$this->_idUpdate);				 		
					 		$this->_resultOp = 'okRegister';
						 }
					 }else{
					 	$this->_aErrors['eUsuario'] = '1';
					 }
				}else{
					$this->_aErrors['status'] = 'no-info';
				}
			}else if($this->_dataOp=='new'){
				$validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],-1,'user');
				 if($validateUser){
				 	$insert = $classObject->insertRow($this->_dataIn);
			 		if($insert['status']){
			 			$this->_idUpdate = $insert['id'];
				 		$dataInfo    	= $classObject->getData($this->_idUpdate);
		    	    	$sPerfil		= $dataInfo['ID_PERFIL'];
		    	    	$sEstatus		= $dataInfo['ACTIVO'];
						$sOperaciones	= $dataInfo['FLAG_OPERACIONES'];
						$sSucursales	= $dataInfo['ID_SUCURSAL'];	
						$aHorarios  	= $cHorarios->getAllDataByUser($dataInfo['ID_SUCURSAL'],$this->_idUpdate);			 		
				 		$this->_resultOp = 'okRegister';
					}else{
						$this->_aErrors['status'] = 'no-insert';
					}
				 }else{
				 	$this->_aErrors['eUsuario'] = '1';
				 }			
			}else if($this->_dataOp=='delete'){
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$answer = Array('answer' => 'no-data');
				    
				$delete = $classObject->deleteRow($this->_dataIn);
				if($delete){
					$answer = Array('answer' => 'deleted'); 
				}
	
		        echo Zend_Json::encode($answer);
		        die();   			
			}
			
    	    if($this->_dataOp=='addEvento'){
    	    	$insert = $cHorarios->insertByUser($this->_dataIn, $aHorarios);
    	    	if($insert['status']){
    	    		$aHorarios  	= $cHorarios->getAllDataByUser($dataInfo['ID_SUCURSAL'],$this->_idUpdate);
					$this->view->eventAction = true;
				}
			}

			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				$dataInfo['ID_PERFIL'] 		= $this->_dataIn['inputPerfil'];
				$dataInfo['ID_SUCURSAL'] 	= $this->_dataIn['inputSucursal'];
				$dataInfo['USUARIO'] 		= $this->_dataIn['inputUsuario'];
				$dataInfo['NOMBRE'] 		= $this->_dataIn['inputNombre'];
				$dataInfo['APELLIDOS'] 		= $this->_dataIn['inputApps'];
				$dataInfo['EMAIL'] 			= $this->_dataIn['inputEmail'];
				$dataInfo['TEL_MOVIL'] 		= $this->_dataIn['inputMovil'];
				$dataInfo['TEL_FIJO'] 		= $this->_dataIn['inputTelFijo'];
				$dataInfo['ACTIVO'] 		= $this->_dataIn['inputEstatus'];
				$dataInfo['FLAG_OPERACIONES']= $this->_dataIn['inputOperaciones'];
				
    	    	$sPerfil	 = $dataInfo['ID_PERFIL'];
    	    	$sEstatus	 = $dataInfo['ACTIVO'];
				$sOperaciones= $dataInfo['FLAG_OPERACIONES'];
				$sSucursales =$dataInfo['ID_SUCURSAL'];	
			}
			
    		$this->view->aHorarios	 = $aHorarios;
			$this->view->aPerfiles   = $cFunctions->selectDb($aPerfiles,$sPerfil);
			$this->view->aSucursales = $cFunctions->selectDb($aSucursales,$sSucursales);
			$this->view->aStatus  	 = $cFunctions->cboStatus($sEstatus);	
			$this->view->aOperaciones= $cFunctions->cboOptions($sOperaciones);		
				
			$this->view->data 		= $dataInfo; 
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
				    		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
    }       
}    