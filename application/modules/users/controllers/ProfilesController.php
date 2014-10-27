<?php
/**
 * Administracion del Perfiles
 * @author epena
 */
class users_ProfilesController extends My_Controller_Action
{
	/**
	 *	Se especifica la clase del Modulo (Obtiene la informacion del mismo para ver en la vista).
	 * 
	 **/
	protected $_clase = 'mprofiles';
	
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
    		 * Se obtienen los perfiles que se han creado por empresa
    		 */
			$classObject = new My_Model_Perfiles();
			$this->view->datatTable = $classObject->getDataTables($this->_dataUser['ID_EMPRESA']);
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	
    }
    
    /**
     * Funcion que obtiene la iformacion del un registro
     */
    public function getinfoAction(){
    	try{
    		/**
    		 * Se crean las instacioas de las clases a utilizar
    		 */
    		$dataInfo = Array();
    		/**
    		 * 
    		 * Esta clase contiene funciones generales como: opciones para crear select desde: un array, un query
    		 * para mas informacion ingresar a la clase.
    		 */
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Perfiles();
    		$sEstatus	= '';
    		
    		/**
    		 * Se valida que la variable que se reciba sea valida, si es correcta se obtiene la 
    		 * informacion del registro. 
    		 */
    		if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
    	    	$sEstatus	= $dataInfo['ACTIVO'];
			}
			    		
			/**
			 * Se valida y se realiza la opcion mencionada.
			 */
    		if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
					$updated = $classObject->updateRow($this->_dataIn);
					 if($updated['status']){	
				 		$dataInfo   = $classObject->getData($this->_idUpdate);
				 		$sEstatus	= $dataInfo['ACTIVO'];			 		
				 		$this->_resultOp = 'okRegister';
					 }else{
						$this->_aErrors['status'] = 'no-info';
					}
				}else{
					$this->_aErrors['status'] = 'no-info';
				}
			}else if($this->_dataOp=='new'){
				/**
				 * Cuando se inserta un nuevo registro, la funcion debera de regresar un arrego
				 * con el estatus de la operacion y el id del nuevo registro.
				 */
			 	$insert = $classObject->insertRow($this->_dataIn);
		 		if($insert['status']){
		 			$this->_idUpdate = $insert['id'];
			 		$dataInfo    = $classObject->getData($this->_idUpdate);
		 			$sEstatus	 = $dataInfo['ACTIVO'];	
			 		$this->_resultOp = 'okRegister';
				}else{
					$this->_aErrors['status'] = 'no-insert';
				}		
			}else if($this->_dataOp=='delete'){
				/**
				 * Para eliminar un registro se hace por medio de ajax.
				 *
				 * Con estas dos primeras lineas se deshabilita la vista del framework
				 */
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				
				$answer = Array('answer' => 'no-data');
				    
				$this->_dataIn['idEmpresa'] = 1; //Aqui va la variable que venga de la session
				$delete = $classObject->deleteRow($this->_dataIn);
				if($delete){
					$answer = Array('answer' => 'deleted'); 
				}	
	
		        echo Zend_Json::encode($answer);
		        die();   			
			}			
			
			/**
			 * Se valida que alguna operacion no contenga algun error
			 * de lo contrario las varialbes recibidas se almacenan y se devuelven a la vista.
			 */
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				$dataInfo['DESCRIPCION'] 	= $this->_dataIn['inputDescripcion'];
				$dataInfo['ACTIVO'] 		= $this->_dataIn['inputEstatus'];
				$dataInfo['EDITAR'] 		= @$this->_dataIn['inputEditar'];
				$dataInfo['LECTURA'] 		= @$this->_dataIn['inputLeer'];
				$dataInfo['INSERTAR'] 		= @$this->_dataIn['inputAgregar'];
				$dataInfo['ELIMINAR'] 		= @$this->_dataIn['inputBorrar'];

    	    	$sEstatus	 = $dataInfo['ACTIVO'];	
			}
			
			/**
			 * Una vez procesada la informacion recibida se envia a la vista las variables que se requieran.
			 */			
			$this->view->aStatus  	= $cFunctions->cboStatus($sEstatus);		
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