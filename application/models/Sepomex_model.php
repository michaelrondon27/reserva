<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Sepomex_model extends CI_Model{

    private $nombre_tabla = "codigo_postal";
        
    public function verificar_codigo_postal($d_codigo, $id_asenta_cpcons)
    {
        $this->db->where('d_codigo', $d_codigo);
        $this->db->where('id_asenta_cpcons', $id_asenta_cpcons);
        $this->db->limit(1);
        $resultados = $this->db->get($this->nombre_tabla);
        return $resultados->result_array();
    }

    public function registrar_codigo_postal($data)
    {
    	$this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_codigo_postal($d_codigo, $id_asenta_cpcons, $data)
    {
    	$this->db->where('d_codigo', $d_codigo);
    	$this->db->where('id_asenta_cpcons', $id_asenta_cpcons);
        $this->db->update($this->nombre_tabla, $data);
    }
}
?>