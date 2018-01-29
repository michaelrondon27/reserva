<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class ListasValores_model extends CI_Model{

    private $nombre_tabla = "lval";
        
    public function listado_valores()
    {
        $this->db->where('a.tabla', $this->nombre_tabla);
        $this->db->select('lv.codlval, lv.tipolval, lv.descriplval, a.fec_regins, u.correo_usuario, a.status, tlv.descriplval as descriptipolval');
        $this->db->from($this->nombre_tabla . " lv");
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('tipolval tlv', 'tlv.tipolval = lv.tipolval');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function tipolval()
    {
        $resultados = $this->db->get('tipolval');
        return $resultados->result();
    }

    public function registrar_lval($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }
        
    public function verificar_lval($data)
    {
        $query=$this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE tipolval='".$data['tipolval']."' AND descriplval='".$data['descriplval']."' LIMIT 1");
        return $query->result_array();
    }
        
    public function actualizar_lval($id, $data)
    {
        $this->db->where('codlval', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function status_lval($id, $status)
    {
        $datos = array(
            'status' => $status,
            'fec_status' => date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_lval($id)
    {
        try { 
            if(!$this->db->delete($this->nombre_tabla, array('codlval' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                echo json_encode("<span>Se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function eliminar_multiple_lval($id)
    {
        $eliminados=0;
        foreach($id as $lval)
        {
            if($this->db->delete($this->nombre_tabla, array('codlval' => $lval))){
                $eliminados++;
            }
        }
        if ($eliminados == 0) {
            echo json_encode("<span>¡No se ha eliminado ninguno de los registros seleccionados porque tienen dependencia en otras tablas!</span>");
        } else if ($eliminados == 1) {
            echo json_encode("<span>¡Se ha eliminado un solo registro de todos los seleccionados!</span>");
        } else if ($eliminados > 1) {
            echo json_encode("<span>¡Se han eliminado " . $eliminados . " registros de todos los seleccionados!</span>");
        }
    }

    public function status_multiple_lval($id, $status)
    {
        $lval=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $lval . ") AND tabla='" . $this->nombre_tabla . "'");
    }
}
?>