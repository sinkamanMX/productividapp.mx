<?php
class dashboard_MainController extends My_Controller_Action
{
	protected $_clase = 'mdashboard';
	
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
			
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function loginAction(){
		try{   			
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();    
	                
	        $answer = Array('answer' => 'no-data');
			$data = $this->_request->getParams();
	        if(isset($data['inputUsuario']) && isset($data['inputPassword'])){
	            $usuarios = new My_Model_Usuarios();
				$validate = $usuarios->validateUser($data);            
				if($validate){					
					 $dataUser = $usuarios->getDataUser($validate['ID_USUARIO']);
				     $sessions = new My_Controller_Auth();
	                 $sessions->setContentSession($dataUser);
	                 $sessions->startSession();
	                 $usuarios->setLastAccess($dataUser);
	                 
	                 if(isset($data['remember'])){
	                 	Zend_Debug::dump("se recordaran");
						setcookie("NickUser", $data['inputUsuario'],432000);
      					setcookie("PassUser", $data['inputPassword'],432000);
      					Zend_Debug::dump($_COOKIE);
	                 }else{
						unset($_COOKIE['inputUsuario']);
	                 	unset($_COOKIE['inputPassword']);
	                 }	                 
				     $answer = Array('answer' => 'logged');
				}else{ 
				    $answer = Array('answer' => 'no-perm'); 
				}
	        }else{
	            $answer = Array('answer' => 'problem');	
	        }
	        echo Zend_Json::encode($answer);   
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function logoutAction(){
		$mysession= new Zend_Session_Namespace('gtpSession');
		$mysession->unsetAll();
		
		Zend_Session::namespaceUnset('gtpSession');
		Zend_Session::destroy();
		
		$this->_redirect('/');
    }  
    
    public function inicioAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    
		$sessions = new My_Controller_Auth();		
        if($sessions->validateSession()){
            $profile = new My_Model_Perfiles();            
            $dataUser = $sessions->getContentSession();
            if($dataUser['ID_PERFIL']!="" && $dataUser['ID_PERFIL']!="NULL"){            	
                $default = $profile->getModuleDefault($dataUser['ID_PERFIL']);                
	            if(count($default)>0){
	            	$this->_redirect($default['SCRIPT']);
	            }else{
	            	$this->_redirect('/dashboard/main/index');	
	            }
            }else{
            	$this->_redirect('/main/main/errorprofile');
            }           
		}		   	
    }
    
    /*
    public function recoveryAction(){
    	try{   	
			$data   = $this->_request->getParams();
			$this->view->layout()->setLayout('layout_blank');
			$this->view->data = $data;
		
			if(isset($data['onaction'])){
				$errors = Array();
				
				$validateAlpha	= new Zend_Validate_Alnum(array('allowWhiteSpace' => true));
				
				if(!$validateAlpha->isValid($data['inputPassword'])){
					$errors['passwordPresent'] = 1;
				}
				
				if(!$validateAlpha->isValid($data['inputNewPass'])){
					$errors['passwordNew'] = 1;
				}

				if(!$validateAlpha->isValid($data['inputRepPass'])){
					$errors['passwordRepeat'] = 1;
				}			
				
				if($data['inputNewPass'] != $data['inputRepPass']){
					$errors['passwordRepeat'] = 1;
				}
				
				if(count($errors)==0){
					$this->view->dataUser['VPASSWORD'] = $data['inputPassword'];
					$usuarios = new My_Model_Usuarios();
					$validatePass = $usuarios->validatePassword($this->view->dataUser);
					if(count($validatePass)>0){
						$this->view->dataUser['NPASSWORD'] = $data['inputRepPass'];
						$update = $usuarios->changePass($this->view->dataUser);
						if($update){
							$this->view->changed = 1;		
						}else{
							$errors['noupdate'] = 1;
						}
					}else{
						$errors['noPerm'] = 1;
					}
				}
				
				$this->view->errors = $errors;				
				$this->view->data	= $data;
			}
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }    	
    }
    */
    
    public function errorprofileAction(){
    	
    }
}
