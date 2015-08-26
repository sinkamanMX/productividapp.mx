<?php
class landing_MainController extends My_Controller_Action
{
    public function init()
    {
		$this->view->layout()->setLayout('landing');
		
		$sessions = new My_Controller_Auth();
        if($sessions->validateSession()){
	        $this->view->dataUser   = $sessions->getContentSession();   		
		}
    }
    
    public function indexAction()
    {
		try{
			$sessions = new My_Controller_Auth();

			$sessionRemeber = new My_Controller_Remember();
			if($sessionRemeber->validateSession()){
				$aDataRemember  = $sessionRemeber->getContentSession();
				$this->view->sNameUser = $aDataRemember['NickUser'];
   				$this->view->sPassUser = $aDataRemember['PassUser'];	
			}						
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
}    
/*
<!---------- ---------->
11063772
cto concorde #7 col lomas boulevares...
<!---------- ---------->
*/