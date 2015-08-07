<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Resumen extends My_Db_Table
{	
	public function getTecnicos($idSearch,$iTipo=-1){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		$sFilter = ($iTipo==-1) ? 'AND U.ID_EMPRESA = '.$idSearch : 'AND S.ID_SUCURSAL = '.$idSearch; 		
    	$sql ="SELECT U.ID_USUARIO, S.ID_SUCURSAL,S.DESCRIPCION AS N_SUCURSAL, U.NOMBRE_COMPLETO AS N_TECNICO
				FROM USUARIOS U 
				INNER JOIN USR_EMPRESA R ON U.ID_USUARIO = R.ID_USUARIO
				INNER JOIN SUCURSALES  S ON R.ID_SUCURSAL= S.ID_SUCURSAL
				WHERE U.FLAG_OPERACIONES= 1
				  AND U.ACTIVO			= 1
				  $sFilter
				ORDER BY N_SUCURSAL ASC, N_TECNICO ASC";  	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result = $query;			
		}	
        
		return $result;					
	}
	
	public function getCitasPendientes($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT  C.ID_CITA, C.FECHA_CITA, C.HORA_CITA, C.FOLIO, E.DESCRIPCION AS N_ESTATUS, E.ID_ESTATUS, 
    					T.DESCRIPCION AS N_TIPO, C.ID_USUARIO_ASIGNADO AS  ID_USUARIO, CONCAT(C.FECHA_CITA,' ',C.HORA_CITA) AS FECHA_INICIO, 		
						IF(C.FECHA_TERMINO IS NULL, DATE_SUB(CONVERT(CONCAT(C.FECHA_CITA,' ',C.HORA_CITA), DATETIME), INTERVAL -1 HOUR) ,C.FECHA_TERMINO) AS FECHA_FIN,
						L.RAZON_SOCIAL
	    			FROM PROD_CITAS C
	    			INNER JOIN PROD_CLIENTES       L ON C.ID_CLIENTE  = L.ID_CLIENTE
	    			INNER JOIN PROD_ESTATUS_CITA   E ON C.ID_ESTATUS  = E.ID_ESTATUS	    			
	    			INNER JOIN PROD_TIPO_CITA	   T ON C.ID_TIPO	  = T.ID_TIPO
	    			WHERE C.FECHA_CITA BETWEEN CAST(DATE_SUB(NOW(), INTERVAL 10 DAY) AS DATE) AND  CAST(DATE_SUB(NOW(), INTERVAL -10 DAY) AS DATE)
	    			   AND C.ID_EMPRESA = $idEmpresa
	    			ORDER BY C.FECHA_CITA ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;		
	}	
	
	public function getStatus(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_ESTATUS AS ID, DESCRIPCION AS NAME, COLOR
				FROM PROD_ESTATUS_CITA
				WHERE ACTIVO = 1";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}		
}