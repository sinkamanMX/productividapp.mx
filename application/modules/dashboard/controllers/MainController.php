<?php
class dashboard_MainController extends My_Controller_Action
{
	protected $_clase = 'mdashboard';
	
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

    public function indexAction()
    {
		try{
			$classObject = new My_Model_Dashboard();
			$this->view->componente = $classObject->dibuja($this->_dataUser['ID_EMPRESA']);
			
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
	 public function detallesAction()
    {
		try{

			$this->view->colorES = $this->_colorContenedor;

        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
   
}
