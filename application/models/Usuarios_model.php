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
        $query = $this->db->query("SELECT u.id_usuario, u.id_rol, u.correo_usuario, u.avatar_usuario, u.fec_ult_acceso_usuario, a.status, a.fec_regins, r.nombre_rol, dt.*, c.*, cp.* FROM ".$this->tabla_usuario." u INNER JOIN auditoria a ON u.id_usuario=a.cod_reg INNER JOIN rol r ON u.id_rol=r.id_rol INNER JOIN datos_personales dt ON u.id_usuario=dt.id_usuario INNER JOIN contacto c ON dt.id_contacto=c.id_contacto INNER JOIN codigo_postal cp ON c.id_codigo_postal=cp.id_codigo_postal WHERE a.tabla='".$this->tabla_usuario."'");
        return $query->result();
    }

    public function nacionalidades()
    {
        $query = $this->db->query("SELECT lv.* FROM ".$this->tabla_lval." lv INNER JOIN auditoria a ON lv.codlval=a.cod_reg WHERE lv.tipolval='NACIONALIDAD' AND a.status=1 AND tabla='".$this->tabla_lval."'");
        return $query->result();
    }

    public function estados_civiles()
    {
        $query = $this->db->query("SELECT lv.* FROM ".$this->tabla_lval." lv INNER JOIN auditoria a ON lv.codlval=a.cod_reg WHERE lv.tipolval='EDOCIVIL' AND a.status=1 AND tabla='".$this->tabla_lval."'");
        return $query->result();
    }

    public function sexos()
    {
        $query = $this->db->query("SELECT lv.* FROM ".$this->tabla_lval." lv INNER JOIN auditoria a ON lv.codlval=a.cod_reg WHERE lv.tipolval='SEXO' AND a.status=1 AND tabla='".$this->tabla_lval."'");
        return $query->result();
    }

    public function roles()
    {
        $query = $this->db->query("SELECT * FROM ".$this->tabla_roles);
        return $query->result();
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
            'usr_regins' => '1',
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
        echo $imagen;
        $this->db->update($this->tabla_personal, $personalArray);
        if($imagen!=""){
            subirImagen($imagen, $idArray['id_usuario']);
        }
        $datos=array(
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $idArray['id_usuario'])->where('tabla', $this->tabla_usuario);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_usuario($correo)
    {
        $query=$this->db->query("SELECT * FROM ".$this->tabla_usuario." WHERE correo_usuario='".$correo."' LIMIT 1");
        return $query->result_array();
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
            'usr_regmod' => '1',
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
            if(!$this->db->delete($this->tabla_usuario, array('id_usuario' => $usuario))){
                $noEliminados++;
            }else{
                $eliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_usuario($id, $status)
    {
        $usuarios=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$usuarios.") AND tabla='".$this->tabla_usuario."'");
    }

    public function subirImagen($imagen, $id)
    {
        $config['upload_path'] = "assets/cpanel/usuarios/images/"; //ruta donde carga el archivo
        /*if(!file_exists($config['upload_path'])){
            mkdir($config['upload_path'], 0777, true);
        }*/
        $config['file_name'] = time(); //nombre temporal del archivo
        $config['allowed_types'] = "gif|jpg|jpeg|png";
        $config['overwrite'] = true; //sobreescribe si existe uno con ese nombre
        $config['max_size'] = "2000000"; //tamaño maximo de archivo
        $usuarioArray = array(
            'avatar_usuario' => $config['file_name'],
        );
        $this->load->library('upload', 'avatar_usuario');
        if($this->upload->do_upload($imagen)){
            $this->db->where('id_usuario', $id);
            $this->db->update($this->tabla_usuario, $usuarioArray);
        }
    }

}