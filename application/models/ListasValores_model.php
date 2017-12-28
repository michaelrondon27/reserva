<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class ListasValores_model extends CI_Model{

    private $nombre_tabla = "lval";
        
    public function listado_valores()
    {
        $query=$this->db->query("SELECT lv.codlval, lv.tipolval, lv.descriplval, a.fec_regins, u.correo_usuario, a.status, tlv.descriplval as descriptipolval FROM ".$this->nombre_tabla." lv INNER JOIN auditoria a ON lv.codlval=a.cod_reg INNER JOIN usuario u ON a.usr_regins=u.id_usuario INNER JOIN tipolval tlv ON tlv.tipolval=lv.tipolval WHERE a.tabla='".$this->nombre_tabla."'");
        return $query->result();
    }

    public function tipolval()
    {
        $query=$this->db->query("SELECT * FROM tipolval");
        return $query->result();
    }

    public function registrar_lval($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => '1',
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
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function status_lval($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_lval($id)
    {
        try { 
            $this->db->delete($this->nombre_tabla, array('codlval' => $id));
            echo json_encode("<span>Se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        } catch(QueryException $ex){ 
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>"; // envio de mensaje exitoso
        }
    }

    public function eliminar_multiple_lval($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $lval)
        {
            try { 
                $this->db->delete($this->nombre_tabla, array('codlval' => $lval));
                $eliminados++;
            } catch(QueryException $ex){ 
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_lval($id, $status)
    {
        $lval=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$lval.") AND tabla='".$this->nombre_tabla."'");
    }
}
?>