<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Proyectos_model extends CI_Model
{

    private $tabla_proyecto = "proyectos";
    private $tabla_inmobiliarias = "inmobiliarias";
    private $tabla_inmobliarias_proyectos = "inmobiliarias_proyectos";

    public function listado_proyectos()
    {
        $this->db->where('a.tabla', $this->tabla_proyecto);
        $this->db->select('p.*, a.fec_regins, u.correo_usuario, a.status, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno');
        $this->db->from($this->tabla_proyecto . ' p');
        $this->db->join('auditoria a', 'p.id_proyecto = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('usuario c', 'p.director = c.id_usuario');
        $this->db->join('datos_personales dt', 'c.id_usuario = dt.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_proyecto($data, $inmobiliarias){
        $this->db->insert($this->tabla_proyecto, $data);
        $proyecto = $this->db->insert_id();
        $datos=array(
            'tabla' => $this->tabla_proyecto,
            'cod_reg' => $proyecto,
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
        if (sizeof($inmobiliarias) > 0){
            foreach($inmobiliarias as $inmobiliaria)
            {
                $array = array(
                    'id_inmobiliaria' => $inmobiliaria,
                    'id_proyecto' => $proyecto,
                );
                $this->db->insert($this->tabla_inmobliarias_proyectos, $array);
            }
        }
    }

    public function actualizar_proyecto($proyectoArray, $id, $imagen, $inmobiliarias)
    {
        $this->db->where('id_proyecto', $id);
        $this->db->update($this->tabla_proyecto, $proyectoArray);
        if($imagen != ""){
            $data = array(
                'plano' => $imagen,
            );
            $this->db->where('id_proyecto', $id);
            $this->db->update($this->tabla_proyecto, $data);
        }
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_proyecto);
        $this->db->update('auditoria', $datos);
        if (sizeof($inmobiliarias) > 0){
            foreach($inmobiliarias as $inmobiliaria)
            {
                $query = $this->db->query("SELECT * FROM ".$this->tabla_inmobliarias_proyectos." WHERE id_proyecto=".$id." AND id_inmobiliaria=".$inmobiliaria);
                if (sizeof($query->result()) == 0) {
                    $array = array(
                        'id_inmobiliaria' => $inmobiliaria,
                        'id_proyecto' => $id,
                    );
                    $this->db->insert($this->tabla_inmobliarias_proyectos, $array);
                }
            }
        }
    }

    public function verificar_proyecto($data)
    {
        $this->db->where('codigo', $data['codigo']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_proyecto);
        return $resultados->result_array();
    }

    public function eliminar_proyecto($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_proyecto, array('id_proyecto' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_proyecto));
                echo json_encode("<span>La inmobiliaria se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_proyecto($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_proyecto);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_proyecto($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $proyecto)
        {
            if($this->db->delete($this->tabla_proyecto, array('id_proyecto' => $proyecto))){
                $this->db->delete('auditoria', array('cod_reg' => $proyecto, 'tabla' => $this->tabla_proyecto));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_proyecto($id, $status)
    {
        $proyectos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $proyectos . ") AND tabla='" . $this->tabla_proyecto . "'");
    }

    public function directores()
    {
        $this->db->where('r.nombre_rol', 'DIRECTOR');
        $this->db->where('a.tabla', 'usuario');
        $this->db->where('a.status', 1);
        $this->db->select('u.id_usuario, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno');
        $this->db->from('rol r');
        $this->db->join('usuario u', 'r.id_rol = u.id_rol');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function inmobiliarias()
    {
        $this->db->where('a.tabla', 'inmobiliarias');
        $this->db->where('a.status', 1);
        $this->db->select('i.*');
        $this->db->from($this->tabla_inmobiliarias . ' i');
        $this->db->join('auditoria a', 'i.id_inmobiliaria = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function buscarInmobiliarias($proyecto)
    {
        $this->db->where('ip.id_proyecto', $proyecto);
        $this->db->select('i.codigo, i.nombre, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno, ip.*');
        $this->db->from($this->tabla_inmobliarias_proyectos . ' ip');
        $this->db->join($this->tabla_inmobiliarias . ' i', 'ip.id_inmobiliaria = i.id_inmobiliaria');
        $this->db->join('usuario u', 'i.id_coordinador = u.id_usuario');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function eliminar_inmobiliaria_proyecto($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_inmobliarias_proyectos, array('id_inmobiliaria_proyecto' => $id))){
                throw new Exception("<span>Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                echo json_encode("<span>La inmobiliaria se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

}