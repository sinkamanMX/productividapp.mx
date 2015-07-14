<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_TipoCitas extends My_Db_Table
{
	protected $_schema 	= 'BD_SIAMES';
	protected $_name 	= 'PROD_TIPO_CITA';
	protected $_primary = 'ID_TPO';
	
	public function getFormsByType($idObject,$idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		 		
    	$sql ="SELECT F.ID_FORMULARIO AS ID,F.TITULO, F.DESCRIPCION,  
				IF(T.ID_FORMULARIO IS NOT NULL,'1','0') AS ASIGNADO,
				IF(T.ID_FORMULARIO IS NOT NULL,T.ORDEN,'0') AS ORDEN
				FROM PROD_FORMULARIO F
				LEFT JOIN PROD_TIPO_FORMULARIO T ON F.`ID_FORMULARIO` = T.`ID_FORMULARIO` AND T.`ID_TIPO_CITA` = $idObject
				WHERE F.ESTATUS    = 1
				  AND F.ID_EMPRESA = $idEmpresa
				  ORDER BY T.ORDEN ASC, F.DESCRIPCION";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}

	/**
	 * 
	 * Inserta un nuevo elemento
	 * @param Array $aDataIn
	 * @return Array Id, Estatus de la operacion.
	 */
    public function insertElement($aDataElement,$idObject,$idEmpresa){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO PROD_TIPO_FORMULARIO
				SET ID_TIPO_CITA	= ".$idObject.",
					ID_FORMULARIO	='".@$aDataElement['id']."',
					ORDEN			= ".@$aDataElement['orden'];
        try{            
    		$query   = $this->query($sql,false);
    		if(count($query)>0){
    			$result['status']  = true;		
    		}
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }   

	public function deleteForms($idObject){
        $result     = Array();
        $result['status']  = false;    

        $sql="DELETE FROM  PROD_TIPO_FORMULARIO
					 WHERE ID_TIPO_CITA = $idObject";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result['status'];	 		
	}  	
	    
}