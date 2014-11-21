<?php
class location_MainController extends My_Controller_Action
{
	protected $_clase = 'mrastreotels';
	
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
			$cInstalaciones = new My_Model_Cinstalaciones();
			$cFunciones		= new My_Controller_Functions();
			$cTecnicos		= new My_Model_Personal();
			
			$dataCenter		= $cInstalaciones->getCbo($this->_dataUser['ID_EMPRESA']);
			$this->view->cInstalaciones = $cFunciones->selectDb($dataCenter);

			$this->view->aPersonal = $cTecnicos->getAll($this->_dataUser['ID_EMPRESA']);			
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function getlastpAction(){
    	$result = '';
		try{  
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			
			if(isset($this->_dataIn['strInput']) && $this->_dataIn['strInput']){
				$cTecnicos  = new My_Model_Personal();			
				$allInputs  = implode(',', $this->_dataIn['strInput']);
				$dataPos    = $cTecnicos->getLastPositions($allInputs);	
				foreach ($dataPos as $key => $items){
					if($items['ID']!="" && $items['ID']!="NULL")
					$result .= ($result!="") ? "!" : "";
					$result .=  $items['ID']."|".
								$items['FECHA_GPS']."|".
                                $items['EVENTO']."|".
                                $items['LATITUD']."|".
                                $items['LONGITUD']."|".
                                round($items['VELOCIDAD'],2)."|".
                                round($items['NIVEL_BATERIA'],2)."|".
                                $items['TIPO_GPS']."|".
                                $items['ANGULO']."|".
                                $items['UBICACION'];
				}
			}
			echo $result;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }     	
    }    
}
