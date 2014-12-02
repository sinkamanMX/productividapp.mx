<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Mensajes extends My_Db_Table
{
	protected $_schema 	= 'PRODUCTIVIDAPP';
	protected $_name 	= 'PROD_MENSAJES';
	protected $_primary = 'ID_MENSAJE';
	
	public function getContactos($idObject,$idEmpresa,$filter = -1){
		$result= Array();
		$filtro = ($filter>-1) ? '	A.ID_SUCURSAL = $filter OR I.ID_SUCURSAL = $filter OR': '';
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT A.ID_USUARIO AS RECIBIO, I.ID_USUARIO AS ENVIO, M.CREADO, M.MENSAJE,
				  FLOOR(HOUR(TIMEDIFF(M.CREADO, CURRENT_TIMESTAMP)) / 24) AS HAGODAYS,
				  MOD(HOUR(TIMEDIFF(M.CREADO, CURRENT_TIMESTAMP)), 24)   AS HAGOHOURS,  
				  MINUTE(TIMEDIFF(M.CREADO, CURRENT_TIMESTAMP))  AS HAGOMINS,
				  CONCAT(A.NOMBRE,' ',A.APELLIDOS) AS N_RECIBE,
				  T.DESCRIPCION AS TEL_RECIBE,
				  CONCAT(I.NOMBRE,' ',I.APELLIDOS) AS N_ENVIA,
				  U.DESCRIPCION AS TEL_ENVIA,
				  M.PROCESADO,
				  M.LEIDO
				FROM PROD_MENSAJES M
				INNER JOIN USUARIOS          A ON A.ID_USUARIO  = M.ID_USR_TO
				 LEFT JOIN PROD_USR_TELEFONO R ON R.ID_USUARIO  = A.ID_USUARIO
				 LEFT JOIN PROD_TELEFONOS    T ON R.ID_TELEFONO = T.ID_TELEFONO				 
				INNER JOIN USUARIOS          I ON I.ID_USUARIO  = M.ID_USR_SEND
				 LEFT JOIN PROD_USR_TELEFONO S ON S.ID_USUARIO  = I.ID_USUARIO
				 LEFT JOIN PROD_TELEFONOS    U ON U.ID_TELEFONO = S.ID_TELEFONO
				WHERE $filtro (M.ID_USR_TO = $idObject  
						 OR ID_USR_TO   IN(SELECT ID_TELEFONO
											FROM PROD_TELEFONOS 
											WHERE  ID_EMPRESA = $idEmpresa))
				   OR (M.ID_USR_SEND = $idObject
					   OR ID_USR_SEND IN(SELECT ID_TELEFONO
										FROM PROD_TELEFONOS 
										WHERE  ID_EMPRESA = $idEmpresa))				
				GROUP BY 	M.ID_USR_TO, 	M.ID_USR_SEND
				ORDER BY 	M.CREADO ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
	
	public function processListContactos($aDataprocess,$idUsuario){
		$aDataUnica = Array();
		
		foreach($aDataprocess as $items){
			
			$idContacto = 0;
			
			if($items['RECIBIO']!=$idUsuario){
				$idContacto = $items['RECIBIO'];	
				$aDataUnica[$idContacto]['ID'] = $idContacto;								 
				$aDataUnica[$idContacto]['NOMBRE']  = $items['N_RECIBE'];
				$aDataUnica[$idContacto]['TEL'] 	= $items['TEL_RECIBE'];
			}else if($items['ENVIO']!=$idUsuario){
				$idContacto = $items['ENVIO'];		
				$aDataUnica[$idContacto]['ID'] = $idContacto;
				$aDataUnica[$idContacto]['NOMBRE']  = $items['N_ENVIA'];
				$aDataUnica[$idContacto]['TEL'] 	= $items['TEL_ENVIA'];						
			} 

			$aDataUnica[$idContacto]['MENSAJE'] 	= $items['MENSAJE'];
			$aDataUnica[$idContacto]['CREADO']  	= $items['CREADO'];
			$aDataUnica[$idContacto]['HAGODAYS']  	= $items['HAGODAYS'];
			$aDataUnica[$idContacto]['HAGOHOURS'] 	= $items['HAGOHOURS'];
			$aDataUnica[$idContacto]['HAGOMINS'] 	= $items['HAGOMINS'];			
			$aDataUnica[$idContacto]['PROCESADO'] 	= $items['PROCESADO'];
			$aDataUnica[$idContacto]['LEIDO'] 		= $items['LEIDO'];			
		}
		return $aDataUnica;	
	}
	
	public function getConversacion($idUser,$idContacto){
		$this->query("SET NAMES utf8",false); 
		$sql = "SELECT A.ID_USUARIO AS RECIBIO, I.ID_USUARIO AS ENVIO, M.CREADO, M.MENSAJE,
				  CONCAT(A.NOMBRE,' ',A.APELLIDOS) AS N_RECIBE,
				  T.DESCRIPCION AS TEL_RECIBE,
				  CONCAT(I.NOMBRE,' ',I.APELLIDOS) AS N_ENVIA,
				  U.DESCRIPCION AS TEL_ENVIA ,
				  M.PROCESADO,
				  M.LEIDO
				FROM  PROD_MENSAJES M
				INNER JOIN USUARIOS          A ON A.ID_USUARIO  = M.ID_USR_TO
				 LEFT JOIN PROD_USR_TELEFONO R ON R.ID_USUARIO  = A.ID_USUARIO
				 LEFT JOIN PROD_TELEFONOS    T ON R.ID_TELEFONO = T.ID_TELEFONO
				 
				INNER JOIN USUARIOS          I ON I.ID_USUARIO  = M.ID_USR_SEND
				 LEFT JOIN PROD_USR_TELEFONO S ON S.ID_USUARIO  = I.ID_USUARIO
				 LEFT JOIN PROD_TELEFONOS    U ON U.ID_TELEFONO = S.ID_TELEFONO
				WHERE (M.ID_USR_TO = $idUser     AND  M.ID_USR_SEND = $idContacto)
				  OR  (M.ID_USR_TO = $idContacto AND  M.ID_USR_SEND = $idUser)
				ORDER BY M.CREADO ASC  ";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;				
	}
	
	public function newMessage($data){
        $result  = false;
        
        $sql="INSERT INTO PROD_MENSAJES
				SET ID_USR_SEND		=  ".$data['inputSend'].",
					ID_USR_TO		=  ".$data['inputTo'].",
					MENSAJE			=  '".$data['inputMsg']."',
					CREADO			= CURRENT_TIMESTAMP";
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
}