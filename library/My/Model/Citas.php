<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Citas extends My_Db_Table
{
    protected $_schema 	= 'PRODUCTIVIDAPP';
	protected $_name 	= 'PROD_CITAS';
	protected $_primary = 'ID_CITA';
	
	public function insertRow($data){
        $result     = Array();
        $result['status']  = false;

        $sql="INSERT INTO $this->_name
				SET ID_TIPO				=  ".$data['inputTipo'].",
					ID_EMPRESA  		=  ".$data['ID_EMPRESA'].",
					ID_ESTATUS  		=  1,
					ID_USUARIO_CREO 	=  ".$data['userCreate'].",
					ID_CLIENTE          =  ".$data['inputCliente'].",
					ID_USUARIO_ASIGNADO =  ".$data['bRadioInit'].",
					FECHA_CITA			= '".$data['inputFecha']."',
					HORA_CITA			= '".$data['inputHora']."',
					CONTACTO 			= '".$data['inputContacto']."',
					TELEFONO_CONTACTO   = '".$data['inputTelContacto']."', 		 	
					FOLIO				= '".$data['inputFolio']."',	 
					CREADO 	= CURRENT_TIMESTAMP";
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result['id']	   = $query_id[0]['ID_LAST'];
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	   		
	}
	
	public function insertAddress($data){
        $result     = Array();
        $result['status']  = false;
        
        $iLatitud	= (isset($data['inputLatitud'])  && $data['inputLatitud']!="") ? $data['inputLatitud'] : 0;
        $iLongitud	= (isset($data['inputLongitud']) && $data['inputLongitud']!="") ? $data['inputLongitud'] : 0;
        
        $sql="INSERT INTO PROD_CITA_DOMICILIO
				SET ID_CITA		=  ".$data['idCita'].",
					CALLE		= '".$data['inputCalle']."',
					COLONIA		= '".$data['inputColonia']."',
					NO_EXT		= '".$data['inputNext']."',
					NO_INT		= '".$data['inputNint']."',
					MUNICIPIO	= '".$data['inputMun']."',
					CP			= '".$data['inputCp']."',
					ESTADO		= '".$data['inputEdo']."',
					REFERENCIAS	= '".$data['inputRefs']."',
					LATITUD		=  ".$iLatitud.",
					LONGITUD	=  ".$iLongitud;
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result['id']	   = $query_id[0]['ID_LAST'];
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;			
	}	
	
	public function insertExtraCitas($data){
        $result     = false;            
        $sql = "INSERT INTO PROD_CITAS_EXTRAS 
				SET ID_EXTRA=  ".$data['idExtra'].", 
					ID_CITA =  ".$data['idCita'].", 
					ID_EMPRESA=".$data['idEmpresa'].", 
					TITULO  = '".$data['sTitulo']."',
					VALOR   = '".$data['sValor']."'";
        try{
    		$query   = $this->query($sql,false);
    		$result	 = true;	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;			
	}
	
	public function getCitasCalendar($iType=1,$idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false);

		if($iType==1){
			$sql	= "SELECT 'true' AS allDay ,
							E.COLOR AS borderColor,
							E.COLOR AS color,
							C.FECHA_CITA AS start,
							C.FECHA_CITA AS end ,	
							CONCAT(
								T.DESCRIPCION,': ',COUNT(T.ID_TIPO) 
							)  AS title,
							GROUP_CONCAT(DISTINCT C.ID_CITA ORDER BY T.DESCRIPCION SEPARATOR ',') AS IDS
			    			FROM PROD_CITAS C
							INNER JOIN PROD_CLIENTES     L ON C.ID_CLIENTE = L.ID_CLIENTE
							INNER JOIN PROD_ESTATUS_CITA E ON C.ID_ESTATUS = E.ID_ESTATUS
							INNER JOIN PROD_TIPO_CITA	 T ON C.ID_TIPO	   = T.ID_TIPO
			    			 LEFT JOIN USUARIOS          U ON C.ID_USUARIO_ASIGNADO = U.ID_USUARIO
			    			WHERE C.FECHA_CITA BETWEEN CAST(DATE_SUB(NOW(), INTERVAL 15 DAY) AS DATE) AND  CAST(DATE_SUB(NOW(), INTERVAL -15 DAY) AS DATE)
		    				  AND C.ID_EMPRESA = $idEmpresa			    			
			    			GROUP BY T.ID_TIPO, C.FECHA_CITA
			    			ORDER BY C.FECHA_CITA ASC";
		}else{
			$sql = "SELECT 'false' AS allDay ,
					'#438eb9' AS borderColor,
					'#438eb9' AS color,
					CONCAT(C.FECHA_CITA,' ',C.HORA_CITA) AS start,				
					CONCAT(C.FECHA_CITA,' ',TIME(DATE_ADD(CONCAT(C.FECHA_CITA,' ',C.HORA_CITA) , INTERVAL 1 HOUR)))  AS end,
					GROUP_CONCAT(CONCAT(T.DESCRIPCION, ':@' ) ORDER BY T.DESCRIPCION SEPARATOR '<br/> ')   AS title,
					C.FECHA_CITA,
					C.HORA_CITA,
					GROUP_CONCAT(DISTINCT C.ID_CITA ORDER BY T.DESCRIPCION SEPARATOR ',') AS IDS
	    			FROM PROD_CITAS C
						INNER JOIN PROD_CLIENTES     L ON C.ID_CLIENTE = L.ID_CLIENTE
						INNER JOIN PROD_ESTATUS_CITA E ON C.ID_ESTATUS = E.ID_ESTATUS
						INNER JOIN PROD_TIPO_CITA	 T ON C.ID_TIPO	   = T.ID_TIPO
		    			 LEFT JOIN USUARIOS          U ON C.ID_USUARIO_ASIGNADO = U.ID_USUARIO
	    			WHERE C.FECHA_CITA BETWEEN CAST(DATE_SUB(NOW(), INTERVAL 15 DAY) AS DATE) AND  CAST(DATE_SUB(NOW(), INTERVAL -15 DAY) AS DATE)    			
	    			  AND C.ID_EMPRESA = $idEmpresa	
	    			GROUP BY C.FECHA_CITA,C.HORA_CITA
	    			ORDER BY C.FECHA_CITA ASC";
		}
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;				
	}	
	
	public function getResume($date,$hours){
		$result= Array();
		$this->query("SET NAMES utf8",false);		
		$sql= "SELECT COUNT(C.ID_CITA) AS TOTAL, T.DESCRIPCION AS N_TITTLE, 
					GROUP_CONCAT(DISTINCT C.ID_CITA ORDER BY T.DESCRIPCION SEPARATOR ',') AS IDS
					FROM PROD_CITAS C
					LEFT JOIN PROD_TIPO_CITA	   T ON C.ID_TIPO	  = T.ID_TIPO
					WHERE FECHA_CITA = '$date' 
					  AND HORA_CITA  = '$hours'    		    	
					  GROUP BY C.ID_TIPO ";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
	
	public function getDateByList($sIdDates){
		$filter = '';
		$result= Array();
		$this->query("SET NAMES utf8",false); 
		
    	$sql ="SELECT C.ID_CITA AS ID, C.ID_ESTATUS AS IDE, S.DESCRIPCION, S.COLOR,				
				P.RAZON_SOCIAL AS NOMBRE_CLIENTE,C.FOLIO,
				C.FECHA_CITA AS F_PROGRAMADA,
				C.HORA_CITA  AS H_PROGRAMADA,
				IF(C.FECHA_INICIO  IS NULL ,'--',C.FECHA_INICIO) AS FECHA_INICIO,
				IF(C.FECHA_TERMINO IS NULL ,'--',C.FECHA_TERMINO) AS FECHA_TERMINO,
				IF(U.ID_USUARIO    IS NULL ,'Sin Asignar', U.NOMBRE_COMPLETO) AS NOMBRE_TECNICO,
				IF(C.FECHA_CITA<'2015-01-19 00:00:00','A','N') AS NEW_FORM,
				T.DESCRIPCION AS N_TIPO,
				CONCAT(D.CALLE,' ',D.COLONIA,' ',D.NO_EXT,' ',D.NO_INT,' ',D.MUNICIPIO,' ',D.ESTADO,',CP:',D.CP) AS DIRECCION,
				IF(U.ID_USUARIO IS NULL,'0','1') AS TEC_ASIGNADO,
				U.ID_USUARIO AS ID_USER
				FROM PROD_CITAS C
				INNER JOIN PROD_CITA_DOMICILIO D ON C.ID_CITA 	 = D.ID_CITA
				INNER JOIN PROD_ESTATUS_CITA   S ON C.ID_ESTATUS = S.ID_ESTATUS
				INNER JOIN PROD_CLIENTES       P ON C.ID_CLIENTE = P.ID_CLIENTE
				 LEFT JOIN USUARIOS            U ON C.ID_USUARIO_ASIGNADO = U.ID_USUARIO 
				INNER JOIN PROD_TIPO_CITA	   T ON C.ID_TIPO	 = T.ID_TIPO
  	           WHERE C.ID_CITA IN ($sIdDates)
				ORDER BY S.ID_ESTATUS";  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}

	public function getCboTipoCitas(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
		
    	$sql ="SELECT ID_ESTATUS AS ID, DESCRIPCION AS NAME
				FROM PROD_ESTATUS_CITA
				ORDER BY ID_ESTATUS ASC";  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;				
	}
	
	public function getData($idCita,$idEmpresa){
		$filter = '';
		$result= Array();

    	$sql ="SELECT C.ID_CITA,C.ID_TIPO,C.ID_CLIENTE, C.FOLIO, C.FECHA_CITA,C.HORA_CITA,
    				  C.CONTACTO,C.TELEFONO_CONTACTO, T.DESCRIPCION AS N_TIPO, C.ID_ESTATUS, C.ID_USUARIO_ASIGNADO
				FROM PROD_CITAS C
				INNER JOIN PROD_TIPO_CITA T ON C.ID_TIPO = T.ID_TIPO
				WHERE C.ID_CITA    = $idCita
				  AND C.ID_EMPRESA = $idEmpresa
				LIMIT 1";  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}	
	
    public function updateRow($data){
       	$result     = Array();
        $result['status']  = false;

        $sql="UPDATE $this->_name
				SET ID_CLIENTE          =  ".$data['inputCliente'].",
					FECHA_CITA			= '".$data['inputFecha']."',
					HORA_CITA			= '".$data['inputHora']."',
					CONTACTO 			= '".$data['inputContacto']."',
					TELEFONO_CONTACTO   = '".$data['inputTelContacto']."', 		 	
					FOLIO				= '".$data['inputFolio']."' 
			WHERE $this->_primary   = ".$data['catId']." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
    }    

    public function getLocation($idCita){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
		
    	$sql ="SELECT * 
				FROM PROD_CITA_DOMICILIO
				WHERE ID_CITA = $idCita LIMIT 1";  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	  	
    }
    
    public function updateLocationRow($data){
       	$result     = Array();
        $result['status']  = false;
        
        $iLatitud	= (isset($data['inputLatitud'])  && $data['inputLatitud']!="") ? $data['inputLatitud'] : 0;
        $iLongitud	= (isset($data['inputLongitud']) && $data['inputLongitud']!="") ? $data['inputLongitud'] : 0;
        
        $sql="UPDATE PROD_CITA_DOMICILIO
				SET CALLE		= '".$data['inputCalle']."',
					COLONIA		= '".$data['inputColonia']."',
					NO_EXT		= '".$data['inputNext']."',
					NO_INT		= '".$data['inputNint']."',
					MUNICIPIO	= '".$data['inputMun']."',
					CP			= '".$data['inputCp']."',
					ESTADO		= '".$data['inputEdo']."',
					REFERENCIAS	= '".$data['inputRefs']."',
					LATITUD		=  ".$iLatitud.",
					LONGITUD	=  ".$iLongitud."
			WHERE ID_CITA    = ".$data['catId']." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
    }
    
	public function getDataExtras($idCita){
		$filter = '';
		$result= Array();

    	$sql ="SELECT *
				FROM PROD_CITAS_EXTRAS
				WHERE ID_CITA = $idCita";
		$query   = $this->query($sql);
		if(count($query)>0){	  
			$result = $query;
		}
        
		return $result;			
	}	

	public function updateExtraCitas($data){
        $result     = false;            
        $sql = "UPDATE PROD_CITAS_EXTRAS 
				SET TITULO  = '".$data['sTitulo']."',
					VALOR   = '".$data['sValor']."'
				WHERE ID_EXTRA =  ".$data['idExtra']." 
				  AND ID_CITA  =  ".$data['idCita']." LIMIT 1";
        try{
    		$query   = $this->query($sql,false);
    		$result	 = true;	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;			
	}	
	
    public function updatePersonal($data){
       	$result     = Array();
        $result['status']  = false;

        $sql="UPDATE $this->_name
				SET ID_USUARIO_ASIGNADO =  ".$data['bRadioInit']." 
			WHERE $this->_primary   = ".$data['catId']." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
    }  	
}