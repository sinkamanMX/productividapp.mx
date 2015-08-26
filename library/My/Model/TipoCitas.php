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

	public function getCbo($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		 		
    	$sql ="SELECT ID_TIPO AS ID, DESCRIPCION AS NAME 
				FROM PROD_TIPO_CITA
				WHERE ID_EMPRESA = $idEmpresa";    	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	    
	public function getTipoCita($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		 		
    	$sql ="SELECT T.ID_TIPO AS ID, T.DESCRIPCION AS NAME, GROUP_CONCAT( CONCAT(R.ORDEN,'-', F.TITULO) ORDER BY R.ORDEN SEPARATOR ',' ) AS FORMULARIOS
				FROM PROD_TIPO_CITA T
				 LEFT JOIN PROD_TIPO_FORMULARIO R ON T.ID_TIPO = R.ID_TIPO_CITA
				 LEFT JOIN PROD_FORMULARIO 		F ON R.ID_FORMULARIO  = F.ID_FORMULARIO 
				WHERE T.ID_EMPRESA = $idEmpresa
				GROUP BY T.ID_TIPO
				ORDER BY R.ORDEN ASC, F.TITULO ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
	
	public function getFormularios($idTipo){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		 		
    	$sql ="SELECT R.ORDEN, F.TITULO, R.ID_FORMULARIO
				FROM PROD_TIPO_FORMULARIO R
				INNER JOIN PROD_FORMULARIO F ON R.ID_FORMULARIO = F.ID_FORMULARIO
				WHERE ID_TIPO_CITA = $idTipo
				ORDER BY F.TITULO ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
	
	public function getFieldsTipo($idTipo){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT E.*, T.ID_TIPO, T.DESCRIPCION AS N_TELEMENTO, V.DESCRIPCION AS N_VAL, V.OPCIONES, V.ID_VALIDACION
				FROM PROD_TIPO_EXTRAS E
				INNER JOIN PROD_TIPO_ELEMENTO T ON E.ID_TIPO_ELEMENTO = T.ID_TIPO
				 LEFT JOIN DB_VALIDACIONES    V ON T.ID_VALIDACION	  = V.ID_VALIDACION
				WHERE E.ID_TIPO_CITA = $idTipo
				ORDER BY E.ORDEN ASC";  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;
		}        
		return $result;			
	}
	
	public function getElementosCatalogo($idCatalogo){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT DESCRIPCION AS ID, DESCRIPCION AS NAME
				FROM USR_CATALOGOS_ELEMENTOS
				WHERE ID_CATALOGO 	= $idCatalogo				
				ORDER BY DESCRIPCION ASC";    	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;
		}        
		return $result;	
	}
}