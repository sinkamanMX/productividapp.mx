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

	public function getDataReport($aDataFilter){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		$sFilter    = '';
		
		$aDateIn	= explode(' ',$aDataFilter['inputFechaIn']);
		$aDateFin	= explode(' ',$aDataFilter['inputFechaFin']);
		
		if(isset($aDataFilter['cboTipoCita']) && $aDataFilter['cboTipoCita']!='-1'){
			$sFilter    .= 'AND C.ID_TIPO 	= '.$aDataFilter['cboTipoCita'];
		}
		
		if(isset($aDataFilter['cboEstatus']) && $aDataFilter['cboEstatus']!='-1'){
			$sFilter    .= 'AND C.ID_ESTATUS 	= '.$aDataFilter['cboEstatus'];
		}		
		
		if(isset($aDataFilter['cboInstalacion'])){
			if($aDataFilter['cboInstalacion']!='-1'){
				$sFilter    .= 'AND C.ID_USUARIO_ASIGNADO IN(48)';				
			}else if(isset($aDataFilter['cboPersonal']) && $aDataFilter['cboPersonal']!='-1'){
				$sFilter    .= 'AND C.ID_USUARIO_ASIGNADO IN('.$aDataFilter['cboPersonal'].')';
			}
		}
		
		$sql ="SELECT C.ID_CITA ,C.FOLIO,T.DESCRIPCION AS N_TIPO, S.DESCRIPCION AS N_ESTATUS, 
					  L.RAZON_SOCIAL AS N_CLIENTE, U.NOMBRE_COMPLETO AS N_PERSONAL,CONCAT(C.FECHA_CITA,' ',C.HORA_CITA) AS N_FECHA
					  , C.ID_ESTATUS
				FROM PROD_CITAS C
				INNER JOIN PROD_ESTATUS_CITA S ON C.ID_ESTATUS = S.ID_ESTATUS
				INNER JOIN PROD_CLIENTES     L ON C.ID_CLIENTE = L.ID_CLIENTE
				INNER JOIN USUARIOS 		 U ON C.ID_USUARIO_ASIGNADO  = U.ID_USUARIO
				INNER JOIN PROD_TIPO_CITA    T ON C.ID_TIPO    = T.ID_TIPO
				WHERE FECHA_CITA BETWEEN '".$aDateIn[0]."' AND '".$aDateFin[0]."'
				  AND HORA_CITA  BETWEEN '".$aDateIn[1]."' AND '".$aDateFin[1]."'	  
				  $sFilter
				ORDER BY FECHA_CITA ASC, HORA_CITA ASC";		
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
}