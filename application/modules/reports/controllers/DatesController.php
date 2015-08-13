<?php
class reports_DatesController extends My_Controller_Action
{
	protected $_clase = 'mcitas';
	
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
    
    public function indexAction(){
    	try{    		
    		$idEmpresa	  = $this->_dataUser['ID_EMPRESA'];
    		$cSurcursales = new My_Model_Cinstalaciones();	
    		$cFunctions   = new My_Controller_Functions();
    		$cTipoCitas	  = new My_Model_TipoCitas();
    		$cCitas		  = new My_Model_Citas();
    		$cPersonal	  = new My_Model_Personal();
    		$cResumen	  = new My_Model_Resumen();
    		
			$aSucursales  = Array();
			$aResultado   = Array();
			$aInfoPersonal= Array();
			$aEstatus	  = '';
			$sSucursal    = '';
			$sTipo		  = '';
			$sEstatus	  = '';
			$sPersona	  = '';
			
			if($this->_dataOp=='search'){
				$sEstatus	  = $this->_dataIn['cboEstatus'];
				$sSucursal    = $this->_dataIn['cboInstalacion'];
				$sTipo		  = $this->_dataIn['cboTipoCita'];
				$sPersona	  = $this->_dataIn['cboPersonal'];
				$aInfoPersonal= $cPersonal->getCbo($idEmpresa);
			}else{
				$this->_dataIn['inputFechaIn']  = date("Y-m-d 00:00:00");
				$this->_dataIn['inputFechaFin'] = date("Y-m-d 23:59:00");
			}
			
			$aResultado   = $cResumen->getDataReport($this->_dataIn);			
			$aSucursales  = $cSurcursales->getCbo($idEmpresa);
			$aTipos       = $cTipoCitas->getCbo($idEmpresa);
			$aEstatus	  = $cCitas->getCboTipoCitas();
			$aPersonal 	  = $cPersonal->getCboByCompany($idEmpresa);			
    		
    		$this->view->aSucursales = $cFunctions->selectDb($aSucursales,$sSucursal); 
    		$this->view->aTcitas     = $cFunctions->selectDb($aTipos,$sTipo);
    		$this->view->aEstatus    = $cFunctions->selectDb($aEstatus,$sEstatus);
    		$this->view->aPersonal	 = $aPersonal;
    		$this->view->aDataTable  = $aResultado;
    		$this->view->aCboPersonal= $cFunctions->selectDb($aInfoPersonal,$sPersona);
    		$this->view->resultOp    = $this->_resultOp;
    		$this->view->data		 = $this->_dataIn;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }     	
    }
}