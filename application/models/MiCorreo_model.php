<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class MiCorreo_model extends CI_Model{

    private $nombre_tabla = "mi_correo";
        
    public function buscar_mi_correo(){
    	$resultados = $this->db->get($this->nombre_tabla);
        return $resultados->result();
    }

    public function actualizar_mi_correo($id, $data){
    	if($id==0){
    		$this->db->insert($this->nombre_tabla, $data);
	        $datos=array(
	            'tabla' => $this->nombre_tabla,
	            'cod_reg' => $this->db->insert_id(),
	            'usr_regins' =>  $this->session->userdata('id_usuario'),
	            'fec_regins' => date('Y-m-d'),
	        );
	        $this->db->insert('auditoria', $datos);
    	}else{
    		$this->db->where('id_mi_correo', $id);
	        $this->db->update($this->nombre_tabla, $data);
	        $datos=array(
	            'usr_regmod' =>  $this->session->userdata('id_usuario'),
	            'fec_regmod' => date('Y-m-d'),
	        );
	        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
	        $this->db->update('auditoria', $datos);
    	}
    }

}
?>