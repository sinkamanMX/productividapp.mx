<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_DbmanConfig extends My_Db_Table
{
    protected $_schema 	= 'DB_PRODUCTIVIDAPP';
	protected $_name 	= 'DB_MODULOS';
	protected $_primary = 'ID_DB_MODULO';
	protected $_useCache= true;
	
	public function getData($keyModule){		
		$result= Array();
		if($this->_useCache && $this->_manCache!=NULL){
			if( ($result = $this->_manCache->load('getData'.$keyModule)) === false) {
				$this->query("SET NAMES utf8",false);
				 		
		    	$sql ="SELECT *
						FROM DB_MODULOS
						WHERE CLAVE_MODULO = '$keyModule'";    	
				$query   = $this->query($sql);
				if(count($query)>0){		  
					$result = $query[0];			
				}
							
				$this->_manCache->save($result,'getData'.$keyModule);
			}else{				
				$result = $this->_manCache->load('getData'.$keyModule );
			}	
		}else{
			$this->query("SET NAMES utf8",false);
			 		
	    	$sql ="SELECT *
					FROM DB_MODULOS
					WHERE CLAVE_MODULO = '$keyModule'";    	
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query[0];			
			}  
		}
  		
		return $result;			
	}	
	
    public function deleteRow($data){
		$result = false;    	
    	try{    	
			$sql  	= "DELETE FROM $this->_name	 WHERE $this->_primary = ".$data['catId']." LIMIT 1";
    		$query   = $this->query($sql,false);
			if($query){
				$result = true;				
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;    	
    } 	
    
	public function getFieldsForm($idModule){
		$result= Array();

		if($this->_useCache && $this->_manCache!=NULL){
			if( ($result = $this->_manCache->load('getFieldsForm'.$idModule)) === false ) { 		
				$this->query("SET NAMES utf8",false); 		
		    	$sql ="SELECT C.*, V.DESCRIPCION AS V_DESCRIPCION, V.OPCIONES
						FROM DB_MODULOS_CAMPOS C
						LEFT JOIN DB_VALIDACIONES  V ON V.ID_VALIDACION = C.ID_VALIDACION
						WHERE C.ID_DB_MODULO = $idModule
						ORDER BY C.ORDEN ASC";  
				$query   = $this->query($sql);
				if(count($query)>0){		  
					$result = $query;
				}
							
				$this->_manCache->save($result,'getFieldsForm'.$idModule);
			}else{
				$result = $this->_manCache->load('getFieldsForm'.$idModule );		 
			}	
		}else{
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT C.*, V.DESCRIPCION AS V_DESCRIPCION, V.OPCIONES
					FROM DB_MODULOS_CAMPOS C
					LEFT JOIN DB_VALIDACIONES  V ON V.ID_VALIDACION = C.ID_VALIDACION
					WHERE C.ID_DB_MODULO = $idModule
					ORDER BY C.ORDEN ASC";  
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query;
			} 
		}

		return $result;			
	}   
}