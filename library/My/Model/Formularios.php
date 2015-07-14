<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Formularios extends My_Db_Table
{
	protected $_schema 	= 'BD_SIAMES';
	protected $_name 	= 'PROD_FORMULARIO';
	protected $_primary = 'ID_FORMULARIO';

	/**
	 * 
	 * Devuelve la informacion de un unformulario.
	 * @param int $idObject
	 */
	public function getData($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT *
				FROM $this->_name
				WHERE $this->_primary = $idObject LIMIT 1";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}
	/**
	 * 
	 * * Se valida que no existe el mismo titulo.
	 * @param String $stringSearch
	 * @param String $idObject
	 * @param String $idEmpresa
	 * @return Array Resultado del query
	 */	
	public function validateDataBy($stringSearch="", $idObject="",$idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		
    	$sql ="SELECT *
				FROM $this->_name
				WHERE 	TITULO 	   = '".$stringSearch."'
				  AND   ID_EMPRESA = $idEmpresa  
				  AND   $this->_primary <> $idObject";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
	/**
	 * 
	 * Inserta un nuevo registro en la tabla de formularios
	 * @param Array $aDataIn
	 * @return Array Id, Estatus de la operacion.
	 */
    public function insertRow($aDataIn){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO $this->_name			 
					SET ID_EMPRESA		=  ".$aDataIn['inputEmpresa'].",
						TITULO			= '".$aDataIn['inputTitulo']."',
						DESCRIPCION		= '".$aDataIn['inputDescripcion']."',
						ID_USUARIO_CREO	= '".$aDataIn['userCreate']."',
						FECHA_CREACION	= CURRENT_TIMESTAMP,
						ESTATUS			= '".$aDataIn['inputEstatus']."',
						FOTOS_EXTRAS	= '".$aDataIn['inputPhotos']."',
						QRS_EXTRAS		= '".$aDataIn['inputQrs']."',
						FIRMAS_EXTRAS	= '".$aDataIn['inputFirma']."',
						LOCALIZACION	= '".$aDataIn['inputLocate']."'";
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
    
	/**
	 * 
	 * Actualiza un nuevo registro en la tabla de formularios
	 * @param Array $aDataIn
	 * @return Boolean Estatus de la operacion
	 */
    public function updateRow($aDataIn){
        $result     = Array();
        $result['status']  = false;

        $sql="UPDATE $this->_name			 
				SET TITULO			= '".$aDataIn['inputTitulo']."',
					DESCRIPCION		= '".$aDataIn['inputDescripcion']."',
					/*ID_USUARIO_MODIFICO	= '".$aDataIn['userCreate']."',*/
					/*FECHA_MODIFICACION	= CURRENT_TIMESTAMP,*/
					ESTATUS			= '".$aDataIn['inputEstatus']."',
					FOTOS_EXTRAS	= '".$aDataIn['inputPhotos']."',
					QRS_EXTRAS		= '".$aDataIn['inputQrs']."',
					FIRMAS_EXTRAS	= '".$aDataIn['inputFirma']."',
					LOCALIZACION	= '".$aDataIn['inputLocate']."'
				WHERE $this->_primary =".$aDataIn['catId']." LIMIT 1";
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

	/**
	 * 
	 * Obtiene los elementos de un formulario
	 * @param int $idObject
	 * @param int $idEmpresa
	 */
    public function getElementos($idObject,$idEmpresa){
    	$result     = Array();    	
    	try{ 
    		$sql = "SELECT X.ORDEN,X.SUBORDEN, X.ID_ELEMENTO AS ID, X.DEPENDE, X.ORDEN, X.DESCRIPCION AS N_ELEMENTO, 
    					   X.ESTATUS, X.VALORES_CONFIG, X.REQUERIDO, E.DESCRIPCION AS TIPO, X.ID_TIPO , 
    					   X.VALORES_MIN_MAX, X.ID_USR_CATALOGO, X.VALORES_MIN_MAX, E.REQ_OPCIONES,
    					   X.ID_FORMULARIO, X.ID_ELEMENTO
					FROM  PROD_ELEMENTOS X
					INNER JOIN PROD_TIPO_ELEMENTO E ON X.ID_TIPO          = E.ID_TIPO
					 LEFT JOIN USR_CATALOGOS      C ON X.ID_USR_CATALOGO  = C.ID_CATALOGO
					WHERE X.ID_FORMULARIO = $idObject
					ORDER BY X.ORDEN, X.SUBORDEN ASC";
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
	 * Obtiene los elementos de un formulario
	 * @param int $idObject
	 * @param int $idEmpresa
	 */
    public function getSubElementos($idObject,$idElement){
    	$result     = Array();    	
    	try{ 
    		$sql = "SELECT X.ID_ELEMENTO AS ID, X.ORDEN, X.DESCRIPCION AS N_ELEMENTO, 
    					   X.ESTATUS, X.VALORES_CONFIG, X.REQUERIDO, E.DESCRIPCION AS TIPO, X.ID_TIPO , 
    					   X.VALORES_MIN_MAX, X.ID_USR_CATALOGO, X.VALORES_MIN_MAX, E.REQ_OPCIONES,
    					   X.ID_FORMULARIO, X.ID_ELEMENTO
					FROM  PROD_ELEMENTOS X
					INNER JOIN PROD_TIPO_ELEMENTO E ON X.ID_TIPO          = E.ID_TIPO
					 LEFT JOIN USR_CATALOGOS      C ON X.ID_USR_CATALOGO  = C.ID_CATALOGO
					WHERE X.ID_FORMULARIO = $idObject
					  AND X.DEPENDE       = $idElement
					ORDER BY X.SUBORDEN ASC";
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
	 * Inserta un nuevo elemento y lo relaciona con un formulario
	 * @param Array $aDataIn
	 * @return Array Id, Estatus de la operacion.
	 */
    public function insertElement($aDataElement,$idObject,$idEmpresa){
        $result     = Array();
        $result['status']  = false;
        
        $sql= "INSERT INTO PROD_ELEMENTOS  
        		SET ID_FORMULARIO	= $idObject,
					ID_TIPO			= ".$aDataElement['tipo'].",
					DESCRIPCION		='".@$aDataElement['desc']."',
					VALORES_CONFIG	='".@$aDataElement['options']."',
					REQUERIDO		= ".@$aDataElement['requerido'].",
					VALORES_MIN_MAX	='".@$aDataElement['inputmin'].",".@$aDataElement['inputmax']."',
					ID_USR_CATALOGO	= ".@$aDataElement['idcatalog'].",
					ORDEN			= ".@$aDataElement['orden'].",
					ESTATUS			= ".@$aDataElement['status'].",
					DEPENDE			= ".(($aDataElement['depende']=="") ? 'NULL': $aDataElement['depende']).",
					ESPERA			= '".$aDataElement['when']."',
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
        
        $sql="DELETE FROM  PROD_ELEMENTOS
					 WHERE ID_ELEMENTO = ".$aDataElement['id']." LIMIT 1";
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

	/**
	 * 
	 * Actualiza un elemento del formulario
	 * @param Array $aDataIn
	 * @return Boolean Estatus de la operacion
	 */
    public function updateRowRel($aDataElement){
        $result     = Array();
        $result['status']  = false;
        
       $sql="UPDATE PROD_ELEMENTOS
        		SET ID_TIPO			= ".$aDataElement['tipo'].",
					DESCRIPCION		='".@$aDataElement['desc']."',
					VALORES_CONFIG	='".@$aDataElement['options']."',
					REQUERIDO		= ".@$aDataElement['requerido'].",
					VALORES_MIN_MAX	='".@$aDataElement['inputmin'].",".@$aDataElement['inputmax']."',
					ID_USR_CATALOGO	= ".@$aDataElement['idcatalog'].",
					ORDEN			= ".@$aDataElement['orden'].",
					ESTATUS			= ".@$aDataElement['status'].",
					DEPENDE			= ".(($aDataElement['depend']=="") ? 'NULL': $aDataElement['depend']).",
					ESPERA			= '".$aDataElement['when']."'
			WHERE ID_ELEMENTO = ".$aDataElement['id']." LIMIT 1";				        
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

    /**
     * 
     */
    public function getFormsByStatus($idEmpresa,$bStatus='S'){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT *
				FROM $this->_name
				WHERE ID_EMPRESA = $idEmpresa 
				  AND ACTIVO     = '$bStatus'";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	    	
    }
}