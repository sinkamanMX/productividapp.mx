<?php
class dashboard_GanttController extends My_Controller_Action
{
	protected $_clase = 'mgantt';
	
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
    		$cInstalaciones = new My_Model_Cinstalaciones();
    		$cFunciones		= new My_Controller_Functions();
    		$cResumen  		= new My_Model_Resumen();

			$sTypeSearch	= (isset($this->_dataIn['inputSucursal']) && $this->_dataIn['inputSucursal']!="") ? $this->_dataIn['inputSucursal'] : -1;
			$iSearch		= ($sTypeSearch==-1) ? $this->_dataUser['ID_EMPRESA'] : $this->_dataIn['inputSucursal'];
			    		
    		$aTecnicos 		= $cResumen->getTecnicos($iSearch,$sTypeSearch);	    		
    		$aCitas    		= $cResumen->getCitasPendientes($this->_dataUser['ID_EMPRESA']);    		
			$aDataProcess 	= $this->processInfo($aTecnicos, $aCitas);
			$dataCenter		= $cInstalaciones->getCbo($this->view->dataUser['ID_EMPRESA']);
    		
			$this->view->aData 		= $aDataProcess;
			$this->view->aEstatus 	= $cResumen->getStatus();
			$this->view->iCinstalac = $sTypeSearch;
			$this->view->cInstalaciones = $cFunciones->selectDb($dataCenter,$sTypeSearch);	    		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }     	
    }

    public function processInfo($aTecnicos,$aCitas){
    	$aResult = Array();
    	
    	foreach($aTecnicos as $key => $items){
    		$aDataCitas = Array();
    		foreach($aCitas as $key => $itemCita){    			    		
    			if($items['ID_USUARIO'] == $itemCita['ID_USUARIO']){    				
    				$dateSinicio = strtotime(date($itemCita['FECHA_INICIO']));  
    				$dateSfin	 = strtotime(date($itemCita['FECHA_FIN']));  				
    				$itemCita['fechaSin'] 	= str_pad($dateSinicio,13,"0",STR_PAD_RIGHT);
    				$itemCita['fechaSfin'] 	= str_pad($dateSfin,13,"0",STR_PAD_RIGHT);
    				
    				$aDataCitas[] = $itemCita;
    			}
    		}
    		
    		$items['citas'] = $aDataCitas;
    		$aResult[] = $items;
    	}
    	
    	return $aResult;
    }    
}
