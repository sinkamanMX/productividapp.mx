<?php
/**
 * Archivo de definici�n de perfiles
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
        Zend_Debug::dump($sql);
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
				SET ID_CITA =  ".$data['idCita'].", 
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
	
	/*	


	public function insertaFormCita($data){
        $result	= false;
        $sql="INSERT INTO PROD_CITA_FORMULARIO
				SET ID_CITA			=  ".$data['idCita'].",
					ID_FORMULARIO 	=  ".$data['idFormulario'];
        try{
    		$query   = $this->query($sql,false);
    		if($query){
    			$result= true;	
    		}
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
        return $result;	
	}
	
	public function insertDomCitaOther($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO PROD_CITA_DOMICILIO
				SET ID_CITA		=  ".$data['idCita'].",
					CALLE		= '".$data['inputStreetO']."',
					COLONIA		= '".$data['scolonia']."',
					NO_EXT		= '".$data['inputNoExtO']."',
					NO_INT		= '".$data['inputNoIntO']."',
					MUNICIPIO	= '".$data['sMunicipio']."',
					CP			= '".$data['inputCPO']."',
					ESTADO		= '".$data['sEstado']."',
					REFERENCIAS	= '".$data['inputRefsO']."',
					LATITUD		=  ".$data['sLatitud'].",
					LONGITUD	=  ".$data['sLongitud'];
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
	
	public function getCitasPendientes($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT  'false' AS allday ,
				PROD_ESTATUS_CITA.COLOR AS borderColor,
				PROD_ESTATUS_CITA.COLOR AS color,
				CONCAT(CALLE,' #',NO_EXT,', Col.',COLONIA,', ',MUNICIPIO,', ',ESTADO) AS description,
				CONCAT(FECHA_CITA,' ',HORA_CITA) AS end,
				CONCAT(FECHA_CITA,' ',HORA_CITA) AS start ,
				CONCAT(
					'Cli: ',
					CONCAT(
					PROD_CLIENTES.NOMBRE,' ',PROD_CLIENTES.APELLIDOS
					)
					,'
					',
					'Tec: ',
					CONCAT(
					USUARIOS.NOMBRE,' ', USUARIOS.APELLIDOS
					),'
					',
					'Hora: ',
					DATE_FORMAT(HORA_CITA, '%H:%i')
					
				)  AS title,
				CONTACTO AS cliente,
				TELEFONO_CONTACTO AS telefono,
				PROD_CITAS.ID_CITA AS id,
				PROD_ESTATUS_CITA.DESCRIPCION AS estatus
    			FROM PROD_CITAS
    			INNER JOIN PROD_CLIENTES       ON PROD_CITAS.ID_CLIENTE   = PROD_CLIENTES.ID_CLIENTE
    			INNER JOIN PROD_CITA_DOMICILIO ON PROD_CITAS.ID_CITA      = PROD_CITA_DOMICILIO.ID_CITA
    			INNER JOIN PROD_ESTATUS_CITA   ON PROD_CITAS.ID_ESTATUS   = PROD_ESTATUS_CITA.ID_ESTATUS
    			INNER JOIN PROD_CITA_USR       ON PROD_CITAS.ID_CITA      = PROD_CITA_USR.ID_CITA
    			INNER JOIN USUARIOS            ON PROD_CITA_USR.ID_USUARIO= USUARIOS.ID_USUARIO
    			WHERE PROD_CITAS.ID_ESTATUS IN (1,2,5)
    			 AND PROD_CITAS.ID_EMPRESA = $idEmpresa";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
	public function getCboTipoServicio($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_TPO AS ID, DESCRIPCION AS NAME
				FROM PROD_TPO_CITA 
				WHERE ID_EMPRESA = $idEmpresa 
				ORDER BY ID ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
	public function getCboStatus(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_ESTATUS AS ID, DESCRIPCION AS NAME
				FROM PROD_ESTATUS_CITA
				WHERE ACTIVO = 1";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
	public function getCitasDet($idCita){
		$filter = '';
		$result= Array();
		
		if($idCita>-1){
			$filter .= ($filter=='') ? ' WHERE ': ' AND ';
			$filter .= ' A.ID_CITA = '.$idCita;
		}		

    	$sql ="SELECT A.ID_CITA,
  	               A.FECHA_CITA,
  	               A.HORA_CITA,
  	               A.CONTACTO,
  	               A.TELEFONO_CONTACTO,
  	               A.FOLIO,
  	               CONCAT(E.CALLE,' ',E.NUMERO_INT,' ',E.NUMERO_EXT,' ',E.COLONIA,' ',E.MUNICIPIO,' ',E.ESTADO,' ',E.CP) AS DIRECCION_CITA,
  	               B.REFERENCIAS AS REF_CITA,
  	               B.CP AS CP_CITA,
  	               B.LATITUD AS LAT_CITA,
  	               B.LONGITUD AS LON_CITA,
  	               D.COD_CLIENTE,
  	               CONCAT(D.NOMBRE,' ',D.APELLIDOS) AS NOMBRE_CLIENTE,
                   D.TELEFONO_FIJO,
                   D.TELEFONO_MOVIL,
                   D.EMAIL,
                   CONCAT(E.CALLE,' ',E.NUMERO_INT,' ',E.NUMERO_EXT,' ',E.COLONIA,' ',E.MUNICIPIO,' ',E.ESTADO,' ',E.CP) AS DIRECCION_CLIENTE,
                   E.REFERENCIAS,
                   E.LATITUD,
                   E.LONGITUD ,
                   S.DESCRIPCION AS ESTATUS ,
                   A.ID_ESTATUS,
                   IF(U.NOMBRE IS NULL ,'No asignado' ,CONCAT(U.NOMBRE,' ',U.APELLIDOS)) AS OPERADOR,
                   U.ID_USUARIO AS ID_OPERADOR
  	        FROM PROD_CITAS A
  	           INNER JOIN PROD_CITA_DOMICILIO     B ON B.ID_CITA    = A.ID_CITA
  	           INNER JOIN PROD_CLIENTES           D ON D.ID_CLIENTE = A.ID_CLIENTE
  	           INNER JOIN PROD_DOMICILIOS_CLIENTE E ON E.ID_CLIENTE = D.ID_CLIENTE
  	           INNER JOIN PROD_ESTATUS_CITA       S ON A.ID_ESTATUS = S.ID_ESTATUS	  	           
  	           LEFT JOIN PROD_CITA_USR            C ON C.ID_CITA    = A.ID_CITA
  	           LEFT JOIN USUARIOS            	  U ON C.ID_USUARIO = U.ID_USUARIO
  	           ".$filter;  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}		
	
	public function getCitasSearch($dateIn,$dateFin,$keySearch,$Status,$idCita=-1){
		$filter = '';
		$result= Array();
		$this->query("SET NAMES utf8",false); 
		
		if($keySearch!=''){
			$filter .= ' WHERE A.CONTACTO LIKE "%'.$keySearch.'%" OR A.FOLIO LIKE"%'.$keySearch.'%" OR  D.NOMBRE LIKE "%'.$keySearch.'%"';	
		}
		
		if($Status!=''){
			$filter .= ($filter=='') ? ' WHERE ': ' AND ';
			$filter .= ' A.ID_ESTATUS IN ('.$Status.') ';
		}
		
		if($idCita>-1){
			$filter .= ($filter=='') ? ' WHERE ': ' AND ';
			$filter .= ' A.ID_CITA = '.$idCita;
		}		
		
		if($dateIn!='' && $dateFin!=""){
			$filter .= ($filter=='') ? ' WHERE ': ' OR ';
			$filter .= 'A.FECHA_CITA BETWEEN "'.$dateIn.'" AND "'.$dateFin.'"';
		}
    	$sql ="SELECT A.ID_CITA,
  	               A.FECHA_CITA,
  	               A.HORA_CITA,
  	               A.CONTACTO,
  	               A.TELEFONO_CONTACTO,
  	               A.FOLIO,
  	               CONCAT(E.CALLE,' ',E.NUMERO_INT,' ',E.NUMERO_EXT,' ',E.COLONIA,' ',E.MUNICIPIO,' ',E.ESTADO,' ',E.CP) AS DIRECCION_CITA,
  	               B.REFERENCIAS AS REF_CITA,
  	               B.CP AS CP_CITA,
  	               B.LATITUD AS LAT_CITA,
  	               B.LONGITUD AS LON_CITA,
  	               D.COD_CLIENTE,
  	               CONCAT(D.NOMBRE,' ',D.APELLIDOS) AS NOMBRE_CLIENTE,
                   D.TELEFONO_FIJO,
                   D.TELEFONO_MOVIL,
                   D.EMAIL,
                   CONCAT(E.CALLE,' ',E.NUMERO_INT,' ',E.NUMERO_EXT,' ',E.COLONIA,' ',E.MUNICIPIO,' ',E.ESTADO,' ',E.CP) AS DIRECCION_CLIENTE,
                   E.REFERENCIAS,
                   E.LATITUD,
                   E.LONGITUD ,
                   S.DESCRIPCION AS ESTATUS ,
                   A.ID_ESTATUS,
                   IF(U.NOMBRE IS NULL ,'No asignado' ,CONCAT(U.NOMBRE,' ',U.APELLIDOS)) AS OPERADOR,
                   U.ID_USUARIO AS ID_OPERADOR
  	        FROM PROD_CITAS A
  	           INNER JOIN PROD_CITA_DOMICILIO     B ON B.ID_CITA    = A.ID_CITA
  	           INNER JOIN PROD_CLIENTES           D ON D.ID_CLIENTE = A.ID_CLIENTE
  	           INNER JOIN PROD_DOMICILIOS_CLIENTE E ON E.ID_CLIENTE = D.ID_CLIENTE
  	           INNER JOIN PROD_ESTATUS_CITA       S ON A.ID_ESTATUS = S.ID_ESTATUS	  	           
  	           LEFT JOIN PROD_CITA_USR            C ON C.ID_CITA    = A.ID_CITA
  	           LEFT JOIN USUARIOS            	  U ON C.ID_USUARIO = U.ID_USUARIO
  	           ".$filter;    	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
	
	public function setRow($data){
        $result  = false;
        $options = '';
        $idInput		= $data['strInput'];
		$inputfechaCita = explode(" ", $data['inputFecha'] );
		$inputEstatus   = $data['inputEstatus']; 
		$changeDate     = $data['inputChangeDate'];
		
		if($changeDate==1){
			$date = $inputfechaCita[0]; 
			$time = $inputfechaCita[1];			
			$options = "FECHA_CITA			= '".$date."',
					   HORA_CITA			= '".$time.":00',";
		}
		       
        $sql="UPDATE $this->_name
				SET  ID_ESTATUS 			= ".$inputEstatus.",
					".$options."
					 ID_USUARIO_MODIFICO	= ".$data['ID_USUARIO']." , 
					 FECHA_MODIFICACION		= CURRENT_TIMESTAMP  
				WHERE ID_CITA =	$idInput";
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;			
	}
	
	public function changePersonal($data){
        $result = false;
        $idInput		= $data['strInput'];        
	
        if($data['ID_OPERADOR']!= NULL && $data['ID_OPERADOR']!=""){
        	$sql="UPDATE PROD_CITA_USR
				SET  ID_USUARIO =  ".$data['inputPersonal']."
					 WHERE ID_CITA = $idInput LIMIT 1";	
        }if($data['ID_OPERADOR'] == NULL){
        	$sql="INSERT INTO PROD_CITA_USR
				SET  ID_USUARIO = ".$data['inputPersonal'].",
				     ID_CITA    = $idInput";        	
        }else{
        	$sql="DELETE FROM PROD_CITA_USR
					 WHERE ID_CITA = $idInput LIMIT 1";        	
        }
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	   		
	}
	
	public function insertActPrev($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO AVL_ACTIVOS_PREVIO
				SET ID_CLIENTE		= ".$data['idCliente'].",
					DESCRIPCION		= '".$data['nCliente']."-".$data['inputPlacas']."',
					IDENTIFICADOR1	= '".$data['inputPlacas']."',
					SERIE1			= '".$data['inputSerie']."',
					MODELO			= ".$data['idCliente'].",
					TIPO_VEHICULO	= 1,
					COLOR			= ".$data['inputColor']; 
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

	public function assignUser($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO PROD_CITA_USR
				SET ID_CITA		= ".$data['idCita'].",
					ID_USUARIO	= ".$data['uAssign']; 
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

	public function getResumeByDay($idSucursal,$dFechaIn,$dFechaFin,$idTecnico){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		$sFilter = ($idTecnico!="") ? ' C.ID_USUARIO = '.$idTecnico: ' E.ID_SUCURSAL IN ('.$idSucursal.')'; 		
    	$sql ="SELECT C.ID_CITA AS ID, C.ID_ESTATUS AS IDE, S.DESCRIPCION, S.COLOR,				
				CONCAT(P.NOMBRE,' ',P.APELLIDOS) AS NOMBRE_CLIENTE,
				C.FECHA_CITA AS F_PROGRAMADA,
				C.HORA_CITA  AS H_PROGRAMADA,
				IF(C.FECHA_INICIO  IS NULL ,'--',C.FECHA_INICIO) AS FECHA_INICIO,
				IF(C.FECHA_TERMINO IS NULL ,'--',C.FECHA_TERMINO) AS FECHA_TERMINO,
				IF(U.ID_USUARIO    IS NULL ,'Sin Asignar', CONCAT(U.NOMBRE,' ',U.APELLIDOS)) AS NOMBRE_TECNICO
				FROM PROD_CITAS C
				INNER JOIN PROD_CITA_DOMICILIO D ON C.ID_CITA 	 = D.ID_CITA
				INNER JOIN PROD_ESTATUS_CITA   S ON C.ID_ESTATUS = S.ID_ESTATUS
				INNER JOIN PROD_CLIENTES       P ON C.ID_CLIENTE = P.ID_CLIENTE
				 LEFT JOIN PROD_CITA_USR       A ON C.ID_CITA	 = A.ID_CITA
				 LEFT JOIN USUARIOS			   U ON A.ID_USUARIO = U.ID_USUARIO 
				WHERE C.ID_CITA IN (
					SELECT C.ID_CITA
					FROM PROD_CITA_USR C 
					INNER JOIN USR_EMPRESA E ON C.ID_USUARIO = E.ID_USUARIO 
					WHERE $sFilter
					)
				AND C.FECHA_CITA BETWEEN '$dFechaIn' AND '$dFechaFin'
				ORDER BY S.ID_ESTATUS";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;	
	}
	
	
	public function getDataRep($idOject){
		$filter = '';
		$result= Array();
		$this->query("SET NAMES utf8",false);		
    	$sql ="SELECT   C.ID_CITA AS ID, 
						C.FECHA_CITA,
						C.HORA_CITA,
						CONCAT(R.NOMBRE,' ',R.APELLIDOS) AS USR_REGISTRADO,
						CONCAT(P.NOMBRE,' ',P.APELLIDOS) AS NOMBRE_CLIENTE,		
						CONCAT(M.CALLE,' ',M.NUMERO_EXT,' ',M.NUMERO_INT,' ',M.COLONIA) AS DIRECCION_CLIENTE1,
						CONCAT(M.MUNICIPIO,' ',M.ESTADO,' ',M.CP) AS DIRECCION_CLIENTE2,
						CONCAT(D.CALLE,' ',D.NO_EXT,' ',D.NO_INT,' ',D.COLONIA) AS DIRECCION_CITA1,
						CONCAT(D.MUNICIPIO,' ',D.ESTADO,' ',D.CP) AS DIRECCION_CITA2,	
						IF(U.ID_USUARIO    IS NULL ,'Sin Asignar', CONCAT(U.NOMBRE,' ',U.APELLIDOS)) AS NOMBRE_TECNICO,
						IF(C.FECHA_INICIO  IS NULL ,'--',C.FECHA_INICIO) AS FECHA_INICIO,
						IF(C.FECHA_TERMINO IS NULL ,'--',C.FECHA_TERMINO) AS FECHA_TERMINO
				FROM PROD_CITAS C
					INNER JOIN USUARIOS			   R ON C.ID_USUARIO_CREO = R.ID_USUARIO
					INNER JOIN PROD_CITA_DOMICILIO D ON C.ID_CITA 	 = D.ID_CITA
					INNER JOIN PROD_ESTATUS_CITA   S ON C.ID_ESTATUS = S.ID_ESTATUS
					INNER JOIN PROD_CLIENTES       P ON C.ID_CLIENTE = P.ID_CLIENTE
					INNER JOIN PROD_DOMICILIOS_CLIENTE M ON P.ID_CLIENTE = M.ID_CLIENTE
					INNER JOIN PROD_CITA_USR       A ON C.ID_CITA	 = A.ID_CITA
					INNER JOIN USUARIOS			   U ON A.ID_USUARIO = U.ID_USUARIO 
				WHERE C.ID_CITA =".$idOject;  
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}	
	
	*/
}