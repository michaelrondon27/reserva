<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Usuarios_model extends CI_Model
{

    private $tabla_usuario = "usuario";
    private $tabla_lval = "lval";
    private $tabla_roles = "rol";
    private $tabla_contacto = "contacto";
    private $tabla_personal = "datos_personales";

    public function listar_usuarios()
    {
        $this->db->where('a.tabla', $this->tabla_usuario);
        $this->db->select('u.id_usuario, u.id_rol, u.correo_usuario, u.avatar_usuario, u.fec_ult_acceso_usuario, a.status, a.fec_regins, r.nombre_rol, dt.*, c.*, cp.*');
        $this->db->from($this->tabla_usuario . ' u');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $this->db->join($this->tabla_roles . ' r', 'u.id_rol = r.id_rol');
        $this->db->join($this->tabla_personal . ' dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join($this->tabla_contacto . ' c', 'dt.id_contacto = c.id_contacto');
        $this->db->join('codigo_postal cp', 'c.id_codigo_postal = cp.id_codigo_postal');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function nacionalidades()
    {
        $this->db->where('lv.tipolval', 'NACIONALIDAD');
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->select('lv.*');
        $this->db->from($this->tabla_lval . ' lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function estados_civiles()
    {
        $this->db->where('lv.tipolval', 'EDOCIVIL');
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->select('lv.*');
        $this->db->from($this->tabla_lval . ' lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function sexos()
    {
        $this->db->where('lv.tipolval', 'SEXO');
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->select('lv.*');
        $this->db->from($this->tabla_lval . ' lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function roles()
    {
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_roles);
        $this->db->select('*');
        $this->db->from($this->tabla_roles . ' r');
        $this->db->join('auditoria a', 'r.id_rol = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function buscar_codigos($codigo)
    {
        $estados = $this->db->query("SELECT DISTINCT d_estado FROM codigo_postal WHERE d_codigo='$codigo'");
        $estados->result();
        $ciudades = $this->db->query("SELECT DISTINCT d_ciudad FROM codigo_postal WHERE d_codigo='$codigo'");
        $ciudades->result();
        $municipios = $this->db->query("SELECT DISTINCT d_mnpio FROM codigo_postal WHERE d_codigo='$codigo'");
        $municipios->result();
        $colonias = $this->db->query("SELECT id_codigo_postal, d_asenta FROM codigo_postal WHERE d_codigo='$codigo'");
        $colonias->result();
        $data = array(
            'estados' => $estados,
            'ciudades' => $ciudades,
            'municipios' => $municipios,
            'colonias' => $colonias,
        );
        return $data;
    }
        
    public function registrar_usuario($usuarioArray, $contactoArray, $personalArray){
        $this->db->insert($this->tabla_usuario, $usuarioArray);
        $datos = array(
            'tabla' => $this->tabla_usuario,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $personalArray['id_usuario'] = $this->db->insert_id();
        $this->db->insert($this->tabla_contacto, $contactoArray);
        $personalArray['id_contacto']  = $this->db->insert_id();
        $this->db->insert($this->tabla_personal, $personalArray);
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_usuario($usuarioArray, $contactoArray, $personalArray, $idArray, $imagen)
    {
        $this->db->where('id_usuario', $idArray['id_usuario']);
        $this->db->update($this->tabla_usuario, $usuarioArray);
        $this->db->where('id_contacto', $idArray['id_contacto']);
        $this->db->update($this->tabla_contacto, $contactoArray);
        $this->db->where('id_datos_personales', $idArray['id_datos_personales']);
        $this->db->update($this->tabla_personal, $personalArray);
        if($imagen != ""){
            $data = array(
                'avatar_usuario' => $imagen,
            );
            $this->db->where('id_usuario', $idArray['id_usuario']);
            $this->db->update($this->tabla_usuario, $data);
        }
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $idArray['id_usuario'])->where('tabla', $this->tabla_usuario);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_usuario($correo_usuario)
    {
        $this->db->where('correo_usuario', $correo_usuario);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_usuario);
        return $resultados->result_array();
    }

    public function eliminar_usuario($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_usuario, array('id_usuario' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                echo json_encode("<span>El usuario se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_usuario($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_usuario);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_usuario($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $usuario)
        {
            if($this->db->delete($this->tabla_usuario, array('id_usuario' => $usuario))){
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_usuario($id, $status)
    {
        $usuarios = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $usuarios . ") AND tabla='" . $this->tabla_usuario . "'");
    }

    public function login($correo_usuario, $clave_usuario)
    {
        $this->db->where('u.correo_usuario', $correo_usuario);
        $this->db->where('u.clave_usuario', $clave_usuario);
        $this->db->where('a.tabla', $this->tabla_usuario);
        $this->db->select('u.*, dt.nombre_datos_personales, dt.apellido_p_datos_personales, dt.apellido_m_datos_personales, a.status');
        $this->db->from($this->tabla_usuario . ' u');
        $this->db->join($this->tabla_personal . ' dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $resultados = $this->db->get();
        if ($resultados->num_rows() > 0) {
            return $resultados->row();
        } else {
            return false;
        }
    }

    public function ultima_conexion($id_usuario)
    {
        $data = array(
            'fec_ult_acceso_usuario' => date('Y-m-d'),
        );
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update($this->tabla_usuario, $data);
    }
}