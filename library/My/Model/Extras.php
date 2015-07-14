<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Extras extends My_Db_Table
{
    protected $_schema 	= 'DB_PRODUCTIVIDAPP';
	protected $_name 	= 'PROD_EXTRAS_EMPRESA';
	protected $_primary = 'ID_EXTRA';
		
	/**
	 * 
	 * Obtiene los elementos extras de un tipo de cita
	 * @param int $idObject
	 */
    public function getElementos($idObject){
    	$result     = Array();    	
    	try{ 
    		$sql = "SELECT X.ID_EXTRA AS ID, X.ORDEN, X.DESCRIPCION AS N_ELEMENTO, 
    					   X.ACTIVO, X.VALORES_CONFIG, X.REQUERIDO, E.DESCRIPCION AS TIPO, X.ID_TIPO_ELEMENTO, 
    					   X.VALORES_MIN_MAX, X.ID_USR_CATALOGO, X.VALORES_MIN_MAX, E.REQ_OPCIONES
					FROM  PROD_TIPO_EXTRAS X
					INNER JOIN PROD_TIPO_ELEMENTO E ON X.ID_TIPO_ELEMENTO = E.ID_TIPO
					 LEFT JOIN USR_CATALOGOS      C ON X.ID_USR_CATALOGO  = C.ID_CATALOGO
					WHERE X.ID_TIPO_CITA = $idObject
					ORDER BY X.ORDEN ASC";
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
	 * Obtiene los tipos de elementos para los extras
	 * @param int $idObject
	 */
    public function getTipoElementos(){
    	$result     = Array();    	
    	try{ 
    		$sql = "SELECT ID_TIPO AS ID, DESCRIPCION AS NAME, REQ_OPCIONES
					FROM PROD_TIPO_ELEMENTO
					WHERE ON_EXTRA = 1
					ORDER BY DESCRIPCION ASC";
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
	 * Inserta un nuevo elemento
	 * @param Array $aDataIn
	 * @return Array Id, Estatus de la operacion.
	 */
    public function insertElement($aDataElement,$idObject,$idEmpresa){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO PROD_TIPO_EXTRAS
        		SET   ID_TIPO_CITA		= ".$idObject.",
					  ID_TIPO_ELEMENTO	= ".@$aDataElement['tipo'].",
					  ID_USR_CATALOGO	= ".@$aDataElement['idcatalog'].",
					  DESCRIPCION		='".@$aDataElement['desc']."',
					  VALORES_CONFIG	='".@$aDataElement['options']."',
					  VALORES_MIN_MAX	='".@$aDataElement['inputmin'].",".@$aDataElement['inputmax']."',
					  ACTIVO			= ".@$aDataElement['status'].",
					  REQUERIDO			= ".@$aDataElement['requerido'].",
					  ORDEN				= ".@$aDataElement['orden'].",
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
     * Elimina un elemento 
     * @param Array $aDataElement
     * @param int $idObject
     */
    public function deleteRowRel($aDataElement,$idObject){
        $result     = Array();
        $result['status']  = false;  
        
		$sqlDel  = "DELETE FROM PROD_TIPO_EXTRAS 
					WHERE ID_EXTRA    = ".$aDataElement['id']."
					  AND ID_TIPO_CITA= ".$idObject." LIMIT 1";
        try{            
    		$query   = $this->query($sqlDel,false);
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
	 * Actualiza un elemento
	 * @param Array $aDataIn
	 * @return Boolean Estatus de la operacion
	 */
    public function updateRowRel($aDataElement){
        $result     = Array();
        $result['status']  = false;
        
       $sql="UPDATE PROD_TIPO_EXTRAS
				SET ID_TIPO_ELEMENTO	= ".@$aDataElement['tipo'].",
					  ID_USR_CATALOGO	= ".@$aDataElement['idcatalog'].",
					  DESCRIPCION		='".@$aDataElement['desc']."',
					  VALORES_CONFIG	='".@$aDataElement['options']."',
					  VALORES_MIN_MAX	='".@$aDataElement['inputmin'].",".@$aDataElement['inputmax']."',
					  ACTIVO			= ".@$aDataElement['status'].",
					  REQUERIDO			= ".@$aDataElement['requerido'].",
					  ORDEN				= ".@$aDataElement['orden']."
			WHERE ID_EXTRA   			= ".$aDataElement['id']." LIMIT 1";	  
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