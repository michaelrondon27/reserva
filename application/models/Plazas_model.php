<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Plazas_model extends CI_Model{

    private $nombre_tabla = "plaza";

    public function listar_plazas()
    {
        $this->db->where('a.tabla', $this->nombre_tabla);
        $this->db->select('p.id_plaza, p.cod_plaza, p.nombre_plaza, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->nombre_tabla . ' p');
        $this->db->join('auditoria a', 'p.id_plaza = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();
    }
        
    public function registrar_plaza($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' =>  $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }
       
    public function actualizar_plaza($id, $data)
    {
        $this->db->where('id_plaza', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos=array(
            'usr_regmod' =>  $this->session->userdata('id_usuario'),
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
            if(!$this->db->delete($this->nombre_tabla, array('id_plaza' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                echo json_encode("<span>La plaza se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_plaza($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' =>  $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_plaza($id)
    {
        $eliminados=0;
        foreach($id as $plaza)
        {
            if($this->db->delete($this->nombre_tabla, array('id_plaza' => $plaza))){
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

    public function status_multiple_plaza($id, $status)
    {
        $plazas = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $plazas . ") AND tabla='" . $this->nombre_tabla . "'");
    }

}