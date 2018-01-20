<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Roles_model extends CI_Model
{

    private $tabla_rol = "rol";
    private $tabla_lista_vista = "lista_vista";
    private $tabla_rol_operaciones = "rol_operaciones";

    public function listado_roles()
    {
        $query = $this->db->query("SELECT r.*, a.fec_regins, u.correo_usuario, a.status, GROUP_CONCAT(lv.nombre_lista_vista SEPARATOR '-') AS nombre_lista_vista FROM ".$this->tabla_rol." r INNER JOIN auditoria a ON r.id_rol=a.cod_reg INNER JOIN usuario u ON a.usr_regins=u.id_usuario INNER JOIN ".$this->tabla_rol_operaciones." ro ON r.id_rol=ro.id_rol INNER JOIN ".$this->tabla_lista_vista." lv ON ro.id_lista_vista=lv.id_lista_vista WHERE a.tabla='".$this->tabla_rol."' GROUP BY r.nombre_rol");
        return $query->result();
    }   
        
    public function registrar_modulo($data){
        $this->db->insert($this->nombre_tabla, $data);
        $datos=array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => '1',
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_modulo($id, $data)
    {
        $this->db->where('id_modulo_vista', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos=array(
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_modulo($data)
    {
        $query=$this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE nombre_modulo_vista='".$data."' LIMIT 1");
        return $query->result_array();
    }

    public function eliminar_modulo($id)
    {
        try { 
            if(!$this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." ORDER BY posicion_modulo_vista DESC");
                $contador = sizeof($query->result());
                foreach($query->result() as $row)
                {
                    $datos = array(
                        'posicion_modulo_vista' => $contador,
                    );
                    $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                    $this->db->update($this->nombre_tabla, $datos);
                    $contador--;
                }
                echo json_encode("<span>El modulo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_modulo($id, $status)
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

    public function eliminar_multiple_modulos($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $modulo)
        {
            if(!$this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $modulo))){
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." ORDER BY posicion_modulo_vista DESC");
        $contador = sizeof($query->result());
        foreach($query->result() as $row)
        {
            $datos = array(
                'posicion_modulo_vista' => $contador,
            );
            $this->db->where('id_modulo_vista', $row->id_modulo_vista);
            $this->db->update($this->nombre_tabla, $datos);
            $contador--;
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_modulos($id, $status)
    {
        $modulos=str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status=".$status." WHERE cod_reg in (".$modulos.") AND tabla='".$this->nombre_tabla."'");
    }

    public function posicionar_modulos($posicionar)
    {
        if($posicionar['tipo'] == 'insert')
        {
            $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE posicion_modulo_vista >= ".$posicionar['posicion']);
            if(sizeof($query->result()) > 0){
                foreach ($query->result() as $row)
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
                $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE posicion_modulo_vista > ".$posicionar['inicial']." AND posicion_modulo_vista <= ".$posicionar['final']);
                if(sizeof($query->result()) > 0){
                    foreach ($query->result() as $row)
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
                $query = $this->db->query("SELECT * FROM ".$this->nombre_tabla." WHERE posicion_modulo_vista >= ".$posicionar['final']." AND posicion_modulo_vista < ".$posicionar['inicial']);
                if(sizeof($query->result()) > 0){
                    foreach ($query->result() as $row)
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

    public function operaciones_rol($id)
    {
        $query = $this->db->query("SELECT ro.*, lv.nombre_lista_vista FROM ".$this->tabla_rol_operaciones." ro INNER JOIN ".$this->tabla_lista_vista." lv ON ro.id_lista_vista = lv.id_lista_vista WHERE ro.id_rol = ".$id);
        return $query->result();
    }

}