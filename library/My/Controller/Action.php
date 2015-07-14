<?php
/**
 * Archivo de definici�n de clase 
 * 
 * @package library.My.Controller
 * @author andres
 */

/**
 * Definici�n de clase de controlador gen�rico
 *
 * @package library.My.Controller
 * @author andres
 */
class My_Controller_Action extends Zend_Controller_Action
{
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_idUpdate = Array();
		
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_dataUser= Array();
	
    /**
     * 
     * @var array 
     */
    protected $_menuCurrent;
    
    /**
     * 
     * @var array 
     */
    protected $_dataIn;  

    /**
     * 
     * @var array 
     */
    protected $_colorContenedor;  

    /**
     * 
     * @var array 
     */
    protected $_dataOp;    
    /**
     * 
     * @var array 
     */
    protected $_resultOp;  

	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_aErrors= Array();    
	
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_aErrorsFields= Array();   	
	
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_baseUrl= Array();  	
    
   /**
    * Inicializa el contexto, el formato de respuesta a un action
    *
    * @return void
    */
    public function init() {		
		
    }
 

    /**
     * M�todo llamado previo a llamada de controlador
     *
     * @return void
     */
    public function preDispatch()
    {
  
            
    }

    /**
     * Procedimiento posterior a llamado de controlador
     *
     * @return void
     */
    public function postDispatch(){
		
    }
    
    public function validateSession(){
		$cSessions 	= new My_Controller_Auth();
		$cPerfiles 	= new My_Model_Perfiles();
		$cFunctions = new My_Controller_Functions();
		
        if($cSessions->validateSession()){
	        $this->_dataUser   = $cSessions->getContentSession(); 	
		}else{
			$this->_redirect("/");
		}
		
		$this->_dataIn = $this->_request->getParams();
		$this->view->dataUser   = $this->_dataUser;
		$this->view->modules    = $cPerfiles->getModules($this->_dataUser['ID_PERFIL']);
		$this->view->moduleInfo = $cPerfiles->getDataModule($this->_clase);  
		$this->view->nRandom	= $cFunctions->getRandomCode();
		$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
		$this->_dataIn['inputEmpresa']	= $this->_dataUser['ID_EMPRESA']; 
    	if(isset($this->_dataIn['optReg'])){
			$this->_dataOp = $this->_dataIn['optReg'];				
		}
		
		if(isset($this->_dataIn['catId'])){
			$this->_idUpdate 	= $this->_dataIn['catId'];	
			$this->view->catId  = $this->_idUpdate;			
		}				
    }
    
    public function chatOptions(){
    	$aProcesado		= Array();
		$cMensajes	 	= new My_Model_Mensajes();
		$cFunciones		= new My_Controller_Functions();
		$idTipoUsuario  = ($this->_dataUser['VISUALIZACION']!=2) ? $this->_dataUser['ID_SUCURSAL'] : -1;			
		$aContactos		= $cMensajes->getContactos($this->_dataUser['ID_USUARIO'],$this->_dataUser['ID_EMPRESA'],$idTipoUsuario);
		if(count($aContactos)>0){
			$aProcesado  	= $cMensajes->processListContactos($aContactos,$this->_dataUser['ID_USUARIO']);	
		}
							
		$this->view->listContact = $aProcesado;    	
    }
}
