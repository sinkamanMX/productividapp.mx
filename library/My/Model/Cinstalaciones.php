<?php
/**
 * Archivo de definici�n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Cinstalaciones extends My_Db_Table
{
    protected $_schema 	= 'gtp_bd';
	protected $_name 	= 'SUCURSALES';
	protected $_primary = 'ID_SUCURSAL';
		
	public function getAll($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT * 
    			FROM $this->_name ";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
	public function getCbo($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT $this->_primary AS ID, DESCRIPCION AS NAME 
    			FROM $this->_name 
    			WHERE  ID_EMPRESA = $idObject 
    			ORDER BY NAME ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
	public function getCentroFromEdo($idObject,$idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT GROUP_CONCAT(C.ID_SUCURSAL SEPARATOR ',') AS SUCURSALES
				FROM SUCURSALES_COBERTURA C
				INNER JOIN SUCURSALES S ON C.ID_SUCURSAL = S.ID_SUCURSAL
				WHERE C.ID_ESTADO  = $idObject
				  AND S.ID_EMPRESA = $idEmpresa";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}
        
		return $result;		
	}
}