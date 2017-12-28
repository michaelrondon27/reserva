<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Plazas_model extends CI_Model{

    private $nombre_tabla = "plaza";

    public function listar_plazas()
    {
        $query=$this->db->query("SELECT p.id_plaza, p.cod_plaza, p.nombre_plaza, a.fec_regins, u.correo_usuario, a.status FROM ".$this->nombre_tabla." p INNER JOIN auditoria a ON p.id_plaza=a.cod_reg INNER JOIN usuario u ON a.usr_regins=u.id_usuario WHERE a.tabla='".$this->nombre_tabla."'");
        return $query->result();
    }
        
    public function registrar_plaza($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => '1',
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }
       
    public function actualizar_plaza($id, $data)
    {
        $this->db->where('id_plaza', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos=array(
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_plaza($data)
    {
        $query=$this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE nombre_plaza='".$data['nombre_plaza']."' LIMIT 1");
        return $query->result_array();
    }

    public function eliminar_plaza($id)
    {
        try { 
            $this->db->delete($this->nombre_tabla, array('id_plaza' => $id));
            echo json_encode("<span>La plaza se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        } catch(QueryException $ex){ 
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>"; // envio de mensaje exitoso
        }
    }

    public function status_plaza($id, $status)
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

    public function eliminar_multiple_plaza($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $plaza)
        {
            try { 
                $this->db->delete($this->nombre_tabla, array('id_plaza' => $plaza));
                $eliminados++;
            } catch(QueryException $ex){ 
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_plaza($id, $status)
    {
        $plazas=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$plazas.") AND tabla='".$this->nombre_tabla."'");
    }

}