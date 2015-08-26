<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Catalogos extends My_Db_Table
{
	protected $_schema 	= 'PRODUCTIVIDAPP';
	protected $_name 	= 'USR_CATALOGOS';
	protected $_primary = 'ID_CATALOGO';
	
	public function getCbo($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_CATALOGO AS ID, DESCRIPCION AS NAME
				FROM USR_CATALOGOS 
				WHERE ID_EMPRESA = $idObject
				ORDER BY DESCRIPCION ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;				
	}

	public function getElementos($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_USR_ELEMENTO AS ID,DESCRIPCION, ESTATUS
				FROM  USR_CATALOGOS_ELEMENTOS
				WHERE ID_CATALOGO = ".$idObject;
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
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
        
        $sql="INSERT INTO USR_CATALOGOS_ELEMENTOS
				SET ID_CATALOGO		= ".$idObject.",
					DESCRIPCION		='".@$aDataElement['desc']."',
					ESTATUS			= ".@$aDataElement['status'].",
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
        
		$sqlDel  = "DELETE FROM USR_CATALOGOS_ELEMENTOS 
					WHERE ID_USR_ELEMENTO   = ".$aDataElement['id']."
					  AND ID_CATALOGO   	= ".$idObject." LIMIT 1";
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
	 * Actualiza un elemento
	 * @param Array $aDataIn
	 * @return Boolean Estatus de la operacion
	 */
    public function updateRowRel($aDataElement){
        $result     = Array();
        $result['status']  = false;
        
       $sql="UPDATE USR_CATALOGOS_ELEMENTOS
				SET ID_CATALOGO		= ".$idObject.",
					DESCRIPCION		='".@$aDataElement['desc']."',
					ESTATUS			= ".@$aDataElement['status']."
			WHERE ID_USR_ELEMENTO   = ".$aDataElement['id']." LIMIT 1";				        
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