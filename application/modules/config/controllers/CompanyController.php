<?php
/**
 * Administracion del Perfiles
 * @author epena
 */
class config_CompanyController extends My_Controller_Action
{
	/**
	 *	Se especifica la clase del Modulo (Obtiene la informacion del mismo para ver en la vista).
	 * 
	 **/
	protected $_clase = 'mglobal';
	
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
				$this->_redirect("/");
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
			$classObject= new My_Model_Configuracion();
			$cEmpresas  = new My_Model_Empresas();
			$cFunctions = new My_Controller_Functions();			
			
			$aDataInfo 	= $cEmpresas->getDataInfo($this->_dataUser['ID_EMPRESA']); 
			$aConfiguracion = $cEmpresas->getConfiguracion($this->_dataUser['ID_EMPRESA']);
			$aModulos	= $cEmpresas->getModulos($this->_dataUser['ID_EMPRESA']);
			$aConsumo	= $cEmpresas->getConsumido($this->_dataUser['ID_EMPRESA']);
					
    		/**
			 * Se valida y se realiza la opcion mencionada.
			 */
    		if($this->_dataOp=='updateCompany'){
    			$updateConf = $classObject->updateConf($this->_dataIn);
    			if($updateConf['status']){			 	
    				$aDataInfo 	= $cEmpresas->getDataInfo($this->_dataUser['ID_EMPRESA']);	
					$this->_resultOp = 'okCompany';
				}else{
					$this->_aErrors['errorCompany'] = '1';
					$aDataInfo['txtNameCompany'] 	= $this->_dataIn['txtNameCompany'];
					$aDataInfo['txtNameRazon'] 		= $this->_dataIn['txtNameRazon'];
					$aDataInfo['txtNameDir'] 		= $this->_dataIn['txtNameDir'];
					$aDataInfo['txtNameResp'] 		= $this->_dataIn['txtNameResp'];
					$aDataInfo['txtNameTel'] 		= $this->_dataIn['txtNameTel'];
					$aDataInfo['txtNameEMail'] 		= $this->_dataIn['txtNameEMail'];
				}
			}else if($this->_dataOp=='updatePhoneConf'){
			    $updateConf = $classObject->updatePhoneConf($this->_dataIn);
    			if($updateConf['status']){			 	
    				$aConfiguracion = $cEmpresas->getConfiguracion($this->_dataUser['ID_EMPRESA']);
					$this->_resultOp = 'okPhoneConf';
				}else{
					$this->_aErrors['errorPhoneConf']   = '1';
					$aConfiguracion['txtTimeReporte'] 	= $this->_dataIn['txtTimeReporte'];
					$aConfiguracion['txtTimeEncendido'] = $this->_dataIn['txtTimeEncendido'];
					$aConfiguracion['txtTimeApagado'] 	= $this->_dataIn['txtTimeApagado'];
					$aConfiguracion['txtTimeSinRep'] 	= $this->_dataIn['txtTimeSinRep'];
					$aConfiguracion['txtTituloReporteX']= $this->_dataIn['txtTituloReporteX'];
					$aConfiguracion['txtTimeReporteX'] 	= $this->_dataIn['txtTimeReporteX'];
					$aConfiguracion['LOCALIZACION'] 	= $this->_dataIn['cboLocalizar'];										
				}
			}else if($this->_dataOp=='updateModules'){
				$aDataForm = Array();
				$aDataForm['idEmpresa'] = $this->_dataIn['idEmpresa'];
				$aValuesForm = $this->_dataIn['formsValues'];

				$aErrorsModules = 0;
				
				if(count($aValuesForm)>0){
					$moduleOff = $classObject->modulesOff($this->_dataIn['idEmpresa']);
					for($i=0;$i<count($aValuesForm);$i++){
						$aDataForm['idModulo']= $aValuesForm[$i];
						$insertForm = $classObject->updateModulos($aDataForm);
						if(!$insertForm){
							Zend_Debug::dump("error al insertar el formulario ".$aValuesForm[$i]);
							$aErrorsModules++;
						}						
					}					
				}else{
					$aErrorsModules++;
				}
				
				if($aErrorsModules==0){
    				$aModulos		 = $cEmpresas->getModulos($this->_dataUser['ID_EMPRESA']);
					$this->_resultOp = 'okModules';					
				}else{
					$this->_aErrors['errorModules']   = '1';
				}		
			}				
					
			/**
			 * Una vez procesada la informacion recibida se envia a la vista las variables que se requieran.
			 */				
			$this->view->aDataInfo 	= $aDataInfo;
			$this->view->aConfig   	= $aConfiguracion;
			$this->view->aModules  	= $aModulos;
			$this->view->aConsumo  	= $aConsumo;
			$this->view->Localizar 	= $cFunctions->cboOptions($aConfiguracion['LOCALIZACION']);
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;						
		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	
    }
}