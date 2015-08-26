<?php
class dashboard_DatesController extends My_Controller_Action
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
    					
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }     	
    }
    
    public function getcitaspendientesAction(){
		try{    		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();    
	                
			$cCitas = new My_Model_Citas();

			
			$type      = (isset($this->_dataIn['iType']) && $this->_dataIn['iType']!="") ? $this->_dataIn['iType'] : -1;
			$dataCitas = $cCitas->getCitasCalendar($type,$this->_dataUser['ID_EMPRESA']);
			if($type==2){
				$dataCitas = $this->processData($dataCitas);	
			}
			
			echo Zend_Json::encode($dataCitas);
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }    	
    }
    
    public function processData($aDataToProcess){
    	$aResult = Array();
    	$cCitas = new My_Model_Citas();
    	foreach($aDataToProcess as $key => $items){
    		$sTittle = '';
    		$sIds	 = '';
    		$aDataCount = $cCitas->getResume($items['FECHA_CITA'],$items['HORA_CITA']);
    		foreach($aDataCount as $key => $itemsCount){
    			$sTittle .= ($sTittle!="") ? '
    			' : '';
    			$sIds	 .= ($sTittle!="") ? ',':'';
    			$sTittle .= $itemsCount['N_TITTLE'].': '.$itemsCount['TOTAL'];
    			$sIds	 .= $itemsCount['IDS'];    			
    		}	
    		$items['title']	= $sTittle;
    		$items['IDS']	= $sIds;
    		$aResult[]	= $items;
    	}
    	
    	return $aResult;
    }    
    
    public function getlistdatesAction(){
		try{
			$aList = Array();
			$this->view->layout()->setLayout('layout_blank');
			$cCitas   = new My_Model_Citas();
			if(isset($this->_dataIn['strInput']) && $this->_dataIn['strInput']!=''){
				$sIds  = $this->_dataIn['strInput'];
				$aList = $cCitas->getDateByList($sIds);
			}
			
			$this->view->dataSearch = $aList;
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }	 
    }
    
}