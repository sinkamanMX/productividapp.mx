<?php
class main_confgeneralController extends My_Controller_Action
{
    public function init()
    {
		$this->view->layout()->setLayout('layout_login');
		
		$sessions = new My_Controller_Auth();
        if($sessions->validateSession()){
	        $this->view->dataUser   = $sessions->getContentSession();   		
		}
    }