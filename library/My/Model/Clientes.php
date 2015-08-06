<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Clientes extends My_Db_Table
{
    protected $_schema 	= 'SIMA';
	protected $_name 	= 'PROD_CLIENTES';
	protected $_primary = 'ID_CLIENTE';
	
	/**
	 * 
	 * Obtiene los elementos extras de un tipo de cita
	 * @param int $idObject
	 */
    public function getCbo($idEmpresa){
    	$result     = Array();    	
    	try{ 
    		$sql = "SELECT ID_CLIENTE AS ID,  CONCAT(COD_CLIENTE,'-',RAZON_SOCIAL) AS NAME, COD_CLIENTE, RAZON_SOCIAL
					FROM PROD_CLIENTES 
					WHERE ID_EMPRESA = $idEmpresa
					ORDER BY COD_CLIENTE";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query;			
			}	
	        
			return $result;			
    	}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
    }
    
	public function getAddress($idCliente){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_DOMICILIO AS ID, DESCRIPCION AS NAME,PROD_CLIENTES_DOMICILIOS.*
				FROM PROD_CLIENTES_DOMICILIOS
				WHERE ID_CLIENTE = $idCliente";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
		
	public function insertAddress($aDataElement){
        $result     = Array();
        $result['status']  = false;
 		$sql="INSERT INTO PROD_CLIENTES_DOMICILIOS
				SET ID_CLIENTE	=  ".$aDataElement['idCliente'].",
					DESCRIPCION	= '".$aDataElement['desc']."',	
					ESTADO		= '".$aDataElement['edo']."',
					MUNICIPIO	= '".$aDataElement['mun']."',
					COLONIA		= '".$aDataElement['col']."',
					CALLE		= '".$aDataElement['calle']."',
					CP			= '".$aDataElement['cp']."',
					NUMERO_EXT	= '".$aDataElement['noext']."',
					NUMERO_INT	= '".$aDataElement['noint']."',
					REFERENCIAS = '".$aDataElement['refs']."', 
					LATITUD		=  ".$aDataElement['lat'].",
					LONGITUD	=  ".$aDataElement['lon'].",
					ESTATUS		=  ".$aDataElement['status'].",
					CREADO		=  CURRENT_TIMESTAMP";
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
	
	public function updateAddress($aDataElement){
        $result     = Array();
        $result['status']  = false;
        
 		$sql="UPDATE PROD_CLIENTES_DOMICILIOS
				SET DESCRIPCION	= '".$aDataElement['desc']."',	
					ESTADO		= '".$aDataElement['edo']."',
					MUNICIPIO	= '".$aDataElement['mun']."',
					COLONIA		= '".$aDataElement['col']."',
					CALLE		= '".$aDataElement['calle']."',
					CP			= '".$aDataElement['cp']."',
					NUMERO_EXT	= '".$aDataElement['noext']."',
					NUMERO_INT	= '".$aDataElement['noint']."',
					REFERENCIAS = '".$aDataElement['refs']."', 
					LATITUD		=  ".$aDataElement['lat'].",
					LONGITUD	=  ".$aDataElement['lon'].",
					ESTATUS		=  ".$aDataElement['status']."				
				WHERE ID_DOMICILIO = ".$aDataElement['id']." LIMIT 1"; 						       
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
	
	public function deleteAddress($aDataElement,$idObject){
        $result     = Array();
        $result['status']  = false;  
        
		$sql  = "DELETE FROM PROD_CLIENTES_DOMICILIOS 
					WHERE ID_DOMICILIO  = ".$aDataElement['id']."
					  AND ID_CLIENTE 	= ".$idObject." LIMIT 1";
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