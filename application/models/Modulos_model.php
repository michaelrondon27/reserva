<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Modulos_model extends CI_Model
{

    private $nombre_tabla = "modulo_vista";

    public function listar_modulos()
    {
        $this->db->where('a.tabla', $this->nombre_tabla);
        $this->db->select('mv.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->nombre_tabla . ' mv');
        $this->db->join('auditoria a', 'mv.id_modulo_vista = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_modulo($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos = array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_modulo($id, $data)
    {
        $this->db->where('id_modulo_vista', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos = array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_modulo($nombre_modulo_vista)
    {
        $this->db->where('nombre_modulo_vista', $nombre_modulo_vista);
        $this->db->limit(1);
        $resultados = $this->db->get($this->nombre_tabla);
        return $resultados->result_array();
    }

    public function eliminar_modulo($id)
    {
        try { 
            if(!$this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->order_by('posicion_modulo_vista', 'DESC');
                $resultados = $this->db->get($this->nombre_tabla);
                $contador = $resultados->num_rows();
                foreach($resultados->result() as $row)
                {
                    $datos = array(
                        'posicion_modulo_vista' => $contador,
                    );
                    $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                    $this->db->update($this->nombre_tabla, $datos);
                    $contador--;
                }
                echo json_encode("<span>El módulo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_modulo($id, $status)
    {
        $datos = array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_modulos($id)
    {
        $eliminados=0;
        foreach($id as $modulo)
        {
            if($this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $modulo))){
                $eliminados++;
            }
        }
        $this->db->order_by('posicion_modulo_vista', 'DESC');
        $resultados = $this->db->get($this->nombre_tabla);
        $contador = $resultados->num_rows();
        foreach($resultados->result() as $row)
        {
            $datos = array(
                'posicion_modulo_vista' => $contador,
            );
            $this->db->where('id_modulo_vista', $row->id_modulo_vista);
            $this->db->update($this->nombre_tabla, $datos);
            $contador--;
        }
        if ($eliminados == 0) {
            echo json_encode("<span>¡No se ha eliminado ninguno de los registros seleccionados porque tienen dependencia en otras tablas!</span>");
        } else if ($eliminados == 1) {
            echo json_encode("<span>¡Se ha eliminado un solo registro de todos los seleccionados!</span>");
        } else if ($eliminados > 1) {
            echo json_encode("<span>¡Se han eliminado " . $eliminados . " registros de todos los seleccionados!</span>");
        }
    }

    public function status_multiple_modulos($id, $status)
    {
        $modulos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $modulos . ") AND tabla='" . $this->nombre_tabla . "'");
    }

    public function posicionar_modulos($posicionar)
    {
        if($posicionar['tipo'] == 'insert')
        {
            $this->db->where('posicion_modulo_vista >= ' . $posicionar['posicion']);
            $resultados = $this->db->get($this->nombre_tabla);
            if($resultados->num_rows() > 0){
                foreach ($resultados->result() as $row)
                {
                    $datos=array(
                        'posicion_modulo_vista' => $row->posicion_modulo_vista + 1,
                    );
                    $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                    $this->db->update($this->nombre_tabla, $datos);
                }
            }
        }
        else if($posicionar['tipo'] == 'update')
        {
            if($posicionar['final'] > $posicionar['inicial'])
            {
                $this->db->where('posicion_modulo_vista > ' . $posicionar['inicial'] . ' AND posicionar_modulos <= ' . $posicionar['final']);
                $resultados = $this->db->get($this->nombre_tabla);
                if($resultados->num_rows() > 0){
                    foreach ($resultados->result() as $row)
                    {
                        $datos=array(
                            'posicion_modulo_vista' => $row->posicion_modulo_vista - 1,
                        );
                        $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                        $this->db->update($this->nombre_tabla, $datos);
                    }
                }
            }
            else if($posicionar['final'] < $posicionar['inicial'])
            {
                $this->db->where('posicion_modulo_vista >= ' . $posicionar['final'] . ' AND posicion_modulo_vista < ' . $posicionar['inicial']);
                $resultados = $this->db->get($this->nombre_tabla);
                if($resultados->num_rows() > 0){
                    foreach ($resultados->result() as $row)
                    {
                        $datos=array(
                            'posicion_modulo_vista' => $row->posicion_modulo_vista + 1,
                        );
                        $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                        $this->db->update($this->nombre_tabla, $datos);
                    }
                }
            }
        }
    }

}