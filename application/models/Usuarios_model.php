<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Usuarios_model extends CI_Model
{

    private $tabla_usuario = "usuario";
    private $tabla_lval = "lval";
    private $tabla_roles = "rol";

    public function listar_usuarios()
    {
        $query=$this->db->query("SELECT u.id_usuario, u.id_rol, u.correo_usuario, u.avatar_usuario, u.fec_ult_acceso_usuario, a.status, a.fec_regins, r.nombre_rol, dt.*, c.*, cp.* FROM ".$this->tabla_usuario." u INNER JOIN auditoria a ON u.id_usuario=a.cod_reg INNER JOIN rol r ON u.id_rol=r.id_rol INNER JOIN datos_personales dt ON u.id_usuario=dt.id_usuario INNER JOIN contacto c ON dt.id_contacto=c.id_contacto INNER JOIN codigo_postal cp ON c.id_codigo_postal=cp.id_codigo_postal WHERE a.tabla='".$this->tabla_usuario."'");
        return $query->result();
    }

    public function nacionalidades()
    {
        $query=$this->db->query("SELECT * FROM ".$this->tabla_lval." WHERE tipolval='NACIONALIDAD'");
        return $query->result();
    }

    public function roles()
    {
        $query=$this->db->query("SELECT * FROM ".$this->tabla_roles);
        return $query->result();
    }
        
    public function registrar_banco($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => '1',
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_banco($id, $data)
    {
        $this->db->where('id_banco', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos=array(
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_banco($data)
    {
        $query=$this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE nombre_banco='".$data['nombre_banco']."' LIMIT 1");
        return $query->result_array();
    }

    public function eliminar_banco($id)
    {
        try { 
            $this->db->delete($this->nombre_tabla, array('id_banco' => $id));
            echo json_encode("<span>El Banco se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        } catch(QueryException $ex){ 
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>"; // envio de mensaje exitoso
        }
    }

    public function status_banco($id, $status)
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

    public function eliminar_multiple_banco($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $banco)
        {
            try { 
                $this->db->delete($this->nombre_tabla, array('id_banco' => $banco));
                $eliminados++;
            } catch(QueryException $ex){ 
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_banco($id, $status)
    {
        $bancos=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$bancos.") AND tabla='".$this->nombre_tabla."'");
    }

}