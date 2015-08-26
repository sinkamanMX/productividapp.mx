<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_DetailDates extends My_Db_Table
{
	protected $_schema 	= 'BD_SIAMES';
	protected $_name 	= 'PROD_CITAS';
	protected $_primary = 'ID_CITA';
	
	public function getFormularios($idTipo){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		 		
    	$sql ="SELECT R.ORDEN, F.TITULO, R.ID_FORMULARIO
				FROM PROD_TIPO_FORMULARIO R
				INNER JOIN PROD_FORMULARIO F ON R.ID_FORMULARIO = F.ID_FORMULARIO
				WHERE ID_TIPO_CITA = $idTipo
				ORDER BY R.ORDEN ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;		
	}
	
	public function getAllData($idDate){
		$result= Array();
		$this->query("SET NAMES utf8",false);		
		$sql = "SELECT *
				FROM PROD_ELEMENTOS E
				LEFT JOIN PROD_FORM_DETALLE_RESULTADO R 
							ON E.ID_ELEMENTO = R.ID_ELEMENTO AND R.ID_RESULTADO IN (
					SELECT ID_RESULTADO
					FROM PROD_FORM_RESULTADO 
					WHERE ID_CITA = $idDate
				)
				WHERE E.ID_FORMULARIO IN(
					SELECT ID_FORMULARIO
					FROM PROD_FORM_RESULTADO 
					WHERE ID_CITA = $idDate
				)
				ORDER BY ID_FORMULARIO ASC, E.ORDEN ASC, 
						 E.SUBORDEN ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
}