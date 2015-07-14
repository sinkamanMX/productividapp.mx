<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Modulos extends My_Db_Table
{
	protected $_schema 	= 'DB_PRODUCTIVIDAPP';
	protected $_name 	= 'DB_MODULOS';
	protected $_primary = 'ID_DB_MODULO';

	/**
	 * 
	 * Obtiene los elementos de un formulario
	 * @param int $idObject
	 * @param int $idEmpresa
	 */
    public function getElementos($idObject){
    	$result     = Array();    	
    	try{ 
    		$sql = "SELECT *
					FROM DB_MODULOS_CAMPOS
					WHERE ID_DB_MODULO = $idObject
					ORDER BY ORDEN ASC";
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
    
    public function getValidaciones(){
     	$result     = Array();    	
    	try{   
    		$sql = "SELECT ID_VALIDACION AS ID, DESCRIPCION AS  NAME
					FROM DB_VALIDACIONES
					ORDER BY ID ASC";
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
    
    public function getTipos(){
     	$result     = Array();    	
    	try{   
    		$sql = "SELECT ID_DB_TIPO AS ID, DESCRIPCION AS NAME
					FROM DB_TIPO_CAMPOS 
					ORDER BY  ID";
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
    
	/**
	 * 
	 * Inserta un nuevo elemento y lo relaciona con un modulo
	 * @param Array $aDataIn
	 * @return Array Id, Estatus de la operacion.
	 */
    public function insertElement($aDataElement,$idObject,$idEmpresa){
        $result     = Array();
        $result['status']  = false;
               
        $sql="INSERT INTO DB_MODULOS_CAMPOS
			  SET ID_DB_MODULO		= ".$idObject.",
				  ID_VALIDACION		='".@$aDataElement['validacion']."',
				  ID_TIPO_CAMPO		= ".@$aDataElement['tipo'].",
				  DESCRIPCION		='".@$aDataElement['desc']."',
				  NOMBRE_BD			='".@$aDataElement['namebd']."',
				  INPUT_NAME		='".@$aDataElement['namein']."',
				  DIV_CONTENEDOR	= ".@$aDataElement['contenedor']." ,
				  ON_UPDATES		= ".@$aDataElement['onupdates']." ,
				  ON_INSERTS		= ".@$aDataElement['oninserts']." ,
				  TIPO				='".@$aDataElement['tdato']."',
				  VALUE				='".@$aDataElement['invalue']."',
				  OPCIONES_AJAX		='".@$aDataElement['opsajax']."',
				  OPCIONES_FUNCTIONS='".@$aDataElement['opsfuncion']."',
				  ACCION			='".@$aDataElement['accion']."',
				  VALIDACION_QUERY	='".@$aDataElement['valquery']."',
				  VALIDACION_MENSAJE='".@$aDataElement['msgval']."',
				  OPCIONES_QUERY	='".@$aDataElement['opsquery']."',
				  REPLACE_QUERY		='".@$aDataElement['opsreplace']."',
				  QUERY_DEPENDENCIES= ".@$aDataElement['usequery']." ,
				  ORDEN				= ".@$aDataElement['orden']." ,
				  VISIBLE			= ".@$aDataElement['visible']." ,
				  ESTATUS			= ".@$aDataElement['status']." ,
				  CREADO			= CURRENT_TIMESTAMP";
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

    /**
     * 
     * Elimina un elemento de un formulario
     * @param Array $aDataElement
     * @param int $idObject
     */
    public function deleteRowRel($aDataElement,$idObject){
        $result     = Array();
        $result['status']  = false;  
        
		$sqlDel  = "DELETE FROM DB_MODULOS_CAMPOS 
					WHERE ID_DB_CAMPO   = ".$aDataElement['id']."
					  AND ID_DB_MODULO  = ".$idObject." LIMIT 1";
	    $query   = $this->query($sqlDel,false);    

        try{
			if($query){
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	     	
    }    

	/**
	 * 
	 * Actualiza un elemento del modulo
	 * @param Array $aDataIn
	 * @return Boolean Estatus de la operacion
	 */
    public function updateRowRel($aDataElement){
        $result     = Array();
        $result['status']  = false;
        
       $sql="UPDATE DB_MODULOS_CAMPOS
			 SET ID_VALIDACION		='".@$aDataElement['validacion']."',
				  ID_TIPO_CAMPO		= ".@$aDataElement['tipo'].",
				  DESCRIPCION		='".@$aDataElement['desc']."',
				  NOMBRE_BD			='".@$aDataElement['namebd']."',
				  INPUT_NAME		='".@$aDataElement['namein']."',
				  DIV_CONTENEDOR	= ".@$aDataElement['contenedor']." ,
				  ON_UPDATES		= ".@$aDataElement['onupdates']." ,
				  ON_INSERTS		= ".@$aDataElement['oninserts']." ,
				  TIPO				='".@$aDataElement['tdato']."',
				  VALUE				='".@$aDataElement['invalue']."',
				  OPCIONES_AJAX		='".@$aDataElement['opsajax']."',
				  OPCIONES_FUNCTIONS='".@$aDataElement['opsfuncion']."',
				  ACCION			='".@$aDataElement['accion']."',
				  VALIDACION_QUERY	='".@$aDataElement['valquery']."',
				  VALIDACION_MENSAJE='".@$aDataElement['msgval']."',
				  OPCIONES_QUERY	='".@$aDataElement['opsquery']."',
				  REPLACE_QUERY		='".@$aDataElement['opsreplace']."',
				  QUERY_DEPENDENCIES= ".@$aDataElement['usequery']." ,
				  ORDEN				= ".@$aDataElement['orden']." ,
				  VISIBLE			= ".@$aDataElement['visible']." ,
				  ESTATUS			= ".@$aDataElement['status']."
			WHERE ID_DB_CAMPO = ".$aDataElement['id']." LIMIT 1";		        
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