<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Roles_model extends CI_Model
{

    private $tabla_rol = "rol";
    private $tabla_lista_vista = "lista_vista";
    private $tabla_rol_operaciones = "rol_operaciones";

    public function listado_roles()
    {
        $roles = array();
        $query = $this->db->query("SELECT r.*, a.fec_regins, u.correo_usuario, a.status FROM ".$this->tabla_rol." r INNER JOIN auditoria a ON r.id_rol=a.cod_reg INNER JOIN usuario u ON a.usr_regins=u.id_usuario WHERE a.tabla='".$this->tabla_rol."' GROUP BY r.nombre_rol");
        foreach ($query->result() as $rol) {
            $sql = $this->db->query("SELECT GROUP_CONCAT(lv.nombre_lista_vista SEPARATOR ' - ') AS nombre_lista_vista FROM ".$this->tabla_rol_operaciones." ro INNER JOIN ".$this->tabla_lista_vista." lv ON ro.id_lista_vista = lv.id_lista_vista WHERE ro.id_rol = ".$rol->id_rol);
            $array = array(
                'id_rol' => $rol->id_rol,
                'nombre_rol' => $rol->nombre_rol,
                'descripcion_rol' => $rol->descripcion_rol,
                'editable_rol' => $rol->editable_rol,
                'nombre_lista_vista' => $sql->result(),
                'fec_regins' => $rol->fec_regins,
                'correo_usuario' => $rol->correo_usuario,
                'status' => $rol->status,
            );
            array_push($roles, $array);
        }
        return $roles;
    }

    public function listas_vistas()
    {
        $query = $this->db->query("SELECT * FROM ".$this->tabla_lista_vista." lv INNER JOIN auditoria a ON lv.id_lista_vista=a.cod_reg WHERE a.tabla='".$this->tabla_lista_vista."'");
        return $query->result();
    }
        
    public function registrar_rol($data, $permisos){
        $this->db->insert($this->tabla_rol, $data);
        $rol = $this->db->insert_id();
        $datos=array(
            'tabla' => $this->tabla_rol,
            'cod_reg' => $rol,
            'usr_regins' => '1',
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
        if (sizeof($permisos) > 0){
            foreach($permisos as $permiso)
            {
                $array = array(
                    'id_rol' => $rol,
                    'id_lista_vista' => $permiso[0],
                    'consultar' => $permiso[1],
                    'registrar' => $permiso[2],
                    'actualizar' => $permiso[3],
                    'eliminar' => $permiso[4],
                );
                $this->db->insert($this->tabla_rol_operaciones, $array);
            }
        }
    }

    public function actualizar_rol($id, $rol, $permisos)
    {
        $this->db->where('id_rol', $id);
        $this->db->update($this->tabla_rol, $rol);
        $datos=array(
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_rol);
        $this->db->update('auditoria', $datos);
        foreach($permisos as $permiso)
        {
            $query = $this->db->query("SELECT * FROM ".$this->tabla_rol_operaciones." WHERE id_rol=".$id." AND id_lista_vista=".$permiso[0]);
            if (sizeof($query->result()) > 0) {
                $array = array(
                    'consultar' => $permiso[1],
                    'registrar' => $permiso[2],
                    'actualizar' => $permiso[3],
                    'eliminar' => $permiso[4],
                );
                $this->db->where('id_rol', $id)->where('id_lista_vista', $permiso[0]);
                $this->db->update($this->tabla_rol_operaciones, $array);
            } else {
                $array = array(
                    'id_rol' => $id,
                    'id_lista_vista' => $permiso[0],
                    'consultar' => $permiso[1],
                    'registrar' => $permiso[2],
                    'actualizar' => $permiso[3],
                    'eliminar' => $permiso[4],
                );
                $this->db->insert($this->tabla_rol_operaciones, $array);
            }
        }
    }

    public function eliminar_rol($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_rol, array('id_rol' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                echo json_encode("<span>El rol se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_rol($id, $status)
    {
        $datos = array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_rol);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_roles($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $rol)
        {
            if($this->db->delete($this->tabla_rol, array('id_rol' => $rol))){
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_roles($id, $status)
    {
        $roles = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$roles.") AND tabla='".$this->tabla_rol."'");
    }

    public function operaciones_rol($id)
    {
        $query = $this->db->query("SELECT ro.*, lv.nombre_lista_vista FROM ".$this->tabla_rol_operaciones." ro INNER JOIN ".$this->tabla_lista_vista." lv ON ro.id_lista_vista = lv.id_lista_vista WHERE ro.id_rol = ".$id);
        return $query->result();
    }

    public function eliminar_rol_operacion($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_rol_operaciones, array('id_rol_operaciones' => $id))){
                throw new Exception("<span>¡Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                echo json_encode("<span>¡El registro se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

}